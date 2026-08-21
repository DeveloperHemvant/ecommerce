<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    /**
     * Lightweight product search suggestions for the header autocomplete.
     */
    public function suggest(Request $request): JsonResponse
    {
        $term = trim((string) $request->query('q', ''));

        if (mb_strlen($term) < 2) {
            return response()->json(['results' => []]);
        }

        $products = Product::where('is_active', true)
            ->where(function ($q) use ($term) {
                $q->where('name', 'like', "%{$term}%")
                    ->orWhere('sku', 'like', "%{$term}%")
                    ->orWhere('description', 'like', "%{$term}%");
            })
            ->limit(6)
            ->get(['id', 'name', 'slug', 'main_image', 'price']);

        $results = $products->map(fn (Product $product) => [
            'name' => $product->name,
            'url' => route('product.detail', $product->slug),
            'image' => $product->main_image,
            'price' => $product->formatted_price,
        ]);

        return response()->json(['results' => $results]);
    }
}
