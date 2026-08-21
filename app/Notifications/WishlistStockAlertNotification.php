<?php

namespace App\Notifications;

use App\Models\Product;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;

class WishlistStockAlertNotification extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * @param  'low_stock'|'out_of_stock'  $type
     */
    public function __construct(public Product $product, public string $type) {}

    /**
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        $isOut = $this->type === 'out_of_stock';

        return [
            'type' => 'wishlist_stock_alert',
            'title' => $isOut ? 'Wishlist item sold out' : 'Almost gone from your wishlist',
            'message' => $isOut
                ? "'{$this->product->name}' just sold out."
                : "'{$this->product->name}' is running low — only {$this->product->stock} left.",
            'url' => route('product.detail', $this->product->slug),
            'icon' => $isOut ? 'remove_shopping_cart' : 'timer',
        ];
    }
}
