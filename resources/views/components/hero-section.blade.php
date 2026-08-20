@props(['video' => null])

<section class="mb-16 md:mb-24 flex flex-col md:flex-row items-center gap-8 md:gap-16">
    <div class="w-full md:w-1/2 flex flex-col gap-6">
        <h1 class="font-display-lg text-heritage-burgundy">
            Watch. Discover. Shop.
        </h1>
        <p class="font-body-lg text-on-surface-variant max-w-lg">
            Experience our latest collections through immersive video content. See the drape, feel the texture,
            and shop your favorite looks directly from our runway to your wardrobe.
        </p>
        <div class="flex items-center gap-4 mt-4">
            <a href="{{ route('collections') }}"
                class="bg-heritage-burgundy text-white px-8 py-4 rounded-full font-label-caps tracking-widest hover:bg-primary-container transition-all shadow-[0px_4px_14px_rgba(96,0,24,0.2)] hover:shadow-lg cursor-pointer inline-flex items-center gap-2">
                <span>EXPLORE COLLECTIONS</span>
                <span class="material-symbols-outlined text-sm">arrow_forward</span>
            </a>
        </div>
    </div>

    <div class="w-full md:w-1/2 relative rounded-2xl overflow-hidden aspect-video shadow-[0px_10px_30px_rgba(96,0,24,0.1)] group bg-surface-container">
        <!-- Featured Video Thumbnail -->
        @php
            $thumb = $video?->thumbnail ?? 'https://lh3.googleusercontent.com/aida-public/AB6AXuC1Hu3OfkwTktP6wQ0aNFYMc_6CD5juQzGEHa19Mn2yMQwcsi9peml6mcXFTHW8UBgiOf3vLe1cIUd7wQWpGTV5yLm_VsmXCc7rZDx8V2U8amfe2V8FxTbV7i2eO-1GHS2W2bTdbRqGcLQEDLkjmjuE1NuFtqyqq6OlHqe9FdYBGZW5Ill49JfOAW5SntA2DBupYL8yHndNE6vhaQgT9jh-r7VIRCwpOHE5T-QGGns3ANuhRJeT0yu8';
            $firstProduct = $video?->products?->first();
        @endphp
        <div class="bg-cover bg-center w-full h-full absolute inset-0 transition-transform duration-700 group-hover:scale-105"
            data-alt="{{ $video->title ?? 'Featured Video' }}"
            style="background-image: url('{{ $thumb }}')">
        </div>
        <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/30 to-transparent flex flex-col justify-end p-6 md:p-8">
            <span class="font-label-caps text-xs text-muted-gold uppercase tracking-widest font-semibold mb-1">Featured Masterclass</span>
            <h2 class="font-headline-lg text-white mb-2 line-clamp-2">
                {{ $video->title ?? 'The Royal Heirloom Collection' }}
            </h2>
            <p class="font-body-md text-white/80 mb-4 flex items-center gap-2 text-xs">
                <span class="material-symbols-outlined text-[16px]">visibility</span> {{ $video->views_text ?? '124K Views' }}
                @if($video?->duration)
                    &bull; <span>{{ $video->duration }}</span>
                @endif
            </p>
            @if($firstProduct)
                <a href="{{ route('product.detail', $firstProduct->slug) }}"
                    class="self-start border border-white text-white px-6 py-2 rounded-full font-label-caps tracking-widest hover:bg-white hover:text-heritage-burgundy transition-all backdrop-blur-sm bg-white/10 cursor-pointer text-xs">
                    SHOP FEATURED LOOK ({{ $firstProduct->name }})
                </a>
            @else
                <a href="{{ route('collections') }}"
                    class="self-start border border-white text-white px-6 py-2 rounded-full font-label-caps tracking-widest hover:bg-white hover:text-heritage-burgundy transition-all backdrop-blur-sm bg-white/10 cursor-pointer text-xs">
                    SHOP FEATURED LOOKS
                </a>
            @endif
        </div>
        <a href="{{ $video->youtube_url ?? 'https://youtube.com' }}" target="_blank"
            class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 bg-white/25 backdrop-blur-md p-4 rounded-full flex items-center justify-center cursor-pointer hover:bg-white/50 transition-transform hover:scale-110 shadow-lg text-white">
            <span class="material-symbols-outlined text-4xl" data-weight="fill"
                style="font-variation-settings: 'FILL' 1;">play_arrow</span>
        </a>
    </div>
</section>
