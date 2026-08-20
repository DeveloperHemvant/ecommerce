<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class YouTubeVideo extends Model
{
    use HasFactory;

    protected $table = 'youtube_videos';

    protected $fillable = [
        'title',
        'slug',
        'youtube_url',
        'youtube_id',
        'thumbnail',
        'duration',
        'views_text',
        'description',
        'is_hero',
        'is_trending',
        'is_lookbook',
        'display_order',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'is_hero' => 'boolean',
            'is_trending' => 'boolean',
            'is_lookbook' => 'boolean',
            'is_active' => 'boolean',
            'display_order' => 'integer',
        ];
    }

    /**
     * Get products featured in this YouTube lookbook.
     */
    public function products(): BelongsToMany
    {
        return $this->belongsToMany(Product::class, 'product_youtube_video', 'youtube_video_id', 'product_id');
    }

    /**
     * Get the YouTube embed URL.
     */
    public function getEmbedUrlAttribute(): ?string
    {
        if ($this->youtube_id) {
            return "https://www.youtube.com/embed/{$this->youtube_id}";
        }

        return $this->youtube_url;
    }

    /**
     * Helper to extract YouTube video ID from various URL formats.
     */
    public static function extractYoutubeId(?string $url): ?string
    {
        if (empty($url)) {
            return null;
        }

        $pattern = '%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?)/|.*[?&]v=)|youtu\.be/)([^"&?/ ]{11})%i';
        if (preg_match($pattern, $url, $matches)) {
            return $matches[1];
        }

        return null;
    }
}
