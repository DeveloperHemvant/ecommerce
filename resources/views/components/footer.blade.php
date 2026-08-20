<footer
    class="w-full pt-12 pb-24 md:pb-8 px-margin-mobile md:px-margin-desktop grid grid-cols-1 md:grid-cols-4 gap-8 bg-warm-ivory border-t border-border-subtle mt-16 max-w-[1440px] mx-auto">
    <div class="col-span-1 md:col-span-1 flex flex-col gap-4">
        <a href="{{ route('home') }}" class="font-headline-md text-2xl md:text-headline-md font-semibold text-heritage-burgundy">
            Sonakshi
        </a>
        <p class="font-body-md text-xs text-on-surface-variant max-w-sm leading-relaxed">
            Bridging traditional Indian craftsmanship with modern editorial sensibilities. Discover handwoven Banarasi and bespoke bridal heritage re-imagined.
        </p>
    </div>
    
    <div class="flex flex-col gap-2.5 text-xs font-body-md">
        <h5 class="font-label-caps text-heritage-burgundy tracking-widest mb-1 font-bold">COMPANY</h5>
        <a class="text-on-surface-variant hover:text-heritage-burgundy transition-colors"
            href="{{ route('about') }}">About Us</a>
        <a class="text-on-surface-variant hover:text-heritage-burgundy transition-colors"
            href="{{ route('contact') }}">Contact Us &amp; Concierge</a>
        <a class="text-on-surface-variant hover:text-heritage-burgundy transition-colors"
            href="{{ route('collections') }}">Explore Collections</a>
    </div>

    <div class="flex flex-col gap-2.5 text-xs font-body-md">
        <h5 class="font-label-caps text-heritage-burgundy tracking-widest mb-1 font-bold">CUSTOMER CARE</h5>
        <a class="text-on-surface-variant hover:text-heritage-burgundy transition-colors"
            href="{{ route('track.order') }}">Live Order Tracking</a>
        <a class="text-on-surface-variant hover:text-heritage-burgundy transition-colors"
            href="{{ route('shipping.policy') }}">Shipping &amp; Dispatch</a>
        <a class="text-on-surface-variant hover:text-heritage-burgundy transition-colors"
            href="{{ route('return.policy') }}">Returns &amp; Alterations</a>
    </div>
    
    <div class="flex flex-col gap-2.5 text-xs font-body-md">
        <h5 class="font-label-caps text-heritage-burgundy tracking-widest mb-1 font-bold">LEGAL</h5>
        <a class="text-on-surface-variant hover:text-heritage-burgundy transition-colors"
            href="{{ route('terms') }}">Terms of Service</a>
        <a class="text-on-surface-variant hover:text-heritage-burgundy transition-colors"
            href="{{ route('privacy.policy') }}">Privacy Policy</a>
    </div>
    
    <div class="col-span-1 md:col-span-4 mt-6 pt-6 border-t border-border-subtle flex flex-col sm:flex-row items-center justify-between gap-4 text-xs">
        <p class="font-body-md text-on-surface-variant/70 text-center sm:text-left">
            &copy; {{ date('Y') }} Sonakshi Fashion Hub. Handcrafted with Royal Heritage in India.
        </p>
        <div class="flex items-center gap-4 text-on-surface-variant/70 text-[11px] font-label-caps">
            <span>256-Bit SSL Encrypted</span>
            <span>&bull;</span>
            <span>100% Certified Handloom</span>
        </div>
    </div>
</footer>
