<x-layouts.admin title="Edit YouTube Lookbook - Sonakshi Admin" active="youtube">
    <div class="max-w-4xl mx-auto space-y-6">
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.youtube.index') }}" class="text-on-surface-variant hover:text-heritage-burgundy transition-colors p-1">
                <span class="material-symbols-outlined text-xl">arrow_back</span>
            </a>
            <div>
                <h1 class="font-headline-lg text-heritage-burgundy">Edit YouTube Lookbook</h1>
                <p class="font-body-md text-xs text-on-surface-variant">Update video file/URL, local thumbnail, placement, styling notes, and tagged catalog products.</p>
            </div>
        </div>

        @if($errors->any())
            <div class="p-4 bg-red-50 border border-red-200 text-error text-xs rounded-xl flex items-center gap-2">
                <span class="material-symbols-outlined text-base">error</span>
                <span>{{ $errors->first() }}</span>
            </div>
        @endif

        <form action="{{ route('admin.youtube.update', $video) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf
            @method('PUT')

            <!-- Section 1: Video Information -->
            <div class="bg-surface-container-lowest rounded-2xl border border-border-subtle p-6 md:p-8 shadow-xs space-y-5">
                <h2 class="font-title-lg text-base font-semibold text-heritage-burgundy border-b border-border-subtle pb-3">
                    1. Video Details &amp; Media
                </h2>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div class="md:col-span-2">
                        <label class="block font-label-caps text-xs text-on-surface-variant uppercase mb-1.5 font-semibold" for="title">
                            Video Title <span class="text-error">*</span>
                        </label>
                        <input class="w-full bg-warm-ivory/60 border border-border-subtle rounded-xl px-4 py-3 font-body-md text-sm text-charcoal-text focus:border-heritage-burgundy focus:outline-none transition-colors"
                            id="title" name="title" value="{{ old('title', $video->title) }}" required type="text" />
                    </div>

                    <!-- Video File Upload OR URL -->
                    <div class="md:col-span-2 space-y-2">
                        <label class="block font-label-caps text-xs text-on-surface-variant uppercase font-semibold">
                            Video Source
                        </label>

                        @if($video->youtube_url)
                            <p class="text-xs text-emerald-800 font-semibold flex items-center gap-1 mb-1">
                                <span class="material-symbols-outlined text-sm">smart_display</span>
                                Current: <a href="{{ $video->youtube_url }}" target="_blank" class="underline truncate max-w-xs">{{ $video->youtube_url }}</a>
                            </p>
                        @endif

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="p-4 bg-warm-ivory/60 border-2 border-dashed border-border-subtle rounded-xl hover:border-heritage-burgundy transition-colors text-center">
                                <span class="material-symbols-outlined text-3xl text-heritage-burgundy mb-1">movie</span>
                                <span class="block font-title-lg text-xs font-bold text-charcoal-text">Select Video From Your System</span>
                                <p class="text-[11px] text-on-surface-variant mb-2">MP4, WEBM, MOV up to 100MB</p>
                                <input type="file" name="video_file" accept="video/*"
                                    class="text-xs file:mr-3 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-heritage-burgundy file:text-white cursor-pointer" />
                            </div>

                            <div class="p-4 bg-warm-ivory/40 border border-border-subtle rounded-xl flex flex-col justify-center">
                                <span class="block font-title-lg text-xs font-bold text-charcoal-text mb-1">Or YouTube Video URL</span>
                                <input class="w-full bg-white border border-border-subtle rounded-xl px-3 py-2 text-xs text-charcoal-text focus:border-heritage-burgundy focus:outline-none"
                                    id="youtube_url" name="youtube_url" value="{{ old('youtube_url', $video->youtube_url) }}" type="url" />
                            </div>
                        </div>
                    </div>

                    <div>
                        <label class="block font-label-caps text-xs text-on-surface-variant uppercase mb-1.5 font-semibold" for="duration">
                            Video Duration (MM:SS)
                        </label>
                        <input class="w-full bg-warm-ivory/60 border border-border-subtle rounded-xl px-4 py-3 font-body-md text-sm text-charcoal-text focus:border-heritage-burgundy focus:outline-none transition-colors"
                            id="duration" name="duration" value="{{ old('duration', $video->duration) }}" type="text" />
                    </div>

                    <div>
                        <label class="block font-label-caps text-xs text-on-surface-variant uppercase mb-1.5 font-semibold" for="views_text">
                            Views Display Text
                        </label>
                        <input class="w-full bg-warm-ivory/60 border border-border-subtle rounded-xl px-4 py-3 font-body-md text-sm text-charcoal-text focus:border-heritage-burgundy focus:outline-none transition-colors"
                            id="views_text" name="views_text" value="{{ old('views_text', $video->views_text) }}" type="text" />
                    </div>

                    <!-- Custom Thumbnail File OR URL -->
                    <div class="md:col-span-2 space-y-2">
                        <label class="block font-label-caps text-xs text-on-surface-variant uppercase font-semibold">
                            Thumbnail Cover Image
                        </label>

                        @if($video->thumbnail)
                            <div class="flex items-center gap-3 mb-2">
                                <div class="w-16 aspect-video rounded overflow-hidden border border-border-subtle shrink-0">
                                    <img src="{{ $video->thumbnail }}" alt="{{ $video->title }}" class="w-full h-full object-cover" />
                                </div>
                                <span class="text-xs text-on-surface-variant">Current thumbnail. Upload a new file from system to replace it.</span>
                            </div>
                        @endif

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="p-4 bg-warm-ivory/60 border-2 border-dashed border-border-subtle rounded-xl hover:border-heritage-burgundy transition-colors text-center">
                                <span class="material-symbols-outlined text-3xl text-heritage-burgundy mb-1">upload_file</span>
                                <span class="block font-title-lg text-xs font-bold text-charcoal-text">Select Thumbnail From System</span>
                                <p class="text-[11px] text-on-surface-variant mb-2">PNG, JPG, WEBP</p>
                                <input type="file" name="thumbnail_file" accept="image/*"
                                    class="text-xs file:mr-3 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-heritage-burgundy file:text-white cursor-pointer" />
                            </div>

                            <div class="p-4 bg-warm-ivory/40 border border-border-subtle rounded-xl flex flex-col justify-center">
                                <span class="block font-title-lg text-xs font-bold text-charcoal-text mb-1">Or Paste Image URL</span>
                                <input class="w-full bg-white border border-border-subtle rounded-xl px-3 py-2 text-xs text-charcoal-text focus:border-heritage-burgundy focus:outline-none"
                                    id="thumbnail" name="thumbnail" value="{{ old('thumbnail', $video->thumbnail) }}" type="url" />
                            </div>
                        </div>
                    </div>

                    <div class="md:col-span-2">
                        <label class="block font-label-caps text-xs text-on-surface-variant uppercase mb-1.5 font-semibold" for="description">
                            Video Notes / Episode Description
                        </label>
                        <textarea class="w-full bg-warm-ivory/60 border border-border-subtle rounded-xl px-4 py-3 font-body-md text-sm text-charcoal-text focus:border-heritage-burgundy focus:outline-none transition-colors"
                            id="description" name="description" rows="3">{{ old('description', $video->description) }}</textarea>
                    </div>
                </div>
            </div>

            <!-- Section 2: Placement Flags -->
            <div class="bg-surface-container-lowest rounded-2xl border border-border-subtle p-6 md:p-8 shadow-xs space-y-5">
                <h2 class="font-title-lg text-base font-semibold text-heritage-burgundy border-b border-border-subtle pb-3">
                    2. Storefront Placement &amp; Visibility
                </h2>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <label class="p-4 bg-cream-silk/40 border border-border-subtle rounded-xl flex items-start gap-3 cursor-pointer hover:border-heritage-burgundy transition-colors">
                        <input type="checkbox" name="is_hero" value="1" {{ old('is_hero', $video->is_hero) ? 'checked' : '' }}
                            class="mt-1 rounded border-border-subtle text-heritage-burgundy focus:ring-heritage-burgundy" />
                        <div>
                            <span class="font-title-lg text-xs font-bold text-charcoal-text block">Home Hero Section</span>
                            <span class="text-[11px] text-on-surface-variant">Featured main video preview in the top hero banner.</span>
                        </div>
                    </label>

                    <label class="p-4 bg-cream-silk/40 border border-border-subtle rounded-xl flex items-start gap-3 cursor-pointer hover:border-heritage-burgundy transition-colors">
                        <input type="checkbox" name="is_trending" value="1" {{ old('is_trending', $video->is_trending) ? 'checked' : '' }}
                            class="mt-1 rounded border-border-subtle text-heritage-burgundy focus:ring-heritage-burgundy" />
                        <div>
                            <span class="font-title-lg text-xs font-bold text-charcoal-text block">Collections Trending</span>
                            <span class="text-[11px] text-on-surface-variant">Featured in "Trending on YouTube" showcase with sidebar products.</span>
                        </div>
                    </label>

                    <label class="p-4 bg-cream-silk/40 border border-border-subtle rounded-xl flex items-start gap-3 cursor-pointer hover:border-heritage-burgundy transition-colors">
                        <input type="checkbox" name="is_lookbook" value="1" {{ old('is_lookbook', $video->is_lookbook) ? 'checked' : '' }}
                            class="mt-1 rounded border-border-subtle text-heritage-burgundy focus:ring-heritage-burgundy" />
                        <div>
                            <span class="font-title-lg text-xs font-bold text-charcoal-text block">Home Lookbook Grid</span>
                            <span class="text-[11px] text-on-surface-variant">Displayed as interactive cards in the "Video Lookbooks" grid.</span>
                        </div>
                    </label>
                </div>
            </div>

            <!-- Section 3: Tagged Products -->
            <div class="bg-surface-container-lowest rounded-2xl border border-border-subtle p-6 md:p-8 shadow-xs space-y-5">
                <div>
                    <h2 class="font-title-lg text-base font-semibold text-heritage-burgundy">
                        3. Tag Products Featured in this Video
                    </h2>
                    <p class="font-body-md text-xs text-on-surface-variant mt-1">Select products shown in this video so customers can purchase them directly from the lookbook preview.</p>
                </div>

                <x-admin.product-picker :selected="$selected" />
            </div>

            <!-- Submit Buttons -->
            <div class="pt-2 flex justify-end gap-3">
                <a href="{{ route('admin.youtube.index') }}" class="px-5 py-3 border border-border-subtle rounded-xl text-xs font-label-caps uppercase text-on-surface-variant hover:text-charcoal-text">
                    Cancel
                </a>
                <button type="submit" class="bg-heritage-burgundy text-white font-label-caps text-xs uppercase tracking-wider px-8 py-3.5 rounded-xl hover:bg-primary-container transition-all flex items-center gap-2 font-bold cursor-pointer shadow-sm">
                    <span class="material-symbols-outlined text-base">save</span>
                    Update Lookbook
                </button>
            </div>
        </form>
    </div>
</x-layouts.admin>
