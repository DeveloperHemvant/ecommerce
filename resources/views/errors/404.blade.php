<x-layouts.app title="Page Not Found - Sonakshi Fashion Hub">
    <div class="flex flex-col items-center justify-center text-center py-20 md:py-32 gap-6">
        <span class="material-symbols-outlined text-heritage-burgundy" style="font-size: 4rem;">search_off</span>
        <h1 class="font-headline-lg text-on-surface">Page Not Found</h1>
        <p class="text-on-surface-variant max-w-md">
            We couldn't find the page you were looking for. It may have been moved, or the link may be outdated.
        </p>
        <a href="{{ route('collections') }}"
            class="inline-flex items-center gap-2 mt-4 px-8 py-3 rounded-full bg-heritage-burgundy text-white font-medium hover:bg-heritage-burgundy/90 transition-colors">
            Continue Shopping
        </a>
    </div>
</x-layouts.app>
