<x-layouts.app title="All Collections - Sonakshi Fashion Hub">
    <!-- Search & Filter Row -->
    <section class="py-4 md:py-6 flex items-center justify-between gap-4 sticky top-16 z-30 bg-warm-ivory/95 backdrop-blur-md border-b border-border-subtle/60 -mx-margin-mobile md:-mx-margin-desktop px-margin-mobile md:px-margin-desktop mb-8">
        <form action="{{ route('collections') }}" method="GET" class="relative w-full max-w-md flex items-center">
            @if(request('category'))
                <input type="hidden" name="category" value="{{ request('category') }}" />
            @endif
            <span class="material-symbols-outlined absolute left-0 top-1/2 -translate-y-1/2 text-on-surface-variant/50">search</span>
            <input
                class="w-full bg-transparent border-0 border-b border-border-subtle pl-8 pb-2 text-body-lg font-body-lg focus:ring-0 focus:border-heritage-burgundy transition-colors placeholder:text-on-surface-variant/50 outline-none"
                placeholder="Search collections (Lacha, Lehenga, Suits...)"
                name="search"
                value="{{ request('search') }}"
                type="text" />
            @if(request('search') || request('category'))
                <a href="{{ route('collections') }}" class="text-xs font-label-caps uppercase text-error hover:underline ml-2 shrink-0">Reset</a>
            @endif
        </form>

        <div class="flex items-center gap-3">
            <span class="font-data-tabular text-xs text-on-surface-variant hidden md:inline">
                Showing <strong>{{ $products->count() }}</strong> items
            </span>
        </div>
    </section>

    <!-- Dynamic Category Navigation Slider -->
    <section class="py-4 md:py-6 overflow-hidden mb-12">
        <div class="flex gap-6 overflow-x-auto hide-scrollbar snap-x snap-mandatory pb-4">
            <!-- All Filter -->
            <a href="{{ route('collections') }}" class="flex flex-col items-center gap-3 shrink-0 group snap-start">
                <div class="w-20 h-20 md:w-24 md:h-24 rounded-full overflow-hidden border-2 {{ !request('category') ? 'border-heritage-burgundy ring-2 ring-heritage-burgundy/20' : 'border-transparent group-hover:border-heritage-burgundy' }} transition-all p-1 bg-surface shadow-xs flex items-center justify-center">
                    <div class="w-full h-full rounded-full bg-cream-silk flex flex-col items-center justify-center text-heritage-burgundy">
                        <span class="material-symbols-outlined text-2xl">auto_awesome</span>
                        <span class="text-[10px] font-label-caps font-bold">ALL</span>
                    </div>
                </div>
                <span class="font-label-caps text-xs uppercase {{ !request('category') ? 'text-heritage-burgundy font-bold' : 'text-charcoal-text' }} transition-colors">All</span>
            </a>

            @foreach($categories as $cat)
                <a href="{{ route('collections', ['category' => $cat->slug]) }}" class="flex flex-col items-center gap-3 shrink-0 group snap-start">
                    <div class="w-20 h-20 md:w-24 md:h-24 rounded-full overflow-hidden border-2 {{ request('category') == $cat->slug ? 'border-heritage-burgundy ring-2 ring-heritage-burgundy/20' : 'border-transparent group-hover:border-heritage-burgundy' }} transition-all p-1 bg-surface shadow-xs">
                        @if($cat->image)
                            <img class="w-full h-full object-cover rounded-full group-hover:scale-105 transition-transform"
                                data-alt="{{ $cat->name }}"
                                src="{{ $cat->image }}" />
                        @else
                            <div class="w-full h-full rounded-full bg-cream-silk flex items-center justify-center text-heritage-burgundy">
                                <span class="material-symbols-outlined text-2xl">category</span>
                            </div>
                        @endif
                    </div>
                    <span class="font-label-caps text-xs uppercase {{ request('category') == $cat->slug ? 'text-heritage-burgundy font-bold' : 'text-charcoal-text group-hover:text-heritage-burgundy' }} transition-colors">
                        {{ $cat->name }}
                    </span>
                </a>
            @endforeach
        </div>
    </section>

    <!-- Trending on YouTube Lookbook (Dynamic from YouTube CMS) -->
    @if(isset($trendingVideo) && $trendingVideo)
        <section class="py-8 md:py-12 mb-16 border-b border-border-subtle pb-16">
            <div class="mb-8 flex items-end justify-between">
                <div>
                    <span class="font-label-caps text-xs text-muted-gold uppercase tracking-widest font-semibold mb-1 block">Curated Video Masterclass</span>
                    <h2 class="font-headline-lg text-heritage-burgundy">
                        Trending on YouTube
                    </h2>
                </div>
                <a class="font-label-caps text-xs uppercase text-muted-gold hover:text-heritage-burgundy transition-colors border-b border-muted-gold pb-1 hidden md:block font-bold"
                    href="{{ $trendingVideo->youtube_url }}" target="_blank">Watch on YouTube &rarr;</a>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
                <!-- Video Block (8 Cols) -->
                <div class="lg:col-span-8 relative group cursor-pointer overflow-hidden rounded-2xl bg-surface-container shadow-[0px_10px_30px_rgba(96,0,24,0.05)]">
                    <a href="{{ $trendingVideo->youtube_url }}" target="_blank" class="block aspect-video w-full relative">
                        <img class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-105"
                            data-alt="{{ $trendingVideo->title }}"
                            src="{{ $trendingVideo->thumbnail ?? 'https://lh3.googleusercontent.com/aida-public/AB6AXuCzQlvbBZ_7sBoCi083plH6_cPAtuOm2s7NPLKZvmSANG7K09ZNrbIPDaRZCkHMnYdK27C5TsIL0IOnTFP5X3MKW2S1Tt2ghmuhABQx-O9imTL5wVx3mYlYDB7UVKvnN9d7TXVBTsAdUJiaERBH9U4EeaGmi_uhOjXssQaxKzlTvliMn39rAXx8AP2RLguYT9l-4m0lZvtDCulkHmZgjl9q6W1CFaItU1CirxuXldi197muluEMndo5' }}" />
                        <div class="absolute inset-0 bg-black/25 group-hover:bg-black/15 transition-colors"></div>
                        <div class="absolute inset-0 flex items-center justify-center">
                            <div class="w-16 h-16 bg-warm-ivory/90 backdrop-blur rounded-full flex items-center justify-center text-heritage-burgundy group-hover:scale-110 transition-transform shadow-lg">
                                <span class="material-symbols-outlined text-4xl ml-1" style="font-variation-settings: 'FILL' 1;">play_arrow</span>
                            </div>
                        </div>
                    </a>
                </div>

                <!-- Featured in Video Sidebar (4 Cols) -->
                <div class="lg:col-span-4 flex flex-col gap-5">
                    <h3 class="font-title-lg text-charcoal-text border-b border-border-subtle pb-2">
                        Featured in this video
                    </h3>

                    @forelse($trendingVideo->products as $p)
                        <a href="{{ route('product.detail', $p->slug) }}"
                            class="flex gap-4 group cursor-pointer p-3 -mx-2 hover:bg-cream-silk/60 rounded-xl transition-colors bg-cream-silk/30 border border-border-subtle/50">
                            <div class="w-24 h-32 shrink-0 overflow-hidden rounded-lg bg-surface shadow-xs">
                                <img class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"
                                    data-alt="{{ $p->name }}"
                                    src="{{ $p->main_image }}" />
                            </div>
                            <div class="flex flex-col justify-center flex-1">
                                <h4 class="font-headline-md text-base text-charcoal-text mb-1 line-clamp-2 group-hover:text-heritage-burgundy transition-colors">
                                    {{ $p->name }}
                                </h4>
                                <div class="flex items-center gap-2">
                                    <p class="font-data-tabular text-heritage-burgundy font-bold">{{ $p->formatted_price }}</p>
                                    @if($p->discount_percentage)
                                        <span class="text-[10px] font-label-caps text-emerald-700 font-bold bg-emerald-50 px-1.5 py-0.2 rounded">{{ $p->discount_percentage }}% OFF</span>
                                    @endif
                                </div>
                                <span class="mt-3 self-start text-xs font-label-caps uppercase text-heritage-burgundy border border-heritage-burgundy rounded-full px-4 py-1 group-hover:bg-heritage-burgundy group-hover:text-warm-ivory transition-colors font-bold">
                                    Shop Now
                                </span>
                            </div>
                        </a>
                    @empty
                        <p class="text-xs font-body-md text-on-surface-variant italic">Explore our latest bridal catalog below.</p>
                    @endforelse
                </div>
            </div>
        </section>
    @endif

    <!-- All Products Listing Grid -->
    <section class="py-6">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-10">
            <div>
                <h2 class="font-headline-lg text-heritage-burgundy">
                    @if($selectedCategory)
                        {{ $selectedCategory->name }}
                    @elseif(request('search'))
                        Search: "{{ request('search') }}"
                    @else
                        Explore Catalog
                    @endif
                </h2>
                @if($selectedCategory && $selectedCategory->description)
                    <p class="font-body-md text-xs text-on-surface-variant mt-1">{{ $selectedCategory->description }}</p>
                @endif
            </div>

            <div class="flex items-center gap-2">
                <span class="font-label-caps text-xs text-on-surface-variant uppercase">Sort:</span>
                <span class="font-body-md text-xs font-semibold text-charcoal-text">Featured &amp; Newest</span>
            </div>
        </div>

        <div class="grid grid-cols-2 lg:grid-cols-4 gap-x-4 gap-y-10 md:gap-x-8 md:gap-y-16">
            @forelse($products as $product)
                <div class="group flex flex-col">
                    <div class="relative p-1 bg-cream-silk/40 rounded-xl overflow-hidden mb-4 border border-border-subtle/50 group-hover:border-heritage-burgundy/30 transition-all shadow-xs group-hover:shadow-md">
                        <div class="aspect-[3/4] overflow-hidden relative rounded-lg bg-surface">
                            <a href="{{ route('product.detail', $product->slug) }}" class="block w-full h-full">
                                <img class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700"
                                    data-alt="{{ $product->name }}"
                                    src="{{ $product->main_image }}" />
                            </a>

                            <!-- Badges -->
                            <div class="absolute top-3 left-3 flex flex-col gap-1 z-10 pointer-events-none">
                                @if($product->discount_percentage)
                                    <span class="bg-heritage-burgundy text-white font-label-caps text-[10px] px-2 py-0.5 rounded-full font-bold shadow-xs">
                                        {{ $product->discount_percentage }}% OFF
                                    </span>
                                @endif
                                @if($product->stock <= 0)
                                    <span class="bg-gray-800 text-white font-label-caps text-[10px] px-2 py-0.5 rounded-full font-bold shadow-xs">
                                        Sold Out
                                    </span>
                                @elseif($product->is_low_stock)
                                    <span class="bg-amber-600 text-white font-label-caps text-[10px] px-2 py-0.5 rounded-full font-bold shadow-xs">
                                        Only {{ $product->stock }} left
                                    </span>
                                @endif
                            </div>

                            <button class="absolute top-3 right-3 w-8 h-8 rounded-full bg-warm-ivory/80 backdrop-blur flex items-center justify-center text-on-surface-variant hover:text-heritage-burgundy transition-colors z-10 cursor-pointer">
                                <span class="material-symbols-outlined text-[20px]">favorite</span>
                            </button>
                        </div>
                    </div>

                    <div class="flex justify-between items-start gap-2 px-1">
                        <a href="{{ route('product.detail', $product->slug) }}" class="flex-1">
                            <span class="text-[10px] font-label-caps text-on-surface-variant uppercase tracking-wider block mb-0.5">{{ $product->category->name ?? 'Collection' }}</span>
                            <h3 class="font-headline-md text-base text-charcoal-text mb-1 group-hover:text-heritage-burgundy transition-colors line-clamp-1">
                                {{ $product->name }}
                            </h3>
                            <div class="flex items-center gap-2">
                                <p class="font-data-tabular text-heritage-burgundy font-bold">{{ $product->formatted_price }}</p>
                                @if($product->formatted_compare_price)
                                    <p class="font-data-tabular text-xs text-on-surface-variant line-through">{{ $product->formatted_compare_price }}</p>
                                @endif
                            </div>
                        </a>
                        <a href="{{ route('cart') }}" class="w-9 h-9 rounded-full bg-surface-container-high flex items-center justify-center text-heritage-burgundy hover:bg-heritage-burgundy hover:text-white transition-colors shrink-0 mt-1" title="Add to Bag">
                            <span class="material-symbols-outlined text-[18px]">add_shopping_cart</span>
                        </a>
                    </div>
                </div>
            @empty
                <div class="col-span-full py-16 text-center">
                    <div class="w-16 h-16 rounded-full bg-cream-silk text-heritage-burgundy flex items-center justify-center mx-auto mb-3">
                        <span class="material-symbols-outlined text-3xl">dry_cleaning</span>
                    </div>
                    <h3 class="font-headline-md text-lg text-charcoal-text">No Products Found</h3>
                    <p class="font-body-md text-xs text-on-surface-variant mt-1">Try clearing your search query or selecting another category.</p>
                    <a href="{{ route('collections') }}" class="inline-block mt-4 text-xs font-label-caps text-heritage-burgundy border border-heritage-burgundy px-5 py-2 rounded-full uppercase font-bold hover:bg-heritage-burgundy hover:text-white transition-colors">
                        View All Collections
                    </a>
                </div>
            @endforelse
        </div>
    </section>
</x-layouts.app>
