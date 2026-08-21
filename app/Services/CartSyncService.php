<?php

namespace App\Services;

use App\Models\CartItem;

class CartSyncService
{
    /**
     * Persist (create or update) a single cart line for a logged-in user.
     */
    public function upsert(int $userId, string $cartKey, array $item): void
    {
        CartItem::updateOrCreate(
            ['user_id' => $userId, 'cart_key' => $cartKey],
            [
                'product_id' => $item['product_id'] ?? null,
                'name' => $item['name'],
                'slug' => $item['slug'] ?? null,
                'sku' => $item['sku'] ?? null,
                'price' => $item['price'],
                'image' => $item['image'] ?? null,
                'size' => $item['size'] ?? null,
                'color' => $item['color'] ?? null,
                'custom_measurements' => $item['custom_measurements'] ?? null,
                'quantity' => $item['quantity'],
            ]
        );
    }

    /**
     * Remove a single cart line for a logged-in user.
     */
    public function remove(int $userId, string $cartKey): void
    {
        CartItem::where('user_id', $userId)->where('cart_key', $cartKey)->delete();
    }

    /**
     * Clear all persisted cart lines for a user (e.g. after an order is placed).
     */
    public function clear(int $userId): void
    {
        CartItem::where('user_id', $userId)->delete();
    }

    /**
     * Merge a guest session cart into the user's persisted DB cart on login,
     * persist the merged result, and return it in session-cart array shape.
     */
    public function mergeOnLogin(int $userId, array $sessionCart): array
    {
        $merged = [];

        foreach (CartItem::where('user_id', $userId)->get() as $dbItem) {
            $merged[$dbItem->cart_key] = [
                'product_id' => $dbItem->product_id,
                'name' => $dbItem->name,
                'slug' => $dbItem->slug,
                'sku' => $dbItem->sku,
                'price' => (float) $dbItem->price,
                'image' => $dbItem->image,
                'size' => $dbItem->size,
                'color' => $dbItem->color,
                'custom_measurements' => $dbItem->custom_measurements,
                'quantity' => $dbItem->quantity,
            ];
        }

        foreach ($sessionCart as $key => $item) {
            if (isset($merged[$key])) {
                $merged[$key]['quantity'] += $item['quantity'];
            } else {
                $merged[$key] = $item;
            }

            $this->upsert($userId, $key, $merged[$key]);
        }

        return $merged;
    }
}
