@props([
    'video' => null,
    'title' => '',
    'duration' => '10:00',
    'views' => '50K',
    'image' => '',
    'alt' => '',
    'url' => '#',
])

@php
    $cardTitle = $video ? $video->title : $title;
    $cardDuration = $video ? $video->duration : $duration;
    $cardViews = $video ? $video->views_text : $views;
    $cardImage = $video ? ($video->thumbnail ?? 'https://lh3.googleusercontent.com/aida-public/AB6AXuCzQlvbBZ_7sBoCi083plH6_cPAtuOm2s7NPLKZvmSANG7K09ZNrbIPDaRZCkHMnYdK27C5TsIL0IOnTFP5X3MKW2S1Tt2ghmuhABQx-O9imTL5wVx3mYlYDB7UVKvnN9d7TXVBTsAdUJiaERBH9U4EeaGmi_uhOjXssQaxKzlTvliMn39rAXx8AP2RLguYT9l-4m0lZvtDCulkHmZgjl9q6W1CFaItU1CirxuXldi197muluEMndo5') : $image;
    $cardUrl = $video ? $video->youtube_url : $url;
    $firstProduct = $video?->products?->first();
@endphp

<div class="group flex flex-col gap-4">
    <a href="{{ $cardUrl }}" target="_blank" class="relative aspect-video rounded-xl overflow-hidden bg-surface-container-high shadow-sm block cursor-pointer">
        <div class="bg-cover bg-center w-full h-full transform group-hover:scale-105 transition-transform duration-500"
            data-alt="{{ $cardTitle }}"
            style="background-image: url('{{ $cardImage }}')">
        </div>
        @if($cardDuration)
            <div class="absolute bottom-2.5 right-2.5 bg-black/80 text-white font-data-tabular text-[11px] px-2 py-0.5 rounded backdrop-blur-sm font-semibold">
                {{ $cardDuration }}
            </div>
        @endif
        <div class="absolute inset-0 bg-black/25 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
            <span class="material-symbols-outlined text-white text-5xl transform group-hover:scale-110 transition-transform" data-weight="fill"
                style="font-variation-settings: 'FILL' 1;">play_circle</span>
        </div>
    </a>
    
    <div class="flex flex-col gap-1.5 px-1 flex-grow justify-between">
        <div>
            <h4 class="font-title-lg text-heritage-burgundy group-hover:text-muted-gold transition-colors line-clamp-2">
                <a href="{{ $cardUrl }}" target="_blank">{{ $cardTitle }}</a>
            </h4>
            <p class="font-body-md text-xs text-on-surface-variant flex items-center gap-1.5 mt-1">
                <span class="material-symbols-outlined text-sm">visibility</span>
                {{ $cardViews }}
            </p>
        </div>

        <div class="mt-3">
            @if($firstProduct)
                <a href="{{ route('product.detail', $firstProduct->slug) }}"
                    class="bg-cream-silk border border-muted-gold/40 text-heritage-burgundy px-4 py-2.5 rounded-xl font-label-caps text-xs tracking-wider hover:bg-heritage-burgundy hover:text-white transition-colors w-full text-center shadow-xs block font-bold">
                    SHOP THIS LOOK ({{ $firstProduct->formatted_price }})
                </a>
            @else
                <a href="{{ route('collections') }}"
                    class="bg-cream-silk border border-border-subtle text-heritage-burgundy px-4 py-2.5 rounded-xl font-label-caps text-xs tracking-wider hover:bg-heritage-burgundy hover:text-white transition-colors w-full text-center shadow-xs block font-bold">
                    SHOP LOOKBOOK
                </a>
            @endif
        </div>
    </div>
</div>
