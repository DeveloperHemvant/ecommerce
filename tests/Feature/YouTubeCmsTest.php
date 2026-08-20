<?php

use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use App\Models\YouTubeVideo;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;

uses(RefreshDatabase::class);

test('home page renders dynamic hero video and lookbook grid', function () {
    $video = YouTubeVideo::create([
        'title' => 'The Royal Heritage Masterclass',
        'slug' => 'royal-heritage-masterclass',
        'youtube_url' => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ',
        'duration' => '15:00',
        'views_text' => '50K views',
        'is_hero' => true,
        'is_lookbook' => true,
        'is_active' => true,
    ]);

    $response = $this->get('/');

    $response->assertStatus(200);
    $response->assertSee('The Royal Heritage Masterclass');
});

test('admin can access youtube cms index', function () {
    $admin = User::create([
        'name' => 'Admin User',
        'email' => 'admin@gmail.com',
        'password' => Hash::make('12345678'),
        'role' => 'admin',
    ]);

    $response = $this->actingAs($admin)->get('/admin/youtube');

    $response->assertStatus(200);
    $response->assertSee('YouTube CMS');
});

test('admin can create youtube lookbook and tag products', function () {
    $admin = User::create([
        'name' => 'Admin User',
        'email' => 'admin@gmail.com',
        'password' => Hash::make('12345678'),
        'role' => 'admin',
    ]);

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

    $response = $this->actingAs($admin)->post('/admin/youtube', [
        'title' => 'Grand Sangeet Lookbook',
        'youtube_url' => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ',
        'duration' => '11:20',
        'views_text' => '30K views',
        'is_hero' => 1,
        'is_lookbook' => 1,
        'product_ids' => [$product->id],
    ]);

    $response->assertRedirect(route('admin.youtube.index'));
    $this->assertDatabaseHas('youtube_videos', ['slug' => 'grand-sangeet-lookbook']);

    $video = YouTubeVideo::where('slug', 'grand-sangeet-lookbook')->first();
    expect($video->products->pluck('id')->toArray())->toContain($product->id);
});
