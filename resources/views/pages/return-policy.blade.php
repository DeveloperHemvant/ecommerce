<x-layouts.app title="Returns & Exchange Policy - Sonakshi Fashion Hub">
    <div class="max-w-6xl mx-auto space-y-10">
        <!-- Header -->
        <div class="text-center space-y-2 pt-4">
            <span class="font-label-caps text-xs text-muted-gold uppercase tracking-widest font-bold block">Assurance &amp; Guarantees</span>
            <h1 class="font-headline-lg text-3xl sm:text-4xl text-heritage-burgundy font-serif">
                Returns &amp; Exchange Policy
            </h1>
            <p class="font-body-md text-xs sm:text-sm text-on-surface-variant max-w-xl mx-auto">
                Our 7-day royal satisfaction guarantee ensures peace of mind with every heirloom acquisition.
            </p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
            <!-- Sticky Sidebar Nav -->
            <aside class="lg:col-span-4 lg:sticky lg:top-28 space-y-6">
                <nav class="bg-surface-container-lowest rounded-2xl border border-border-subtle p-6 shadow-xs space-y-3">
                    <h3 class="font-label-caps text-[11px] uppercase tracking-widest text-muted-gold font-bold">On This Page</h3>
                    <ul class="space-y-1 text-xs font-body-md">
                        <li><a href="#exchange-window" class="flex items-center justify-between py-2 px-3 rounded-lg text-on-surface-variant hover:text-heritage-burgundy hover:bg-warm-ivory/60 transition-colors"><span>1. 7-Day Exchange Window</span><span class="material-symbols-outlined text-sm">arrow_forward</span></a></li>
                        <li><a href="#eligibility" class="flex items-center justify-between py-2 px-3 rounded-lg text-on-surface-variant hover:text-heritage-burgundy hover:bg-warm-ivory/60 transition-colors"><span>2. Return Eligibility</span><span class="material-symbols-outlined text-sm">arrow_forward</span></a></li>
                        <li><a href="#custom-fit" class="flex items-center justify-between py-2 px-3 rounded-lg text-on-surface-variant hover:text-heritage-burgundy hover:bg-warm-ivory/60 transition-colors"><span>3. Custom Fit Alterations</span><span class="material-symbols-outlined text-sm">arrow_forward</span></a></li>
                        <li><a href="#refund-processing" class="flex items-center justify-between py-2 px-3 rounded-lg text-on-surface-variant hover:text-heritage-burgundy hover:bg-warm-ivory/60 transition-colors"><span>4. Refund Processing</span><span class="material-symbols-outlined text-sm">arrow_forward</span></a></li>
                    </ul>
                </nav>

                <div class="bg-cream-silk rounded-2xl border border-muted-gold/40 p-6 shadow-xs space-y-3">
                    <div class="w-10 h-10 rounded-full bg-warm-ivory text-heritage-burgundy flex items-center justify-center border border-muted-gold/40">
                        <span class="material-symbols-outlined text-lg">assignment_return</span>
                    </div>
                    <h3 class="font-headline-md text-sm text-heritage-burgundy font-serif">Start a Return</h3>
                    <p class="text-xs text-on-surface-variant leading-relaxed">Track your order first — return requests are initiated directly from your order detail page.</p>
                    <a href="{{ route('account.orders') }}" class="inline-flex items-center gap-1.5 text-xs font-label-caps uppercase font-bold text-heritage-burgundy hover:underline">
                        <span>Go to My Orders</span>
                        <span class="material-symbols-outlined text-sm">arrow_forward</span>
                    </a>
                </div>
            </aside>

            <!-- Policy Content -->
            <div class="lg:col-span-8 bg-surface-container-lowest p-8 sm:p-12 rounded-3xl border border-border-subtle shadow-xs space-y-8 font-body-md text-xs sm:text-sm text-charcoal-text leading-relaxed">
                <section id="exchange-window" class="space-y-3 scroll-mt-28">
                    <h2 class="font-headline-md text-lg text-heritage-burgundy font-serif font-semibold">1. 7-Day Exchange Window</h2>
                    <p class="text-on-surface-variant">
                        We want you to be absolutely delighted with your Sonakshi ensemble. You may request an exchange or return for standard-sized ready-to-ship garments within <strong>7 days</strong> of delivery.
                    </p>
                </section>

                <section id="eligibility" class="space-y-3 border-t border-border-subtle pt-6 scroll-mt-28">
                    <h2 class="font-headline-md text-lg text-heritage-burgundy font-serif font-semibold">2. Return Eligibility Criteria</h2>
                    <ul class="list-disc pl-5 space-y-1.5 text-on-surface-variant text-xs sm:text-sm">
                        <li>The garment must be unused, unwashed, and in its original pristine condition with all fabric brand tags attached.</li>
                        <li>The item must be returned in the original packaging box with the protective muslin preservation wrap.</li>
                        <li>Items showing perfume scents, make-up marks, or alteration by third-party tailors cannot be accepted.</li>
                    </ul>
                </section>

                <section id="custom-fit" class="space-y-3 border-t border-border-subtle pt-6 scroll-mt-28">
                    <h2 class="font-headline-md text-lg text-heritage-burgundy font-serif font-semibold">3. Custom Fit Bridal Alterations</h2>
                    <p class="text-on-surface-variant">
                        Due to the bespoke nature of customized bridal wear, garments tailored to custom individual measurements cannot be returned for full refund, but we provide <strong>100% complimentary bespoke alterations</strong>. Simply contact our concierge to schedule a complimentary pickup and alteration.
                    </p>
                </section>

                <section id="refund-processing" class="space-y-3 border-t border-border-subtle pt-6 scroll-mt-28">
                    <h2 class="font-headline-md text-lg text-heritage-burgundy font-serif font-semibold">4. Refund Processing</h2>
                    <p class="text-on-surface-variant">
                        Once the returned piece is received and inspected at our quality assurance facility, your refund will be processed within <strong>3 to 5 business days</strong> to the original method of payment (or as store credit if requested).
                    </p>
                </section>
            </div>
        </div>
    </div>
</x-layouts.app>
