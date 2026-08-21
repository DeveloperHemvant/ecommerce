<?php

use App\Models\Category;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;

uses(RefreshDatabase::class);

test('admin can view reports for weekly, monthly, and yearly periods', function () {
    $admin = User::create([
        'name' => 'Admin User',
        'email' => 'admin@gmail.com',
        'password' => Hash::make('12345678'),
        'role' => 'admin',
    ]);

    foreach (['week', 'month', 'year'] as $period) {
        $response = $this->actingAs($admin)->get("/admin/reports?period={$period}");
        $response->assertOk();
        $response->assertSee('Financial Reports');
    }
});

test('reports compute revenue and profit only from paid orders with known cost price', function () {
    $admin = User::create([
        'name' => 'Admin User',
        'email' => 'admin@gmail.com',
        'password' => Hash::make('12345678'),
        'role' => 'admin',
    ]);

    $category = Category::create(['name' => 'Lacha', 'slug' => 'lacha', 'is_active' => true]);

    $costedProduct = Product::create([
        'category_id' => $category->id,
        'name' => 'Costed Lacha',
        'slug' => 'costed-lacha',
        'sku' => 'SKU-COST',
        'price' => 1000,
        'cost_price' => 600,
        'stock' => 10,
        'main_image' => 'https://example.com/image.jpg',
        'is_active' => true,
    ]);

    $order = Order::create([
        'order_number' => 'ORD-COST-1',
        'customer_name' => 'Priya Sharma',
        'customer_email' => 'priya@test.com',
        'shipping_address' => '1 MG Road',
        'city' => 'Varanasi',
        'state' => 'UP',
        'postal_code' => '221001',
        'payment_method' => 'UPI',
        'payment_status' => 'paid',
        'subtotal' => 1000,
        'total_amount' => 1000,
        'status' => 'processing',
    ]);

    OrderItem::create([
        'order_id' => $order->id,
        'product_id' => $costedProduct->id,
        'product_name' => $costedProduct->name,
        'price' => 1000,
        'cost_price' => 600,
        'quantity' => 1,
        'total' => 1000,
    ]);

    $response = $this->actingAs($admin)->get('/admin/reports?period=month');

    $response->assertOk();
    $response->assertSee('₹1,000'); // revenue
    $response->assertSee('₹400');  // profit = 1000 - 600
});

test('cost coverage banner appears when some sold items are missing a cost price', function () {
    $admin = User::create([
        'name' => 'Admin User',
        'email' => 'admin@gmail.com',
        'password' => Hash::make('12345678'),
        'role' => 'admin',
    ]);

    $category = Category::create(['name' => 'Lacha', 'slug' => 'lacha', 'is_active' => true]);

    $product = Product::create([
        'category_id' => $category->id,
        'name' => 'No Cost Lacha',
        'slug' => 'no-cost-lacha',
        'sku' => 'SKU-NOCOST',
        'price' => 500,
        'stock' => 10,
        'main_image' => 'https://example.com/image.jpg',
        'is_active' => true,
    ]);

    $order = Order::create([
        'order_number' => 'ORD-NOCOST-1',
        'customer_name' => 'Riya Desai',
        'customer_email' => 'riya@test.com',
        'shipping_address' => '1 MG Road',
        'city' => 'Varanasi',
        'state' => 'UP',
        'postal_code' => '221001',
        'payment_method' => 'COD',
        'payment_status' => 'paid',
        'subtotal' => 500,
        'total_amount' => 500,
        'status' => 'processing',
    ]);

    OrderItem::create([
        'order_id' => $order->id,
        'product_id' => $product->id,
        'product_name' => $product->name,
        'price' => 500,
        'cost_price' => null,
        'quantity' => 1,
        'total' => 500,
    ]);

    $response = $this->actingAs($admin)->get('/admin/reports?period=month');

    $response->assertOk();
    $response->assertSee('Set Cost Prices');
    $response->assertSee('0%');
});

test('marking a COD order delivered flips it to paid', function () {
    $admin = User::create([
        'name' => 'Admin User',
        'email' => 'admin@gmail.com',
        'password' => Hash::make('12345678'),
        'role' => 'admin',
    ]);

    $order = Order::create([
        'order_number' => 'ORD-COD-1',
        'customer_name' => 'Ananya Sharma',
        'customer_email' => 'ananya@test.com',
        'shipping_address' => '1 MG Road',
        'city' => 'Varanasi',
        'state' => 'UP',
        'postal_code' => '221001',
        'payment_method' => 'COD',
        'payment_status' => 'pending',
        'subtotal' => 2000,
        'total_amount' => 2000,
        'status' => 'shipped',
    ]);

    $this->actingAs($admin)->post("/admin/orders/{$order->id}/status", ['status' => 'delivered']);

    expect($order->fresh()->payment_status)->toBe('paid');
});

test('marking a COD order shipped does not flip it to paid', function () {
    $admin = User::create([
        'name' => 'Admin User',
        'email' => 'admin@gmail.com',
        'password' => Hash::make('12345678'),
        'role' => 'admin',
    ]);

    $order = Order::create([
        'order_number' => 'ORD-COD-2',
        'customer_name' => 'Ananya Sharma',
        'customer_email' => 'ananya@test.com',
        'shipping_address' => '1 MG Road',
        'city' => 'Varanasi',
        'state' => 'UP',
        'postal_code' => '221001',
        'payment_method' => 'COD',
        'payment_status' => 'pending',
        'subtotal' => 2000,
        'total_amount' => 2000,
        'status' => 'processing',
    ]);

    $this->actingAs($admin)->post("/admin/orders/{$order->id}/status", ['status' => 'shipped']);

    expect($order->fresh()->payment_status)->toBe('pending');
});

test('marking an online-paid order delivered does not change its payment status', function () {
    $admin = User::create([
        'name' => 'Admin User',
        'email' => 'admin@gmail.com',
        'password' => Hash::make('12345678'),
        'role' => 'admin',
    ]);

    $order = Order::create([
        'order_number' => 'ORD-UPI-1',
        'customer_name' => 'Meera Sharma',
        'customer_email' => 'meera@test.com',
        'shipping_address' => '1 MG Road',
        'city' => 'Varanasi',
        'state' => 'UP',
        'postal_code' => '221001',
        'payment_method' => 'UPI',
        'payment_status' => 'paid',
        'transaction_id' => 'pay_test123',
        'subtotal' => 2000,
        'total_amount' => 2000,
        'status' => 'shipped',
    ]);

    $this->actingAs($admin)->post("/admin/orders/{$order->id}/status", ['status' => 'delivered']);

    expect($order->fresh())
        ->payment_status->toBe('paid')
        ->status->toBe('delivered');
});
