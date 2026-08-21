<?php

namespace App\Services;

use App\Exceptions\OutOfStockException;
use App\Models\Coupon;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\PendingCheckout;
use App\Models\Product;
use App\Models\User;
use App\Notifications\WishlistStockAlertNotification;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;

/**
 * Owns the money- and inventory-critical parts of checkout: turning a cart
 * into a persisted order, locking stock so concurrent checkouts can't oversell,
 * and making payment finalization idempotent across both the browser callback
 * and the Razorpay webhook.
 */
class OrderFulfillmentService
{
    /**
     * Revalidate a coupon against the live database and the current subtotal,
     * rather than trusting whatever was cached in the session when it was applied.
     *
     * @return array{discount: float, coupon_id: int|null}
     */
    public function resolveDiscount(?int $couponId, float $subtotal): array
    {
        if (! $couponId) {
            return ['discount' => 0.0, 'coupon_id' => null];
        }

        $coupon = Coupon::find($couponId);
        if (! $coupon) {
            return ['discount' => 0.0, 'coupon_id' => null];
        }

        $check = $coupon->isValidFor($subtotal);
        if (! $check['valid']) {
            return ['discount' => 0.0, 'coupon_id' => null];
        }

        return ['discount' => $coupon->calculateDiscount($subtotal), 'coupon_id' => $coupon->id];
    }

    /**
     * Create and confirm a Cash on Delivery order immediately. Stock is locked
     * and decremented right away since COD has no separate payment-confirmation
     * step — if any item is out of stock, the whole order is rejected before
     * anything is persisted.
     *
     * @throws OutOfStockException
     */
    public function createCodOrder(int $userId, array $shipping, array $cart, ?int $couponId, float $subtotal): Order
    {
        $stockChanges = [];

        $order = DB::transaction(function () use ($userId, $shipping, $cart, $couponId, $subtotal, &$stockChanges) {
            $resolved = $this->resolveDiscount($couponId, $subtotal);
            $total = max(0, $subtotal - $resolved['discount']);

            $order = Order::create([
                'order_number' => Order::generateOrderNumber(),
                'user_id' => $userId,
                'customer_name' => $shipping['customer_name'],
                'customer_email' => $shipping['customer_email'],
                'customer_phone' => $shipping['customer_phone'],
                'shipping_address' => $shipping['shipping_address'],
                'city' => $shipping['city'],
                'state' => $shipping['state'],
                'postal_code' => $shipping['postal_code'],
                'country' => $shipping['country'] ?? 'India',
                'payment_method' => 'COD',
                'payment_status' => 'pending',
                'transaction_id' => null,
                'subtotal' => $subtotal,
                'discount' => $resolved['discount'],
                'shipping_fee' => 0.00,
                'total_amount' => $total,
                'status' => 'processing',
            ]);

            $this->createOrderItems($order, $cart);
            ['stock_changes' => $stockChanges] = $this->decrementStock($cart, strict: true);
            $this->recordCouponUsage($resolved['coupon_id']);

            return $order;
        });

        $this->dispatchStockAlerts($stockChanges);

        return $order;
    }

    /**
     * Snapshot everything needed to build the order once a Razorpay payment is
     * captured. Nothing here touches stock or the orders table yet — the order
     * is only created once payment is confirmed, by finalizePayment().
     */
    public function createPendingCheckout(
        int $userId,
        string $razorpayOrderId,
        array $shipping,
        array $cart,
        ?int $couponId,
        float $subtotal,
        float $discount,
        float $total,
        string $paymentMethod
    ): PendingCheckout {
        return PendingCheckout::create([
            'razorpay_order_id' => $razorpayOrderId,
            'user_id' => $userId,
            'payload' => [
                'shipping' => $shipping,
                'cart' => $cart,
                'coupon_id' => $couponId,
                'subtotal' => $subtotal,
                'discount' => $discount,
                'total' => $total,
                'payment_method' => $paymentMethod,
            ],
        ]);
    }

