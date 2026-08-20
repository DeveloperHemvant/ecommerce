<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Review;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ReviewController extends Controller
{
    /**
     * Store a newly created product review.
     */
    public function store(Request $request, int $productId): RedirectResponse
    {
        $product = Product::findOrFail($productId);

        $validated = $request->validate([
            'rating' => ['required', 'integer', 'min:1', 'max:5'],
            'customer_name' => ['required', 'string', 'max:255'],
            'title' => ['nullable', 'string', 'max:255'],
            'comment' => ['required', 'string', 'min:10', 'max:2000'],
            'photos' => ['nullable', 'array', 'max:5'],
            'photos.*' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:10240'],
        ]);

        $photoPaths = [];
        if ($request->hasFile('photos')) {
            foreach ($request->file('photos') as $file) {
                if ($file) {
                    $path = $file->store('reviews', 'public');
                    $photoPaths[] = Storage::url($path);
                }
            }
        }

        $userId = Auth::id();
        $isVerified = false;
        if ($userId) {
            $isVerified = Auth::user()->orders()
                ->whereHas('items', fn ($q) => $q->where('product_id', $product->id))
                ->exists();
        }

        Review::create([
            'product_id' => $product->id,
            'user_id' => $userId,
            'customer_name' => $validated['customer_name'],
            'rating' => (int) $validated['rating'],
            'title' => $validated['title'] ?? null,
            'comment' => $validated['comment'],
            'photos' => $photoPaths,
            'is_verified_buyer' => $isVerified,
            'is_approved' => true,
        ]);

        // Recalculate Product average rating & count
        $approvedReviews = $product->reviews()->where('is_approved', true)->get();
        $product->update([
            'rating' => $approvedReviews->avg('rating') ?: 5.0,
            'reviews_count' => $approvedReviews->count(),
        ]);

        return back()->with('success', 'Thank you! Your review and outfit photos have been submitted.');
    }
}
