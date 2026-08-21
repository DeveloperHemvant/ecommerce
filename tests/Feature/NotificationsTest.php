<?php

use App\Models\Category;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use App\Models\Wishlist;
use App\Notifications\NewOfferNotification;
use App\Notifications\NewProductNotification;
use App\Notifications\OrderConfirmedNotification;
use App\Notifications\OrderStatusChangedNotification;
use App\Notifications\WishlistStockAlertNotification;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;

uses(RefreshDatabase::class);

test('placing a cod order notifies the customer', function () {
    Notification::fake();

    $customer = User::create([
        'name' => 'Priya Sharma',
        'email' => 'priya@test.com',
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

    $this->post('/cart/add', ['product_id' => $product->id, 'quantity' => 1, 'size' => 'M']);

    $this->actingAs($customer)->post('/checkout/shipping', [
        'customer_name' => 'Priya Sharma',
        'customer_email' => 'priya@test.com',
        'customer_phone' => '+91 98765 43210',
        'shipping_address' => '402, Royal Residency, MG Road',
        'city' => 'Varanasi',
        'state' => 'Uttar Pradesh',
        'postal_code' => '221001',
        'country' => 'India',
    ]);
    $this->actingAs($customer)->post('/checkout/payment', ['payment_method' => 'COD']);
    $this->actingAs($customer)->post('/checkout/place-order');

    Notification::assertSentTo($customer, OrderConfirmedNotification::class);
});

test('admin updating order status notifies the customer', function () {
    Notification::fake();

    $admin = User::create([
        'name' => 'Admin User',
        'email' => 'admin@gmail.com',
        'password' => Hash::make('12345678'),
        'role' => 'admin',
    ]);

    $customer = User::create([
        'name' => 'Riya Desai',
        'email' => 'riya@test.com',
        'password' => Hash::make('password'),
        'role' => 'customer',
    ]);

    $order = Order::create([
        'order_number' => 'ORD-9001',
        'user_id' => $customer->id,
        'customer_name' => 'Riya Desai',
        'customer_email' => 'riya@test.com',
        'shipping_address' => '1 MG Road',
        'city' => 'Varanasi',
        'state' => 'UP',
        'postal_code' => '221001',
        'payment_method' => 'UPI',
        'subtotal' => 5000,
        'total_amount' => 5000,
        'status' => 'processing',
    ]);

    $this->actingAs($admin)->post("/admin/orders/{$order->id}/status", ['status' => 'shipped']);

    Notification::assertSentTo($customer, OrderStatusChangedNotification::class);
});

test('creating an active coupon notifies all customers', function () {
    Notification::fake();

    $admin = User::create([
        'name' => 'Admin User',
        'email' => 'admin@gmail.com',
        'password' => Hash::make('12345678'),
        'role' => 'admin',
    ]);

    $customerOne = User::create(['name' => 'A', 'email' => 'a@test.com', 'password' => Hash::make('pw'), 'role' => 'customer']);
    $customerTwo = User::create(['name' => 'B', 'email' => 'b@test.com', 'password' => Hash::make('pw'), 'role' => 'customer']);

    $this->actingAs($admin)->post('/admin/coupons', [
        'code' => 'FESTIVE20',
        'type' => 'percent',
        'value' => 20,
        'is_active' => true,
    ]);

    Notification::assertSentTo($customerOne, NewOfferNotification::class);
    Notification::assertSentTo($customerTwo, NewOfferNotification::class);
    Notification::assertNotSentTo($admin, NewOfferNotification::class);
});

test('publishing an active product notifies all customers', function () {
    Notification::fake();

    $admin = User::create([
        'name' => 'Admin User',
        'email' => 'admin@gmail.com',
        'password' => Hash::make('12345678'),
        'role' => 'admin',
    ]);

    $customer = User::create(['name' => 'A', 'email' => 'a@test.com', 'password' => Hash::make('pw'), 'role' => 'customer']);
    $category = Category::create(['name' => 'Suits', 'slug' => 'suits', 'is_active' => true]);

    $this->actingAs($admin)->post('/admin/products', [
        'category_id' => $category->id,
        'name' => 'Ivory Chanderi Suit',
        'sku' => 'SS-CHN-04',
        'price' => 10000,
        'stock' => 5,
        'is_active' => true,
    ]);

    Notification::assertSentTo($customer, NewProductNotification::class);
});

test('a product selling out notifies customers who wishlisted it', function () {
    Notification::fake();

    $customer = User::create([
        'name' => 'Priya Sharma',
        'email' => 'priya@test.com',
        'password' => Hash::make('password'),
        'role' => 'customer',
    ]);
    $watcher = User::create([
        'name' => 'Ananya',
        'email' => 'ananya@test.com',
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
        'stock' => 1,
        'low_stock_threshold' => 5,
        'main_image' => 'https://example.com/image.jpg',
        'is_active' => true,
    ]);

    Wishlist::create(['user_id' => $watcher->id, 'product_id' => $product->id]);

    $this->post('/cart/add', ['product_id' => $product->id, 'quantity' => 1, 'size' => 'M']);

    $this->actingAs($customer)->post('/checkout/shipping', [
        'customer_name' => 'Priya Sharma',
        'customer_email' => 'priya@test.com',
        'customer_phone' => '+91 98765 43210',
        'shipping_address' => '402, Royal Residency, MG Road',
        'city' => 'Varanasi',
        'state' => 'Uttar Pradesh',
        'postal_code' => '221001',
        'country' => 'India',
    ]);
    $this->actingAs($customer)->post('/checkout/payment', ['payment_method' => 'COD']);
    $this->actingAs($customer)->post('/checkout/place-order');

    Notification::assertSentTo($watcher, WishlistStockAlertNotification::class);
});

test('customer can view and mark notifications as read', function () {
    $customer = User::create([
        'name' => 'Priya Sharma',
        'email' => 'priya@test.com',
        'password' => Hash::make('password'),
        'role' => 'customer',
    ]);

    $order = Order::create([
        'order_number' => 'ORD-9002',
        'user_id' => $customer->id,
        'customer_name' => 'Priya Sharma',
        'customer_email' => 'priya@test.com',
        'shipping_address' => '1 MG Road',
        'city' => 'Varanasi',
        'state' => 'UP',
        'postal_code' => '221001',
        'payment_method' => 'COD',
        'subtotal' => 5000,
        'total_amount' => 5000,
        'status' => 'processing',
    ]);

    $customer->notify(new OrderConfirmedNotification($order));

    $indexResponse = $this->actingAs($customer)->get('/account/notifications');
    $indexResponse->assertOk();
    $indexResponse->assertSee('Order confirmed');

    $notificationId = $customer->notifications()->first()->id;
    $this->actingAs($customer)->post("/account/notifications/{$notificationId}/read");

    expect($customer->notifications()->first()->read_at)->not->toBeNull();
});
