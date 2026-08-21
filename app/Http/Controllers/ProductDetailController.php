<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\View\View;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ProductDetailController extends Controller
{
    /**
     * Display the specified product detail page.
     */
    public function show(?string $slug = null): View
    {
        if (! $slug) {
            throw new NotFoundHttpException;
        }

        $product = Product::where('slug', $slug)->where('is_active', true)->with('category', 'tags')->first();

        if (! $product) {
            throw new NotFoundHttpException;
        }

        // Related "Complete the Look" products — prefer the same category, then
        // shared tags, and fall back to other active products to always fill the row.
        $relatedProducts = Product::where('is_active', true)
            ->where('id', '!=', $product->id)
            ->where('category_id', $product->category_id)
            ->inRandomOrder()
            ->take(4)
            ->get();

        if ($relatedProducts->count() < 4 && $product->tags->isNotEmpty()) {
            $tagIds = $product->tags->pluck('id');
            $excludeIds = $relatedProducts->pluck('id')->push($product->id);

            $tagMatches = Product::where('is_active', true)
                ->whereNotIn('id', $excludeIds)
                ->whereHas('tags', fn ($q) => $q->whereIn('tags.id', $tagIds))
                ->inRandomOrder()
                ->take(4 - $relatedProducts->count())
                ->get();

            $relatedProducts = $relatedProducts->concat($tagMatches);
        }

        if ($relatedProducts->count() < 4) {
            $excludeIds = $relatedProducts->pluck('id')->push($product->id);

            $fallback = Product::where('is_active', true)
                ->whereNotIn('id', $excludeIds)
                ->inRandomOrder()
                ->take(4 - $relatedProducts->count())
                ->get();

            $relatedProducts = $relatedProducts->concat($fallback);
        }

        return view('product-detail', compact('product', 'relatedProducts'));
    }
}
