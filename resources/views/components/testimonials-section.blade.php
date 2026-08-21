@props([
    'reviews' => collect(),
    'reviewCount' => 0,
    'averageRating' => null,
])

@if($reviews->isNotEmpty())
    <section class="mb-16 md:mb-24" id="reviews">
        <div class="flex flex-col md:flex-row md:items-end justify-between gap-4 mb-8">
            <div>
                <span class="font-label-caps text-xs text-muted-gold uppercase tracking-widest font-bold block mb-1">Verified Customer Reviews</span>
                <h2 class="font-headline-lg text-2xl md:text-3xl text-heritage-burgundy font-serif">What Our Brides Are Saying</h2>
            </div>

            @if($averageRating)
                <div class="flex items-center gap-3 bg-surface-container-lowest border border-border-subtle rounded-2xl px-5 py-3 shadow-xs self-start md:self-auto">
                    <div class="flex text-amber-500">
                        @for($s = 1; $s <= 5; $s++)
                            <span class="material-symbols-outlined text-lg" style="font-variation-settings: 'FILL' 1;">
                                {{ $s <= round($averageRating) ? 'star' : 'star_border' }}
                            </span>
                        @endfor
                    </div>
                    <div class="text-xs leading-tight">
                        <span class="block font-bold text-charcoal-text font-data-tabular">{{ $averageRating }} / 5</span>
                        <span class="block text-on-surface-variant">from {{ $reviewCount }} {{ Str::plural('review', $reviewCount) }}</span>
                    </div>
                </div>
            @endif
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($reviews as $rev)
                <div class="bg-surface-container-lowest p-6 rounded-2xl border border-border-subtle shadow-xs space-y-3 flex flex-col">
                    <div class="flex justify-between items-start">
                        <div class="flex text-amber-500">
                            @for($s = 1; $s <= 5; $s++)
                                <span class="material-symbols-outlined text-sm {{ $s <= $rev->rating ? 'text-amber-500' : 'text-gray-300' }}" style="font-variation-settings: 'FILL' 1;">star</span>
                            @endfor
                        </div>
                        <span class="text-[10px] text-on-surface-variant font-data-tabular">{{ $rev->created_at->format('M Y') }}</span>
                    </div>

                    @if($rev->title)
                        <h4 class="font-bold text-charcoal-text text-sm">{{ $rev->title }}</h4>
                    @endif

                    <p class="text-xs text-charcoal-text leading-relaxed flex-1">{{ Str::limit($rev->comment, 160) }}</p>

                    @if($rev->product)
                        <a href="{{ route('product.detail', $rev->product->slug) }}" class="flex items-center gap-2.5 pt-3 border-t border-border-subtle/50 group">
                            <span class="w-9 h-11 rounded-lg overflow-hidden bg-surface shrink-0 border border-border-subtle">
                                <img src="{{ $rev->product->main_image }}" alt="{{ $rev->product->name }}" class="w-full h-full object-cover" />
                            </span>
                            <span class="min-w-0">
                                <span class="block text-[11px] text-on-surface-variant">Reviewed:</span>
                                <span class="block text-xs font-semibold text-charcoal-text truncate group-hover:text-heritage-burgundy transition-colors">{{ $rev->product->name }}</span>
                            </span>
                        </a>
                    @endif

                    <div class="flex items-center gap-2 pt-2 {{ $rev->product ? '' : 'border-t border-border-subtle/50' }} text-[11px] text-on-surface-variant">
                        <span class="font-bold text-charcoal-text">{{ $rev->customer_name }}</span>
                        @if($rev->is_verified_buyer)
                            <span class="text-emerald-800 font-semibold inline-flex items-center gap-0.5">
                                <span class="material-symbols-outlined text-xs">verified</span>
                                Verified Buyer
                            </span>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    </section>
@endif
