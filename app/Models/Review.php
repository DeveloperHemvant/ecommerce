<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Review extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'user_id',
        'order_id',
        'customer_name',
        'rating',
        'title',
        'comment',
        'photos',
        'is_verified_buyer',
        'is_approved',
    ];

    protected function casts(): array
    {
        return [
            'rating' => 'integer',
            'photos' => 'array',
            'is_verified_buyer' => 'boolean',
            'is_approved' => 'boolean',
        ];
    }

    /**
     * Get the reviewed product.
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Get the user who submitted the review.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the order linked to this review (if any).
     */
    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }
}
