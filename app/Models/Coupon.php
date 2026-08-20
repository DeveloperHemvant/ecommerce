<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Coupon extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'type',
        'value',
        'min_order_amount',
        'max_discount_amount',
        'description',
        'starts_at',
        'expires_at',
        'usage_limit',
        'used_count',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'value' => 'decimal:2',
            'min_order_amount' => 'decimal:2',
            'max_discount_amount' => 'decimal:2',
            'starts_at' => 'datetime',
            'expires_at' => 'datetime',
            'usage_limit' => 'integer',
            'used_count' => 'integer',
            'is_active' => 'boolean',
        ];
    }

    /**
     * Check if coupon is currently valid for a given order subtotal.
     */
    public function isValidFor(float $subtotal): array
    {
        if (! $this->is_active) {
            return ['valid' => false, 'message' => 'This coupon is no longer active.'];
        }

        if ($this->starts_at && Carbon::now()->lt($this->starts_at)) {
            return ['valid' => false, 'message' => 'This coupon is not valid yet.'];
        }

        if ($this->expires_at && Carbon::now()->gt($this->expires_at)) {
            return ['valid' => false, 'message' => 'This coupon has expired.'];
        }

        if ($this->usage_limit !== null && $this->used_count >= $this->usage_limit) {
            return ['valid' => false, 'message' => 'This coupon has reached its maximum usage limit.'];
        }

        if ($this->min_order_amount > 0 && $subtotal < $this->min_order_amount) {
            return [
                'valid' => false,
                'message' => 'This coupon requires a minimum cart total of ₹'.number_format((float) $this->min_order_amount).'.',
            ];
        }

        return ['valid' => true, 'message' => 'Coupon applied successfully.'];
    }

    /**
     * Calculate discount amount for a given order subtotal.
     */
    public function calculateDiscount(float $subtotal): float
    {
        if ($this->type === 'percent') {
            $discount = ($subtotal * $this->value) / 100;
            if ($this->max_discount_amount && $discount > $this->max_discount_amount) {
                $discount = (float) $this->max_discount_amount;
            }

            return round($discount, 2);
        }

        // Fixed discount
        return min($subtotal, (float) $this->value);
    }
}
