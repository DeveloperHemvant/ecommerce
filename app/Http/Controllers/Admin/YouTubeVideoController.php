<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\YouTubeVideo;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\View\View;

class YouTubeVideoController extends Controller
{
    /**
     * Display a listing of YouTube lookbooks and video CMS entries.
     */
    public function index(): View
    {
        $videos = YouTubeVideo::with('products')
            ->orderBy('display_order', 'asc')
            ->latest()
            ->get();

        return view('admin.youtube.index', compact('videos'));
    }

    /**
     * Show the form for creating a new YouTube lookbook video.
     */
    public function create(): View
    {
        $products = Product::where('is_active', true)->orderBy('name')->get();

        return view('admin.youtube.create', compact('products'));
    }

    /**
     * Store a newly created YouTube lookbook with file and URL support.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'youtube_url' => ['nullable', 'string', 'max:500'],
            'video_file' => ['nullable', 'file', 'mimes:mp4,webm,mov,avi,mkv', 'max:102400'],
            'thumbnail' => ['nullable', 'string', 'max:2000'],
            'thumbnail_file' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp,gif', 'max:10240'],
            'duration' => ['nullable', 'string', 'max:50'],
            'views_text' => ['nullable', 'string', 'max:50'],
            'description' => ['nullable', 'string'],
            'is_hero' => ['nullable', 'boolean'],
            'is_trending' => ['nullable', 'boolean'],
            'is_lookbook' => ['nullable', 'boolean'],
            'display_order' => ['nullable', 'integer', 'min:0'],
            'is_active' => ['nullable', 'boolean'],
            'product_ids' => ['nullable', 'array'],
            'product_ids.*' => ['exists:products,id'],
        ]);

        $slug = Str::slug($validated['title']);
        $originalSlug = $slug;
        $count = 1;
        while (YouTubeVideo::where('slug', $slug)->exists()) {
            $slug = "{$originalSlug}-{$count}";
            $count++;
        }

        $videoUrl = $validated['youtube_url'] ?? null;
        if ($request->hasFile('video_file')) {
            $path = $request->file('video_file')->store('videos', 'public');
            $videoUrl = Storage::url($path);
        }

        $youtubeId = $videoUrl ? YouTubeVideo::extractYoutubeId($videoUrl) : null;

        $thumbnail = $validated['thumbnail'] ?? ($youtubeId ? "https://img.youtube.com/vi/{$youtubeId}/maxresdefault.jpg" : null);
        if ($request->hasFile('thumbnail_file')) {
            $tPath = $request->file('thumbnail_file')->store('thumbnails', 'public');
            $thumbnail = Storage::url($tPath);
        }

        if ($request->boolean('is_hero')) {
            YouTubeVideo::where('is_hero', true)->update(['is_hero' => false]);
        }

        if ($request->boolean('is_trending')) {
            YouTubeVideo::where('is_trending', true)->update(['is_trending' => false]);
        }

        $video = YouTubeVideo::create([
            'title' => $validated['title'],
            'slug' => $slug,
            'youtube_url' => $videoUrl ?? 'https://youtube.com',
            'youtube_id' => $youtubeId,
            'thumbnail' => $thumbnail,
            'duration' => $validated['duration'] ?? '10:00',
            'views_text' => $validated['views_text'] ?? '10K views',
            'description' => $validated['description'] ?? null,
            'is_hero' => $request->boolean('is_hero'),
            'is_trending' => $request->boolean('is_trending'),
            'is_lookbook' => $request->boolean('is_lookbook', true),
            'display_order' => $validated['display_order'] ?? 0,
            'is_active' => $request->boolean('is_active', true),
        ]);

        if (! empty($validated['product_ids'])) {
            $video->products()->sync($validated['product_ids']);
        }

        return redirect()->route('admin.youtube.index')->with('success', "Lookbook '{$video->title}' published successfully.");
    }

    /**
     * Show the form for editing the specified YouTube video.
     */
    public function edit(YouTubeVideo $youtube): View
    {
        $products = Product::where('is_active', true)->orderBy('name')->get();
        $youtube->load('products');

        return view('admin.youtube.edit', [
            'video' => $youtube,
            'products' => $products,
        ]);
    }

    /**
     * Update the specified YouTube lookbook with file and URL support.
     */
    public function update(Request $request, YouTubeVideo $youtube): RedirectResponse
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'youtube_url' => ['nullable', 'string', 'max:500'],
            'video_file' => ['nullable', 'file', 'mimes:mp4,webm,mov,avi,mkv', 'max:102400'],
            'thumbnail' => ['nullable', 'string', 'max:2000'],
            'thumbnail_file' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp,gif', 'max:10240'],
            'duration' => ['nullable', 'string', 'max:50'],
            'views_text' => ['nullable', 'string', 'max:50'],
            'description' => ['nullable', 'string'],
            'is_hero' => ['nullable', 'boolean'],
            'is_trending' => ['nullable', 'boolean'],
            'is_lookbook' => ['nullable', 'boolean'],
            'display_order' => ['nullable', 'integer', 'min:0'],
            'is_active' => ['nullable', 'boolean'],
            'product_ids' => ['nullable', 'array'],
            'product_ids.*' => ['exists:products,id'],
        ]);

        $videoUrl = $validated['youtube_url'] ?? $youtube->youtube_url;
        if ($request->hasFile('video_file')) {
            $path = $request->file('video_file')->store('videos', 'public');
            $videoUrl = Storage::url($path);
        }

        $youtubeId = $videoUrl ? YouTubeVideo::extractYoutubeId($videoUrl) : $youtube->youtube_id;

        $thumbnail = $validated['thumbnail'] ?? ($youtubeId ? "https://img.youtube.com/vi/{$youtubeId}/maxresdefault.jpg" : $youtube->thumbnail);
        if ($request->hasFile('thumbnail_file')) {
            $tPath = $request->file('thumbnail_file')->store('thumbnails', 'public');
            $thumbnail = Storage::url($tPath);
        }

        if ($request->boolean('is_hero') && ! $youtube->is_hero) {
            YouTubeVideo::where('is_hero', true)->where('id', '!=', $youtube->id)->update(['is_hero' => false]);
        }

        if ($request->boolean('is_trending') && ! $youtube->is_trending) {
            YouTubeVideo::where('is_trending', true)->where('id', '!=', $youtube->id)->update(['is_trending' => false]);
        }

        $youtube->update([
            'title' => $validated['title'],
            'youtube_url' => $videoUrl,
            'youtube_id' => $youtubeId,
            'thumbnail' => $thumbnail,
            'duration' => $validated['duration'] ?? $youtube->duration,
            'views_text' => $validated['views_text'] ?? $youtube->views_text,
            'description' => $validated['description'] ?? null,
            'is_hero' => $request->boolean('is_hero'),
            'is_trending' => $request->boolean('is_trending'),
            'is_lookbook' => $request->boolean('is_lookbook', true),
            'display_order' => $validated['display_order'] ?? 0,
            'is_active' => $request->boolean('is_active', true),
        ]);

        if (isset($validated['product_ids'])) {
            $youtube->products()->sync($validated['product_ids']);
        }

        return redirect()->route('admin.youtube.index')->with('success', "Lookbook '{$youtube->title}' updated successfully.");
    }

    /**
     * Remove the specified YouTube lookbook.
     */
    public function destroy(YouTubeVideo $youtube): RedirectResponse
    {
        $title = $youtube->title;
        $youtube->delete();

        return redirect()->route('admin.youtube.index')->with('success', "Lookbook '{$title}' deleted successfully.");
    }
}
