<?php

namespace App\Notifications;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;

class OrderStatusChangedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(public Order $order) {}

    /**
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        $status = ucfirst($this->order->status);

        return [
            'type' => 'order_status_changed',
            'title' => "Order {$status}",
            'message' => "Your order #{$this->order->order_number} is now {$status}.",
            'url' => route('account.orders.show', $this->order->order_number),
            'icon' => match ($this->order->status) {
                'shipped' => 'local_shipping',
                'delivered' => 'package_2',
                'cancelled' => 'cancel',
                'packed' => 'inventory_2',
                default => 'info',
            },
        ];
    }
}
