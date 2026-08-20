<x-layouts.checkout title="Checkout: Shipping Details - Sonakshi Fashion Hub" currentStep="1">
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 lg:gap-12">
        <!-- Shipping Form (8 Cols) -->
        <div class="lg:col-span-8">
            <div class="bg-surface-container-lowest rounded-2xl border border-border-subtle p-6 md:p-8 shadow-xs">
                <div class="flex items-center justify-between border-b border-border-subtle pb-4 mb-6">
                    <div>
                        <h2 class="font-headline-md text-xl text-heritage-burgundy">1. Shipping &amp; Contact Details</h2>
                        <p class="font-body-md text-xs text-on-surface-variant mt-0.5">Please provide the delivery address where your royal order should be dispatched.</p>
                    </div>
                    <span class="text-xs font-label-caps text-heritage-burgundy bg-cream-silk px-3 py-1 rounded-full font-bold border border-muted-gold/30">
                        Step 1 of 3
                    </span>
                </div>

                @if($errors->any())
                    <div class="mb-6 p-4 bg-red-50 border border-red-200 text-error text-xs rounded-xl flex items-center gap-2">
                        <span class="material-symbols-outlined text-base">error</span>
                        <span>{{ $errors->first() }}</span>
                    </div>
                @endif

                <form action="{{ route('checkout.shipping.save') }}" method="POST" class="space-y-5">
                    @csrf

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div class="md:col-span-2">
                            <label class="block font-label-caps text-xs text-on-surface-variant uppercase mb-1.5 font-semibold" for="customer_name">
                                Full Name <span class="text-error">*</span>
                            </label>
                            <input class="w-full bg-warm-ivory/60 border border-border-subtle rounded-xl px-4 py-3 font-body-md text-sm text-charcoal-text focus:border-heritage-burgundy focus:outline-none transition-colors"
                                id="customer_name" name="customer_name" value="{{ old('customer_name', $savedShipping['customer_name'] ?? $user->name ?? '') }}" placeholder="Priya Sharma" required type="text" />
                        </div>

                        <div>
                            <label class="block font-label-caps text-xs text-on-surface-variant uppercase mb-1.5 font-semibold" for="customer_email">
                                Email Address (for order tracking) <span class="text-error">*</span>
                            </label>
                            <input class="w-full bg-warm-ivory/60 border border-border-subtle rounded-xl px-4 py-3 font-body-md text-sm text-charcoal-text focus:border-heritage-burgundy focus:outline-none transition-colors"
                                id="customer_email" name="customer_email" value="{{ old('customer_email', $savedShipping['customer_email'] ?? $user->email ?? '') }}" placeholder="priya@example.com" required type="email" />
                        </div>

                        <div>
                            <label class="block font-label-caps text-xs text-on-surface-variant uppercase mb-1.5 font-semibold" for="customer_phone">
                                Mobile Number (for delivery updates) <span class="text-error">*</span>
                            </label>
                            <input class="w-full bg-warm-ivory/60 border border-border-subtle rounded-xl px-4 py-3 font-body-md text-sm text-charcoal-text focus:border-heritage-burgundy focus:outline-none transition-colors"
                                id="customer_phone" name="customer_phone" value="{{ old('customer_phone', $savedShipping['customer_phone'] ?? $user->phone ?? '+91 98765 43210') }}" placeholder="+91 98765 43210" required type="tel" />
                        </div>

                        <div class="md:col-span-2">
                            <label class="block font-label-caps text-xs text-on-surface-variant uppercase mb-1.5 font-semibold" for="shipping_address">
                                Street Address, Apartment / Flat No. <span class="text-error">*</span>
                            </label>
                            <input class="w-full bg-warm-ivory/60 border border-border-subtle rounded-xl px-4 py-3 font-body-md text-sm text-charcoal-text focus:border-heritage-burgundy focus:outline-none transition-colors"
                                id="shipping_address" name="shipping_address" value="{{ old('shipping_address', $savedShipping['shipping_address'] ?? '402, Royal Residency, MG Road') }}" placeholder="House/Flat No., Landmark, Street" required type="text" />
                        </div>

                        <div>
                            <label class="block font-label-caps text-xs text-on-surface-variant uppercase mb-1.5 font-semibold" for="city">
                                City <span class="text-error">*</span>
                            </label>
                            <input class="w-full bg-warm-ivory/60 border border-border-subtle rounded-xl px-4 py-3 font-body-md text-sm text-charcoal-text focus:border-heritage-burgundy focus:outline-none transition-colors"
                                id="city" name="city" value="{{ old('city', $savedShipping['city'] ?? 'Varanasi') }}" placeholder="City" required type="text" />
                        </div>

                        <div>
                            <label class="block font-label-caps text-xs text-on-surface-variant uppercase mb-1.5 font-semibold" for="state">
                                State <span class="text-error">*</span>
                            </label>
                            <input class="w-full bg-warm-ivory/60 border border-border-subtle rounded-xl px-4 py-3 font-body-md text-sm text-charcoal-text focus:border-heritage-burgundy focus:outline-none transition-colors"
                                id="state" name="state" value="{{ old('state', $savedShipping['state'] ?? 'Uttar Pradesh') }}" placeholder="State" required type="text" />
                        </div>

                        <div>
                            <label class="block font-label-caps text-xs text-on-surface-variant uppercase mb-1.5 font-semibold" for="postal_code">
                                PIN Code <span class="text-error">*</span>
                            </label>
                            <input class="w-full bg-warm-ivory/60 border border-border-subtle rounded-xl px-4 py-3 font-body-md text-sm text-charcoal-text focus:border-heritage-burgundy focus:outline-none transition-colors"
                                id="postal_code" name="postal_code" value="{{ old('postal_code', $savedShipping['postal_code'] ?? '221001') }}" placeholder="221001" required type="text" />
                        </div>

                        <div>
                            <label class="block font-label-caps text-xs text-on-surface-variant uppercase mb-1.5 font-semibold" for="country">
                                Country
                            </label>
                            <input class="w-full bg-surface-container border border-border-subtle rounded-xl px-4 py-3 font-body-md text-sm text-charcoal-text cursor-not-allowed"
                                id="country" name="country" value="India" readonly type="text" />
                        </div>
                    </div>

                    <div class="pt-6 border-t border-border-subtle flex flex-col sm:flex-row justify-between items-center gap-4">
                        <a href="{{ route('cart') }}" class="text-xs font-label-caps uppercase text-heritage-burgundy hover:underline flex items-center gap-1 font-bold">
                            <span class="material-symbols-outlined text-base">arrow_back</span>
                            Back to Shopping Bag
                        </a>
                        <button type="submit"
                            class="w-full sm:w-auto bg-heritage-burgundy text-white font-label-caps text-xs uppercase tracking-wider px-8 py-4 rounded-xl hover:bg-primary-container transition-all flex items-center justify-center gap-2 font-bold cursor-pointer shadow-sm">
                            <span>Proceed to Payment</span>
                            <span class="material-symbols-outlined text-base">arrow_forward</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Sidebar Summary (4 Cols) -->
        <div class="lg:col-span-4">
            <div class="bg-surface-container-low rounded-2xl p-6 border border-border-subtle sticky top-24 space-y-5">
                <h3 class="font-headline-md text-lg text-heritage-burgundy border-b border-border-subtle pb-3">
                    In Your Bag ({{ count($cart) }})
                </h3>

                <div class="space-y-4 max-h-[300px] overflow-y-auto pr-1">
                    @foreach($cart as $item)
                        <div class="flex gap-3 items-center">
                            <div class="w-14 h-18 rounded-lg overflow-hidden bg-surface shrink-0 border border-border-subtle">
                                <img src="{{ $item['image'] ?? '' }}" alt="{{ $item['name'] }}" class="w-full h-full object-cover" />
                            </div>
                            <div class="min-w-0 flex-1">
                                <h4 class="font-title-lg text-xs font-semibold text-charcoal-text truncate">{{ $item['name'] }}</h4>
                                <p class="text-[11px] text-on-surface-variant">Size: <strong>{{ $item['size'] }}</strong> &bull; Qty: {{ $item['quantity'] }}</p>
                                <p class="font-data-tabular text-xs font-bold text-heritage-burgundy">₹{{ number_format($item['price'] * $item['quantity']) }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="border-t border-border-subtle pt-4 space-y-2 text-xs font-body-md">
                    <div class="flex justify-between text-on-surface-variant">
                        <span>Subtotal</span>
                        <span class="font-data-tabular font-semibold text-charcoal-text">₹{{ number_format($subtotal) }}</span>
                    </div>
                    @if($discount > 0)
                        <div class="flex justify-between text-emerald-800 font-semibold">
                            <span>Discount</span>
                            <span class="font-data-tabular">-₹{{ number_format($discount) }}</span>
                        </div>
                    @endif
                    <div class="flex justify-between text-on-surface-variant">
                        <span>Shipping</span>
                        <span class="text-emerald-700 font-semibold">FREE</span>
                    </div>
                    <div class="border-t border-border-subtle pt-2 flex justify-between items-center">
                        <span class="font-title-lg text-sm font-bold text-charcoal-text">Total Payable</span>
                        <span class="font-headline-md text-xl font-bold text-heritage-burgundy">₹{{ number_format($total) }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layouts.checkout>
