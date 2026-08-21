<?php

use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

uses(RefreshDatabase::class);

test('collections page renders with categories and products', function () {
    $category = Category::create([
        'name' => 'Lehenga',
        'slug' => 'lehenga',
        'is_active' => true,
    ]);

    $product = Product::create([
        'category_id' => $category->id,
        'name' => 'Royal Velvet Lehenga',
        'slug' => 'royal-velvet-lehenga',
        'sku' => 'LEH-01',
        'price' => 25000.00,
        'compare_price' => 30000.00,
        'stock' => 10,
        'main_image' => 'https://example.com/image.jpg',
        'is_active' => true,
    ]);

    $response = $this->get('/collections');

    $response->assertStatus(200);
    $response->assertSee('Lehenga');
    $response->assertSee('Royal Velvet Lehenga');
});

test('collections page filters products by price range, size, and color', function () {
    $category = Category::create(['name' => 'Lehenga', 'slug' => 'lehenga', 'is_active' => true]);

    $cheapProduct = Product::create([
        'category_id' => $category->id,
        'name' => 'Budget Cotton Kurti',
        'slug' => 'budget-cotton-kurti',
        'sku' => 'KUR-01',
        'price' => 1500.00,
        'stock' => 10,
        'main_image' => 'https://example.com/image.jpg',
        'sizes' => ['S', 'M'],
        'colors' => ['Red'],
        'is_active' => true,
    ]);

    $expensiveProduct = Product::create([
        'category_id' => $category->id,
        'name' => 'Royal Velvet Lehenga',
        'slug' => 'royal-velvet-lehenga',
        'sku' => 'LEH-01',
        'price' => 25000.00,
        'stock' => 10,
        'main_image' => 'https://example.com/image.jpg',
        'sizes' => ['L', 'XL'],
        'colors' => ['Maroon'],
        'is_active' => true,
    ]);

    $priceFiltered = $this->get('/collections?max_price=5000');
    $priceFiltered->assertSee('Budget Cotton Kurti');
    $priceFiltered->assertDontSee('Royal Velvet Lehenga');

    $sizeFiltered = $this->get('/collections?size=XL');
    $sizeFiltered->assertSee('Royal Velvet Lehenga');
    $sizeFiltered->assertDontSee('Budget Cotton Kurti');

    $colorFiltered = $this->get('/collections?color=Red');
    $colorFiltered->assertSee('Budget Cotton Kurti');
    $colorFiltered->assertDontSee('Royal Velvet Lehenga');
});

test('header search suggest endpoint returns matching active products', function () {
    $category = Category::create(['name' => 'Lehenga', 'slug' => 'lehenga', 'is_active' => true]);

    Product::create([
        'category_id' => $category->id,
        'name' => 'Royal Velvet Lehenga',
        'slug' => 'royal-velvet-lehenga',
        'sku' => 'LEH-01',
        'price' => 25000.00,
        'stock' => 10,
        'main_image' => 'https://example.com/image.jpg',
        'is_active' => true,
    ]);

    Product::create([
        'category_id' => $category->id,
        'name' => 'Inactive Silk Saree',
        'slug' => 'inactive-silk-saree',
        'sku' => 'SAR-99',
        'price' => 5000.00,
        'stock' => 10,
        'main_image' => 'https://example.com/image.jpg',
        'is_active' => false,
    ]);

    $response = $this->getJson('/search/suggest?q=Velvet');

    $response->assertOk();
    $response->assertJsonCount(1, 'results');
    $response->assertJsonFragment(['name' => 'Royal Velvet Lehenga']);

    $tooShort = $this->getJson('/search/suggest?q=V');
    $tooShort->assertJsonCount(0, 'results');
});

test('product detail page shows related products from the same category', function () {
    $category = Category::create(['name' => 'Lehenga', 'slug' => 'lehenga', 'is_active' => true]);

    $main = Product::create([
        'category_id' => $category->id,
        'name' => 'Royal Velvet Lehenga',
        'slug' => 'royal-velvet-lehenga',
        'sku' => 'LEH-01',
        'price' => 25000.00,
        'stock' => 10,
        'main_image' => 'https://example.com/image.jpg',
        'is_active' => true,
    ]);

    Product::create([
        'category_id' => $category->id,
        'name' => 'Emerald Silk Lehenga',
        'slug' => 'emerald-silk-lehenga',
        'sku' => 'LEH-02',
        'price' => 22000.00,
        'stock' => 5,
        'main_image' => 'https://example.com/image2.jpg',
        'is_active' => true,
    ]);

    $response = $this->get('/products/'.$main->slug);

    $response->assertOk();
    $response->assertSee('You May Also Like');
    $response->assertSee('Emerald Silk Lehenga');
});

