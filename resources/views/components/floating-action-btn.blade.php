@props([
    'url' => 'https://youtube.com',
    'label' => 'YouTube Lookbook',
])

<a class="fixed bottom-20 md:bottom-8 right-4 md:right-8 bg-[#E60000] text-white px-4 py-2.5 sm:px-5 sm:py-3 rounded-full flex items-center gap-2 shadow-[0px_8px_18px_rgba(230,0,0,0.3)] hover:-translate-y-0.5 hover:shadow-[0px_12px_22px_rgba(230,0,0,0.4)] transition-all z-40 group cursor-pointer"
    href="{{ $url }}"
    target="_blank"
    rel="noopener noreferrer"
    title="Watch Sonakshi Lookbooks on YouTube">
    <span class="material-symbols-outlined text-[20px] group-hover:scale-110 transition-transform">smart_display</span>
    <span class="font-label-caps tracking-wider font-semibold text-xs hidden sm:inline">{{ $label }}</span>
</a>
