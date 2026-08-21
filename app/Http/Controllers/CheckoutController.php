<?php

namespace App\Http\Controllers;

use App\Exceptions\OutOfStockException;
use App\Mail\OrderConfirmation;
use App\Models\Order;
use App\Notifications\OrderConfirmedNotification;
use App\Services\CartSyncService;
use App\Services\OrderFulfillmentService;
use App\Services\RazorpayService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\View\View;

class CheckoutController extends Controller
{
    public function __construct(
        protected RazorpayService $razorpay,
        protected CartSyncService $cartSync,
        protected OrderFulfillmentService $fulfillment
    ) {}

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
        $discount = $this->fulfillment->resolveDiscount($coupon['id'] ?? null, $subtotal)['discount'];
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
        $discount = $this->fulfillment->resolveDiscount($coupon['id'] ?? null, $subtotal)['discount'];
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
        $discount = $this->fulfillment->resolveDiscount($coupon['id'] ?? null, $subtotal)['discount'];
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
        $couponId = $coupon['id'] ?? null;

        $subtotal = array_sum(array_map(fn ($item) => $item['price'] * $item['quantity'], $cart));

        // Revalidate against the live coupon record before committing anything —
        // the cart may have changed, or a limited coupon may have since been
        // exhausted by another customer, since it was applied in the cart.
        $resolved = $this->fulfillment->resolveDiscount($couponId, $subtotal);
        if ($couponId && ! $resolved['coupon_id']) {
            session()->forget('coupon');

            return redirect()->route('cart')->with('error', 'Your coupon is no longer valid and has been removed. Please review your total and try again.');
        }

        $discount = $resolved['discount'];
        $total = max(0, $subtotal - $discount);

        if ($paymentMethod === 'COD') {
            try {
                $order = $this->fulfillment->createCodOrder(Auth::id(), $shipping, $cart, $resolved['coupon_id'], $subtotal);
            } catch (OutOfStockException $e) {
                return redirect()->route('cart')->with('error', $e->getMessage());
            }

            session()->forget(['cart', 'coupon', 'checkout']);
            $this->cartSync->clear(Auth::id());

            Mail::to($order->customer_email)->send(new OrderConfirmation($order));
            Auth::user()->notify(new OrderConfirmedNotification($order));

            return redirect()->route('order.success', ['order' => $order->order_number]);
        }

        // Online payment: create a Razorpay order, snapshot the cart so a captured
        // payment can still be turned into an order even if the browser never
        // completes the verify() round trip, then hand off to the checkout widget.
        $razorpayOrder = $this->razorpay->createOrder(
            amountInPaise: (int) round($total * 100),
            receipt: 'rcpt_'.Auth::id().'_'.now()->timestamp
        );

        $this->fulfillment->createPendingCheckout(
            userId: Auth::id(),
            razorpayOrderId: $razorpayOrder['id'],
            shipping: $shipping,
            cart: $cart,
            couponId: $resolved['coupon_id'],
            subtotal: $subtotal,
            discount: $discount,
            total: $total,
            paymentMethod: $paymentMethod,
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
     * Verify the Razorpay payment signature and finalize the order. Idempotent:
     * safe to be hit twice for the same payment (double-submit, retry, or a
     * race with the webhook) — only the first call creates the order and
     * sends the confirmation email.
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

        try {
            $result = $this->fulfillment->finalizePayment($validated['razorpay_order_id'], $validated['razorpay_payment_id']);
        } catch (\RuntimeException) {
            return redirect()->route('checkout.payment')->with('error', 'We could not locate your order. Please contact support with payment ID '.$validated['razorpay_payment_id'].'.');
        }

        $order = $result['order'];

        if ((int) $order->user_id !== (int) Auth::id()) {
            abort(403);
        }

        if ($result['created']) {
            Mail::to($order->customer_email)->send(new OrderConfirmation($order));
            Auth::user()->notify(new OrderConfirmedNotification($order));
        }

        session()->forget(['cart', 'coupon', 'checkout']);
        $this->cartSync->clear(Auth::id());

        return redirect()->route('order.success', ['order' => $order->order_number]);
    }

    /**
     * Display order success confirmation page.
     */
    public function orderSuccess(Request $request): View|RedirectResponse
    {
        if (! Auth::check()) {
            return redirect()->route('login');
        }

        $orderNumber = $request->query('order');
        if (! $orderNumber) {
            return redirect()->route('home');
        }

        $order = Order::where('order_number', $orderNumber)
            ->where('user_id', Auth::id())
            ->with('items.product')
            ->firstOrFail();

        return view('order-success', compact('order'));
    }
}
