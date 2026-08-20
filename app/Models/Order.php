<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_number',
        'user_id',
        'customer_name',
        'customer_email',
        'customer_phone',
        'shipping_address',
        'city',
        'state',
        'postal_code',
        'country',
        'payment_method',
        'payment_status',
        'transaction_id',
        'subtotal',
        'discount',
        'shipping_fee',
        'total_amount',
        'status',
        'courier_name',
        'tracking_number',
        'tracking_url',
        'estimated_delivery',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'subtotal' => 'decimal:2',
            'discount' => 'decimal:2',
            'shipping_fee' => 'decimal:2',
            'total_amount' => 'decimal:2',
            'estimated_delivery' => 'date',
        ];
    }

    /**
     * Get the customer user who placed the order.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get items in this order.
     */
    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    /**
     * Get reviews linked to this order.
     */
    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }

    /**
     * Get formatted total amount.
     */
    public function getFormattedTotalAttribute(): string
    {
        return '₹'.number_format((float) $this->total_amount);
    }

    /**
     * Get formatted subtotal.
     */
    public function getFormattedSubtotalAttribute(): string
    {
        return '₹'.number_format((float) $this->subtotal);
    }

    /**
     * Get formatted discount.
     */
    public function getFormattedDiscountAttribute(): string
    {
        return '₹'.number_format((float) $this->discount);
    }

    /**
     * Generate unique order number (e.g. ORD-8829).
     */
    public static function generateOrderNumber(): string
    {
        do {
            $number = 'ORD-'.rand(1000, 9999);
        } while (self::where('order_number', $number)->exists());

        return $number;
    }
}
