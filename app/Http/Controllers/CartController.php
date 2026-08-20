<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CartController extends Controller
{
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

        $discount = 0;
        if ($coupon) {
            $discount = round(($subtotal * $coupon['percent']) / 100);
        }

        $total = max(0, $subtotal - $discount);

        return view('cart', compact('cart', 'subtotal', 'discount', 'coupon', 'total'));
    }

    /**
     * Add a product to the shopping cart.
     */
    public function add(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'product_id' => ['required', 'exists:products,id'],
            'quantity' => ['nullable', 'integer', 'min:1'],
            'size' => ['nullable', 'string', 'max:50'],
            'color' => ['nullable', 'string', 'max:50'],
        ]);

        $product = Product::findOrFail($validated['product_id']);

        if ($product->stock <= 0) {
            return back()->with('error', "Sorry, '{$product->name}' is currently out of stock.");
        }

        $size = $validated['size'] ?? ($product->sizes[0] ?? 'Standard');
        $color = $validated['color'] ?? ($product->colors[0] ?? 'Default');
        $quantity = (int) ($validated['quantity'] ?? 1);

        $cartKey = "{$product->id}_{$size}";
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
                'quantity' => $quantity,
            ];
        }

        session()->put('cart', $cart);

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

            return redirect()->route('cart')->with('success', "Removed '{$name}' from your cart.");
        }

        return redirect()->route('cart');
    }

    /**
     * Apply a discount coupon.
     */
    public function applyCoupon(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'code' => ['required', 'string', 'max:50'],
        ]);

        $code = strtoupper(trim($validated['code']));

        if ($code === 'WELCOME10') {
            session()->put('coupon', [
                'code' => 'WELCOME10',
                'percent' => 10,
                'description' => '10% Welcome Discount',
            ]);

            return redirect()->route('cart')->with('success', "Coupon 'WELCOME10' applied! You got 10% OFF.");
        } elseif ($code === 'HERITAGE20') {
            session()->put('coupon', [
                'code' => 'HERITAGE20',
                'percent' => 20,
                'description' => '20% Heritage Festive Discount',
            ]);

            return redirect()->route('cart')->with('success', "Coupon 'HERITAGE20' applied! You got 20% OFF.");
        }

        return redirect()->route('cart')->with('error', "Invalid coupon code '{$code}'. Try WELCOME10 for 10% off.");
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
