<?php

namespace App\Http\Controllers;

use App\Mail\OrderConfirmation;
use App\Models\Coupon;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Services\CartSyncService;
use App\Services\RazorpayService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\View\View;

class CheckoutController extends Controller
{
    public function __construct(protected RazorpayService $razorpay, protected CartSyncService $cartSync) {}

    /**
     * Step 1: Shipping & Contact Details (Requires Customer Login).
     */
    public function shipping(): View|RedirectResponse
    {
        if (! Auth::check()) {
            session()->put('url.intended', route('checkout.shipping'));

            return redirect()->route('login')->with('info', 'Please sign in or create an account to proceed with checkout. Your shopping cart has been saved.');
        }

        $cart = session()->get('cart', []);
        if (empty($cart)) {
            return redirect()->route('cart')->with('error', 'Your shopping cart is empty. Please add items before checking out.');
        }

        $coupon = session()->get('coupon');
        $subtotal = array_sum(array_map(fn ($item) => $item['price'] * $item['quantity'], $cart));
        $discount = $this->calculateDiscount($coupon, $subtotal);
        $total = max(0, $subtotal - $discount);

        $savedShipping = session()->get('checkout.shipping', []);
        $user = Auth::user();

        return view('checkout.shipping', compact('cart', 'subtotal', 'discount', 'total', 'savedShipping', 'user'));
    }

