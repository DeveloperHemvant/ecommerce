<x-layouts.app title="Privacy Policy - Sonakshi Fashion Hub">
    <div class="max-w-6xl mx-auto space-y-10">
        <!-- Header -->
        <div class="text-center space-y-2 pt-4">
            <span class="font-label-caps text-xs text-muted-gold uppercase tracking-widest font-bold block">Security &amp; Data Protection</span>
            <h1 class="font-headline-lg text-3xl sm:text-4xl text-heritage-burgundy font-serif">
                Privacy Policy
            </h1>
            <p class="font-body-md text-xs sm:text-sm text-on-surface-variant max-w-xl mx-auto">
                How we protect and manage your personal and order data at Sonakshi Fashion Hub.
            </p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
            <!-- Sticky Sidebar Nav -->
            <aside class="lg:col-span-4 lg:sticky lg:top-28 space-y-6">
                <nav class="bg-surface-container-lowest rounded-2xl border border-border-subtle p-6 shadow-xs space-y-3">
                    <h3 class="font-label-caps text-[11px] uppercase tracking-widest text-muted-gold font-bold">On This Page</h3>
                    <ul class="space-y-1 text-xs font-body-md">
                        <li><a href="#information-we-collect" class="flex items-center justify-between py-2 px-3 rounded-lg text-on-surface-variant hover:text-heritage-burgundy hover:bg-warm-ivory/60 transition-colors"><span>1. Information We Collect</span><span class="material-symbols-outlined text-sm">arrow_forward</span></a></li>
                        <li><a href="#how-used" class="flex items-center justify-between py-2 px-3 rounded-lg text-on-surface-variant hover:text-heritage-burgundy hover:bg-warm-ivory/60 transition-colors"><span>2. How It's Used</span><span class="material-symbols-outlined text-sm">arrow_forward</span></a></li>
                        <li><a href="#payment-security" class="flex items-center justify-between py-2 px-3 rounded-lg text-on-surface-variant hover:text-heritage-burgundy hover:bg-warm-ivory/60 transition-colors"><span>3. Payment Data Security</span><span class="material-symbols-outlined text-sm">arrow_forward</span></a></li>
                        <li><a href="#cookies" class="flex items-center justify-between py-2 px-3 rounded-lg text-on-surface-variant hover:text-heritage-burgundy hover:bg-warm-ivory/60 transition-colors"><span>4. Cookies &amp; PWA Storage</span><span class="material-symbols-outlined text-sm">arrow_forward</span></a></li>
                    </ul>
                </nav>

                <div class="bg-cream-silk rounded-2xl border border-muted-gold/40 p-6 shadow-xs space-y-3">
                    <div class="w-10 h-10 rounded-full bg-warm-ivory text-heritage-burgundy flex items-center justify-center border border-muted-gold/40">
                        <span class="material-symbols-outlined text-lg">support_agent</span>
                    </div>
                    <h3 class="font-headline-md text-sm text-heritage-burgundy font-serif">Questions About Your Data?</h3>
                    <p class="text-xs text-on-surface-variant leading-relaxed">Our concierge team can walk you through exactly how your information is stored and used.</p>
                    <a href="{{ route('contact') }}" class="inline-flex items-center gap-1.5 text-xs font-label-caps uppercase font-bold text-heritage-burgundy hover:underline">
                        <span>Contact Concierge</span>
                        <span class="material-symbols-outlined text-sm">arrow_forward</span>
                    </a>
                </div>
            </aside>

            <!-- Policy Content -->
            <div class="lg:col-span-8 bg-surface-container-lowest p-8 sm:p-12 rounded-3xl border border-border-subtle shadow-xs space-y-8 font-body-md text-xs sm:text-sm text-charcoal-text leading-relaxed">
                <section id="information-we-collect" class="space-y-3 scroll-mt-28">
                    <h2 class="font-headline-md text-lg text-heritage-burgundy font-serif font-semibold">1. Information We Collect</h2>
                    <p class="text-on-surface-variant">
                        We collect personal details necessary to fulfill your orders, including your name, email address, contact phone number, shipping address, and bespoke tailoring measurements.
                    </p>
                </section>

                <section id="how-used" class="space-y-3 border-t border-border-subtle pt-6 scroll-mt-28">
                    <h2 class="font-headline-md text-lg text-heritage-burgundy font-serif font-semibold">2. How Your Information is Used</h2>
                    <p class="text-on-surface-variant">
                        Your details are used strictly for order fulfillment, courier delivery updates, customer support communication, and bespoke bridal tailoring. We never sell or lease your personal information to third-party marketing companies.
                    </p>
                </section>

                <section id="payment-security" class="space-y-3 border-t border-border-subtle pt-6 scroll-mt-28">
                    <h2 class="font-headline-md text-lg text-heritage-burgundy font-serif font-semibold">3. Payment Data Security</h2>
                    <p class="text-on-surface-variant">
                        All payment transactions are encrypted using 256-bit SSL technology. Card data and UPI credentials are processed directly through certified PCI-DSS compliant payment gateways.
                    </p>
                </section>

                <section id="cookies" class="space-y-3 border-t border-border-subtle pt-6 scroll-mt-28">
                    <h2 class="font-headline-md text-lg text-heritage-burgundy font-serif font-semibold">4. Cookies &amp; PWA Offline Storage</h2>
                    <p class="text-on-surface-variant">
                        We use cookies and local caching to retain your shopping bag contents, wishlist selections, and PWA offline state. You may disable cookies through your browser settings at any time.
                    </p>
                </section>
            </div>
        </div>
    </div>
</x-layouts.app>
