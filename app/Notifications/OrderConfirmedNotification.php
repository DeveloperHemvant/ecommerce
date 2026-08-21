<?php

namespace App\Notifications;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;

class OrderConfirmedNotification extends Notification implements ShouldQueue
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
        return [
            'type' => 'order_confirmed',
            'title' => 'Order confirmed',
            'message' => "Your order #{$this->order->order_number} has been placed successfully.",
            'url' => route('account.orders.show', $this->order->order_number),
            'icon' => 'check_circle',
        ];
    }
}
