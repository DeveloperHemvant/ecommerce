<x-layouts.checkout title="Checkout: Payment Method - Sonakshi Fashion Hub" currentStep="2">
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 lg:gap-12">
        <!-- Payment Options Form (8 Cols) -->
        <div class="lg:col-span-8">
            <div class="bg-surface-container-lowest rounded-2xl border border-border-subtle p-6 md:p-8 shadow-xs space-y-6">
                <div class="flex items-center justify-between border-b border-border-subtle pb-4">
                    <div>
                        <h2 class="font-headline-md text-xl text-heritage-burgundy">2. Select Payment Method</h2>
                        <p class="font-body-md text-xs text-on-surface-variant mt-0.5">All transactions are encrypted with 256-bit SSL bank-grade security.</p>
                    </div>
                    <span class="text-xs font-label-caps text-heritage-burgundy bg-cream-silk px-3 py-1 rounded-full font-bold border border-muted-gold/30">
                        Step 2 of 3
                    </span>
                </div>

                <!-- Shipping Address Summary Chip -->
                <div class="p-4 bg-warm-ivory/50 rounded-xl border border-border-subtle flex justify-between items-center text-xs">
                    <div>
                        <span class="font-label-caps text-[10px] uppercase text-on-surface-variant font-bold block mb-0.5">Delivering to</span>
                        <p class="font-semibold text-charcoal-text">{{ $shipping['customer_name'] }} &bull; {{ $shipping['shipping_address'] }}, {{ $shipping['city'] }} ({{ $shipping['postal_code'] }})</p>
                    </div>
                    <a href="{{ route('checkout.shipping') }}" class="text-heritage-burgundy hover:underline font-bold font-label-caps uppercase text-xs shrink-0 ml-4">
                        Change
                    </a>
                </div>

                <form action="{{ route('checkout.payment.save') }}" method="POST" class="space-y-4">
                    @csrf

                    <!-- Option 1: UPI -->
                    <label class="p-4 bg-surface-container-low border border-border-subtle rounded-xl flex items-center justify-between cursor-pointer hover:border-heritage-burgundy transition-all group has-[:checked]:border-2 has-[:checked]:border-heritage-burgundy has-[:checked]:bg-cream-silk">
                        <div class="flex items-center gap-3">
                            <input type="radio" name="payment_method" value="UPI" {{ ($selectedPayment ?? 'UPI') === 'UPI' ? 'checked' : '' }}
                                class="w-4 h-4 text-heritage-burgundy focus:ring-heritage-burgundy" />
                            <div>
                                <span class="font-title-lg text-sm font-bold text-charcoal-text block">UPI &bull; Instant Pay</span>
                                <span class="text-xs text-on-surface-variant">Google Pay, PhonePe, Paytm, BHIM UPI</span>
                            </div>
                        </div>
                        <span class="material-symbols-outlined text-2xl text-heritage-burgundy">qr_code_scanner</span>
                    </label>

                    <!-- Option 2: Credit / Debit Card -->
                    <label class="p-4 bg-surface-container-low border border-border-subtle rounded-xl flex items-center justify-between cursor-pointer hover:border-heritage-burgundy transition-all group has-[:checked]:border-2 has-[:checked]:border-heritage-burgundy has-[:checked]:bg-cream-silk">
                        <div class="flex items-center gap-3">
                            <input type="radio" name="payment_method" value="Card" {{ ($selectedPayment ?? '') === 'Card' ? 'checked' : '' }}
                                class="w-4 h-4 text-heritage-burgundy focus:ring-heritage-burgundy" />
                            <div>
                                <span class="font-title-lg text-sm font-bold text-charcoal-text block">Credit / Debit Card</span>
                                <span class="text-xs text-on-surface-variant">Visa, Mastercard, RuPay, American Express</span>
                            </div>
                        </div>
                        <span class="material-symbols-outlined text-2xl text-heritage-burgundy">credit_card</span>
                    </label>

                    <!-- Option 3: Net Banking -->
                    <label class="p-4 bg-surface-container-low border border-border-subtle rounded-xl flex items-center justify-between cursor-pointer hover:border-heritage-burgundy transition-all group has-[:checked]:border-2 has-[:checked]:border-heritage-burgundy has-[:checked]:bg-cream-silk">
                        <div class="flex items-center gap-3">
                            <input type="radio" name="payment_method" value="Net Banking" {{ ($selectedPayment ?? '') === 'Net Banking' ? 'checked' : '' }}
                                class="w-4 h-4 text-heritage-burgundy focus:ring-heritage-burgundy" />
                            <div>
                                <span class="font-title-lg text-sm font-bold text-charcoal-text block">Net Banking</span>
                                <span class="text-xs text-on-surface-variant">All major Indian banks (HDFC, ICICI, SBI, Axis)</span>
                            </div>
                        </div>
                        <span class="material-symbols-outlined text-2xl text-heritage-burgundy">account_balance</span>
                    </label>

                    <!-- Option 4: COD -->
                    <label class="p-4 bg-surface-container-low border border-border-subtle rounded-xl flex items-center justify-between cursor-pointer hover:border-heritage-burgundy transition-all group has-[:checked]:border-2 has-[:checked]:border-heritage-burgundy has-[:checked]:bg-cream-silk">
                        <div class="flex items-center gap-3">
                            <input type="radio" name="payment_method" value="COD" {{ ($selectedPayment ?? '') === 'COD' ? 'checked' : '' }}
                                class="w-4 h-4 text-heritage-burgundy focus:ring-heritage-burgundy" />
                            <div>
                                <span class="font-title-lg text-sm font-bold text-charcoal-text block">Cash on Delivery (COD)</span>
                                <span class="text-xs text-on-surface-variant">Pay in cash or UPI at the time of doorstep delivery</span>
                            </div>
                        </div>
                        <span class="material-symbols-outlined text-2xl text-heritage-burgundy">payments</span>
                    </label>

                    <div class="pt-6 border-t border-border-subtle flex flex-col sm:flex-row justify-between items-center gap-4">
                        <a href="{{ route('checkout.shipping') }}" class="text-xs font-label-caps uppercase text-heritage-burgundy hover:underline flex items-center gap-1 font-bold">
                            <span class="material-symbols-outlined text-base">arrow_back</span>
                            Back to Shipping
                        </a>
                        <button type="submit"
                            class="w-full sm:w-auto bg-heritage-burgundy text-white font-label-caps text-xs uppercase tracking-wider px-8 py-4 rounded-xl hover:bg-primary-container transition-all flex items-center justify-center gap-2 font-bold cursor-pointer shadow-sm">
                            <span>Review Your Order</span>
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
                    Order Summary
                </h3>

                <div class="border-t border-border-subtle pt-4 space-y-2 text-xs font-body-md">
                    <div class="flex justify-between text-on-surface-variant">
                        <span>Subtotal ({{ count($cart) }} items)</span>
                        <span class="font-data-tabular font-semibold text-charcoal-text">₹{{ number_format($subtotal) }}</span>
                    </div>
                    @if($discount > 0)
                        <div class="flex justify-between text-emerald-800 font-semibold">
                            <span>Discount</span>
                            <span class="font-data-tabular">-₹{{ number_format($discount) }}</span>
                        </div>
                    @endif
                    <div class="flex justify-between text-on-surface-variant">
                        <span>Insured Delivery</span>
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
