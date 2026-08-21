<?php

use App\Models\Category;
use App\Models\Coupon;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\Review;
use App\Models\User;
use App\Models\Wishlist;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

uses(RefreshDatabase::class);

test('customer can view their orders and order detail page with invoice', function () {
    $customer = User::create([
        'name' => 'Priya Sharma',
        'email' => 'priya@test.com',
        'password' => Hash::make('password'),
        'role' => 'customer',
    ]);

    $order = Order::create([
        'order_number' => 'ORD-1234',
        'user_id' => $customer->id,
        'customer_name' => 'Priya Sharma',
        'customer_email' => 'priya@test.com',
        'shipping_address' => 'Varanasi Residency',
        'city' => 'Varanasi',
        'state' => 'Uttar Pradesh',
        'postal_code' => '221001',
        'payment_method' => 'UPI',
        'subtotal' => 12499.00,
        'total_amount' => 12499.00,
        'status' => 'shipped',
        'courier_name' => 'BlueDart',
        'tracking_number' => 'AWB-12345',
    ]);

    // Customer orders list
    $response = $this->actingAs($customer)->get('/account/orders');
    $response->assertStatus(200);
    $response->assertSee('ORD-1234');
    $response->assertSee('Shipped');

    // Customer order detail
    $detail = $this->actingAs($customer)->get('/account/orders/ORD-1234');
    $detail->assertStatus(200);
    $detail->assertSee('BlueDart');
    $detail->assertSee('AWB-12345');
    $detail->assertSee('Print Order Invoice');
});

test('customer can toggle wishlist and move item to cart', function () {
    $customer = User::create([
        'name' => 'Meera Sharma',
        'email' => 'meera@test.com',
        'password' => Hash::make('password'),
        'role' => 'customer',
    ]);

    $category = Category::create(['name' => 'Lacha', 'slug' => 'lacha', 'is_active' => true]);
    $product = Product::create([
        'category_id' => $category->id,
        'name' => 'Royal Banarasi Lacha',
        'slug' => 'royal-banarasi-lacha',
        'sku' => 'SS-BAN-01',
        'price' => 12499.00,
        'stock' => 10,
        'main_image' => 'https://example.com/image.jpg',
        'is_active' => true,
    ]);

    // 1. Add to wishlist
    $toggle = $this->actingAs($customer)->post('/wishlist/toggle', ['product_id' => $product->id]);
    $this->assertDatabaseHas('wishlists', ['user_id' => $customer->id, 'product_id' => $product->id]);

    // 2. View wishlist page
    $wishlistPage = $this->actingAs($customer)->get('/wishlist');
    $wishlistPage->assertStatus(200);
    $wishlistPage->assertSee('Royal Banarasi Lacha');

    // 3. Move to cart
    $move = $this->actingAs($customer)->post("/wishlist/{$product->id}/move-to-cart", ['size' => 'M']);
    $move->assertRedirect(route('cart'));
    $this->assertDatabaseMissing('wishlists', ['user_id' => $customer->id, 'product_id' => $product->id]);
    $this->assertEquals(1, count(session('cart')));
});

test('wishlist page paginates instead of loading every saved item at once', function () {
    $customer = User::create([
        'name' => 'Meera Sharma',
        'email' => 'meera@test.com',
        'password' => Hash::make('password'),
        'role' => 'customer',
    ]);

    $category = Category::create(['name' => 'Lacha', 'slug' => 'lacha', 'is_active' => true]);

    $letters = ['Alpha', 'Bravo', 'Charlie', 'Delta', 'Echo', 'Foxtrot', 'Golf', 'Hotel', 'India', 'Juliet', 'Kilo', 'Lima', 'Mike'];
    foreach ($letters as $letter) {
        $product = Product::create([
            'category_id' => $category->id,
            'name' => "Wishlist Piece {$letter}",
            'slug' => 'wishlist-piece-'.strtolower($letter),
            'sku' => "SKU-{$letter}",
            'price' => 1000,
            'stock' => 10,
            'main_image' => 'https://example.com/image.jpg',
            'is_active' => true,
        ]);
        Wishlist::create(['user_id' => $customer->id, 'product_id' => $product->id]);
    }

    // 13 items, 12 per page: the most recently saved (Mike) is on page 1,
    // the oldest (Alpha) is pushed to page 2.
    $firstPage = $this->actingAs($customer)->get('/wishlist');
    $firstPage->assertStatus(200);
    $firstPage->assertSee('Wishlist Piece Mike');
    $firstPage->assertDontSee('Wishlist Piece Alpha');

    $secondPage = $this->actingAs($customer)->get('/wishlist?page=2');
    $secondPage->assertStatus(200);
    $secondPage->assertSee('Wishlist Piece Alpha');
});

test('database coupon validation applies correct discount and respects min order', function () {
    $coupon = Coupon::create([
        'code' => 'ROYAL5000',
        'type' => 'fixed',
        'value' => 5000.00,
        'min_order_amount' => 20000.00,
        'is_active' => true,
    ]);

    $category = Category::create(['name' => 'Lehenga', 'slug' => 'lehenga', 'is_active' => true]);
    $product = Product::create([
        'category_id' => $category->id,
        'name' => 'Midnight Velvet Lehenga',
        'slug' => 'velvet-lehenga',
        'sku' => 'SS-VEL-01',
        'price' => 25000.00,
        'stock' => 5,
        'main_image' => 'https://example.com/lehenga.jpg',
        'is_active' => true,
    ]);

    // Add to cart
    $this->post('/cart/add', ['product_id' => $product->id, 'quantity' => 1]);

    // Apply database coupon
    $res = $this->post('/cart/coupon', ['code' => 'ROYAL5000']);
    $res->assertRedirect(route('cart'));
    $this->assertEquals(5000, session('coupon')['discount_amount']);

    $cartPage = $this->get('/cart');
    $cartPage->assertSee('-₹5,000');
    $cartPage->assertSee('₹20,000');
});

