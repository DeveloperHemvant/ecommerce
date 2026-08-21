<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Models\YouTubeVideo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
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

        // Price range filter
        if ($request->filled('min_price')) {
            $query->where('price', '>=', (float) $request->min_price);
        }
        if ($request->filled('max_price')) {
            $query->where('price', '<=', (float) $request->max_price);
        }

        // Size filter
        if ($request->filled('size')) {
            $query->whereJsonContains('sizes', $request->size);
        }

        // Color filter
        if ($request->filled('color')) {
            $query->whereJsonContains('colors', $request->color);
        }

        $products = $query->latest()->paginate(12)->withQueryString();

        // Distinct filter options drawn from the active catalog — cached since
        // this is a full-column scan that would otherwise re-run on every
        // collections page view and every filter change as the catalog grows.
        // Admin\ProductController clears these keys whenever a product is
        // created, updated, or deleted.
        $availableSizes = Cache::remember('catalog.available_sizes', now()->addHour(), function () {
            return Product::where('is_active', true)->pluck('sizes')
                ->flatten(1)->filter()->unique()->sort()->values();
        });
        $availableColors = Cache::remember('catalog.available_colors', now()->addHour(), function () {
            return Product::where('is_active', true)->pluck('colors')
                ->flatten(1)->filter()->unique()->sort()->values();
        });

        // Trending YouTube Lookbook Video
        $trendingVideo = YouTubeVideo::where('is_trending', true)
            ->where('is_active', true)
            ->with('products')
            ->first() ?? YouTubeVideo::where('is_active', true)->with('products')->first();

        return view('collections', compact('categories', 'products', 'selectedCategory', 'trendingVideo', 'availableSizes', 'availableColors'));
    }
}
