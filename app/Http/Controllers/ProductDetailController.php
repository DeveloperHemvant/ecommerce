<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\View\View;

class ProductDetailController extends Controller
{
    /**
     * Display the specified product detail page.
     */
    public function show(?string $slug = null): View
    {
        if ($slug) {
            $product = Product::where('slug', $slug)->where('is_active', true)->with('category', 'tags')->first();
        }

        // Fallback to first active product if not found
        if (empty($product)) {
            $product = Product::where('is_active', true)->with('category', 'tags')->firstOrFail();
        }

        // Related "Complete the Look" products
        $relatedProducts = Product::where('is_active', true)
            ->where('id', '!=', $product->id)
            ->take(3)
            ->get();

        return view('product-detail', compact('product', 'relatedProducts'));
    }
}
