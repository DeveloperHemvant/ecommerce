<x-layouts.admin title="Edit {{ $product->name }} - Sonakshi Admin" active="products">
    <div class="max-w-4xl mx-auto space-y-6">
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.products.index') }}" class="text-on-surface-variant hover:text-heritage-burgundy transition-colors p-1">
                <span class="material-symbols-outlined text-xl">arrow_back</span>
            </a>
            <div>
                <h1 class="font-headline-lg text-heritage-burgundy">Edit Product</h1>
                <p class="font-body-md text-xs text-on-surface-variant">Update SKU, inventory stock, local system images/videos, and product specifications.</p>
            </div>
        </div>

        @if($errors->any())
            <div class="p-4 bg-red-50 border border-red-200 text-error text-xs rounded-xl flex items-center gap-2">
                <span class="material-symbols-outlined text-base">error</span>
                <span>{{ $errors->first() }}</span>
            </div>
        @endif

        <form action="{{ route('admin.products.update', $product) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf
            @method('PUT')

            <!-- Section 1: Basic Details -->
            <div class="bg-surface-container-lowest rounded-2xl border border-border-subtle p-6 md:p-8 shadow-xs space-y-5">
                <h2 class="font-title-lg text-base font-semibold text-heritage-burgundy border-b border-border-subtle pb-3">
                    1. Product Information
                </h2>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div class="md:col-span-2">
                        <label class="block font-label-caps text-xs text-on-surface-variant uppercase mb-1.5 font-semibold" for="name">
                            Product Name <span class="text-error">*</span>
                        </label>
                        <input class="w-full bg-warm-ivory/60 border border-border-subtle rounded-xl px-4 py-3 font-body-md text-sm text-charcoal-text focus:border-heritage-burgundy focus:outline-none transition-colors"
                            id="name" name="name" value="{{ old('name', $product->name) }}" required type="text" />
                    </div>

                    <div>
                        <label class="block font-label-caps text-xs text-on-surface-variant uppercase mb-1.5 font-semibold" for="category_id">
                            Category <span class="text-error">*</span>
                        </label>
                        <select class="w-full bg-warm-ivory/60 border border-border-subtle rounded-xl px-4 py-3 font-body-md text-sm text-charcoal-text focus:border-heritage-burgundy focus:outline-none transition-colors"
                            id="category_id" name="category_id" required>
                            @foreach($categories as $cat)
                                <option value="{{ $cat->id }}" {{ old('category_id', $product->category_id) == $cat->id ? 'selected' : '' }}>
                                    {{ $cat->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block font-label-caps text-xs text-on-surface-variant uppercase mb-1.5 font-semibold" for="sku">
                            SKU Code <span class="text-error">*</span>
                        </label>
                        <input class="w-full bg-warm-ivory/60 border border-border-subtle rounded-xl px-4 py-3 font-body-md text-sm text-charcoal-text focus:border-heritage-burgundy focus:outline-none transition-colors uppercase font-data-tabular"
                            id="sku" name="sku" value="{{ old('sku', $product->sku) }}" required type="text" />
                    </div>
                </div>
            </div>

            <!-- Section 2: Pricing & Stock Inventory -->
            <div class="bg-surface-container-lowest rounded-2xl border border-border-subtle p-6 md:p-8 shadow-xs space-y-5">
                <h2 class="font-title-lg text-base font-semibold text-heritage-burgundy border-b border-border-subtle pb-3">
                    2. Pricing &amp; Warehouse Inventory
                </h2>

                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-5">
                    <div>
                        <label class="block font-label-caps text-xs text-on-surface-variant uppercase mb-1.5 font-semibold" for="price">
                            Selling Price (₹) <span class="text-error">*</span>
                        </label>
                        <input class="w-full bg-warm-ivory/60 border border-border-subtle rounded-xl px-4 py-3 font-body-md text-sm text-charcoal-text focus:border-heritage-burgundy focus:outline-none transition-colors font-data-tabular"
                            id="price" name="price" value="{{ old('price', $product->price) }}" required step="0.01" type="number" />
                    </div>

                    <div>
                        <label class="block font-label-caps text-xs text-on-surface-variant uppercase mb-1.5 font-semibold" for="compare_price">
                            Original / Compare Price (₹)
                        </label>
                        <input class="w-full bg-warm-ivory/60 border border-border-subtle rounded-xl px-4 py-3 font-body-md text-sm text-charcoal-text focus:border-heritage-burgundy focus:outline-none transition-colors font-data-tabular"
                            id="compare_price" name="compare_price" value="{{ old('compare_price', $product->compare_price) }}" step="0.01" type="number" />
                    </div>

                    <div>
                        <label class="block font-label-caps text-xs text-on-surface-variant uppercase mb-1.5 font-semibold" for="stock">
                            Stock Quantity <span class="text-error">*</span>
                        </label>
                        <input class="w-full bg-warm-ivory/60 border border-border-subtle rounded-xl px-4 py-3 font-body-md text-sm text-charcoal-text focus:border-heritage-burgundy focus:outline-none transition-colors font-data-tabular"
                            id="stock" name="stock" value="{{ old('stock', $product->stock) }}" required type="number" />
                    </div>

                    <div>
                        <label class="block font-label-caps text-xs text-on-surface-variant uppercase mb-1.5 font-semibold" for="low_stock_threshold">
                            Low Stock Alert Level
                        </label>
                        <input class="w-full bg-warm-ivory/60 border border-border-subtle rounded-xl px-4 py-3 font-body-md text-sm text-charcoal-text focus:border-heritage-burgundy focus:outline-none transition-colors font-data-tabular"
                            id="low_stock_threshold" name="low_stock_threshold" value="{{ old('low_stock_threshold', $product->low_stock_threshold) }}" type="number" />
                    </div>
                </div>
            </div>

            <!-- Section 3: Media & Video (Upload From System OR URL) -->
            <div class="bg-surface-container-lowest rounded-2xl border border-border-subtle p-6 md:p-8 shadow-xs space-y-5">
                <h2 class="font-title-lg text-base font-semibold text-heritage-burgundy border-b border-border-subtle pb-3">
                    3. Product Imagery &amp; Video Media
                </h2>

                <!-- Main Image -->
                <div class="space-y-3">
                    <label class="block font-label-caps text-xs text-on-surface-variant uppercase font-semibold">
                        Main Cover Image
                    </label>

                    <div class="flex items-center gap-4 mb-2">
                        <div class="w-16 h-20 rounded-xl overflow-hidden bg-surface shrink-0 border border-border-subtle">
                            <img src="{{ $product->main_image }}" alt="{{ $product->name }}" class="w-full h-full object-cover" />
                        </div>
                        <div class="text-xs text-on-surface-variant">
                            <span class="font-bold text-charcoal-text block">Current Cover Image</span>
                            <span class="text-[11px]">Upload a new file from your system to replace it or update URL below.</span>
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <!-- File Upload -->
                        <div class="p-4 bg-warm-ivory/60 border-2 border-dashed border-border-subtle rounded-xl hover:border-heritage-burgundy transition-colors text-center">
                            <span class="material-symbols-outlined text-3xl text-heritage-burgundy mb-1">upload_file</span>
                            <span class="block font-title-lg text-xs font-bold text-charcoal-text">Select Image From Your System</span>
                            <p class="text-[11px] text-on-surface-variant mb-2">PNG, JPG, WEBP up to 10MB</p>
                            <input type="file" name="main_image_file" accept="image/*"
                                class="text-xs file:mr-3 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-heritage-burgundy file:text-white hover:file:bg-primary-container cursor-pointer" />
                        </div>

                        <!-- URL Alternative -->
                        <div class="p-4 bg-warm-ivory/40 border border-border-subtle rounded-xl flex flex-col justify-center">
                            <span class="block font-title-lg text-xs font-bold text-charcoal-text mb-1">Or Direct Image URL</span>
                            <input class="w-full bg-white border border-border-subtle rounded-xl px-3 py-2 font-body-md text-xs text-charcoal-text focus:border-heritage-burgundy focus:outline-none"
                                name="main_image" value="{{ old('main_image', $product->main_image) }}" type="url" />
                        </div>
                    </div>
                </div>

                <!-- Product Video -->
                <div class="space-y-3 pt-4 border-t border-border-subtle">
                    <label class="block font-label-caps text-xs text-on-surface-variant uppercase font-semibold">
                        Product Video (Optional)
                    </label>

                    @if($product->video)
                        <p class="text-xs text-emerald-800 font-semibold flex items-center gap-1 mb-1">
                            <span class="material-symbols-outlined text-sm">video_library</span>
                            Video attached: <a href="{{ $product->video }}" target="_blank" class="underline truncate max-w-xs">{{ $product->video }}</a>
                        </p>
                    @endif
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <!-- Video File Upload -->
                        <div class="p-4 bg-warm-ivory/60 border-2 border-dashed border-border-subtle rounded-xl hover:border-heritage-burgundy transition-colors text-center">
                            <span class="material-symbols-outlined text-3xl text-heritage-burgundy mb-1">movie</span>
                            <span class="block font-title-lg text-xs font-bold text-charcoal-text">Select Video From Your System</span>
                            <p class="text-[11px] text-on-surface-variant mb-2">MP4, WEBM, MOV up to 100MB</p>
                            <input type="file" name="video_file" accept="video/*"
                                class="text-xs file:mr-3 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-heritage-burgundy file:text-white hover:file:bg-primary-container cursor-pointer" />
                        </div>

                        <!-- Video URL Alternative -->
                        <div class="p-4 bg-warm-ivory/40 border border-border-subtle rounded-xl flex flex-col justify-center">
                            <span class="block font-title-lg text-xs font-bold text-charcoal-text mb-1">Or Paste Video URL / YouTube</span>
                            <input class="w-full bg-white border border-border-subtle rounded-xl px-3 py-2 font-body-md text-xs text-charcoal-text focus:border-heritage-burgundy focus:outline-none"
                                name="video" value="{{ old('video', $product->video) }}" placeholder="https://..." type="url" />
                        </div>
                    </div>
                </div>

                <!-- Gallery Images -->
                <div class="space-y-3 pt-4 border-t border-border-subtle">
                    <label class="block font-label-caps text-xs text-on-surface-variant uppercase font-semibold">
                        Multi-Angle Gallery Thumbnails
                    </label>

                    @if(!empty($product->gallery_images))
                        <div class="flex gap-2 mb-2 overflow-x-auto pb-1">
                            @foreach($product->gallery_images as $gImg)
                                <div class="w-12 h-16 rounded overflow-hidden bg-surface shrink-0 border border-border-subtle">
                                    <img src="{{ $gImg }}" alt="Gallery" class="w-full h-full object-cover" />
                                </div>
                            @endforeach
                        </div>
                    @endif
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <!-- Multiple File Upload -->
                        <div class="p-4 bg-warm-ivory/60 border-2 border-dashed border-border-subtle rounded-xl hover:border-heritage-burgundy transition-colors text-center">
                            <span class="material-symbols-outlined text-3xl text-heritage-burgundy mb-1">collections</span>
                            <span class="block font-title-lg text-xs font-bold text-charcoal-text">Add More Images From System</span>
                            <p class="text-[11px] text-on-surface-variant mb-2">Select 1 or more photos to append</p>
                            <input type="file" name="gallery_images_files[]" multiple accept="image/*"
                                class="text-xs file:mr-3 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-heritage-burgundy file:text-white hover:file:bg-primary-container cursor-pointer" />
                        </div>

                        <!-- Multi-Line URLs -->
                        <div class="p-4 bg-warm-ivory/40 border border-border-subtle rounded-xl">
                            <span class="block font-title-lg text-xs font-bold text-charcoal-text mb-1">Or Multi-Line URLs</span>
                            <textarea class="w-full bg-white border border-border-subtle rounded-xl p-2.5 font-body-md text-xs text-charcoal-text focus:border-heritage-burgundy focus:outline-none font-mono"
                                name="gallery_images" rows="3">{{ old('gallery_images', !empty($product->gallery_images) ? implode("\n", $product->gallery_images) : '') }}</textarea>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Section 4: Variants & Sizing -->
            <div class="bg-surface-container-lowest rounded-2xl border border-border-subtle p-6 md:p-8 shadow-xs space-y-5">
                <h2 class="font-title-lg text-base font-semibold text-heritage-burgundy border-b border-border-subtle pb-3">
                    4. Sizing &amp; Color Variants
                </h2>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div>
                        <label class="block font-label-caps text-xs text-on-surface-variant uppercase mb-1.5 font-semibold" for="sizes_text">
                            Available Sizes (comma separated)
                        </label>
                        <input class="w-full bg-warm-ivory/60 border border-border-subtle rounded-xl px-4 py-3 font-body-md text-sm text-charcoal-text focus:border-heritage-burgundy focus:outline-none transition-colors"
                            id="sizes_text" name="sizes_text" value="{{ old('sizes_text', !empty($product->sizes) ? implode(', ', $product->sizes) : 'S, M, L, XL, CUSTOM FIT') }}" type="text" />
                    </div>

                    <div>
                        <label class="block font-label-caps text-xs text-on-surface-variant uppercase mb-1.5 font-semibold" for="colors_text">
                            Color Variants (comma separated)
                        </label>
                        <input class="w-full bg-warm-ivory/60 border border-border-subtle rounded-xl px-4 py-3 font-body-md text-sm text-charcoal-text focus:border-heritage-burgundy focus:outline-none transition-colors"
                            id="colors_text" name="colors_text" value="{{ old('colors_text', !empty($product->colors) ? implode(', ', $product->colors) : '') }}" type="text" />
                    </div>
                </div>
            </div>

            <!-- Section 5: Descriptions & Specifications -->
            <div class="bg-surface-container-lowest rounded-2xl border border-border-subtle p-6 md:p-8 shadow-xs space-y-5">
                <h2 class="font-title-lg text-base font-semibold text-heritage-burgundy border-b border-border-subtle pb-3">
                    5. Description &amp; Care Details
                </h2>

                <div class="space-y-4">
                    <div>
                        <label class="block font-label-caps text-xs text-on-surface-variant uppercase mb-1.5 font-semibold" for="description">
                            Product Description &amp; Craft Story
                        </label>
                        <textarea class="w-full bg-warm-ivory/60 border border-border-subtle rounded-xl px-4 py-3 font-body-md text-sm text-charcoal-text focus:border-heritage-burgundy focus:outline-none transition-colors"
                            id="description" name="description" rows="4">{{ old('description', $product->description) }}</textarea>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block font-label-caps text-xs text-on-surface-variant uppercase mb-1.5 font-semibold" for="fabric_care">
                                Fabric &amp; Care Instructions
                            </label>
                            <textarea class="w-full bg-warm-ivory/60 border border-border-subtle rounded-xl px-4 py-3 font-body-md text-sm text-charcoal-text focus:border-heritage-burgundy focus:outline-none transition-colors"
                                id="fabric_care" name="fabric_care" rows="3">{{ old('fabric_care', $product->fabric_care) }}</textarea>
                        </div>

                        <div>
                            <label class="block font-label-caps text-xs text-on-surface-variant uppercase mb-1.5 font-semibold" for="shipping_info">
                                Shipping &amp; Delivery Timeline
                            </label>
                            <textarea class="w-full bg-warm-ivory/60 border border-border-subtle rounded-xl px-4 py-3 font-body-md text-sm text-charcoal-text focus:border-heritage-burgundy focus:outline-none transition-colors"
                                id="shipping_info" name="shipping_info" rows="3">{{ old('shipping_info', $product->shipping_info) }}</textarea>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Section 6: Tags & Visibility -->
            <div class="bg-surface-container-lowest rounded-2xl border border-border-subtle p-6 md:p-8 shadow-xs space-y-5">
                <h2 class="font-title-lg text-base font-semibold text-heritage-burgundy border-b border-border-subtle pb-3">
                    6. Tags &amp; Storefront Visibility
                </h2>

                @php
                    $selectedTagIds = old('tag_ids', $product->tags->pluck('id')->toArray());
                @endphp
                <div class="space-y-4">
                    <div>
                        <label class="block font-label-caps text-xs text-on-surface-variant uppercase mb-2 font-semibold">
                            Select Tags / Curations
                        </label>
                        <div class="flex flex-wrap gap-4 max-h-48 overflow-y-auto pr-1">
                            @foreach($tags as $t)
                                <label class="inline-flex items-center gap-2 px-3 py-1.5 bg-warm-ivory/60 border border-border-subtle rounded-xl cursor-pointer hover:border-heritage-burgundy transition-colors text-xs font-body-md">
                                    <input type="checkbox" name="tag_ids[]" value="{{ $t->id }}"
                                        {{ in_array($t->id, $selectedTagIds) ? 'checked' : '' }}
                                        class="rounded border-border-subtle text-heritage-burgundy focus:ring-heritage-burgundy" />
                                    <span>{{ $t->name }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>

                    <div class="flex flex-wrap gap-6 pt-2 border-t border-border-subtle">
                        <label class="flex items-center gap-2 cursor-pointer text-xs font-title-lg">
                            <input type="checkbox" name="is_featured" value="1" {{ old('is_featured', $product->is_featured) ? 'checked' : '' }}
                                class="rounded border-border-subtle text-heritage-burgundy focus:ring-heritage-burgundy" />
                            <span class="text-charcoal-text font-semibold">Featured on Homepage</span>
                        </label>

                        <label class="flex items-center gap-2 cursor-pointer text-xs font-title-lg">
                            <input type="checkbox" name="is_active" value="1" {{ old('is_active', $product->is_active) ? 'checked' : '' }}
                                class="rounded border-border-subtle text-heritage-burgundy focus:ring-heritage-burgundy" />
                            <span class="text-charcoal-text font-semibold">Published &amp; Active</span>
                        </label>
                    </div>
                </div>
            </div>

            <!-- Submit Actions -->
            <div class="pt-2 flex justify-end gap-3">
                <a href="{{ route('admin.products.index') }}" class="px-5 py-3 border border-border-subtle rounded-xl text-xs font-label-caps uppercase text-on-surface-variant hover:text-charcoal-text">
                    Cancel
                </a>
                <button type="submit" class="bg-heritage-burgundy text-white font-label-caps text-xs uppercase tracking-wider px-8 py-3.5 rounded-xl hover:bg-primary-container transition-all flex items-center gap-2 font-bold cursor-pointer shadow-sm">
                    <span class="material-symbols-outlined text-base">save</span>
                    Update Product
                </button>
            </div>
        </form>
    </div>
</x-layouts.admin>
