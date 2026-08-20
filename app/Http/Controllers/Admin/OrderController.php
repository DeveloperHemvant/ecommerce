<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
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
     * Update the fulfillment status of an order.
     */
    public function updateStatus(Request $request, Order $order): RedirectResponse
    {
        $validated = $request->validate([
            'status' => ['required', 'string', 'in:processing,packed,shipped,delivered,cancelled'],
        ]);

        $order->update([
            'status' => $validated['status'],
        ]);

        return back()->with('success', "Order #{$order->order_number} status updated to '".ucfirst($validated['status'])."'.");
    }
}
