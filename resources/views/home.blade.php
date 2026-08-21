<x-layouts.app title="Sonakshi Fashion Hub - Royal Banarasi & Festive Couture">
    <!-- Dynamic Hero Video Section -->
    <x-hero-section :video="$heroVideo ?? null" />

    <!-- Dynamic Video Lookbooks Grid -->
    <x-video-grid :videos="$lookbookVideos ?? collect()" />

    <!-- Customer Reviews & Testimonials -->
    <x-testimonials-section :reviews="$testimonials ?? collect()" :review-count="$reviewCount ?? 0" :average-rating="$averageRating ?? null" />
</x-layouts.app>
