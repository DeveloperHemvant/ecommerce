@props([
    'url' => 'https://youtube.com',
    'label' => 'WATCH ON YOUTUBE',
])

<a class="fixed bottom-24 md:bottom-8 right-4 md:right-8 bg-[#FF0000] text-white px-6 py-3.5 rounded-full flex items-center gap-3 shadow-[0px_10px_20px_rgba(255,0,0,0.25)] hover:-translate-y-1 hover:shadow-[0px_14px_24px_rgba(255,0,0,0.35)] transition-all z-40 group cursor-pointer"
    href="{{ $url }}"
    target="_blank"
    rel="noopener noreferrer">
    <span class="material-symbols-outlined text-[24px] group-hover:scale-110 transition-transform">smart_display</span>
    <span class="font-label-caps tracking-wider font-semibold text-xs">{{ $label }}</span>
</a>
