<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\OrderStatusUpdated;
use App\Models\Order;
use App\Notifications\OrderStatusChangedNotification;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\View\View;

class OrderController extends Controller
{
    /**
     * Display a listing of customer orders with filters and search.
     */
    public function index(Request $request): View
    {
        $query = Order::with('items', 'user')->latest();

        // Status filter
        if ($request->filled('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        // Search filter
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('order_number', 'like', "%{$search}%")
                    ->orWhere('customer_name', 'like', "%{$search}%")
                    ->orWhere('customer_email', 'like', "%{$search}%");
            });
        }

        $orders = $query->paginate(15)->withQueryString();

        return view('admin.orders.index', compact('orders'));
    }

    /**
     * Display the specified order detail view.
     */
    public function show(string $orderNumber): View
    {
        $order = Order::where('order_number', $orderNumber)
            ->with(['items.product', 'user'])
            ->firstOrFail();

        return view('admin.order-detail', compact('order'));
    }

    /**
     * Update the fulfillment status and courier tracking info of an order.
     */
    public function updateStatus(Request $request, Order $order): RedirectResponse
    {
        $validated = $request->validate([
            'status' => ['required', 'string', 'in:processing,packed,shipped,delivered,cancelled'],
            'courier_name' => ['nullable', 'string', 'max:100'],
            'tracking_number' => ['nullable', 'string', 'max:100'],
            'tracking_url' => ['nullable', 'string', 'max:500'],
        ]);

        $paymentStatus = $order->payment_status;

        // COD orders sit at payment_status=pending until cash is actually
        // collected, which happens at delivery — without this, COD revenue
        // (the majority of orders for this market) would never appear as
        // "paid" anywhere, including every report on this page.
        if ($validated['status'] === 'delivered' && $order->payment_method === 'COD' && $order->payment_status === 'pending') {
            $paymentStatus = 'paid';
        }

        $order->update([
            'status' => $validated['status'],
            'payment_status' => $paymentStatus,
            'courier_name' => $validated['courier_name'] ?? $order->courier_name,
            'tracking_number' => $validated['tracking_number'] ?? $order->tracking_number,
            'tracking_url' => $validated['tracking_url'] ?? $order->tracking_url,
        ]);

        Mail::to($order->customer_email)->send(new OrderStatusUpdated($order));
        $order->user?->notify(new OrderStatusChangedNotification($order));

        return back()->with('success', "Order #{$order->order_number} status updated to '".ucfirst($validated['status'])."'.");
    }
}
