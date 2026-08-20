<x-layouts.admin title="Edit {{ $category->name }} - Sonakshi Admin" active="categories">
    <div class="max-w-2xl mx-auto space-y-6">
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.categories.index') }}" class="text-on-surface-variant hover:text-heritage-burgundy transition-colors p-1">
                <span class="material-symbols-outlined text-xl">arrow_back</span>
            </a>
            <div>
                <h1 class="font-headline-lg text-heritage-burgundy">Edit Category</h1>
                <p class="font-body-md text-xs text-on-surface-variant">Update category title, local image, description, and navigation order.</p>
            </div>
        </div>

        @if($errors->any())
            <div class="p-4 bg-red-50 border border-red-200 text-error text-xs rounded-xl flex items-center gap-2">
                <span class="material-symbols-outlined text-base">error</span>
                <span>{{ $errors->first() }}</span>
            </div>
        @endif

        <form action="{{ route('admin.categories.update', $category) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf
            @method('PUT')

            <div class="bg-surface-container-lowest rounded-2xl border border-border-subtle p-6 md:p-8 shadow-xs space-y-5">
                <div>
                    <label class="block font-label-caps text-xs text-on-surface-variant uppercase mb-1.5 font-semibold" for="name">
                        Category Name <span class="text-error">*</span>
                    </label>
                    <input class="w-full bg-warm-ivory/60 border border-border-subtle rounded-xl px-4 py-3 font-body-md text-sm text-charcoal-text focus:border-heritage-burgundy focus:outline-none transition-colors"
                        id="name" name="name" value="{{ old('name', $category->name) }}" required type="text" />
                </div>

                <!-- Category Image Upload OR URL -->
                <div class="space-y-2">
                    <label class="block font-label-caps text-xs text-on-surface-variant uppercase font-semibold">
                        Category Thumbnail Image
                    </label>

                    @if($category->image)
                        <div class="flex items-center gap-3 mb-2">
                            <div class="w-12 h-12 rounded-full overflow-hidden border border-border-subtle shrink-0">
                                <img src="{{ $category->image }}" alt="{{ $category->name }}" class="w-full h-full object-cover" />
                            </div>
                            <span class="text-xs text-on-surface-variant">Current Image. Upload a new file from system to replace it.</span>
                        </div>
                    @endif

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div class="p-4 bg-warm-ivory/60 border-2 border-dashed border-border-subtle rounded-xl text-center hover:border-heritage-burgundy transition-colors">
                            <span class="material-symbols-outlined text-2xl text-heritage-burgundy mb-1">upload_file</span>
                            <span class="block text-xs font-bold text-charcoal-text">Select From System</span>
                            <p class="text-[10px] text-on-surface-variant mb-2">PNG, JPG, WEBP</p>
                            <input type="file" name="image_file" accept="image/*"
                                class="text-xs file:mr-2 file:py-1 file:px-2.5 file:rounded file:border-0 file:text-[11px] file:font-semibold file:bg-heritage-burgundy file:text-white cursor-pointer" />
                        </div>

                        <div class="p-4 bg-warm-ivory/40 border border-border-subtle rounded-xl flex flex-col justify-center">
                            <span class="block text-xs font-bold text-charcoal-text mb-1">Or Paste Image URL</span>
                            <input class="w-full bg-white border border-border-subtle rounded-xl px-3 py-2 text-xs text-charcoal-text focus:border-heritage-burgundy focus:outline-none"
                                id="image" name="image" value="{{ old('image', $category->image) }}" placeholder="https://..." type="url" />
                        </div>
                    </div>
                </div>

                <div>
                    <label class="block font-label-caps text-xs text-on-surface-variant uppercase mb-1.5 font-semibold" for="description">
                        Description / Story
                    </label>
                    <textarea class="w-full bg-warm-ivory/60 border border-border-subtle rounded-xl px-4 py-3 font-body-md text-sm text-charcoal-text focus:border-heritage-burgundy focus:outline-none transition-colors"
                        id="description" name="description" rows="3">{{ old('description', $category->description) }}</textarea>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5 pt-2 border-t border-border-subtle">
                    <div>
                        <label class="block font-label-caps text-xs text-on-surface-variant uppercase mb-1.5 font-semibold" for="display_order">
                            Display Order (Sort Order)
                        </label>
                        <input class="w-full bg-warm-ivory/60 border border-border-subtle rounded-xl px-4 py-3 font-body-md text-sm text-charcoal-text focus:border-heritage-burgundy focus:outline-none transition-colors"
                            id="display_order" name="display_order" value="{{ old('display_order', $category->display_order) }}" type="number" />
                    </div>

                    <div class="flex items-center pt-6">
                        <label class="flex items-center gap-2 cursor-pointer text-xs font-title-lg">
                            <input type="checkbox" name="is_active" value="1" {{ old('is_active', $category->is_active) ? 'checked' : '' }}
                                class="rounded border-border-subtle text-heritage-burgundy focus:ring-heritage-burgundy" />
                            <span class="text-charcoal-text font-semibold">Active &amp; Visible in Navigation</span>
                        </label>
                    </div>
                </div>
            </div>

            <div class="flex justify-end gap-3">
                <a href="{{ route('admin.categories.index') }}" class="px-5 py-3 border border-border-subtle rounded-xl text-xs font-label-caps uppercase text-on-surface-variant hover:text-charcoal-text">
                    Cancel
                </a>
                <button type="submit" class="bg-heritage-burgundy text-white font-label-caps text-xs uppercase tracking-wider px-8 py-3.5 rounded-xl hover:bg-primary-container transition-all flex items-center gap-2 font-bold cursor-pointer shadow-sm">
                    <span class="material-symbols-outlined text-base">save</span>
                    Update Category
                </button>
            </div>
        </form>
    </div>
</x-layouts.admin>
