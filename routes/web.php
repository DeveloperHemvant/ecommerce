<?php

use App\Http\Controllers\AccountController;
use App\Http\Controllers\Admin\AuthController as AdminAuthController;
use App\Http\Controllers\Admin\CategoryController as AdminCategoryController;
use App\Http\Controllers\Admin\CouponController as AdminCouponController;
use App\Http\Controllers\Admin\CustomerController as AdminCustomerController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\ExportController as AdminExportController;
use App\Http\Controllers\Admin\OrderController as AdminOrderController;
use App\Http\Controllers\Admin\ProductController as AdminProductController;
use App\Http\Controllers\Admin\ReviewController as AdminReviewController;
use App\Http\Controllers\Admin\YouTubeVideoController as AdminYouTubeVideoController;
use App\Http\Controllers\Auth\CustomerAuthController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\CollectionsController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\OrderTrackingController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\ProductDetailController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\WishlistController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Progressive Web App (PWA) Routes
|--------------------------------------------------------------------------
*/
Route::get('/manifest.json', function () {
    $content = file_get_contents(public_path('manifest.json'));

    return response($content, 200, [
        'Content-Type' => 'application/manifest+json; charset=utf-8',
        'Cache-Control' => 'public, max-age=3600',
    ]);
})->name('pwa.manifest');

Route::get('/sw.js', function () {
    $content = file_get_contents(public_path('sw.js'));

    return response($content, 200, [
        'Content-Type' => 'application/javascript; charset=utf-8',
        'Cache-Control' => 'no-cache',
    ]);
})->name('pwa.serviceworker');

Route::get('/offline.html', function () {
    $content = file_get_contents(public_path('offline.html'));

    return response($content, 200, [
        'Content-Type' => 'text/html; charset=utf-8',
    ]);
})->name('pwa.offline');

/*
|--------------------------------------------------------------------------
| Storefront & Public Routes
|--------------------------------------------------------------------------
*/
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/collections', [CollectionsController::class, 'index'])->name('collections');
Route::get('/shop', [CollectionsController::class, 'index'])->name('shop');
Route::get('/products/{slug?}', [ProductDetailController::class, 'show'])->name('product.detail');

// Company & Information Pages
Route::get('/about-us', [PageController::class, 'about'])->name('about');
Route::get('/contact-us', [PageController::class, 'contact'])->name('contact');
Route::post('/contact-us', [PageController::class, 'contactSubmit'])->name('contact.submit');

// Legal & Customer Policies
Route::get('/shipping-policy', [PageController::class, 'shipping'])->name('shipping.policy');
Route::get('/terms-of-service', [PageController::class, 'terms'])->name('terms');
Route::get('/return-policy', [PageController::class, 'returns'])->name('return.policy');
Route::get('/privacy-policy', [PageController::class, 'privacy'])->name('privacy.policy');

// Live Public Order Tracking
Route::get('/track-order', [OrderTrackingController::class, 'index'])->name('track.order');

// Customer Reviews Submission
Route::post('/products/{product}/reviews', [ReviewController::class, 'store'])->name('reviews.store');

/*
|--------------------------------------------------------------------------
| Wishlist Routes
|--------------------------------------------------------------------------
*/
Route::get('/wishlist', [WishlistController::class, 'index'])->name('wishlist');
Route::post('/wishlist/toggle', [WishlistController::class, 'toggle'])->name('wishlist.toggle');
Route::post('/wishlist/{product}/move-to-cart', [WishlistController::class, 'moveToCart'])->name('wishlist.move-to-cart');

/*
|--------------------------------------------------------------------------
| Shopping Cart Routes
|--------------------------------------------------------------------------
*/
Route::get('/cart', [CartController::class, 'index'])->name('cart');
Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add');
Route::post('/cart/update', [CartController::class, 'updateQuantity'])->name('cart.update');
Route::post('/cart/remove', [CartController::class, 'remove'])->name('cart.remove');
Route::post('/cart/coupon', [CartController::class, 'applyCoupon'])->name('cart.coupon');
Route::post('/cart/coupon/remove', [CartController::class, 'removeCoupon'])->name('cart.coupon.remove');

