<x-layouts.admin title="YouTube CMS & Video Shopping - Sonakshi Admin" active="youtube">
    <div class="space-y-6 max-w-[1400px]">
        <!-- Top Bar -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <h1 class="font-headline-lg text-heritage-burgundy">YouTube CMS &amp; Video Shopping</h1>
                <p class="font-body-md text-xs text-on-surface-variant mt-1">Manage video lookbooks, masterclasses, and tag products featured inside each video stream.</p>
            </div>
            <a href="{{ route('admin.youtube.create') }}"
                class="bg-heritage-burgundy text-white font-label-caps text-xs uppercase tracking-wider px-5 py-3 rounded-xl hover:bg-primary-container transition-all flex items-center gap-2 shadow-xs shrink-0 self-start sm:self-auto font-bold">
                <span class="material-symbols-outlined text-base">video_call</span>
                Add New Video Lookbook
            </a>
        </div>

        @if(session('success'))
            <div class="p-4 bg-green-50 border border-green-200 text-green-800 text-xs rounded-xl flex items-center gap-2">
                <span class="material-symbols-outlined text-base">check_circle</span>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        <!-- Video Cards Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($videos as $video)
                <div class="bg-surface-container-lowest rounded-2xl border border-border-subtle shadow-xs overflow-hidden flex flex-col justify-between group hover:border-heritage-burgundy/40 transition-all">
                    <div>
                        <!-- Thumbnail Box with Duration Badge -->
                        <div class="aspect-video w-full relative bg-surface-container overflow-hidden">
                            @if($video->thumbnail)
                                <img src="{{ $video->thumbnail }}" alt="{{ $video->title }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" />
                            @else
                                <div class="w-full h-full flex items-center justify-center bg-black/20 text-heritage-burgundy">
                                    <span class="material-symbols-outlined text-4xl">smart_display</span>
                                </div>
                            @endif

                            <div class="absolute inset-0 bg-black/20 group-hover:bg-black/10 transition-colors"></div>

                            @if($video->duration)
                                <div class="absolute bottom-2.5 right-2.5 bg-black/80 text-white font-data-tabular text-[11px] px-2 py-0.5 rounded backdrop-blur font-semibold">
                                    {{ $video->duration }}
                                </div>
                            @endif

                            <!-- Placement Badges -->
                            <div class="absolute top-2.5 left-2.5 flex flex-col gap-1">
                                @if($video->is_hero)
                                    <span class="bg-heritage-burgundy text-white font-label-caps text-[9px] px-2 py-0.5 rounded-full font-bold shadow-xs">
                                        Home Hero
                                    </span>
                                @endif
                                @if($video->is_trending)
                                    <span class="bg-muted-gold text-charcoal-text font-label-caps text-[9px] px-2 py-0.5 rounded-full font-bold shadow-xs">
                                        Collections Trending
                                    </span>
                                @endif
                                @if($video->is_lookbook)
                                    <span class="bg-white/90 text-charcoal-text font-label-caps text-[9px] px-2 py-0.5 rounded-full font-bold shadow-xs backdrop-blur">
                                        Lookbook Grid
                                    </span>
                                @endif
                            </div>
                        </div>

                        <!-- Video Content Info -->
                        <div class="p-5 space-y-3">
                            <div class="flex justify-between items-start gap-2">
                                <h3 class="font-title-lg text-base font-semibold text-charcoal-text line-clamp-2 group-hover:text-heritage-burgundy transition-colors">
                                    {{ $video->title }}
                                </h3>
                            </div>

                            @if($video->views_text)
                                <p class="font-data-tabular text-xs text-on-surface-variant flex items-center gap-1">
                                    <span class="material-symbols-outlined text-sm">visibility</span>
                                    {{ $video->views_text }}
                                </p>
                            @endif

                            <!-- Tagged Products Chips -->
                            <div class="pt-2 border-t border-border-subtle">
                                <span class="font-label-caps text-[10px] uppercase text-on-surface-variant font-bold block mb-1.5">
                                    Tagged Products ({{ $video->products->count() }})
                                </span>
                                <div class="flex flex-wrap gap-1.5">
                                    @forelse($video->products as $p)
                                        <span class="inline-flex items-center gap-1 px-2 py-0.5 bg-cream-silk border border-muted-gold/30 rounded text-[11px] font-body-md text-charcoal-text">
                                            <span class="w-1.5 h-1.5 rounded-full bg-heritage-burgundy"></span>
                                            <span class="truncate max-w-[140px]">{{ $p->name }}</span>
                                        </span>
                                    @empty
                                        <span class="text-[11px] text-on-surface-variant italic">No products tagged</span>
                                    @endforelse
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Footer Actions -->
                    <div class="p-4 bg-warm-ivory/50 border-t border-border-subtle flex justify-between items-center text-xs">
                        <a href="{{ $video->youtube_url }}" target="_blank" class="text-heritage-burgundy hover:underline font-semibold flex items-center gap-1 font-label-caps">
                            <span class="material-symbols-outlined text-base">open_in_new</span>
                            Watch
                        </a>

                        <div class="flex items-center gap-2">
                            <a href="{{ route('admin.youtube.edit', $video) }}" class="p-1.5 text-on-surface-variant hover:text-heritage-burgundy hover:bg-surface-container rounded-lg transition-colors" title="Edit">
                                <span class="material-symbols-outlined text-lg">edit</span>
                            </a>
                            <form action="{{ route('admin.youtube.destroy', $video) }}" method="POST" onsubmit="return confirm('Delete video lookbook {{ $video->title }}?');" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="p-1.5 text-on-surface-variant hover:text-error hover:bg-surface-container rounded-lg transition-colors cursor-pointer" title="Delete">
                                    <span class="material-symbols-outlined text-lg">delete</span>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-full py-16 text-center bg-surface-container-lowest rounded-2xl border border-border-subtle">
                    <div class="w-16 h-16 rounded-full bg-cream-silk text-heritage-burgundy flex items-center justify-center mx-auto mb-3">
                        <span class="material-symbols-outlined text-3xl">smart_display</span>
                    </div>
                    <h3 class="font-headline-md text-lg text-charcoal-text">No YouTube Lookbooks Created</h3>
                    <p class="font-body-md text-xs text-on-surface-variant mt-1">Start by adding your styling masterclasses and linking them to catalog products.</p>
                    <a href="{{ route('admin.youtube.create') }}" class="inline-block mt-4 text-xs font-label-caps text-white bg-heritage-burgundy px-6 py-2.5 rounded-full uppercase font-bold hover:bg-primary-container transition-all">
                        Add First Video Lookbook
                    </a>
                </div>
            @endforelse
        </div>
    </div>
</x-layouts.admin>
