<x-layouts.app title="Terms of Service - Sonakshi Fashion Hub">
    <div class="max-w-6xl mx-auto space-y-10">
        <!-- Header -->
        <div class="text-center space-y-2 pt-4">
            <span class="font-label-caps text-xs text-muted-gold uppercase tracking-widest font-bold block">Legal &amp; Store Terms</span>
            <h1 class="font-headline-lg text-3xl sm:text-4xl text-heritage-burgundy font-serif">
                Terms of Service
            </h1>
            <p class="font-body-md text-xs sm:text-sm text-on-surface-variant max-w-xl mx-auto">
                Last updated: {{ date('F Y') }}. Please read these terms carefully before placing your couture order.
            </p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
            <!-- Sticky Sidebar Nav -->
            <aside class="lg:col-span-4 lg:sticky lg:top-28 space-y-6">
                <nav class="bg-surface-container-lowest rounded-2xl border border-border-subtle p-6 shadow-xs space-y-3">
                    <h3 class="font-label-caps text-[11px] uppercase tracking-widest text-muted-gold font-bold">On This Page</h3>
                    <ul class="space-y-1 text-xs font-body-md">
                        <li><a href="#overview" class="flex items-center justify-between py-2 px-3 rounded-lg text-on-surface-variant hover:text-heritage-burgundy hover:bg-warm-ivory/60 transition-colors"><span>1. Overview &amp; Acceptance</span><span class="material-symbols-outlined text-sm">arrow_forward</span></a></li>
                        <li><a href="#authenticity" class="flex items-center justify-between py-2 px-3 rounded-lg text-on-surface-variant hover:text-heritage-burgundy hover:bg-warm-ivory/60 transition-colors"><span>2. Handloom Authenticity</span><span class="material-symbols-outlined text-sm">arrow_forward</span></a></li>
                        <li><a href="#pricing" class="flex items-center justify-between py-2 px-3 rounded-lg text-on-surface-variant hover:text-heritage-burgundy hover:bg-warm-ivory/60 transition-colors"><span>3. Pricing &amp; Payments</span><span class="material-symbols-outlined text-sm">arrow_forward</span></a></li>
                        <li><a href="#custom-tailoring" class="flex items-center justify-between py-2 px-3 rounded-lg text-on-surface-variant hover:text-heritage-burgundy hover:bg-warm-ivory/60 transition-colors"><span>4. Custom Tailoring</span><span class="material-symbols-outlined text-sm">arrow_forward</span></a></li>
                        <li><a href="#ip" class="flex items-center justify-between py-2 px-3 rounded-lg text-on-surface-variant hover:text-heritage-burgundy hover:bg-warm-ivory/60 transition-colors"><span>5. Intellectual Property</span><span class="material-symbols-outlined text-sm">arrow_forward</span></a></li>
                        <li><a href="#governing-law" class="flex items-center justify-between py-2 px-3 rounded-lg text-on-surface-variant hover:text-heritage-burgundy hover:bg-warm-ivory/60 transition-colors"><span>6. Governing Law</span><span class="material-symbols-outlined text-sm">arrow_forward</span></a></li>
                    </ul>
                </nav>

                <div class="bg-cream-silk rounded-2xl border border-muted-gold/40 p-6 shadow-xs space-y-3">
                    <div class="w-10 h-10 rounded-full bg-warm-ivory text-heritage-burgundy flex items-center justify-center border border-muted-gold/40">
                        <span class="material-symbols-outlined text-lg">gavel</span>
                    </div>
                    <h3 class="font-headline-md text-sm text-heritage-burgundy font-serif">Need Clarification?</h3>
                    <p class="text-xs text-on-surface-variant leading-relaxed">Our team is happy to walk you through any clause before you place an order.</p>
                    <a href="{{ route('contact') }}" class="inline-flex items-center gap-1.5 text-xs font-label-caps uppercase font-bold text-heritage-burgundy hover:underline">
                        <span>Contact Concierge</span>
                        <span class="material-symbols-outlined text-sm">arrow_forward</span>
                    </a>
                </div>
            </aside>

            <!-- Policy Content -->
            <div class="lg:col-span-8 bg-surface-container-lowest p-8 sm:p-12 rounded-3xl border border-border-subtle shadow-xs space-y-8 font-body-md text-xs sm:text-sm text-charcoal-text leading-relaxed">
                <section id="overview" class="space-y-3 scroll-mt-28">
                    <h2 class="font-headline-md text-lg text-heritage-burgundy font-serif font-semibold">1. Overview &amp; Acceptance</h2>
                    <p class="text-on-surface-variant">
                        These Terms of Service govern your use of the website <strong>Sonakshi Fashion Hub</strong> and the purchase of our handcrafted ethnic couture, bridal lehengas, silk suits, lachas, and related accessories. By accessing our platform, registering an account, or placing an order, you agree to be bound by these terms.
                    </p>
                </section>

                <section id="authenticity" class="space-y-3 border-t border-border-subtle pt-6 scroll-mt-28">
                    <h2 class="font-headline-md text-lg text-heritage-burgundy font-serif font-semibold">2. Handloom Authenticity &amp; Product Representation</h2>
                    <p class="text-on-surface-variant">
                        Our garments are woven and hand-embroidered by human master artisans. Because each piece is handmade from pure silks, subtle variations in weave density, zari shading, and natural dye consistency are hallmarks of genuine handloom heritage and are not considered manufacturing defects.
                    </p>
                </section>

                <section id="pricing" class="space-y-3 border-t border-border-subtle pt-6 scroll-mt-28">
                    <h2 class="font-headline-md text-lg text-heritage-burgundy font-serif font-semibold">3. Pricing, Taxes &amp; Secure Payments</h2>
                    <p class="text-on-surface-variant">
                        All prices displayed on the store are in Indian Rupees (INR ₹) inclusive of all applicable Goods and Services Tax (GST). We accept major credit/debit cards, UPI, net banking, and verified online gateways. We do not store sensitive payment card details on our servers.
                    </p>
                </section>

                <section id="custom-tailoring" class="space-y-3 border-t border-border-subtle pt-6 scroll-mt-28">
                    <h2 class="font-headline-md text-lg text-heritage-burgundy font-serif font-semibold">4. Custom Tailoring &amp; Measurements</h2>
                    <p class="text-on-surface-variant">
                        When choosing "CUSTOM FIT", the customer is responsible for providing accurate measurement parameters. Our atelier creates patterns tailored specifically to the submitted blouse, waist, and length dimensions. We offer complimentary minor alterations should adjustments be required upon receipt.
                    </p>
                </section>

                <section id="ip" class="space-y-3 border-t border-border-subtle pt-6 scroll-mt-28">
                    <h2 class="font-headline-md text-lg text-heritage-burgundy font-serif font-semibold">5. Intellectual Property</h2>
                    <p class="text-on-surface-variant">
                        All product designs, photography, video masterclasses, logos, typography, and brand assets are the exclusive intellectual property of Sonakshi Fashion Hub and are protected under copyright and trademark law. Unauthorized reproduction is strictly prohibited.
                    </p>
                </section>

                <section id="governing-law" class="space-y-3 border-t border-border-subtle pt-6 scroll-mt-28">
                    <h2 class="font-headline-md text-lg text-heritage-burgundy font-serif font-semibold">6. Governing Law &amp; Jurisdiction</h2>
                    <p class="text-on-surface-variant">
                        These Terms of Service and any transactional disputes arising hereunder shall be governed by and construed in accordance with the laws of India, subject to the exclusive jurisdiction of the courts in Varanasi, Uttar Pradesh.
                    </p>
                </section>
            </div>
        </div>
    </div>
</x-layouts.app>