/*
|--------------------------------------------------------------------------
| Customer Authentication Routes
|--------------------------------------------------------------------------
*/
Route::get('/login', [CustomerAuthController::class, 'showLogin'])->name('login');
Route::post('/login', [CustomerAuthController::class, 'login'])->name('login.submit');
Route::get('/register', [CustomerAuthController::class, 'showRegister'])->name('register');
Route::post('/register', [CustomerAuthController::class, 'register'])->name('register.submit');
Route::post('/logout', [CustomerAuthController::class, 'logout'])->name('logout');

/*
|--------------------------------------------------------------------------
| Customer Account Portal (Requires Auth)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->prefix('account')->name('account.')->group(function () {
    Route::get('/orders', [AccountController::class, 'orders'])->name('orders');
    Route::get('/orders/{orderNumber}', [AccountController::class, 'orderDetail'])->name('orders.show');
    Route::get('/profile', [AccountController::class, 'profile'])->name('profile');
    Route::post('/profile', [AccountController::class, 'updateProfile'])->name('profile.update');
});

/*
|--------------------------------------------------------------------------
| Checkout Flow Routes (Requires Auth)
|--------------------------------------------------------------------------
*/
Route::get('/checkout', [CheckoutController::class, 'shipping'])->name('checkout.shipping');
Route::post('/checkout/shipping', [CheckoutController::class, 'saveShipping'])->name('checkout.shipping.save');
Route::get('/checkout/payment', [CheckoutController::class, 'payment'])->name('checkout.payment');
Route::post('/checkout/payment', [CheckoutController::class, 'savePayment'])->name('checkout.payment.save');
Route::get('/checkout/review', [CheckoutController::class, 'review'])->name('checkout.review');
Route::post('/checkout/place-order', [CheckoutController::class, 'placeOrder'])->name('checkout.place_order');
Route::get('/order-success', [CheckoutController::class, 'orderSuccess'])->name('order.success');

/*
|--------------------------------------------------------------------------
| Admin Backoffice & Management Routes
|--------------------------------------------------------------------------
*/
Route::prefix('admin')->name('admin.')->group(function () {
    // Admin Guest Routes
    Route::get('/login', [AdminAuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AdminAuthController::class, 'login'])->name('login.submit');
    Route::post('/logout', [AdminAuthController::class, 'logout'])->name('logout');

    // Admin Protected Routes
    Route::middleware(['admin'])->group(function () {
        Route::get('/', [AdminDashboardController::class, 'index'])->name('dashboard');
        Route::get('/dashboard', [AdminDashboardController::class, 'index']);

        // Category Management
        Route::resource('categories', AdminCategoryController::class)->except(['show']);

        // Product & Stock Management
        Route::resource('products', AdminProductController::class)->except(['show']);

        // YouTube Lookbook CMS
        Route::resource('youtube', AdminYouTubeVideoController::class)->except(['show']);

        // Promotional Coupons Engine
        Route::resource('coupons', AdminCouponController::class)->except(['show']);

        // Customer Reviews Moderation
        Route::get('/reviews', [AdminReviewController::class, 'index'])->name('reviews.index');
        Route::post('/reviews/{review}/toggle', [AdminReviewController::class, 'toggleApproval'])->name('reviews.toggle');
        Route::delete('/reviews/{review}', [AdminReviewController::class, 'destroy'])->name('reviews.destroy');

        // Orders Management
        Route::get('/orders', [AdminOrderController::class, 'index'])->name('orders.index');
        Route::get('/orders/{orderNumber}', [AdminOrderController::class, 'show'])->name('orders.show');
        Route::post('/orders/{order}/status', [AdminOrderController::class, 'updateStatus'])->name('orders.status');

        // Customer Directory
        Route::get('/customers', [AdminCustomerController::class, 'index'])->name('customers.index');
        Route::get('/customers/{customer}', [AdminCustomerController::class, 'show'])->name('customers.show');

        // Data Exports (CSV)
        Route::get('/export/orders', [AdminExportController::class, 'orders'])->name('export.orders');
        Route::get('/export/customers', [AdminExportController::class, 'customers'])->name('export.customers');
    });
});
