<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Wishlist;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class WishlistController extends Controller
{
    /**
     * Display customer's wishlist items, paginated so a long-time customer's
     * saved list stays fast to render instead of loading everything at once.
     */
    public function index(Request $request): View
    {
        $perPage = 12;

        if (Auth::check()) {
            // orderByDesc('id') rather than latest(): created_at only has
            // second precision, so wishlist items added in quick succession
            // can tie and sort unpredictably — id is always monotonic.
            $productIds = Auth::user()->wishlists()->orderByDesc('id')->pluck('product_id');
        } else {
            $productIds = collect(session('wishlist', []))->reverse()->values();
        }

        $page = LengthAwarePaginator::resolveCurrentPage();
        $pageIds = $productIds->forPage($page, $perPage);

        $productsById = Product::whereIn('id', $pageIds)->with('category')->get()->keyBy('id');
        $pageProducts = $pageIds->map(fn ($id) => $productsById->get($id))->filter()->values();

        $products = new LengthAwarePaginator(
            $pageProducts,
            $productIds->count(),
            $perPage,
            $page,
            ['path' => $request->url(), 'query' => $request->query()]
        );

        return view('wishlist', compact('products'));
    }

    /**
     * Toggle product in/out of wishlist.
     */
    public function toggle(Request $request): JsonResponse|RedirectResponse
    {
        $productId = $request->input('product_id');
        $product = Product::findOrFail($productId);

        $isSaved = false;

        if (Auth::check()) {
            $user = Auth::user();
            $existing = Wishlist::where('user_id', $user->id)->where('product_id', $product->id)->first();
            if ($existing) {
                $existing->delete();
                $isSaved = false;
                $message = "Removed '{$product->name}' from your wishlist.";
            } else {
                Wishlist::create([
                    'user_id' => $user->id,
                    'product_id' => $product->id,
                ]);
                $isSaved = true;
                $message = "Saved '{$product->name}' to your wishlist.";
            }
            $count = Wishlist::where('user_id', $user->id)->count();
        } else {
            $wishlist = session('wishlist', []);
            if (in_array($product->id, $wishlist)) {
                $wishlist = array_values(array_diff($wishlist, [$product->id]));
                $isSaved = false;
                $message = "Removed '{$product->name}' from your wishlist.";
            } else {
                $wishlist[] = $product->id;
                $isSaved = true;
                $message = "Saved '{$product->name}' to your wishlist.";
            }
            session(['wishlist' => $wishlist]);
            $count = count($wishlist);
        }

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'is_saved' => $isSaved,
                'count' => $count,
                'message' => $message,
            ]);
        }

        return back()->with('success', $message);
    }

    /**
     * Move an item from wishlist into the shopping bag.
     */
    public function moveToCart(Request $request, int $productId): RedirectResponse
    {
        $product = Product::findOrFail($productId);
        $size = $request->input('size', 'M');
        $cartKey = "{$product->id}_{$size}";

        $cart = session('cart', []);

        if (isset($cart[$cartKey])) {
            $cart[$cartKey]['quantity'] += 1;
        } else {
            $cart[$cartKey] = [
                'product_id' => $product->id,
                'name' => $product->name,
                'slug' => $product->slug,
                'sku' => $product->sku,
                'price' => (float) $product->price,
                'main_image' => $product->main_image,
                'size' => $size,
                'quantity' => 1,
            ];
        }

        session(['cart' => $cart]);

        // Remove from wishlist
        if (Auth::check()) {
            Wishlist::where('user_id', Auth::id())->where('product_id', $product->id)->delete();
        } else {
            $wishlist = session('wishlist', []);
            session(['wishlist' => array_values(array_diff($wishlist, [$product->id]))]);
        }

        return redirect()->route('cart')->with('success', "Moved '{$product->name}' to your shopping bag.");
    }
}