    /**
     * Save shipping information to session and proceed to payment.
     */
    public function saveShipping(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'customer_name' => ['required', 'string', 'max:255'],
            'customer_email' => ['required', 'email', 'max:255'],
            'customer_phone' => ['required', 'string', 'max:20'],
            'shipping_address' => ['required', 'string', 'max:500'],
            'city' => ['required', 'string', 'max:100'],
            'state' => ['required', 'string', 'max:100'],
            'postal_code' => ['required', 'string', 'max:20'],
            'country' => ['nullable', 'string', 'max:100'],
        ]);

        $validated['country'] = $validated['country'] ?? 'India';

        session()->put('checkout.shipping', $validated);

        return redirect()->route('checkout.payment');
    }

    /**
     * Step 2: Payment Method.
     */
    public function payment(): View|RedirectResponse
    {
        if (! Auth::check()) {
            return redirect()->route('login');
        }

        $cart = session()->get('cart', []);
        if (empty($cart)) {
            return redirect()->route('cart');
        }

        $shipping = session()->get('checkout.shipping');
        if (empty($shipping)) {
            return redirect()->route('checkout.shipping');
        }

        $coupon = session()->get('coupon');
        $subtotal = array_sum(array_map(fn ($item) => $item['price'] * $item['quantity'], $cart));
        $discount = $this->calculateDiscount($coupon, $subtotal);
        $total = max(0, $subtotal - $discount);

        $selectedPayment = session()->get('checkout.payment', 'UPI');

        return view('checkout.payment', compact('cart', 'shipping', 'subtotal', 'discount', 'total', 'selectedPayment'));
    }

    /**
     * Save payment method to session and proceed to order review.
     */
    public function savePayment(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'payment_method' => ['required', 'string', 'in:UPI,Card,Net Banking,COD'],
        ]);

        session()->put('checkout.payment', $validated['payment_method']);

        return redirect()->route('checkout.review');
    }

    /**
     * Step 3: Order Review & Confirmation.
     */
    public function review(): View|RedirectResponse
    {
        if (! Auth::check()) {
            return redirect()->route('login');
        }

        $cart = session()->get('cart', []);
        if (empty($cart)) {
            return redirect()->route('cart');
        }

        $shipping = session()->get('checkout.shipping');
        if (empty($shipping)) {
            return redirect()->route('checkout.shipping');
        }

        $paymentMethod = session()->get('checkout.payment', 'UPI');

        $coupon = session()->get('coupon');
        $subtotal = array_sum(array_map(fn ($item) => $item['price'] * $item['quantity'], $cart));
        $discount = $this->calculateDiscount($coupon, $subtotal);
        $total = max(0, $subtotal - $discount);

        return view('checkout.review', compact('cart', 'shipping', 'paymentMethod', 'coupon', 'subtotal', 'discount', 'total'));
    }

    /**
     * Place order. Cash on Delivery is confirmed immediately; online payment
     * methods create a Razorpay order and hand off to the secure payment widget.
     */
    public function placeOrder(Request $request): RedirectResponse|View
    {
        if (! Auth::check()) {
            return redirect()->route('login');
        }

        $cart = session()->get('cart', []);
        if (empty($cart)) {
            return redirect()->route('cart')->with('error', 'Your cart is empty.');
        }

        $shipping = session()->get('checkout.shipping');
        if (empty($shipping)) {
            return redirect()->route('checkout.shipping');
        }

        $paymentMethod = session()->get('checkout.payment', 'UPI');
        $coupon = session()->get('coupon');

        $subtotal = array_sum(array_map(fn ($item) => $item['price'] * $item['quantity'], $cart));
        $discount = $this->calculateDiscount($coupon, $subtotal);
        $total = max(0, $subtotal - $discount);

        if ($paymentMethod === 'COD') {
            $order = $this->createOrderRecord($shipping, $paymentMethod, $subtotal, $discount, $total, $cart, $coupon, null, 'pending');

            session()->forget(['cart', 'coupon', 'checkout']);
        $this->cartSync->clear(Auth::id());

            Mail::to($order->customer_email)->send(new OrderConfirmation($order));

            return redirect()->route('order.success', ['order' => $order->order_number]);
        }

        // Online payment: create a Razorpay order and hand off to the secure checkout widget.
        $razorpayOrder = $this->razorpay->createOrder(
            amountInPaise: (int) round($total * 100),
            receipt: 'rcpt_'.Auth::id().'_'.now()->timestamp
        );

        session()->put('checkout.razorpay_order_id', $razorpayOrder['id']);

        return view('checkout.razorpay-pay', [
            'razorpayOrderId' => $razorpayOrder['id'],
            'razorpayKey' => config('services.razorpay.key'),
            'amountInPaise' => (int) round($total * 100),
            'total' => $total,
            'shipping' => $shipping,
            'paymentMethod' => $paymentMethod,
        ]);
    }

    /**
     * Verify the Razorpay payment signature and finalize the order.
     */
    public function verifyPayment(Request $request): RedirectResponse
    {
        if (! Auth::check()) {
            return redirect()->route('login');
        }

        $validated = $request->validate([
            'razorpay_payment_id' => ['required', 'string'],
            'razorpay_order_id' => ['required', 'string'],
            'razorpay_signature' => ['required', 'string'],
        ]);

        $expectedOrderId = session()->get('checkout.razorpay_order_id');

        if (! $expectedOrderId || $expectedOrderId !== $validated['razorpay_order_id']) {
            return redirect()->route('checkout.payment')->with('error', 'Payment session expired. Please try again.');
        }

        if (! $this->razorpay->verifySignature($validated)) {
            return redirect()->route('checkout.payment')->with('error', 'Payment verification failed. Please try again or choose a different payment method.');
        }

        $cart = session()->get('cart', []);
        if (empty($cart)) {
            return redirect()->route('cart')->with('error', 'Your cart is empty.');
        }

        $shipping = session()->get('checkout.shipping');
        if (empty($shipping)) {
            return redirect()->route('checkout.shipping');
        }

        $paymentMethod = session()->get('checkout.payment', 'UPI');
        $coupon = session()->get('coupon');

        $subtotal = array_sum(array_map(fn ($item) => $item['price'] * $item['quantity'], $cart));
        $discount = $this->calculateDiscount($coupon, $subtotal);
        $total = max(0, $subtotal - $discount);

        $order = $this->createOrderRecord($shipping, $paymentMethod, $subtotal, $discount, $total, $cart, $coupon, $validated['razorpay_payment_id'], 'paid');

        session()->forget(['cart', 'coupon', 'checkout']);
        $this->cartSync->clear(Auth::id());

        Mail::to($order->customer_email)->send(new OrderConfirmation($order));

        return redirect()->route('order.success', ['order' => $order->order_number]);
    }

    /**
     * Persist the order and its items, decrement stock, and record coupon usage.
     */
    private function createOrderRecord(
        array $shipping,
        string $paymentMethod,
        float $subtotal,
        float $discount,
        float $total,
        array $cart,
        ?array $coupon,
        ?string $transactionId,
        string $paymentStatus
    ): Order {
        return DB::transaction(function () use ($shipping, $paymentMethod, $subtotal, $discount, $total, $cart, $coupon, $transactionId, $paymentStatus) {
            $order = Order::create([
                'order_number' => Order::generateOrderNumber(),
                'user_id' => Auth::id(),
                'customer_name' => $shipping['customer_name'],
                'customer_email' => $shipping['customer_email'],
                'customer_phone' => $shipping['customer_phone'],
                'shipping_address' => $shipping['shipping_address'],
                'city' => $shipping['city'],
                'state' => $shipping['state'],
                'postal_code' => $shipping['postal_code'],
                'country' => $shipping['country'] ?? 'India',
                'payment_method' => $paymentMethod,
                'payment_status' => $paymentStatus,
                'transaction_id' => $transactionId,
                'subtotal' => $subtotal,
                'discount' => $discount,
                'shipping_fee' => 0.00,
                'total_amount' => $total,
                'status' => 'processing',
            ]);

            // Increment coupon usage count if database coupon was used
            if (! empty($coupon['id'])) {
                Coupon::where('id', $coupon['id'])->increment('used_count');
            }

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

                // Decrement inventory stock
                if (! empty($item['product_id'])) {
                    $prod = Product::find($item['product_id']);
                    if ($prod) {
                        $prod->decrement('stock', max(1, (int) $item['quantity']));
                    }
                }
            }

            return $order;
        });
    }

    /**
     * Display order success confirmation page.
     */
    public function orderSuccess(Request $request): View|RedirectResponse
    {
        $orderNumber = $request->query('order');
        if (! $orderNumber) {
            return redirect()->route('home');
        }

        $order = Order::where('order_number', $orderNumber)->with('items.product')->firstOrFail();

        return view('order-success', compact('order'));
    }

    /**
     * Calculate discount amount safely based on coupon session payload.
     */
    private function calculateDiscount(?array $coupon, float $subtotal): float
    {
        if (! $coupon) {
            return 0.00;
        }

        if (isset($coupon['discount_amount'])) {
            return (float) $coupon['discount_amount'];
        }

        if (isset($coupon['percent'])) {
            return round(($subtotal * $coupon['percent']) / 100, 2);
        }

        return 0.00;
    }
}
