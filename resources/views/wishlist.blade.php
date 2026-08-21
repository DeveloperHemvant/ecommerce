<x-layouts.app title="My Wishlist - Sonakshi Fashion Hub">
    <div class="max-w-6xl mx-auto py-6 md:py-10 space-y-8">
        <!-- Header -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 border-b border-border-subtle pb-6">
            <div>
                <span class="font-label-caps text-xs text-muted-gold uppercase tracking-widest font-bold">Curated Treasures</span>
                <h1 class="font-headline-md text-2xl md:text-3xl text-heritage-burgundy">My Saved Wishlist</h1>
                <p class="font-body-md text-xs text-on-surface-variant mt-1">Saved bridal ensembles and festive silhouettes for your upcoming celebrations.</p>
            </div>
            <div class="flex items-center gap-2">
                <a href="{{ route('collections') }}" class="px-4 py-2 bg-surface-container text-charcoal-text rounded-xl border border-border-subtle text-xs font-label-caps uppercase font-bold hover:bg-surface-container-high transition-colors">
                    Continue Browsing
                </a>
            </div>
        </div>

        @if(session('success'))
            <div class="p-4 bg-green-50 border border-green-200 text-green-800 text-xs rounded-xl flex items-center gap-2">
                <span class="material-symbols-outlined text-base">check_circle</span>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        @if($products->total() > 0)
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                @foreach($products as $product)
                    <div class="bg-surface-container-lowest rounded-2xl border border-border-subtle overflow-hidden shadow-xs flex flex-col justify-between group hover:border-heritage-burgundy/40 transition-all">
                        <!-- Image & Badges -->
                        <div class="relative aspect-[3/4] bg-surface overflow-hidden">
                            <img src="{{ $product->main_image }}" alt="{{ $product->name }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" />
                            
                            <!-- Remove Form -->
                            <form action="{{ route('wishlist.toggle') }}" method="POST" class="absolute top-3 right-3 z-10">
                                @csrf
                                <input type="hidden" name="product_id" value="{{ $product->id }}" />
                                <button type="submit" class="w-8 h-8 rounded-full bg-white/90 backdrop-blur-md text-error flex items-center justify-center shadow-sm hover:scale-110 transition-transform cursor-pointer" title="Remove from Wishlist">
                                    <span class="material-symbols-outlined text-base">favorite</span>
                                </button>
                            </form>

                            @if($product->discount_percentage)
                                <span class="absolute top-3 left-3 bg-heritage-burgundy text-white text-[10px] font-label-caps px-2 py-0.5 rounded-full font-bold uppercase">
                                    {{ $product->discount_percentage }}% OFF
                                </span>
                            @endif
                        </div>

                        <!-- Card Info -->
                        <div class="p-5 flex-1 flex flex-col justify-between space-y-4">
                            <div>
                                <span class="text-[10px] font-label-caps uppercase text-on-surface-variant">{{ $product->category->name ?? 'Couture' }}</span>
                                <h3 class="font-title-lg text-sm font-semibold text-charcoal-text line-clamp-1 mt-0.5">
                                    <a href="{{ route('product.detail', $product->slug) }}" class="hover:text-heritage-burgundy transition-colors">
                                        {{ $product->name }}
                                    </a>
                                </h3>
                                <div class="flex items-center gap-2 mt-1.5">
                                    <span class="font-data-tabular font-bold text-sm text-heritage-burgundy">{{ $product->formatted_price }}</span>
                                    @if($product->formatted_compare_price)
                                        <span class="font-data-tabular text-xs text-on-surface-variant line-through">{{ $product->formatted_compare_price }}</span>
                                    @endif
                                </div>
                            </div>

                            <!-- Move to Cart Form -->
                            <form action="{{ route('wishlist.move-to-cart', $product->id) }}" method="POST">
                                @csrf
                                <button type="submit" class="w-full py-2.5 bg-heritage-burgundy text-white font-label-caps text-xs uppercase tracking-wider rounded-xl font-bold hover:bg-primary-container transition-all flex items-center justify-center gap-1.5 cursor-pointer shadow-xs">
                                    <span class="material-symbols-outlined text-sm">shopping_bag</span>
                                    <span>Move to Bag</span>
                                </button>
                            </form>
                        </div>
                    </div>
                @endforeach
            </div>

            @if($products->hasPages())
                <div class="pt-4">
                    {{ $products->links() }}
                </div>
            @endif
        @else
            <div class="text-center py-20 bg-surface-container-lowest rounded-2xl border border-border-subtle p-8">
                <div class="w-16 h-16 rounded-full bg-cream-silk text-heritage-burgundy flex items-center justify-center mx-auto mb-3">
                    <span class="material-symbols-outlined text-3xl">favorite_border</span>
                </div>
                <h3 class="font-headline-md text-lg text-charcoal-text">Your Wishlist is Empty</h3>
                <p class="font-body-md text-xs text-on-surface-variant mt-1">Tap the heart icon on any outfit while browsing our collections to save it here.</p>
                <a href="{{ route('collections') }}" class="inline-block mt-4 text-xs font-label-caps text-white bg-heritage-burgundy px-6 py-3 rounded-xl uppercase font-bold hover:bg-primary-container transition-all">
                    Explore Collections
                </a>
            </div>
        @endif
    </div>
</x-layouts.app>
