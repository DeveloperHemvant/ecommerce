<x-layouts.checkout title="Checkout: Review & Place Order - Sonakshi Fashion Hub" currentStep="3">
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 lg:gap-12">
        <!-- Review Order Details (8 Cols) -->
        <div class="lg:col-span-8 space-y-6">
            <!-- Review Items Card -->
            <div class="bg-surface-container-lowest rounded-2xl border border-border-subtle p-6 md:p-8 shadow-xs space-y-5">
                <div class="flex items-center justify-between border-b border-border-subtle pb-4">
                    <div>
                        <h2 class="font-headline-md text-xl text-heritage-burgundy">3. Review Order Items</h2>
                        <p class="font-body-md text-xs text-on-surface-variant mt-0.5">Please review your selected items and shipping details before completing payment.</p>
                    </div>
                    <span class="text-xs font-label-caps text-heritage-burgundy bg-cream-silk px-3 py-1 rounded-full font-bold border border-muted-gold/30">
                        Step 3 of 3
                    </span>
                </div>

                <div class="space-y-4 divide-y divide-border-subtle">
                    @foreach($cart as $item)
                        <div class="pt-4 first:pt-0 flex gap-4 items-center">
                            <div class="w-16 h-20 rounded-xl overflow-hidden bg-surface shrink-0 border border-border-subtle">
                                <img src="{{ $item['image'] ?? '' }}" alt="{{ $item['name'] }}" class="w-full h-full object-cover" />
                            </div>
                            <div class="flex-1 min-w-0">
                                <h4 class="font-headline-md text-sm text-charcoal-text truncate">{{ $item['name'] }}</h4>
                                <p class="text-xs text-on-surface-variant mt-0.5">
                                    Size: <strong>{{ $item['size'] }}</strong>
                                    @if(!empty($item['color']))
                                        &bull; Color: <strong>{{ $item['color'] }}</strong>
                                    @endif
                                    &bull; Qty: <strong>{{ $item['quantity'] }}</strong>
                                </p>
                                <p class="font-data-tabular text-xs font-bold text-heritage-burgundy mt-1">₹{{ number_format($item['price'] * $item['quantity']) }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Shipping & Payment Summary Card -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <!-- Shipping Address Block -->
                <div class="bg-surface-container-lowest rounded-2xl border border-border-subtle p-6 shadow-xs flex flex-col justify-between">
                    <div>
                        <div class="flex justify-between items-center mb-3">
                            <h3 class="font-title-lg text-sm font-bold text-heritage-burgundy uppercase font-label-caps">Shipping Address</h3>
                            <a href="{{ route('checkout.shipping') }}" class="text-xs font-label-caps text-heritage-burgundy hover:underline">Edit</a>
                        </div>
                        <p class="font-semibold text-charcoal-text text-sm">{{ $shipping['customer_name'] }}</p>
                        <p class="text-xs text-on-surface-variant mt-1 leading-relaxed">
                            {{ $shipping['shipping_address'] }}<br />
                            {{ $shipping['city'] }}, {{ $shipping['state'] }} - {{ $shipping['postal_code'] }}<br />
                            {{ $shipping['country'] }}
                        </p>
                        <p class="text-xs text-on-surface-variant mt-2">
                            Mobile: <strong>{{ $shipping['customer_phone'] }}</strong><br />
                            Email: <strong>{{ $shipping['customer_email'] }}</strong>
                        </p>
                    </div>
                </div>

                <!-- Payment Method Block -->
                <div class="bg-surface-container-lowest rounded-2xl border border-border-subtle p-6 shadow-xs flex flex-col justify-between">
                    <div>
                        <div class="flex justify-between items-center mb-3">
                            <h3 class="font-title-lg text-sm font-bold text-heritage-burgundy uppercase font-label-caps">Payment Mode</h3>
                            <a href="{{ route('checkout.payment') }}" class="text-xs font-label-caps text-heritage-burgundy hover:underline">Edit</a>
                        </div>
                        <div class="flex items-center gap-2 p-3 bg-cream-silk rounded-xl border border-muted-gold/30">
                            <span class="material-symbols-outlined text-xl text-heritage-burgundy">
                                {{ $paymentMethod === 'UPI' ? 'qr_code_scanner' : ($paymentMethod === 'Card' ? 'credit_card' : ($paymentMethod === 'Net Banking' ? 'account_balance' : 'payments')) }}
                            </span>
                            <span class="font-title-lg text-xs font-bold text-charcoal-text">{{ $paymentMethod }}</span>
                        </div>
                        <p class="text-[11px] text-on-surface-variant mt-3">
                            @if($paymentMethod === 'COD')
                                Pay cash or UPI upon delivery.
                            @else
                                Secure payment verified. Immediate dispatch prioritized.
                            @endif
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Order Total & Place Order Button (4 Cols) -->
        <div class="lg:col-span-4">
            <div class="bg-surface-container-low rounded-2xl p-6 md:p-8 border border-border-subtle sticky top-24 space-y-6">
                <h3 class="font-headline-md text-lg text-heritage-burgundy border-b border-border-subtle pb-3">
                    Order Summary
                </h3>

                <div class="space-y-3 text-xs font-body-md">
                    <div class="flex justify-between text-on-surface-variant">
                        <span>Items Subtotal</span>
                        <span class="font-data-tabular font-semibold text-charcoal-text">₹{{ number_format($subtotal) }}</span>
                    </div>
                    @if($discount > 0)
                        <div class="flex justify-between text-emerald-800 font-semibold">
                            <span>Coupon Savings ({{ $coupon['code'] }})</span>
                            <span class="font-data-tabular">-₹{{ number_format($discount) }}</span>
                        </div>
                    @endif
                    <div class="flex justify-between text-on-surface-variant">
                        <span>Insured Shipping</span>
                        <span class="text-emerald-700 font-semibold">FREE</span>
                    </div>
                    <div class="border-t border-border-subtle pt-3 flex justify-between items-center">
                        <span class="font-title-lg text-sm font-bold text-charcoal-text">Grand Total</span>
                        <span class="font-headline-md text-2xl font-bold text-heritage-burgundy">₹{{ number_format($total) }}</span>
                    </div>
                </div>

                <form action="{{ route('checkout.place_order') }}" method="POST">
                    @csrf
                    <button type="submit"
                        class="w-full bg-heritage-burgundy text-white font-label-caps text-xs uppercase tracking-wider py-4 rounded-xl hover:bg-primary-container transition-all flex items-center justify-center gap-2 font-bold cursor-pointer shadow-lg">
                        <span class="material-symbols-outlined text-lg">check_circle</span>
                        <span>Confirm &amp; Place Order</span>
                    </button>
                </form>

                <p class="text-[11px] text-center text-on-surface-variant opacity-80">
                    By clicking Confirm, you agree to Sonakshi Fashion Hub's ordering policies.
                </p>
            </div>
        </div>
    </div>
</x-layouts.checkout>
