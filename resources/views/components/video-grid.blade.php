@props(['videos' => collect()])

<section class="mb-20">
    <div class="flex justify-between items-end mb-8">
        <div>
            <h2 class="font-headline-lg text-heritage-burgundy">
                Video Lookbooks
            </h2>
            <p class="font-body-md text-xs text-on-surface-variant mt-1">
                Explore masterclasses and runway drapes &bull; Click to shop each look
            </p>
        </div>
        <a class="font-label-caps text-xs text-muted-gold hover:text-heritage-burgundy transition-colors uppercase tracking-wider hidden md:block font-bold border-b border-muted-gold pb-0.5"
            href="https://youtube.com" target="_blank">
            View YouTube Channel &rarr;
        </a>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 md:gap-8">
        @forelse($videos as $video)
            <x-video-card :video="$video" />
        @empty
            <div class="col-span-full py-8 text-center text-on-surface-variant">
                No video lookbooks published yet.
            </div>
        @endforelse
    </div>
</section>