test('custom fit measurements are stored in cart and order item', function () {
    $customer = User::create([
        'name' => 'Ananya Sharma',
        'email' => 'ananya@test.com',
        'password' => Hash::make('password'),
        'role' => 'customer',
        'phone' => '+91 98765 43210',
    ]);

    $category = Category::create(['name' => 'Lacha', 'slug' => 'lacha', 'is_active' => true]);
    $product = Product::create([
        'category_id' => $category->id,
        'name' => 'Royal Banarasi Lacha',
        'slug' => 'royal-banarasi-lacha',
        'sku' => 'SS-BAN-01',
        'price' => 12499.00,
        'stock' => 10,
        'main_image' => 'https://example.com/image.jpg',
        'is_active' => true,
    ]);

    // 1. Add to cart with custom measurements
    $this->post('/cart/add', [
        'product_id' => $product->id,
        'quantity' => 1,
        'size' => 'CUSTOM FIT',
        'custom_measurements' => [
            'blouse_bust' => '36',
            'blouse_waist' => '30',
            'skirt_waist' => '32',
            'skirt_length' => '42.5',
        ],
    ]);

    // 2. Checkout
    $this->actingAs($customer)->post('/checkout/shipping', [
        'customer_name' => 'Ananya Sharma',
        'customer_email' => 'ananya@test.com',
        'customer_phone' => '+91 98765 43210',
        'shipping_address' => 'Varanasi Residency',
        'city' => 'Varanasi',
        'state' => 'UP',
        'postal_code' => '221001',
    ]);

    $this->actingAs($customer)->post('/checkout/payment', ['payment_method' => 'COD']);
    $this->actingAs($customer)->post('/checkout/place-order');

    $orderItem = OrderItem::first();
    $this->assertNotNull($orderItem);
    $this->assertEquals('CUSTOM FIT', $orderItem->size);
    $this->assertEquals('36', $orderItem->custom_measurements['blouse_bust']);
});

test('public order tracking page looks up order status by order number', function () {
    $order = Order::create([
        'order_number' => 'ORD-9988',
        'customer_name' => 'Riya Desai',
        'customer_email' => 'riya@test.com',
        'customer_phone' => '+91 98765 99887',
        'shipping_address' => 'Mumbai',
        'city' => 'Mumbai',
        'state' => 'Maharashtra',
        'postal_code' => '400049',
        'payment_method' => 'UPI',
        'subtotal' => 8950.00,
        'total_amount' => 8950.00,
        'status' => 'delivered',
        'courier_name' => 'Delhivery Air',
        'tracking_number' => 'DEL-778899',
    ]);

    $response = $this->get('/track-order?order=ORD-9988');
    $response->assertStatus(200);
    $response->assertSee('ORD-9988');
    $response->assertSee('Riya Desai');
    $response->assertSee('Delhivery Air');
    $response->assertSee('DEL-778899');
});

test('customer can submit review with photo and admin can moderate it', function () {
    Storage::fake('public');

    $customer = User::create([
        'name' => 'Shreya Rao',
        'email' => 'shreya@test.com',
        'password' => Hash::make('password'),
        'role' => 'customer',
    ]);

    $admin = User::create([
        'name' => 'Admin User',
        'email' => 'admin@gmail.com',
        'password' => Hash::make('12345678'),
        'role' => 'admin',
    ]);

    $category = Category::create(['name' => 'Lacha', 'slug' => 'lacha', 'is_active' => true]);
    $product = Product::create([
        'category_id' => $category->id,
        'name' => 'Royal Banarasi Lacha',
        'slug' => 'royal-banarasi-lacha',
        'sku' => 'SS-BAN-01',
        'price' => 12499.00,
        'stock' => 10,
        'main_image' => 'https://example.com/image.jpg',
        'is_active' => true,
    ]);

    $photo = UploadedFile::fake()->image('bride_outfit.jpg');

    // 1. Submit review
    $response = $this->actingAs($customer)->post("/products/{$product->id}/reviews", [
        'rating' => 5,
        'customer_name' => 'Shreya Rao',
        'title' => 'Magnificent wedding look',
        'comment' => 'The zari weave was sublime and the fit was pristine.',
        'photos' => [$photo],
    ]);

    $response->assertSessionHas('success');
    $this->assertDatabaseHas('reviews', ['customer_name' => 'Shreya Rao', 'rating' => 5]);

    $review = Review::first();

    // 2. Admin moderates review
    $adminView = $this->actingAs($admin)->get('/admin/reviews');
    $adminView->assertStatus(200);
    $adminView->assertSee('Shreya Rao');

    // Admin toggles approval
    $toggle = $this->actingAs($admin)->post("/admin/reviews/{$review->id}/toggle");
    $toggle->assertSessionHas('success');
});

test('admin can export orders and customers to csv streams', function () {
    $admin = User::create([
        'name' => 'Admin User',
        'email' => 'admin@gmail.com',
        'password' => Hash::make('12345678'),
        'role' => 'admin',
    ]);

    $ordersExport = $this->actingAs($admin)->get('/admin/export/orders');
    $ordersExport->assertStatus(200);
    $ordersExport->assertHeader('Content-Type', 'text/csv; charset=UTF-8');

    $customersExport = $this->actingAs($admin)->get('/admin/export/customers');
    $customersExport->assertStatus(200);
    $customersExport->assertHeader('Content-Type', 'text/csv; charset=UTF-8');
});
