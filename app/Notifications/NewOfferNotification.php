<?php

namespace App\Notifications;

use App\Models\Coupon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;

class NewOfferNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(public Coupon $coupon) {}

    /**
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        $saving = $this->coupon->type === 'percent'
            ? "{$this->coupon->value}% off"
            : '₹'.number_format((float) $this->coupon->value).' off';

        return [
            'type' => 'new_offer',
            'title' => 'New offer just dropped',
            'message' => "Use code {$this->coupon->code} for {$saving}".($this->coupon->description ? " — {$this->coupon->description}" : '').'.',
            'url' => route('collections'),
            'icon' => 'sell',
        ];
    }
}
