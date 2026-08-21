<x-layouts.app title="Contact Us & Bridal Concierge - Sonakshi Fashion Hub">
    <div class="max-w-6xl mx-auto space-y-12">
        <!-- Header -->
        <div class="text-center space-y-3 pt-4">
            <span class="font-label-caps text-xs text-muted-gold uppercase tracking-widest font-bold block">Personal Atelier Concierge</span>
            <h1 class="font-headline-lg text-3xl sm:text-4xl md:text-5xl text-heritage-burgundy font-serif">
                Connect with Our Stylists
            </h1>
            <p class="font-body-md text-xs sm:text-sm text-on-surface-variant max-w-xl mx-auto leading-relaxed">
                Whether you need bespoke bridal sizing advice, custom colorways, or order assistance, our dedicated fashion consultants are here for you.
            </p>
        </div>

        @if(session('success'))
            <div class="p-5 bg-green-50 border border-green-200 text-green-800 text-xs sm:text-sm rounded-2xl flex items-center gap-3 shadow-xs">
                <span class="material-symbols-outlined text-2xl text-green-600">check_circle</span>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
            <!-- Contact Details & Atelier Info (5 Cols) -->
            <div class="lg:col-span-5 space-y-6">
                <!-- Concierge Card -->
                <div class="bg-surface-container-lowest p-6 sm:p-8 rounded-3xl border border-border-subtle shadow-xs space-y-6">
                    <div>
                        <h3 class="font-headline-md text-lg text-heritage-burgundy font-serif mb-1">Flagship Atelier</h3>
                        <p class="font-body-md text-xs text-on-surface-variant leading-relaxed">
                            Sonakshi Couture House, 402 Heritage Chambers, MG Road, Varanasi, Uttar Pradesh - 221001, India
                        </p>
                    </div>

                    <div class="space-y-4 text-xs font-body-md">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-full bg-cream-silk text-heritage-burgundy flex items-center justify-center shrink-0 border border-muted-gold/30">
                                <span class="material-symbols-outlined text-lg">call</span>
                            </div>
                            <div>
                                <span class="font-label-caps uppercase text-[10px] text-on-surface-variant block">Client Hotline</span>
                                <a href="tel:+919876500000" class="font-bold text-charcoal-text hover:text-heritage-burgundy transition-colors">+91 98765 00000</a>
                            </div>
                        </div>

                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-full bg-cream-silk text-heritage-burgundy flex items-center justify-center shrink-0 border border-muted-gold/30">
                                <span class="material-symbols-outlined text-lg">mail</span>
                            </div>
                            <div>
                                <span class="font-label-caps uppercase text-[10px] text-on-surface-variant block">Email Inquiries</span>
                                <a href="mailto:concierge@sonakshifashion.com" class="font-bold text-charcoal-text hover:text-heritage-burgundy transition-colors">concierge@sonakshifashion.com</a>
                            </div>
                        </div>

                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-full bg-cream-silk text-heritage-burgundy flex items-center justify-center shrink-0 border border-muted-gold/30">
                                <span class="material-symbols-outlined text-lg">schedule</span>
                            </div>
                            <div>
                                <span class="font-label-caps uppercase text-[10px] text-on-surface-variant block">Styling Hours</span>
                                <p class="font-semibold text-charcoal-text">Mon - Sat: 10:00 AM – 7:30 PM IST</p>
                            </div>
                        </div>
                    </div>

                    <!-- Direct WhatsApp Button -->
                    <div class="pt-2 border-t border-border-subtle">
                        <a href="https://wa.me/?text={{ urlencode('Hi Sonakshi Bridal Team, I would like to schedule a virtual styling session or inquire about an order.') }}" target="_blank"
                            class="w-full py-3 px-4 rounded-xl bg-[#25D366] text-white font-label-caps text-xs uppercase font-bold flex items-center justify-center gap-2 hover:bg-[#1EBE5D] transition-all shadow-xs">
                            <span class="material-symbols-outlined text-lg">chat</span>
                            <span>Chat on WhatsApp</span>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Interactive Inquiry Form (7 Cols) -->
            <div class="lg:col-span-7 bg-surface-container-lowest p-6 sm:p-8 md:p-10 rounded-3xl border border-border-subtle shadow-xs space-y-6">
                <div>
                    <h2 class="font-headline-md text-xl sm:text-2xl text-heritage-burgundy font-serif">Send an Inquiry</h2>
                    <p class="font-body-md text-xs text-on-surface-variant mt-1">Fill out the form below and our head stylist will get back to you promptly.</p>
                </div>

                <form action="{{ route('contact.submit') }}" method="POST" class="space-y-4">
                    @csrf
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-label-caps uppercase text-on-surface-variant font-semibold mb-1">Your Full Name <span class="text-error">*</span></label>
                            <input type="text" name="name" required value="{{ old('name', Auth::check() ? Auth::user()->name : '') }}" placeholder="e.g. Ananya Sharma"
                                class="w-full bg-warm-ivory/60 border border-border-subtle rounded-xl px-4 py-2.5 text-xs text-charcoal-text focus:border-heritage-burgundy focus:outline-none transition-colors" />
                            @error('name') <span class="text-error text-[10px] mt-1 block">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-xs font-label-caps uppercase text-on-surface-variant font-semibold mb-1">Email Address <span class="text-error">*</span></label>
                            <input type="email" name="email" required value="{{ old('email', Auth::check() ? Auth::user()->email : '') }}" placeholder="e.g. ananya@example.com"
                                class="w-full bg-warm-ivory/60 border border-border-subtle rounded-xl px-4 py-2.5 text-xs text-charcoal-text focus:border-heritage-burgundy focus:outline-none transition-colors" />
                            @error('email') <span class="text-error text-[10px] mt-1 block">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-label-caps uppercase text-on-surface-variant font-semibold mb-1">Phone / WhatsApp</label>
                            <input type="tel" name="phone" value="{{ old('phone', Auth::check() ? Auth::user()->phone : '') }}" placeholder="e.g. +91 98765 43210"
                                class="w-full bg-warm-ivory/60 border border-border-subtle rounded-xl px-4 py-2.5 text-xs text-charcoal-text focus:border-heritage-burgundy focus:outline-none transition-colors" />
                        </div>

                        <div>
                            <label class="block text-xs font-label-caps uppercase text-on-surface-variant font-semibold mb-1">Inquiry Subject <span class="text-error">*</span></label>
                            <select name="subject" required class="w-full bg-warm-ivory/60 border border-border-subtle rounded-xl px-4 py-2.5 text-xs text-charcoal-text focus:border-heritage-burgundy focus:outline-none transition-colors">
                                <option value="Bespoke Bridal Sizing & Measurements">Bespoke Bridal Sizing &amp; Measurements</option>
                                <option value="Existing Order Status & Tracking">Existing Order Status &amp; Tracking</option>
                                <option value="Custom Color & Blouse Tailoring">Custom Color &amp; Blouse Tailoring</option>
                                <option value="Bulk / Wedding Trousseau Inquiry">Bulk / Wedding Trousseau Inquiry</option>
                                <option value="Other Inquiries">Other Inquiries</option>
                            </select>
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-label-caps uppercase text-on-surface-variant font-semibold mb-1">Your Message <span class="text-error">*</span></label>
                        <textarea name="message" rows="4" required placeholder="Provide details regarding your upcoming wedding date, desired ensemble, or questions..."
                            class="w-full bg-warm-ivory/60 border border-border-subtle rounded-xl px-4 py-2.5 text-xs text-charcoal-text focus:border-heritage-burgundy focus:outline-none transition-colors">{{ old('message') }}</textarea>
                        @error('message') <span class="text-error text-[10px] mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <button type="submit" class="w-full py-3.5 bg-heritage-burgundy text-white rounded-xl font-label-caps text-xs uppercase font-bold hover:bg-primary-container transition-all shadow-xs cursor-pointer">
                        Send Message to Stylist
                    </button>
                </form>
            </div>
        </div>
    </div>
</x-layouts.app>
