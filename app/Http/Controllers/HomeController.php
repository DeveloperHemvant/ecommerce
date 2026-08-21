<?php

namespace App\Http\Controllers;

use App\Models\Review;
use App\Models\YouTubeVideo;
use Illuminate\View\View;

class HomeController extends Controller
{
    /**
     * Display the dynamic landing page with YouTube shopping hero and lookbooks.
     */
    public function index(): View
    {
        $heroVideo = YouTubeVideo::where('is_hero', true)
            ->where('is_active', true)
            ->with('products')
            ->first() ?? YouTubeVideo::where('is_active', true)->with('products')->first();

        $lookbookVideos = YouTubeVideo::where('is_lookbook', true)
            ->where('is_active', true)
            ->with('products')
            ->orderBy('display_order', 'asc')
            ->get();

        $testimonials = Review::where('is_approved', true)
            ->with('product:id,name,slug,main_image')
            ->latest()
            ->take(6)
            ->get();

        $reviewCount = Review::where('is_approved', true)->count();
        $averageRating = $reviewCount > 0
            ? round(Review::where('is_approved', true)->avg('rating'), 1)
            : null;

        return view('home', compact('heroVideo', 'lookbookVideos', 'testimonials', 'reviewCount', 'averageRating'));
    }
}
