<x-layouts.app title="Shipping & Delivery Policy - Sonakshi Fashion Hub">
    <div class="max-w-6xl mx-auto space-y-10">
        <!-- Header -->
        <div class="text-center space-y-2 pt-4">
            <span class="font-label-caps text-xs text-muted-gold uppercase tracking-widest font-bold block">Logistics &amp; Delivery</span>
            <h1 class="font-headline-lg text-3xl sm:text-4xl text-heritage-burgundy font-serif">
                Shipping &amp; Delivery Policy
            </h1>
            <p class="font-body-md text-xs sm:text-sm text-on-surface-variant max-w-xl mx-auto">
                Discover our delivery timelines, insured packaging standards, and premium courier fulfillment partners.
            </p>
        </div>

        <!-- Highlights Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
            <div class="bg-surface-container-lowest p-5 rounded-2xl border border-border-subtle shadow-xs text-center space-y-1">
                <span class="material-symbols-outlined text-2xl text-heritage-burgundy">schedule</span>
                <h4 class="font-bold text-xs text-charcoal-text uppercase font-label-caps">Dispatch Time</h4>
                <p class="text-xs text-on-surface-variant">Standard orders dispatch within 24-48 hours.</p>
            </div>

            <div class="bg-surface-container-lowest p-5 rounded-2xl border border-border-subtle shadow-xs text-center space-y-1">
                <span class="material-symbols-outlined text-2xl text-heritage-burgundy">shield</span>
                <h4 class="font-bold text-xs text-charcoal-text uppercase font-label-caps">100% Insured Transit</h4>
                <p class="text-xs text-on-surface-variant">Full transit protection on high-value silk &amp; bridal ensembles.</p>
            </div>

            <div class="bg-surface-container-lowest p-5 rounded-2xl border border-border-subtle shadow-xs text-center space-y-1">
                <span class="material-symbols-outlined text-2xl text-heritage-burgundy">local_shipping</span>
                <h4 class="font-bold text-xs text-charcoal-text uppercase font-label-caps">Free Shipping</h4>
                <p class="text-xs text-on-surface-variant">Complimentary express shipping across India.</p>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
            <!-- Sticky Sidebar Nav -->
            <aside class="lg:col-span-4 lg:sticky lg:top-28 space-y-6">
                <nav class="bg-surface-container-lowest rounded-2xl border border-border-subtle p-6 shadow-xs space-y-3">
                    <h3 class="font-label-caps text-[11px] uppercase tracking-widest text-muted-gold font-bold">On This Page</h3>
                    <ul class="space-y-1 text-xs font-body-md">
                        <li><a href="#domestic-delivery" class="flex items-center justify-between py-2 px-3 rounded-lg text-on-surface-variant hover:text-heritage-burgundy hover:bg-warm-ivory/60 transition-colors"><span>1. Domestic Delivery</span><span class="material-symbols-outlined text-sm">arrow_forward</span></a></li>
                        <li><a href="#bespoke-orders" class="flex items-center justify-between py-2 px-3 rounded-lg text-on-surface-variant hover:text-heritage-burgundy hover:bg-warm-ivory/60 transition-colors"><span>2. Bespoke Orders</span><span class="material-symbols-outlined text-sm">arrow_forward</span></a></li>
                        <li><a href="#courier-partners" class="flex items-center justify-between py-2 px-3 rounded-lg text-on-surface-variant hover:text-heritage-burgundy hover:bg-warm-ivory/60 transition-colors"><span>3. Courier &amp; Tracking</span><span class="material-symbols-outlined text-sm">arrow_forward</span></a></li>
                        <li><a href="#packaging" class="flex items-center justify-between py-2 px-3 rounded-lg text-on-surface-variant hover:text-heritage-burgundy hover:bg-warm-ivory/60 transition-colors"><span>4. Luxury Packaging</span><span class="material-symbols-outlined text-sm">arrow_forward</span></a></li>
                        <li><a href="#escalations" class="flex items-center justify-between py-2 px-3 rounded-lg text-on-surface-variant hover:text-heritage-burgundy hover:bg-warm-ivory/60 transition-colors"><span>5. Questions &amp; Escalations</span><span class="material-symbols-outlined text-sm">arrow_forward</span></a></li>
                    </ul>
                </nav>

                <div class="bg-cream-silk rounded-2xl border border-muted-gold/40 p-6 shadow-xs space-y-3">
                    <div class="w-10 h-10 rounded-full bg-warm-ivory text-heritage-burgundy flex items-center justify-center border border-muted-gold/40">
                        <span class="material-symbols-outlined text-lg">local_shipping</span>
                    </div>
                    <h3 class="font-headline-md text-sm text-heritage-burgundy font-serif">Track a Live Shipment</h3>
                    <p class="text-xs text-on-surface-variant leading-relaxed">Already have your Airway Bill number? Track live courier milestones instantly.</p>
                    <a href="{{ route('track.order') }}" class="inline-flex items-center gap-1.5 text-xs font-label-caps uppercase font-bold text-heritage-burgundy hover:underline">
                        <span>Track My Order</span>
                        <span class="material-symbols-outlined text-sm">arrow_forward</span>
                    </a>
                </div>
            </aside>

            <!-- Policy Content -->
            <div class="lg:col-span-8 bg-surface-container-lowest p-8 sm:p-12 rounded-3xl border border-border-subtle shadow-xs space-y-8 font-body-md text-xs sm:text-sm text-charcoal-text leading-relaxed">
                <section id="domestic-delivery" class="space-y-3 scroll-mt-28">
                    <h2 class="font-headline-md text-lg text-heritage-burgundy font-serif font-semibold">1. Domestic Delivery Timelines</h2>
                    <p class="text-on-surface-variant">
                        All ready-to-ship garments (standard sizes S, M, L, XL) are quality-checked and dispatched within <strong>24 to 48 hours</strong> of order confirmation. Once dispatched, standard delivery across major metro cities in India takes <strong>3 to 5 business days</strong>.
                    </p>
                    <p class="text-on-surface-variant">
                        For remote and tier-3 locations, delivery may take between <strong>5 to 7 business days</strong> depending on courier network accessibility.
                    </p>
                </section>

                <section id="bespoke-orders" class="space-y-3 border-t border-border-subtle pt-6 scroll-mt-28">
                    <h2 class="font-headline-md text-lg text-heritage-burgundy font-serif font-semibold">2. Bespoke &amp; Custom Fit Orders</h2>
                    <p class="text-on-surface-variant">
                        Garments customized with bespoke atelier measurements (such as custom blouse bust, skirt waist, or length alterations) require hand-tailoring and individual finishing by our senior master tailors. Bespoke orders dispatch within <strong>7 to 10 business days</strong>. You will receive progress notifications as your ensemble transitions through cutting, embroidery, and final press QC.
                    </p>
                </section>

                <section id="courier-partners" class="space-y-3 border-t border-border-subtle pt-6 scroll-mt-28">
                    <h2 class="font-headline-md text-lg text-heritage-burgundy font-serif font-semibold">3. Courier Partners &amp; Real-Time Tracking</h2>
                    <p class="text-on-surface-variant">
                        We partner exclusively with premier express courier networks including <strong>BlueDart Express</strong>, <strong>Delhivery Air</strong>, and <strong>DTDC Premium</strong>.
                    </p>
                    <p class="text-on-surface-variant">
                        Upon dispatch, you will automatically receive an SMS and email containing your Airway Bill (AWB) tracking number and courier tracking link. You can also track your live shipment milestone journey anytime on our <a href="{{ route('track.order') }}" class="text-heritage-burgundy font-bold underline">Live Order Tracking Portal</a>.
                    </p>
                </section>

                <section id="packaging" class="space-y-3 border-t border-border-subtle pt-6 scroll-mt-28">
                    <h2 class="font-headline-md text-lg text-heritage-burgundy font-serif font-semibold">4. Luxury Packaging &amp; Tamper-Proof Seals</h2>
                    <p class="text-on-surface-variant">
                        Each Sonakshi couture garment is wrapped in breathable organic muslin cloth, secured with acid-free tissue paper to protect gold and silver zari embroidery, and encased in our signature royal presentation box with tamper-evident security holographic seals.
                    </p>
                </section>

                <section id="escalations" class="space-y-3 border-t border-border-subtle pt-6 scroll-mt-28">
                    <h2 class="font-headline-md text-lg text-heritage-burgundy font-serif font-semibold">5. Questions &amp; Escalations</h2>
                    <p class="text-on-surface-variant">
                        If your shipment is delayed or you require urgent expedited delivery for an upcoming wedding date, please contact our concierge team at <a href="mailto:concierge@sonakshifashion.com" class="text-heritage-burgundy font-bold">concierge@sonakshifashion.com</a> or call <a href="tel:+919876500000" class="text-heritage-burgundy font-bold">+91 98765 00000</a>.
                    </p>
                </section>
            </div>
        </div>
    </div>
</x-layouts.app>
