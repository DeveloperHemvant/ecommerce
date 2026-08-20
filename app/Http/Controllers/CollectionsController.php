<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Models\YouTubeVideo;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CollectionsController extends Controller
{
    /**
     * Display collections page with categories, products, and dynamic trending lookbook video.
     */
    public function index(Request $request): View
    {
        $categories = Category::where('is_active', true)
            ->withCount('products')
            ->orderBy('display_order', 'asc')
            ->get();

        $query = Product::where('is_active', true)->with('category');

        // Category filter
        $selectedCategory = null;
        if ($request->filled('category')) {
            $selectedCategory = Category::where('slug', $request->category)->first();
            if ($selectedCategory) {
                $query->where('category_id', $selectedCategory->id);
            }
        }

        // Search filter
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%")
                    ->orWhere('sku', 'like', "%{$search}%");
            });
        }

        $products = $query->latest()->get();

        // Trending YouTube Lookbook Video
        $trendingVideo = YouTubeVideo::where('is_trending', true)
            ->where('is_active', true)
            ->with('products')
            ->first() ?? YouTubeVideo::where('is_active', true)->with('products')->first();

        return view('collections', compact('categories', 'products', 'selectedCategory', 'trendingVideo'));
    }
}
