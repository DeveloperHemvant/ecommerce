<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\View\View;

class OrderTrackingController extends Controller
{
    /**
     * Look up and display real-time order tracking.
     */
    public function index(Request $request): View
    {
        $orderQuery = $request->input('order');
        $phoneQuery = $request->input('phone');

        $order = null;
        $searched = false;
        $error = null;

        if (! empty($orderQuery)) {
            $searched = true;
            $cleanNumber = strtoupper(trim(str_replace('#', '', $orderQuery)));

            $query = Order::where('order_number', $cleanNumber)->with('items.product');

            if (! empty($phoneQuery)) {
                $cleanPhone = preg_replace('/[^0-9]/', '', $phoneQuery);
                $query->where(function ($q) use ($cleanPhone, $phoneQuery) {
                    $q->where('customer_phone', 'like', "%{$cleanPhone}%")
                        ->orWhere('customer_phone', 'like', "%{$phoneQuery}%");
                });
            }

            $order = $query->first();

            if (! $order) {
                $error = "No active shipment found for Order #{$cleanNumber}. Please check the order reference number.";
            }
        }

        return view('track-order', compact('order', 'searched', 'error', 'orderQuery', 'phoneQuery'));
    }
}