test('product detail page renders with dynamic specs and discount calculation', function () {
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
        'compare_price' => 15000.00,
        'stock' => 18,
        'main_image' => 'https://example.com/image.jpg',
        'sizes' => ['S', 'M', 'L', 'XL'],
        'is_active' => true,
    ]);

    $response = $this->get('/products/royal-banarasi-lacha');

    $response->assertStatus(200);
    $response->assertSee('Royal Banarasi Lacha');
    $response->assertSee('SS-BAN-01');
    $response->assertSee('₹12,499');
    $response->assertSee('Save 17%');
    $response->assertSee('In Stock');
});

test('admin can create a category with image upload from system', function () {
    Storage::fake('public');

    $admin = User::create([
        'name' => 'Admin User',
        'email' => 'admin@gmail.com',
        'password' => Hash::make('12345678'),
        'role' => 'admin',
    ]);

    $file = UploadedFile::fake()->image('category_thumb.jpg');

    $response = $this->actingAs($admin)->post('/admin/categories', [
        'name' => 'Anarkali Suits',
        'slug' => 'anarkali-suits',
        'image_file' => $file,
        'description' => 'Royal Anarkali collections',
        'display_order' => 1,
        'is_active' => 1,
    ]);

    $response->assertRedirect(route('admin.categories.index'));
    $this->assertDatabaseHas('categories', ['slug' => 'anarkali-suits']);
});

test('admin can create a product with stock and sku', function () {
    $admin = User::create([
        'name' => 'Admin User',
        'email' => 'admin@gmail.com',
        'password' => Hash::make('12345678'),
        'role' => 'admin',
    ]);

    $category = Category::create([
        'name' => 'Suits',
        'slug' => 'suits',
        'is_active' => true,
    ]);

    $response = $this->actingAs($admin)->post('/admin/products', [
        'category_id' => $category->id,
        'name' => 'Chanderi Silk Suit',
        'sku' => 'CHN-SUIT-99',
        'price' => 8500.00,
        'compare_price' => 10000.00,
        'stock' => 20,
        'low_stock_threshold' => 5,
        'main_image' => 'https://example.com/suit.jpg',
        'sizes_text' => 'S, M, L, XL',
        'colors_text' => 'Ivory, Gold',
        'description' => 'Festive Chanderi suit',
        'is_active' => 1,
    ]);

    $response->assertRedirect(route('admin.products.index'));
    $this->assertDatabaseHas('products', ['sku' => 'CHN-SUIT-99', 'stock' => 20]);
});

test('admin can upload product image and video files directly from system', function () {
    Storage::fake('public');

    $admin = User::create([
        'name' => 'Admin User',
        'email' => 'admin@gmail.com',
        'password' => Hash::make('12345678'),
        'role' => 'admin',
    ]);

    $category = Category::create(['name' => 'Lacha', 'slug' => 'lacha', 'is_active' => true]);

    $image = UploadedFile::fake()->image('lehenga.jpg');
    $video = UploadedFile::fake()->create('runway_clip.mp4', 1024, 'video/mp4');

    $response = $this->actingAs($admin)->post('/admin/products', [
        'category_id' => $category->id,
        'name' => 'Zardosi Velvet Lacha',
        'sku' => 'ZAR-VEL-01',
        'price' => 18000.00,
        'stock' => 15,
        'main_image_file' => $image,
        'video_file' => $video,
        'sizes_text' => 'S, M, L',
        'is_active' => 1,
    ]);

    $response->assertRedirect(route('admin.products.index'));
    $product = Product::where('sku', 'ZAR-VEL-01')->first();
    $this->assertNotNull($product);
    $this->assertStringContainsString('/storage/products/', $product->main_image);
    $this->assertStringContainsString('/storage/videos/', $product->video);
});
