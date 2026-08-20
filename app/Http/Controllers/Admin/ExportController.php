<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\User;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ExportController extends Controller
{
    /**
     * Export all orders to CSV.
     */
    public function orders(): StreamedResponse
    {
        $fileName = 'orders-export-'.date('Y-m-d').'.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$fileName}\"",
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0',
        ];

        return response()->stream(function () {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, [
                'Order ID',
                'Customer Name',
                'Email',
                'Phone',
                'Shipping Address',
                'City',
                'State',
                'Postal Code',
                'Payment Method',
                'Payment Status',
                'Subtotal',
                'Discount',
                'Total Amount',
                'Fulfillment Status',
                'Courier Name',
                'Tracking Number',
                'Order Date',
            ]);

            Order::orderBy('id', 'desc')->chunk(100, function ($orders) use ($handle) {
                foreach ($orders as $order) {
                    fputcsv($handle, [
                        $order->order_number,
                        $order->customer_name,
                        $order->customer_email,
                        $order->customer_phone,
                        $order->shipping_address,
                        $order->city,
                        $order->state,
                        $order->postal_code,
                        $order->payment_method,
                        $order->payment_status,
                        $order->subtotal,
                        $order->discount,
                        $order->total_amount,
                        $order->status,
                        $order->courier_name,
                        $order->tracking_number,
                        $order->created_at->toDateTimeString(),
                    ]);
                }
            });

            fclose($handle);
        }, 200, $headers);
    }

    /**
     * Export all customers to CSV.
     */
    public function customers(): StreamedResponse
    {
        $fileName = 'customers-export-'.date('Y-m-d').'.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$fileName}\"",
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0',
        ];

        return response()->stream(function () {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, [
                'Customer ID',
                'Full Name',
                'Email Address',
                'Phone Number',
                'Total Orders Count',
                'Lifetime Spend (INR)',
                'Registered Date',
            ]);

            User::where('role', 'customer')->orderBy('id', 'desc')->chunk(100, function ($customers) use ($handle) {
                foreach ($customers as $cust) {
                    fputcsv($handle, [
                        $cust->id,
                        $cust->name,
                        $cust->email,
                        $cust->phone ?? 'N/A',
                        $cust->orders()->count(),
                        $cust->lifetime_spend,
                        $cust->created_at->toDateTimeString(),
                    ]);
                }
            });

            fclose($handle);
        }, 200, $headers);
    }
}
