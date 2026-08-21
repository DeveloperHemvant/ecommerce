<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Models\Tag;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\View\View;

class ProductController extends Controller
{
    /**
     * Display a listing of products with filters.
     */
    public function index(Request $request): View
    {
        $categories = Category::all();

        $query = Product::with('category', 'tags');

        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('sku', 'like', "%{$search}%");
            });
        }

        $products = $query->latest()->paginate(15)->withQueryString();

        return view('admin.products.index', compact('products', 'categories'));
    }

    /**
     * Show the form for creating a new product.
     */
    public function create(): View
    {
        $categories = Category::where('is_active', true)->orderBy('name')->get();
        $tags = Tag::all();

        return view('admin.products.create', compact('categories', 'tags'));
    }

    /**
     * Store a newly created product with local file & URL support.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'category_id' => ['required', 'exists:categories,id'],
            'name' => ['required', 'string', 'max:255'],
            'sku' => ['required', 'string', 'max:100', 'unique:products,sku'],
            'price' => ['required', 'numeric', 'min:0'],
            'compare_price' => ['nullable', 'numeric', 'min:0'],
            'stock' => ['required', 'integer', 'min:0'],
            'low_stock_threshold' => ['nullable', 'integer', 'min:0'],
            'main_image' => ['nullable', 'string', 'max:2000'],
            'main_image_file' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp,gif', 'max:10240'],
            'video' => ['nullable', 'string', 'max:2000'],
            'video_file' => ['nullable', 'file', 'mimes:mp4,webm,mov,avi,mkv', 'max:102400'],
            'gallery_images' => ['nullable', 'string'],
            'gallery_images_files' => ['nullable', 'array'],
            'gallery_images_files.*' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp,gif', 'max:10240'],
            'sizes_text' => ['nullable', 'string'],
            'colors_text' => ['nullable', 'string'],
            'description' => ['nullable', 'string'],
            'fabric_care' => ['nullable', 'string'],
            'shipping_info' => ['nullable', 'string'],
            'is_featured' => ['nullable', 'boolean'],
            'is_active' => ['nullable', 'boolean'],
            'tag_ids' => ['nullable', 'array'],
            'tag_ids.*' => ['exists:tags,id'],
        ]);

        $slug = Str::slug($validated['name']);
        $originalSlug = $slug;
        $count = 1;
        while (Product::where('slug', $slug)->exists()) {
            $slug = "{$originalSlug}-{$count}";
            $count++;
        }

        // Handle Main Image (File upload priority over URL)
        $mainImage = $validated['main_image'] ?? null;
        if ($request->hasFile('main_image_file')) {
            $path = $request->file('main_image_file')->store('products', 'public');
            $mainImage = Storage::url($path);
        }

        if (empty($mainImage)) {
            $mainImage = 'https://lh3.googleusercontent.com/aida-public/AB6AXuBHo2AI-nuhCDtY7OqAzC7IuIQinAr6fQtpX8m0hWogHweqDrvQyU1BS5Y83-MMJOBkKtlKnvaqO5iBdPxGPekyDKAKl4k711cKT2TqACShhWws87SUjzduLOk9ZJAPsri35wMSmkCCZX3rl8ZmGf24drcMIPch3PLxunrxx0DwjrWT8JBPX4KuyCgkXUXxkMckXHk8Q7BDImoU-UCR05O4GD6O45E4mLyPPypNlRM9jK3Dc6vshirC';
        }

        // Handle Video (File upload priority over URL)
        $videoUrl = $validated['video'] ?? null;
        if ($request->hasFile('video_file')) {
            $videoPath = $request->file('video_file')->store('videos', 'public');
            $videoUrl = Storage::url($videoPath);
        }

        // Handle Gallery Images (Combine multi-line URLs + uploaded files)
        $galleryImages = [];
        if (! empty($validated['gallery_images'])) {
            $galleryImages = array_values(array_filter(array_map('trim', explode("\n", $validated['gallery_images']))));
        }

        if ($request->hasFile('gallery_images_files')) {
            foreach ($request->file('gallery_images_files') as $file) {
                if ($file) {
                    $gPath = $file->store('products', 'public');
                    $galleryImages[] = Storage::url($gPath);
                }
            }
        }

        // Parse Sizes & Colors
        $sizes = ! empty($validated['sizes_text'])
            ? array_values(array_filter(array_map('trim', explode(',', $validated['sizes_text']))))
            : ['S', 'M', 'L', 'XL', 'CUSTOM FIT'];

        $colors = ! empty($validated['colors_text'])
            ? array_values(array_filter(array_map('trim', explode(',', $validated['colors_text']))))
            : [];

        $product = Product::create([
            'category_id' => $validated['category_id'],
            'name' => $validated['name'],
            'slug' => $slug,
            'sku' => strtoupper(trim($validated['sku'])),
            'price' => $validated['price'],
            'compare_price' => $validated['compare_price'] ?? null,
            'stock' => $validated['stock'],
            'low_stock_threshold' => $validated['low_stock_threshold'] ?? 5,
            'main_image' => $mainImage,
            'video' => $videoUrl,
            'gallery_images' => $galleryImages,
            'sizes' => $sizes,
            'colors' => $colors,
            'description' => $validated['description'] ?? null,
            'fabric_care' => $validated['fabric_care'] ?? null,
            'shipping_info' => $validated['shipping_info'] ?? null,
            'is_featured' => $request->boolean('is_featured'),
            'is_active' => $request->boolean('is_active', true),
        ]);

        if (! empty($validated['tag_ids'])) {
            $product->tags()->sync($validated['tag_ids']);
        }

        return redirect()->route('admin.products.index')->with('success', "Product '{$product->name}' created successfully.");
    }

    /**
     * Show the form for editing the specified product.
     */
    public function edit(Product $product): View
    {
        $categories = Category::where('is_active', true)->orderBy('name')->get();
        $tags = Tag::all();
        $product->load('tags');

        return view('admin.products.edit', compact('product', 'categories', 'tags'));
    }

    /**
     * Update the specified product with local file & URL support.
     */
    public function update(Request $request, Product $product): RedirectResponse
    {
        $validated = $request->validate([
            'category_id' => ['required', 'exists:categories,id'],
            'name' => ['required', 'string', 'max:255'],
            'sku' => ['required', 'string', 'max:100', 'unique:products,sku,'.$product->id],
            'price' => ['required', 'numeric', 'min:0'],
            'compare_price' => ['nullable', 'numeric', 'min:0'],
            'stock' => ['required', 'integer', 'min:0'],
            'low_stock_threshold' => ['nullable', 'integer', 'min:0'],
            'main_image' => ['nullable', 'string', 'max:2000'],
            'main_image_file' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp,gif', 'max:10240'],
            'video' => ['nullable', 'string', 'max:2000'],
            'video_file' => ['nullable', 'file', 'mimes:mp4,webm,mov,avi,mkv', 'max:102400'],
            'gallery_images' => ['nullable', 'string'],
            'gallery_images_files' => ['nullable', 'array'],
            'gallery_images_files.*' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp,gif', 'max:10240'],
            'sizes_text' => ['nullable', 'string'],
            'colors_text' => ['nullable', 'string'],
            'description' => ['nullable', 'string'],
            'fabric_care' => ['nullable', 'string'],
            'shipping_info' => ['nullable', 'string'],
            'is_featured' => ['nullable', 'boolean'],
            'is_active' => ['nullable', 'boolean'],
            'tag_ids' => ['nullable', 'array'],
            'tag_ids.*' => ['exists:tags,id'],
        ]);

        // Handle Main Image (File upload priority over URL) — clean up the old local file if replaced
        $mainImage = $validated['main_image'] ?? $product->main_image;
        if ($request->hasFile('main_image_file')) {
            $path = $request->file('main_image_file')->store('products', 'public');
            $this->deleteLocalFile($product->main_image);
            $mainImage = Storage::url($path);
        }

        // Handle Video (File upload priority over URL) — clean up the old local file if replaced
        $videoUrl = $validated['video'] ?? $product->video;
        if ($request->hasFile('video_file')) {
            $videoPath = $request->file('video_file')->store('videos', 'public');
            $this->deleteLocalFile($product->video);
            $videoUrl = Storage::url($videoPath);
        }

        // Handle Gallery Images (Combine existing or new multi-line URLs + newly uploaded files)
        $galleryImages = ! empty($validated['gallery_images'])
            ? array_values(array_filter(array_map('trim', explode("\n", $validated['gallery_images']))))
            : ($product->gallery_images ?? []);

        if ($request->hasFile('gallery_images_files')) {
            foreach ($request->file('gallery_images_files') as $file) {
                if ($file) {
                    $gPath = $file->store('products', 'public');
                    $galleryImages[] = Storage::url($gPath);
                }
            }
        }

        // Parse Sizes & Colors
        $sizes = ! empty($validated['sizes_text'])
            ? array_values(array_filter(array_map('trim', explode(',', $validated['sizes_text']))))
            : ($product->sizes ?? ['S', 'M', 'L', 'XL', 'CUSTOM FIT']);

        $colors = ! empty($validated['colors_text'])
            ? array_values(array_filter(array_map('trim', explode(',', $validated['colors_text']))))
            : ($product->colors ?? []);

        $product->update([
            'category_id' => $validated['category_id'],
            'name' => $validated['name'],
            'sku' => strtoupper(trim($validated['sku'])),
            'price' => $validated['price'],
            'compare_price' => $validated['compare_price'] ?? null,
            'stock' => $validated['stock'],
            'low_stock_threshold' => $validated['low_stock_threshold'] ?? 5,
            'main_image' => $mainImage,
            'video' => $videoUrl,
            'gallery_images' => $galleryImages,
            'sizes' => $sizes,
            'colors' => $colors,
            'description' => $validated['description'] ?? null,
            'fabric_care' => $validated['fabric_care'] ?? null,
            'shipping_info' => $validated['shipping_info'] ?? null,
            'is_featured' => $request->boolean('is_featured'),
            'is_active' => $request->boolean('is_active', true),
        ]);

        if (isset($validated['tag_ids'])) {
            $product->tags()->sync($validated['tag_ids']);
        }

        return redirect()->route('admin.products.index')->with('success', "Product '{$product->name}' updated successfully.");
    }

    /**
     * Remove the specified product.
     */
    public function destroy(Product $product): RedirectResponse
    {
        $name = $product->name;

        $this->deleteLocalFile($product->main_image);
        $this->deleteLocalFile($product->video);
        foreach ($product->gallery_images ?? [] as $image) {
            $this->deleteLocalFile($image);
        }

        $product->delete();

        return redirect()->route('admin.products.index')->with('success', "Product '{$name}' deleted successfully.");
    }

    /**
     * Delete a locally-stored upload (under the "public" disk) if the given
     * value is a local storage URL. External URLs are left untouched.
     */
    private function deleteLocalFile(?string $url): void
    {
        if (empty($url) || ! str_starts_with($url, '/storage/')) {
            return;
        }

        Storage::disk('public')->delete(substr($url, strlen('/storage/')));
    }
}
