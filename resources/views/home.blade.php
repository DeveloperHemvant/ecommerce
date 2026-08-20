<x-layouts.app title="Sonakshi Fashion Hub - Royal Banarasi & Festive Couture">
    <!-- Dynamic Hero Video Section -->
    <x-hero-section :video="$heroVideo ?? null" />

    <!-- Dynamic Video Lookbooks Grid -->
    <x-video-grid :videos="$lookbookVideos ?? collect()" />
</x-layouts.app>
