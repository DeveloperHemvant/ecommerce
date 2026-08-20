<x-layouts.app title="All Collections - Sonakshi Fashion Hub">
    <!-- Page Header & Title -->
    <div class="text-center max-w-3xl mx-auto mb-8 pt-2">
        <span class="font-label-caps text-xs text-muted-gold uppercase tracking-widest font-bold block mb-1">Handcrafted Heritage Atelier</span>
        <h1 class="font-headline-lg text-3xl md:text-4xl text-heritage-burgundy font-serif">
            @if($selectedCategory)
                {{ $selectedCategory->name }} Collection
            @elseif(request('search'))
                Search Results for "{{ request('search') }}"
            @else
                Royal Ethnic Collections
            @endif
        </h1>
        <p class="font-body-md text-xs sm:text-sm text-on-surface-variant mt-2 max-w-xl mx-auto">
            @if($selectedCategory && $selectedCategory->description)
                {{ $selectedCategory->description }}
            @else
                Discover handwoven Banarasi Lachas, bridal lehengas, pure silk suits, and heirloom dupattas.
            @endif
        </p>
    </div>

    <!-- Category Visual Carousel / Selector (Balanced & Centered) -->
    <section class="mb-10">
        <div class="flex items-center justify-start md:justify-center gap-4 sm:gap-6 md:gap-8 overflow-x-auto pb-4 pt-2 hide-scrollbar px-2">
            <!-- All Categories Pill -->
            <a href="{{ route('collections') }}" class="flex flex-col items-center gap-2.5 shrink-0 group">
                <div class="w-18 h-18 sm:w-20 sm:h-20 md:w-22 md:h-22 rounded-full p-0.5 border-2 {{ !request('category') ? 'border-heritage-burgundy ring-4 ring-heritage-burgundy/15 scale-105' : 'border-border-subtle group-hover:border-heritage-burgundy/60' }} transition-all bg-surface shadow-xs flex items-center justify-center">
                    <div class="w-full h-full rounded-full bg-cream-silk flex flex-col items-center justify-center text-heritage-burgundy group-hover:scale-105 transition-transform">
                        <span class="material-symbols-outlined text-2xl">auto_awesome</span>
                        <span class="text-[9px] font-label-caps font-bold tracking-wider mt-0.5">ALL</span>
                    </div>
                </div>
                <span class="font-label-caps text-xs uppercase {{ !request('category') ? 'text-heritage-burgundy font-bold' : 'text-charcoal-text group-hover:text-heritage-burgundy' }} transition-colors">
                    All
                </span>
            </a>

            @foreach($categories as $cat)
                <a href="{{ route('collections', ['category' => $cat->slug]) }}" class="flex flex-col items-center gap-2.5 shrink-0 group">
                    <div class="w-18 h-18 sm:w-20 sm:h-20 md:w-22 md:h-22 rounded-full p-0.5 border-2 {{ request('category') == $cat->slug ? 'border-heritage-burgundy ring-4 ring-heritage-burgundy/15 scale-105' : 'border-border-subtle group-hover:border-heritage-burgundy/60' }} transition-all bg-surface shadow-xs">
                        @if($cat->image)
                            <img class="w-full h-full object-cover rounded-full group-hover:scale-105 transition-transform duration-300"
                                data-alt="{{ $cat->name }}"
                                src="{{ $cat->image }}" />
                        @else
                            <div class="w-full h-full rounded-full bg-cream-silk flex items-center justify-center text-heritage-burgundy">
                                <span class="material-symbols-outlined text-2xl">category</span>
                            </div>
                        @endif
                    </div>
                    <span class="font-label-caps text-xs uppercase {{ request('category') == $cat->slug ? 'text-heritage-burgundy font-bold' : 'text-charcoal-text group-hover:text-heritage-burgundy' }} transition-colors whitespace-nowrap">
                        {{ $cat->name }}
                    </span>
                </a>
            @endforeach
        </div>
    </section>

    <!-- Search & Filter Action Bar (Clean Full-Width Container) -->
    <div class="bg-surface-container-lowest rounded-2xl border border-border-subtle p-4 md:p-5 shadow-xs mb-10 flex flex-col md:flex-row items-center justify-between gap-4">
        <!-- Search Input -->
        <form action="{{ route('collections') }}" method="GET" class="relative w-full md:max-w-md flex items-center">
            @if(request('category'))
                <input type="hidden" name="category" value="{{ request('category') }}" />
            @endif
            <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-on-surface-variant text-base">search</span>
            <input
                class="w-full bg-warm-ivory/60 border border-border-subtle rounded-xl pl-9 pr-4 py-2.5 font-body-md text-xs text-charcoal-text focus:border-heritage-burgundy focus:outline-none transition-colors placeholder:text-on-surface-variant/60"
                placeholder="Search by garment name, fabric, color, or SKU..."
                name="search"
                value="{{ request('search') }}"
                type="text" />
            @if(request('search') || request('category'))
                <a href="{{ route('collections') }}" class="text-xs font-label-caps uppercase text-error hover:underline ml-3 shrink-0 font-bold">Clear</a>
            @endif
        </form>

        <!-- Count & Active Filter Indicator -->
        <div class="flex items-center justify-between md:justify-end gap-4 w-full md:w-auto text-xs">
            <span class="font-data-tabular text-on-surface-variant">
                Showing <strong class="text-heritage-burgundy font-bold">{{ $products->count() }}</strong> {{ Str::plural('piece', $products->count()) }}
            </span>
            <div class="h-4 w-px bg-border-subtle hidden sm:block"></div>
            <div class="flex items-center gap-1 text-on-surface-variant">
                <span class="font-label-caps uppercase text-[11px]">Curation:</span>
                <span class="font-bold text-charcoal-text">{{ $selectedCategory->name ?? 'All Heritage' }}</span>
            </div>
        </div>
    </div>

    <!-- Product Showcase Catalog Grid (2 cols mobile, 3 tablet, 4 desktop) -->
    <section class="mb-16">
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 sm:gap-6 md:gap-8">
            @forelse($products as $product)
                <div class="group flex flex-col justify-between bg-surface-container-lowest rounded-2xl border border-border-subtle p-3 sm:p-4 shadow-xs hover:border-heritage-burgundy/40 hover:shadow-md transition-all">
                    <!-- Product Image & Floating Actions -->
                    <div class="relative aspect-[3/4] rounded-xl overflow-hidden bg-surface mb-3.5">
                        <a href="{{ route('product.detail', $product->slug) }}" class="block w-full h-full">
                            <img class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700"
                                data-alt="{{ $product->name }}"
                                src="{{ $product->main_image }}" />
                        </a>

                        <!-- Discount / Stock Badges -->
                        <div class="absolute top-2.5 left-2.5 flex flex-col gap-1 z-10 pointer-events-none">
                            @if($product->discount_percentage)
                                <span class="bg-heritage-burgundy text-white font-label-caps text-[9px] sm:text-[10px] px-2 py-0.5 rounded-full font-bold shadow-xs">
                                    {{ $product->discount_percentage }}% OFF
                                </span>
                            @endif
                            @if($product->stock <= 0)
                                <span class="bg-gray-800 text-white font-label-caps text-[9px] sm:text-[10px] px-2 py-0.5 rounded-full font-bold shadow-xs">
                                    Sold Out
                                </span>
                            @elseif($product->is_low_stock)
                                <span class="bg-amber-700 text-white font-label-caps text-[9px] sm:text-[10px] px-2 py-0.5 rounded-full font-bold shadow-xs">
                                    Only {{ $product->stock }} left
                                </span>
                            @endif
                        </div>

                        <!-- Wishlist Toggle Button -->
                        <form action="{{ route('wishlist.toggle') }}" method="POST" class="absolute top-2.5 right-2.5 z-10">
                            @csrf
                            <input type="hidden" name="product_id" value="{{ $product->id }}" />
                            @php
                                $isSaved = false;
                                if(Auth::check()) {
                                    $isSaved = Auth::user()->wishlists()->where('product_id', $product->id)->exists();
                                } else {
                                    $isSaved = in_array($product->id, session('wishlist', []));
                                }
                            @endphp
                            <button type="submit" class="w-8 h-8 rounded-full bg-white/90 backdrop-blur-md {{ $isSaved ? 'text-error' : 'text-on-surface-variant hover:text-error' }} flex items-center justify-center shadow-sm hover:scale-110 transition-transform cursor-pointer" title="{{ $isSaved ? 'Remove from Wishlist' : 'Save to Wishlist' }}">
                                <span class="material-symbols-outlined text-base" @if($isSaved) style="font-variation-settings: 'FILL' 1;" @endif>favorite</span>
                            </button>
                        </form>
                    </div>

                    <!-- Product Meta & Price -->
                    <div class="space-y-2 flex-1 flex flex-col justify-between">
                        <div>
                            <span class="text-[10px] font-label-caps uppercase text-on-surface-variant block tracking-wider">{{ $product->category->name ?? 'Couture' }}</span>
                            <h3 class="font-title-lg text-xs sm:text-sm font-semibold text-charcoal-text line-clamp-1 mt-0.5 group-hover:text-heritage-burgundy transition-colors">
                                <a href="{{ route('product.detail', $product->slug) }}">
                                    {{ $product->name }}
                                </a>
                            </h3>
                            <div class="flex items-center gap-2 mt-1">
                                <span class="font-data-tabular font-bold text-sm text-heritage-burgundy">{{ $product->formatted_price }}</span>
                                @if($product->formatted_compare_price)
                                    <span class="font-data-tabular text-[11px] text-on-surface-variant line-through">{{ $product->formatted_compare_price }}</span>
                                @endif
                            </div>
                        </div>

                        <!-- Action Link -->
                        <div class="pt-2 border-t border-border-subtle/60 flex items-center justify-between gap-2">
                            <a href="{{ route('product.detail', $product->slug) }}" class="text-[11px] font-label-caps uppercase font-bold text-heritage-burgundy hover:underline">
                                View Piece &rarr;
                            </a>
                            <form action="{{ route('cart.add') }}" method="POST">
                                @csrf
                                <input type="hidden" name="product_id" value="{{ $product->id }}" />
                                <button type="submit" class="w-8 h-8 rounded-lg bg-warm-ivory text-heritage-burgundy hover:bg-heritage-burgundy hover:text-white transition-colors flex items-center justify-center cursor-pointer" title="Add to Bag">
                                    <span class="material-symbols-outlined text-base">shopping_bag</span>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-full py-16 text-center bg-surface-container-lowest rounded-2xl border border-border-subtle p-8">
                    <div class="w-16 h-16 rounded-full bg-cream-silk text-heritage-burgundy flex items-center justify-center mx-auto mb-3">
                        <span class="material-symbols-outlined text-3xl">dry_cleaning</span>
                    </div>
                    <h3 class="font-headline-md text-lg text-charcoal-text">No Ensembles Found</h3>
                    <p class="font-body-md text-xs text-on-surface-variant mt-1">Try clearing your search query or choosing another category above.</p>
                    <a href="{{ route('collections') }}" class="inline-block mt-4 text-xs font-label-caps text-white bg-heritage-burgundy px-6 py-3 rounded-xl uppercase font-bold hover:bg-primary-container transition-all">
                        View All Collections
                    </a>
                </div>
            @endforelse
        </div>
    </section>

    <!-- Trending on YouTube Lookbook (Dynamic from YouTube CMS) -->
    @if(isset($trendingVideo) && $trendingVideo)
        <section class="bg-surface-container-low rounded-3xl p-6 md:p-10 border border-border-subtle/80 mb-12">
            <div class="mb-6 flex flex-col sm:flex-row sm:items-end justify-between gap-2">
                <div>
                    <span class="font-label-caps text-xs text-muted-gold uppercase tracking-widest font-semibold block mb-1">Curated Video Masterclass</span>
                    <h2 class="font-headline-lg text-2xl md:text-3xl text-heritage-burgundy font-serif">
                        Trending on YouTube
                    </h2>
                </div>
                <a class="font-label-caps text-xs uppercase text-heritage-burgundy hover:underline flex items-center gap-1 font-bold"
                    href="{{ $trendingVideo->youtube_url }}" target="_blank">
                    <span>Watch Full Masterclass</span>
                    <span class="material-symbols-outlined text-sm">open_in_new</span>
                </a>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
                <!-- Video Preview (8 Cols) -->
                <div class="lg:col-span-8 relative group cursor-pointer overflow-hidden rounded-2xl bg-surface-container shadow-md">
                    <a href="{{ $trendingVideo->youtube_url }}" target="_blank" class="block aspect-video w-full relative">
                        <img class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-105"
                            data-alt="{{ $trendingVideo->title }}"
                            src="{{ $trendingVideo->thumbnail ?? 'https://lh3.googleusercontent.com/aida-public/AB6AXuCzQlvbBZ_7sBoCi083plH6_cPAtuOm2s7NPLKZvmSANG7K09ZNrbIPDaRZCkHMnYdK27C5TsIL0IOnTFP5X3MKW2S1Tt2ghmuhABQx-O9imTL5wVx3mYlYDB7UVKvnN9d7TXVBTsAdUJiaERBH9U4EeaGmi_uhOjXssQaxKzlTvliMn39rAXx8AP2RLguYT9l-4m0lZvtDCulkHmZgjl9q6W1CFaItU1CirxuXldi197muluEMndo5' }}" />
                        <div class="absolute inset-0 bg-black/25 group-hover:bg-black/15 transition-colors"></div>
                        <div class="absolute inset-0 flex items-center justify-center">
                            <div class="w-16 h-16 bg-white/90 backdrop-blur rounded-full flex items-center justify-center text-heritage-burgundy group-hover:scale-110 transition-transform shadow-xl">
                                <span class="material-symbols-outlined text-4xl ml-1" style="font-variation-settings: 'FILL' 1;">play_arrow</span>
                            </div>
                        </div>
                    </a>
                </div>

                <!-- Featured in Video Sidebar (4 Cols) -->
                <div class="lg:col-span-4 flex flex-col gap-4">
                    <h3 class="font-title-lg text-sm font-bold text-heritage-burgundy uppercase font-label-caps border-b border-border-subtle pb-2">
                        Garments Styled in this Episode
                    </h3>

                    @forelse($trendingVideo->products as $p)
                        <a href="{{ route('product.detail', $p->slug) }}"
                            class="flex gap-3.5 group cursor-pointer p-3 bg-surface-container-lowest rounded-2xl border border-border-subtle hover:border-heritage-burgundy/40 shadow-2xs transition-all">
                            <div class="w-20 h-26 shrink-0 overflow-hidden rounded-xl bg-surface border border-border-subtle">
                                <img class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"
                                    data-alt="{{ $p->name }}"
                                    src="{{ $p->main_image }}" />
                            </div>
                            <div class="flex flex-col justify-center flex-1 min-w-0">
                                <span class="text-[9px] font-label-caps uppercase text-on-surface-variant">{{ $p->category->name ?? 'Collection' }}</span>
                                <h4 class="font-headline-md text-xs font-semibold text-charcoal-text truncate group-hover:text-heritage-burgundy transition-colors">
                                    {{ $p->name }}
                                </h4>
                                <div class="flex items-center gap-2 mt-1">
                                    <p class="font-data-tabular text-heritage-burgundy font-bold text-xs">{{ $p->formatted_price }}</p>
                                    @if($p->discount_percentage)
                                        <span class="text-[9px] font-label-caps text-emerald-700 font-bold bg-emerald-50 px-1.5 py-0.5 rounded">{{ $p->discount_percentage }}% OFF</span>
                                    @endif
                                </div>
                                <span class="mt-2 self-start text-[10px] font-label-caps uppercase text-heritage-burgundy font-bold hover:underline">
                                    Inspect Piece &rarr;
                                </span>
                            </div>
                        </a>
                    @empty
                        <p class="text-xs font-body-md text-on-surface-variant italic">Browse our full catalog above.</p>
                    @endforelse
                </div>
            </div>
        </section>
    @endif
</x-layouts.app>
