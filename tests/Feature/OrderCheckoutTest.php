<?php

use App\Models\Category;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;

uses(RefreshDatabase::class);

test('customer can add product to cart and see it in shopping bag', function () {
    $category = Category::create([
        'name' => 'Lacha',
        'slug' => 'lacha',
        'is_active' => true,
    ]);

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

    $response = $this->post('/cart/add', [
        'product_id' => $product->id,
        'quantity' => 2,
        'size' => 'M',
    ]);

    $response->assertRedirect(route('cart'));
    $this->assertEquals(1, count(session('cart')));
    $this->assertEquals(2, array_values(session('cart'))[0]['quantity']);

    $cartPage = $this->get('/cart');
    $cartPage->assertStatus(200);
    $cartPage->assertSee('Royal Banarasi Lacha');
    $cartPage->assertSee('₹24,998');
});

test('applying coupon code calculates correct discount in cart', function () {
    $category = Category::create(['name' => 'Suits', 'slug' => 'suits', 'is_active' => true]);
    $product = Product::create([
        'category_id' => $category->id,
        'name' => 'Ivory Chanderi Suit',
        'slug' => 'ivory-suit',
        'sku' => 'SS-CHN-04',
        'price' => 10000.00,
        'main_image' => 'https://example.com/suit.jpg',
        'stock' => 5,
        'is_active' => true,
    ]);

    $this->post('/cart/add', [
        'product_id' => $product->id,
        'quantity' => 1,
    ]);

    $response = $this->post('/cart/coupon', ['code' => 'WELCOME10']);
    $response->assertRedirect(route('cart'));
    $this->assertNotNull(session('coupon'));
    $this->assertEquals(10, session('coupon')['percent']);

    $cartPage = $this->get('/cart');
    $cartPage->assertSee('Coupon (WELCOME10)');
    $cartPage->assertSee('-₹1,000');
    $cartPage->assertSee('₹9,000');
});

test('guest attempting checkout is redirected to login with intended url preserved', function () {
    $response = $this->get('/checkout');

    $response->assertRedirect(route('login'));
    $this->assertEquals(route('checkout.shipping'), session('url.intended'));
});

test('authenticated customer can complete full checkout and stock is decremented', function () {
    $customer = User::create([
        'name' => 'Priya Sharma',
        'email' => 'priya@test.com',
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

    // 1. Add to cart
    $this->post('/cart/add', [
        'product_id' => $product->id,
        'quantity' => 1,
        'size' => 'M',
    ]);

    // 2. Step 1: Save shipping
    $step1 = $this->actingAs($customer)->post('/checkout/shipping', [
        'customer_name' => 'Priya Sharma',
        'customer_email' => 'priya@test.com',
        'customer_phone' => '+91 98765 43210',
        'shipping_address' => '402, Royal Residency, MG Road',
        'city' => 'Varanasi',
        'state' => 'Uttar Pradesh',
        'postal_code' => '221001',
        'country' => 'India',
    ]);
    $step1->assertRedirect(route('checkout.payment'));

    // 3. Step 2: Save payment
    $step2 = $this->actingAs($customer)->post('/checkout/payment', [
        'payment_method' => 'UPI',
    ]);
    $step2->assertRedirect(route('checkout.review'));

    // 4. Step 3: Place order
    $step3 = $this->actingAs($customer)->post('/checkout/place-order');

    $this->assertDatabaseHas('orders', [
        'user_id' => $customer->id,
        'customer_name' => 'Priya Sharma',
        'payment_method' => 'UPI',
        'total_amount' => 12499.00,
    ]);

    $order = Order::where('user_id', $customer->id)->first();
    $step3->assertRedirect(route('order.success', ['order' => $order->order_number]));

    // Check stock decremented
    $product->refresh();
    $this->assertEquals(9, $product->stock);

    // Check cart was emptied
    $this->assertNull(session('cart'));
});

test('admin can view orders list and update status', function () {
    $admin = User::create([
        'name' => 'Admin User',
        'email' => 'admin@gmail.com',
        'password' => Hash::make('12345678'),
        'role' => 'admin',
    ]);

    $order = Order::create([
        'order_number' => 'ORD-8829',
        'customer_name' => 'Ananya Sharma',
        'customer_email' => 'ananya@example.com',
        'shipping_address' => '402, Royal Residency',
        'city' => 'Varanasi',
        'state' => 'UP',
        'postal_code' => '221001',
        'payment_method' => 'UPI',
        'subtotal' => 21050.00,
        'total_amount' => 21050.00,
        'status' => 'processing',
    ]);

    // Admin index
    $response = $this->actingAs($admin)->get('/admin/orders');
    $response->assertStatus(200);
    $response->assertSee('ORD-8829');
    $response->assertSee('Ananya Sharma');

    // Admin detail
    $detailResponse = $this->actingAs($admin)->get('/admin/orders/ORD-8829');
    $detailResponse->assertStatus(200);
    $detailResponse->assertSee('ORD-8829');

    // Admin update status
    $updateResponse = $this->actingAs($admin)->post("/admin/orders/{$order->id}/status", [
        'status' => 'shipped',
    ]);
    $updateResponse->assertSessionHas('success');

    $order->refresh();
    $this->assertEquals('shipped', $order->status);
});

test('admin can view customer directory', function () {
    $admin = User::create([
        'name' => 'Admin User',
        'email' => 'admin@gmail.com',
        'password' => Hash::make('12345678'),
        'role' => 'admin',
    ]);

    $customer = User::create([
        'name' => 'Riya Desai',
        'email' => 'riya@example.com',
        'password' => Hash::make('12345678'),
        'role' => 'customer',
        'phone' => '+91 98765 99887',
    ]);

    $response = $this->actingAs($admin)->get('/admin/customers');
    $response->assertStatus(200);
    $response->assertSee('Riya Desai');
    $response->assertSee('riya@example.com');
});
