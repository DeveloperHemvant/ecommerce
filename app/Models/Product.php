<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id',
        'name',
        'slug',
        'sku',
        'price',
        'compare_price',
        'stock',
        'low_stock_threshold',
        'main_image',
        'video',
        'gallery_images',
        'sizes',
        'colors',
        'description',
        'fabric_care',
        'shipping_info',
        'rating',
        'reviews_count',
        'is_featured',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'price' => 'decimal:2',
            'compare_price' => 'decimal:2',
            'stock' => 'integer',
            'low_stock_threshold' => 'integer',
            'gallery_images' => 'array',
            'sizes' => 'array',
            'colors' => 'array',
            'rating' => 'decimal:2',
            'reviews_count' => 'integer',
            'is_featured' => 'boolean',
            'is_active' => 'boolean',
        ];
    }

    /**
     * Get the category that owns the product.
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Get tags associated with this product.
     */
    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class);
    }

    /**
     * Get YouTube videos that feature this product.
     */
    public function youtubeVideos(): BelongsToMany
    {
        return $this->belongsToMany(YouTubeVideo::class, 'product_youtube_video', 'product_id', 'youtube_video_id');
    }

    /**
     * Calculate discount percentage if compare_price is set.
     */
    public function getDiscountPercentageAttribute(): ?int
    {
        if ($this->compare_price && $this->compare_price > $this->price) {
            return (int) round((($this->compare_price - $this->price) / $this->compare_price) * 100);
        }

        return null;
    }

    /**
     * Check if product is in stock.
     */
    public function getIsInStockAttribute(): bool
    {
        return $this->stock > 0;
    }

    /**
     * Check if product is at low stock.
     */
    public function getIsLowStockAttribute(): bool
    {
        return $this->stock > 0 && $this->stock <= $this->low_stock_threshold;
    }

    /**
     * Get formatted price string.
     */
    public function getFormattedPriceAttribute(): string
    {
        return '₹'.number_format((float) $this->price);
    }

    /**
     * Get formatted compare price string.
     */
    public function getFormattedComparePriceAttribute(): ?string
    {
        return $this->compare_price ? '₹'.number_format((float) $this->compare_price) : null;
    }
}
