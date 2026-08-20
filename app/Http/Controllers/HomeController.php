<?php

namespace App\Http\Controllers;

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

        return view('home', compact('heroVideo', 'lookbookVideos'));
    }
}