    /**
     * Turn a captured Razorpay payment into a confirmed order. Safe to call
     * twice for the same payment — from the browser's verify() callback and
     * from the webhook, in either order or racing concurrently. Whichever
     * call wins creates the order; the other is told it already exists via
     * created=false so the caller knows not to re-send confirmation email.
     *
     * @return array{order: Order, created: bool}
     */
    public function finalizePayment(string $razorpayOrderId, string $paymentId): array
    {
        $existing = Order::where('transaction_id', $paymentId)->first();
        if ($existing) {
            return ['order' => $existing, 'created' => false];
        }

        $pending = PendingCheckout::where('razorpay_order_id', $razorpayOrderId)->first();
        if (! $pending) {
            // Nothing staged — most likely this payment was already finalized
            // and the pending row was cleaned up by the other caller.
            $existing = Order::where('transaction_id', $paymentId)->first();
            if ($existing) {
                return ['order' => $existing, 'created' => false];
            }

            throw new \RuntimeException("No pending checkout found for Razorpay order {$razorpayOrderId}.");
        }

        $stockChanges = [];

        try {
            $order = DB::transaction(function () use ($pending, $paymentId, &$stockChanges) {
                $payload = $pending->payload;

                $order = Order::create([
                    'order_number' => Order::generateOrderNumber(),
                    'user_id' => $pending->user_id,
                    'customer_name' => $payload['shipping']['customer_name'],
                    'customer_email' => $payload['shipping']['customer_email'],
                    'customer_phone' => $payload['shipping']['customer_phone'],
                    'shipping_address' => $payload['shipping']['shipping_address'],
                    'city' => $payload['shipping']['city'],
                    'state' => $payload['shipping']['state'],
                    'postal_code' => $payload['shipping']['postal_code'],
                    'country' => $payload['shipping']['country'] ?? 'India',
                    'payment_method' => $payload['payment_method'],
                    'payment_status' => 'paid',
                    'transaction_id' => $paymentId,
                    'subtotal' => $payload['subtotal'],
                    'discount' => $payload['discount'],
                    'shipping_fee' => 0.00,
                    'total_amount' => $payload['total'],
                    'status' => 'processing',
                ]);

                $this->createOrderItems($order, $payload['cart']);

                // Payment is already captured at this point — an out-of-stock
                // item can no longer reject the order, only flag it for review.
                ['oversell_notes' => $oversellNotes, 'stock_changes' => $stockChanges] = $this->decrementStock($payload['cart'], strict: false);
                $this->recordCouponUsage($payload['coupon_id'] ?? null);

                if (! empty($oversellNotes)) {
                    $order->update(['notes' => implode("\n", $oversellNotes)]);
                }

                $pending->delete();

                return $order;
            });

            $this->dispatchStockAlerts($stockChanges);

            return ['order' => $order, 'created' => true];
        } catch (QueryException $e) {
            // Unique constraint on transaction_id: the webhook and the browser
            // verify() call raced and the other one already created it.
            $existing = Order::where('transaction_id', $paymentId)->first();
            if ($existing) {
                return ['order' => $existing, 'created' => false];
            }

            throw $e;
        }
    }

    private function createOrderItems(Order $order, array $cart): void
    {
        foreach ($cart as $item) {
            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $item['product_id'] ?? null,
                'product_name' => $item['name'],
                'product_sku' => $item['sku'] ?? null,
                'price' => $item['price'],
                'quantity' => $item['quantity'],
                'size' => $item['size'] ?? null,
                'color' => $item['color'] ?? null,
                'custom_measurements' => $item['custom_measurements'] ?? null,
                'product_image' => $item['image'] ?? null,
                'total' => $item['price'] * $item['quantity'],
            ]);
        }
    }

    /**
     * Lock each product row before decrementing so concurrent checkouts can't
     * both read the same stock count and both "succeed". In strict mode
     * (pre-payment) an insufficient item aborts the whole order; otherwise
     * stock is floored at zero and the shortfall is returned for the caller
     * to record against the order.
     *
     * @return array{oversell_notes: list<string>, stock_changes: list<array{product: Product, previous_stock: int}>}
     *
     * @throws OutOfStockException
     */
    private function decrementStock(array $cart, bool $strict): array
    {
        $oversellNotes = [];
        $stockChanges = [];

        foreach ($cart as $item) {
            if (empty($item['product_id'])) {
                continue;
            }

            $product = Product::where('id', $item['product_id'])->lockForUpdate()->first();
            if (! $product) {
                continue;
            }

            $requested = max(1, (int) $item['quantity']);
            $previousStock = $product->stock;

            if ($product->stock < $requested) {
                if ($strict) {
                    throw new OutOfStockException("Sorry, '{$product->name}' only has {$product->stock} left in stock. Please update your cart.");
                }

                $oversellNotes[] = "Oversold: {$product->name} — requested {$requested}, only {$product->stock} in stock at fulfillment time.";
                $product->decrement('stock', $product->stock);
            } else {
                $product->decrement('stock', $requested);
            }

            $stockChanges[] = ['product' => $product, 'previous_stock' => $previousStock];
        }

        return ['oversell_notes' => $oversellNotes, 'stock_changes' => $stockChanges];
    }

    /**
     * Notify wishlisting customers when a product they saved crosses into low
     * stock or sells out entirely. Called only after the enclosing transaction
     * has committed, so a rolled-back order never triggers a false alert.
     *
     * @param  list<array{product: Product, previous_stock: int}>  $stockChanges
     */
    private function dispatchStockAlerts(array $stockChanges): void
    {
        foreach ($stockChanges as $change) {
            $product = $change['product']->fresh();
            if (! $product) {
                continue;
            }

            $crossedOut = $change['previous_stock'] > 0 && $product->stock <= 0;
            $crossedLow = ! $crossedOut
                && $change['previous_stock'] > $product->low_stock_threshold
                && $product->stock > 0
                && $product->stock <= $product->low_stock_threshold;

            if (! $crossedOut && ! $crossedLow) {
                continue;
            }

            $watchers = User::whereHas('wishlists', fn ($q) => $q->where('product_id', $product->id))->get();
            if ($watchers->isEmpty()) {
                continue;
            }

            Notification::send($watchers, new WishlistStockAlertNotification($product, $crossedOut ? 'out_of_stock' : 'low_stock'));
        }
    }

    /**
     * Atomically increment usage_count only if the coupon still has room —
     * guards against a limited coupon being redeemed past its limit when many
     * checkouts land in the same instant.
     */
    private function recordCouponUsage(?int $couponId): void
    {
        if (! $couponId) {
            return;
        }

        Coupon::where('id', $couponId)
            ->where(function ($q) {
                $q->whereNull('usage_limit')->orWhereColumn('used_count', '<', 'usage_limit');
            })
            ->increment('used_count');
    }
}
