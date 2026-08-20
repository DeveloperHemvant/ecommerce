<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'product_id',
        'product_name',
        'product_sku',
        'price',
        'quantity',
        'size',
        'color',
        'custom_measurements',
        'product_image',
        'total',
    ];

    protected function casts(): array
    {
        return [
            'price' => 'decimal:2',
            'quantity' => 'integer',
            'custom_measurements' => 'array',
            'total' => 'decimal:2',
        ];
    }

    /**
     * Get the order that owns this item.
     */
    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    /**
     * Get the product (if still existing).
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Formatted item total.
     */
    public function getFormattedTotalAttribute(): string
    {
        return '₹'.number_format((float) $this->total);
    }

    /**
     * Formatted unit price.
     */
    public function getFormattedPriceAttribute(): string
    {
        return '₹'.number_format((float) $this->price);
    }
}
