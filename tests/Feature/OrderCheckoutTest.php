<?php

use App\Models\Category;
use App\Models\Coupon;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use App\Services\RazorpayService;
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
    Coupon::create([
        'code' => 'WELCOME10',
        'type' => 'percent',
        'value' => 10,
        'description' => '10% Welcome Discount',
        'is_active' => true,
    ]);

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

    // 3. Step 2: Save payment (Cash on Delivery confirms the order immediately)
    $step2 = $this->actingAs($customer)->post('/checkout/payment', [
        'payment_method' => 'COD',
    ]);
    $step2->assertRedirect(route('checkout.review'));

    // 4. Step 3: Place order
    $step3 = $this->actingAs($customer)->post('/checkout/place-order');

    $this->assertDatabaseHas('orders', [
        'user_id' => $customer->id,
        'customer_name' => 'Priya Sharma',
        'payment_method' => 'COD',
        'payment_status' => 'pending',
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

test('online payment order is only created after Razorpay signature verification', function () {
    $this->app->bind(RazorpayService::class, function () {
        return new class extends RazorpayService
        {
            public function __construct() {}

            public function createOrder(int $amountInPaise, string $receipt): array
            {
                return ['id' => 'order_fake123'];
            }

            public function verifySignature(array $attributes): bool
            {
                return $attributes['razorpay_signature'] === 'valid-signature';
            }
        };
    });

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

    $this->actingAs($customer)->post('/checkout/payment', ['payment_method' => 'UPI']);

    // Placing the order for an online method must NOT create the order yet —
    // it hands off to the Razorpay widget instead.
    $placeOrder = $this->actingAs($customer)->post('/checkout/place-order');
    $placeOrder->assertOk();
    $this->assertDatabaseCount('orders', 0);
    $this->assertEquals('order_fake123', session('checkout.razorpay_order_id'));

    // An invalid signature must not create an order.
    $badVerify = $this->actingAs($customer)->post('/checkout/payment/verify', [
        'razorpay_payment_id' => 'pay_fake123',
        'razorpay_order_id' => 'order_fake123',
        'razorpay_signature' => 'wrong-signature',
    ]);
    $badVerify->assertRedirect(route('checkout.payment'));
    $this->assertDatabaseCount('orders', 0);

    // A valid signature finalizes the order.
    $goodVerify = $this->actingAs($customer)->post('/checkout/payment/verify', [
        'razorpay_payment_id' => 'pay_fake123',
        'razorpay_order_id' => 'order_fake123',
        'razorpay_signature' => 'valid-signature',
    ]);

    $order = Order::where('user_id', $customer->id)->first();
    $goodVerify->assertRedirect(route('order.success', ['order' => $order->order_number]));

    $this->assertDatabaseHas('orders', [
        'user_id' => $customer->id,
        'payment_method' => 'UPI',
        'payment_status' => 'paid',
        'transaction_id' => 'pay_fake123',
    ]);

    $product->refresh();
    $this->assertEquals(9, $product->stock);
    $this->assertNull(session('cart'));
});

test('cart persists to the database on login and survives a fresh session', function () {
    $customer = User::create([
        'name' => 'Riya Desai',
        'email' => 'riya@test.com',
        'password' => Hash::make('password'),
        'role' => 'customer',
    ]);

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

    // Guest adds to cart, then logs in — the cart should be persisted to the DB.
    $this->post('/cart/add', ['product_id' => $product->id, 'quantity' => 1]);

    $this->post('/login', ['email' => 'riya@test.com', 'password' => 'password']);

    $this->assertDatabaseCount('cart_items', 1);
    $this->assertDatabaseHas('cart_items', ['user_id' => $customer->id, 'quantity' => 1]);

    // Simulate the session being lost (e.g. new device) — a fresh login should
    // restore the cart from the database instead of showing an empty cart.
    $this->post('/logout');
    $this->flushSession();

    $this->post('/login', ['email' => 'riya@test.com', 'password' => 'password']);

    $cartPage = $this->get('/cart');
    $cartPage->assertSee('Ivory Chanderi Suit');
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
