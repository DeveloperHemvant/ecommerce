<?php

namespace App\Http\Controllers;

use App\Models\Coupon;
use App\Models\Product;
use App\Services\CartSyncService;
use App\Services\OrderFulfillmentService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class CartController extends Controller
{
    public function __construct(protected CartSyncService $cartSync, protected OrderFulfillmentService $fulfillment) {}

    /**
     * Display the shopping cart page.
     */
    public function index(): View
    {
        $cart = session()->get('cart', []);
        $coupon = session()->get('coupon', null);

        $subtotal = 0;
        foreach ($cart as $item) {
            $subtotal += $item['price'] * $item['quantity'];
        }

        // Revalidate live rather than trusting the session snapshot — the cart
        // (and therefore the subtotal a min-order coupon depends on) may have
        // changed since the coupon was applied.
        $discount = 0;
        if ($coupon) {
            $resolved = $this->fulfillment->resolveDiscount($coupon['id'] ?? null, $subtotal);
            if (! $resolved['coupon_id']) {
                session()->forget('coupon');
                $coupon = null;
            } else {
                $discount = $resolved['discount'];
            }
        }

        $total = max(0, $subtotal - $discount);

        return view('cart', compact('cart', 'subtotal', 'discount', 'coupon', 'total'));
    }

    /**
     * Add a product to the shopping cart (supports Custom Fit specs).
     */
    public function add(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'product_id' => ['required', 'exists:products,id'],
            'quantity' => ['nullable', 'integer', 'min:1'],
            'size' => ['nullable', 'string', 'max:50'],
            'color' => ['nullable', 'string', 'max:50'],
            'custom_measurements' => ['nullable', 'array'],
        ]);

        $product = Product::findOrFail($validated['product_id']);

        if ($product->stock <= 0) {
            return back()->with('error', "Sorry, '{$product->name}' is currently out of stock.");
        }

        $size = $validated['size'] ?? ($product->sizes[0] ?? 'Standard');
        $color = $validated['color'] ?? ($product->colors[0] ?? 'Default');
        $quantity = (int) ($validated['quantity'] ?? 1);
        $measurements = $validated['custom_measurements'] ?? null;

        // Key by product_id + size + hash of measurements if custom fit
        $measurementKey = ! empty($measurements) ? '_'.substr(md5(json_encode($measurements)), 0, 6) : '';
        $cartKey = "{$product->id}_{$size}{$measurementKey}";
        $cart = session()->get('cart', []);

        if (isset($cart[$cartKey])) {
            $cart[$cartKey]['quantity'] += $quantity;
        } else {
            $cart[$cartKey] = [
                'product_id' => $product->id,
                'name' => $product->name,
                'slug' => $product->slug,
                'sku' => $product->sku,
                'price' => (float) $product->price,
                'image' => $product->main_image,
                'size' => $size,
                'color' => $color,
                'custom_measurements' => $measurements,
                'quantity' => $quantity,
            ];
        }

        session()->put('cart', $cart);

        if (Auth::check()) {
            $this->cartSync->upsert(Auth::id(), $cartKey, $cart[$cartKey]);
        }

        if ($request->filled('buy_now')) {
            return redirect()->route('checkout.shipping');
        }

        return redirect()->route('cart')->with('success', "Added '{$product->name}' to your shopping bag.");
    }

    /**
     * Update item quantity in the cart.
     */
    public function updateQuantity(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'cart_key' => ['required', 'string'],
            'action' => ['required', 'in:increase,decrease'],
        ]);

        $cart = session()->get('cart', []);
        $key = $validated['cart_key'];

        if (isset($cart[$key])) {
            if ($validated['action'] === 'increase') {
                $cart[$key]['quantity'] += 1;
            } elseif ($validated['action'] === 'decrease') {
                $cart[$key]['quantity'] -= 1;
                if ($cart[$key]['quantity'] <= 0) {
                    unset($cart[$key]);
                }
            }
            session()->put('cart', $cart);

            if (Auth::check()) {
                if (isset($cart[$key])) {
                    $this->cartSync->upsert(Auth::id(), $key, $cart[$key]);
                } else {
                    $this->cartSync->remove(Auth::id(), $key);
                }
            }
        }

        return redirect()->route('cart');
    }

    /**
     * Remove an item from the cart.
     */
    public function remove(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'cart_key' => ['required', 'string'],
        ]);

        $cart = session()->get('cart', []);
        if (isset($cart[$validated['cart_key']])) {
            $name = $cart[$validated['cart_key']]['name'];
            unset($cart[$validated['cart_key']]);
            session()->put('cart', $cart);

            if (Auth::check()) {
                $this->cartSync->remove(Auth::id(), $validated['cart_key']);
            }

            return redirect()->route('cart')->with('success', "Removed '{$name}' from your cart.");
        }

        return redirect()->route('cart');
    }

    /**
     * Apply a discount coupon dynamically from DB.
     */
    public function applyCoupon(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'code' => ['required', 'string', 'max:50'],
        ]);

        $code = strtoupper(trim($validated['code']));
        $cart = session()->get('cart', []);

        $subtotal = 0;
        foreach ($cart as $item) {
            $subtotal += $item['price'] * $item['quantity'];
        }

        $coupon = Coupon::where('code', $code)->first();

        if (! $coupon) {
            return redirect()->route('cart')->with('error', "Invalid coupon code '{$code}'.");
        }

        $check = $coupon->isValidFor($subtotal);
        if (! $check['valid']) {
            return redirect()->route('cart')->with('error', $check['message']);
        }

        $discountAmount = $coupon->calculateDiscount($subtotal);

        session()->put('coupon', [
            'id' => $coupon->id,
            'code' => $coupon->code,
            'type' => $coupon->type,
            'value' => (float) $coupon->value,
            'discount_amount' => $discountAmount,
            'percent' => $coupon->type === 'percent' ? (int) $coupon->value : null,
            'description' => $coupon->description ?? "Coupon {$coupon->code}",
        ]);

        return redirect()->route('cart')->with('success', "Coupon '{$coupon->code}' applied! You saved ₹".number_format($discountAmount).'.');
    }

    /**
     * Remove the active coupon.
     */
    public function removeCoupon(): RedirectResponse
    {
        session()->forget('coupon');

        return redirect()->route('cart')->with('success', 'Coupon removed.');
    }
}
