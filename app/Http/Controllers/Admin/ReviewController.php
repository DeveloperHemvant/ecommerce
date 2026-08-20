<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Review;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ReviewController extends Controller
{
    /**
     * Display a listing of customer reviews for moderation.
     */
    public function index(Request $request): View
    {
        $query = Review::with(['product', 'user']);

        if ($request->filled('status')) {
            if ($request->status === 'pending') {
                $query->where('is_approved', false);
            } elseif ($request->status === 'approved') {
                $query->where('is_approved', true);
            }
        }

        $reviews = $query->latest()->paginate(15)->withQueryString();

        return view('admin.reviews.index', compact('reviews'));
    }

    /**
     * Toggle review approval status.
     */
    public function toggleApproval(Review $review): RedirectResponse
    {
        $review->update(['is_approved' => ! $review->is_approved]);

        // Refresh parent product rating
        $product = $review->product;
        if ($product) {
            $approvedReviews = $product->reviews()->where('is_approved', true)->get();
            $product->update([
                'rating' => $approvedReviews->avg('rating') ?: 5.0,
                'reviews_count' => $approvedReviews->count(),
            ]);
        }

        $status = $review->is_approved ? 'approved' : 'hidden';

        return back()->with('success', "Review #{$review->id} is now {$status}.");
    }

    /**
     * Remove the review from storage.
     */
    public function destroy(Review $review): RedirectResponse
    {
        $product = $review->product;
        $review->delete();

        if ($product) {
            $approvedReviews = $product->reviews()->where('is_approved', true)->get();
            $product->update([
                'rating' => $approvedReviews->avg('rating') ?: 5.0,
                'reviews_count' => $approvedReviews->count(),
            ]);
        }

        return back()->with('success', 'Review deleted successfully.');
    }
}
