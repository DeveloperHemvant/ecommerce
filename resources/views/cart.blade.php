<x-layouts.app title="Your Shopping Bag - Sonakshi Fashion Hub">
    <div class="py-4 md:py-8 max-w-6xl mx-auto">
        <h1 class="font-headline-lg text-heritage-burgundy mb-8">
            Your Shopping Bag
        </h1>

        @if(session('success'))
            <div class="mb-6 p-4 bg-green-50 border border-green-200 text-green-800 text-xs rounded-xl flex items-center gap-2">
                <span class="material-symbols-outlined text-base">check_circle</span>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        @if(session('error'))
            <div class="mb-6 p-4 bg-red-50 border border-red-200 text-error text-xs rounded-xl flex items-center gap-2">
                <span class="material-symbols-outlined text-base">error</span>
                <span>{{ session('error') }}</span>
            </div>
        @endif

        @if(!empty($cart) && count($cart) > 0)
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 lg:gap-12">
                <!-- Cart Items List (8 Cols) -->
                <div class="lg:col-span-8 flex flex-col gap-6">
                    @foreach($cart as $key => $item)
                        <div class="flex flex-col sm:flex-row gap-6 p-5 sm:p-6 bg-cream-silk/70 rounded-2xl border border-border-subtle relative group shadow-xs">
                            <div class="w-full sm:w-36 aspect-[3/4] shrink-0 overflow-hidden rounded-xl bg-surface border border-border-subtle/50">
                                <img alt="{{ $item['name'] }}"
                                    class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105"
                                    src="{{ $item['image'] ?? 'https://lh3.googleusercontent.com/aida-public/AB6AXuBAK0vnDLJGQJi8y1WUxmNTchnWdXiZ3_Wo0qaXEC3V2PLHJ0wS4njOTz10GpImOTDM9o6Is_fxcU1yW8WXQUKqO6ErVYhhxSV5cMYgCSZ0tSOAfvtgTEO4GmpQClasEIew62MYSl_BwDcv3AqwjYDN_gS0R8LH--dMyCZI9FpGEz7_scEVm0QagZdXqX-GUdebvsF3EFsxdBxTE27bWLt0ewSNfb3HXsriFXObIL0zljjZZQuMug8E' }}" />
                            </div>

                            <div class="flex-grow flex flex-col justify-between">
                                <div>
                                    <div class="flex justify-between items-start mb-2">
                                        <a href="{{ route('product.detail', $item['slug'] ?? 'royal-banarasi-lacha') }}" class="font-headline-md text-xl text-on-surface hover:text-heritage-burgundy transition-colors">
                                            {{ $item['name'] }}
                                        </a>
                                        <form action="{{ route('cart.remove') }}" method="POST">
                                            @csrf
                                            <input type="hidden" name="cart_key" value="{{ $key }}" />
                                            <button type="submit" aria-label="Remove item" class="text-on-surface-variant hover:text-error transition-colors p-1 cursor-pointer" title="Remove">
                                                <span class="material-symbols-outlined text-xl">delete</span>
                                            </button>
                                        </form>
                                    </div>
                                    <p class="font-body-md text-xs text-on-surface-variant mb-2">
                                        Size: <strong class="text-charcoal-text">{{ $item['size'] ?? 'Standard' }}</strong>
                                        @if(!empty($item['color']))
                                            &bull; Color: <strong class="text-charcoal-text">{{ $item['color'] }}</strong>
                                        @endif
                                        @if(!empty($item['sku']))
                                            &bull; SKU: <code>{{ $item['sku'] }}</code>
                                        @endif
                                    </p>
                                    <p class="font-title-lg text-lg font-bold text-heritage-burgundy">
                                        ₹{{ number_format($item['price']) }}
                                    </p>
                                </div>

                                <div class="flex items-center gap-4 mt-6">
                                    <span class="font-label-caps text-xs text-on-surface-variant uppercase font-semibold">Qty</span>
                                    <div class="flex items-center border border-border-subtle bg-white rounded-lg overflow-hidden shadow-xs">
                                        <form action="{{ route('cart.update') }}" method="POST" class="inline">
                                            @csrf
                                            <input type="hidden" name="cart_key" value="{{ $key }}" />
                                            <input type="hidden" name="action" value="decrease" />
                                            <button type="submit" class="px-3.5 py-1 text-on-surface-variant hover:text-heritage-burgundy hover:bg-surface-container transition-colors cursor-pointer font-bold">−</button>
                                        </form>
                                        <span class="px-4 py-1 font-body-md text-sm font-semibold border-x border-border-subtle">{{ $item['quantity'] }}</span>
                                        <form action="{{ route('cart.update') }}" method="POST" class="inline">
                                            @csrf
                                            <input type="hidden" name="cart_key" value="{{ $key }}" />
                                            <input type="hidden" name="action" value="increase" />
                                            <button type="submit" class="px-3.5 py-1 text-on-surface-variant hover:text-heritage-burgundy hover:bg-surface-container transition-colors cursor-pointer font-bold">+</button>
                                        </form>
                                    </div>

                                    <span class="font-data-tabular text-sm font-bold text-charcoal-text ml-auto">
                                        Total: ₹{{ number_format($item['price'] * $item['quantity']) }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    @endforeach

                    <div class="flex justify-between items-center pt-2">
                        <a href="{{ route('collections') }}" class="text-xs font-label-caps uppercase tracking-wider text-heritage-burgundy hover:underline flex items-center gap-1 font-bold">
                            <span class="material-symbols-outlined text-base">arrow_back</span>
                            Continue Shopping
                        </a>
                    </div>
                </div>

                <!-- Order Summary Sidebar (4 Cols) -->
                <div class="lg:col-span-4 mt-4 lg:mt-0">
                    <div class="bg-surface-container-low rounded-2xl p-6 md:p-8 sticky top-24 border border-border-subtle shadow-sm">
                        <h2 class="font-headline-md text-xl text-on-surface mb-6 border-b border-border-subtle pb-4">
                            Order Summary
                        </h2>

                        <div class="flex flex-col gap-4 font-body-md text-sm text-on-surface">
                            <div class="flex justify-between">
                                <span class="text-on-surface-variant">Subtotal ({{ count($cart) }} items)</span>
                                <span class="font-data-tabular font-semibold">₹{{ number_format($subtotal) }}</span>
                            </div>

                            @if($discount > 0)
                                <div class="flex justify-between text-emerald-800 font-semibold">
                                    <span class="flex items-center gap-1">
                                        <span class="material-symbols-outlined text-sm">local_offer</span>
                                        Coupon ({{ $coupon['code'] }})
                                    </span>
                                    <span class="font-data-tabular">-₹{{ number_format($discount) }}</span>
                                </div>
                            @endif

                            <div class="flex justify-between">
                                <span class="text-on-surface-variant">Shipping &amp; Insurance</span>
                                <span class="text-emerald-700 font-semibold flex items-center gap-1">
                                    <span class="material-symbols-outlined text-sm">verified</span>
                                    FREE
                                </span>
                            </div>
                            
                            <hr class="border-t border-border-subtle my-1" />

                            <div class="flex justify-between items-end mb-2">
                                <span class="font-title-lg text-base font-semibold text-on-surface">Estimated Total</span>
                                <span class="font-headline-md text-2xl text-heritage-burgundy font-bold">₹{{ number_format($total) }}</span>
                            </div>
                        </div>

                        <!-- Promo Code Form -->
                        <form action="{{ route('cart.coupon') }}" method="POST" class="mt-6 mb-6">
                            @csrf
                            <div class="relative">
                                <label class="sr-only" for="coupon">Coupon Code</label>
                                <input
                                    class="w-full bg-surface-container-lowest border border-border-subtle rounded-xl font-body-md text-xs py-3 pl-3 pr-20 focus:outline-none focus:border-heritage-burgundy transition-colors placeholder:text-on-surface-variant/50 uppercase"
                                    id="coupon" name="code" value="{{ $coupon['code'] ?? '' }}" placeholder="Coupon (WELCOME10)" type="text" />
                                <button type="submit"
                                    class="absolute right-1.5 top-1.5 bottom-1.5 px-3 bg-cream-silk text-heritage-burgundy font-label-caps text-xs uppercase hover:bg-heritage-burgundy hover:text-white rounded-lg transition-colors font-bold cursor-pointer">
                                    Apply
                                </button>
                            </div>
                        </form>

                        <a href="{{ route('checkout.shipping') }}"
                            class="w-full bg-heritage-burgundy text-white font-label-caps text-xs uppercase tracking-wider py-4 rounded-xl hover:bg-primary-container transition-all flex justify-center items-center gap-2 shadow-[0px_4px_14px_rgba(96,0,24,0.2)] hover:shadow-lg cursor-pointer font-bold">
                            Proceed to Checkout
                            <span class="material-symbols-outlined text-lg">arrow_forward</span>
                        </a>

                        <p class="font-body-md text-[11px] text-center text-on-surface-variant mt-4 opacity-75">
                            100% Secure SSL Checkout &bull; Handcrafted Quality Guaranteed
                        </p>
                    </div>
                </div>
            </div>
        @else
            <!-- Empty Cart State -->
            <div class="text-center py-20 bg-surface-container-lowest rounded-2xl border border-border-subtle p-8 max-w-lg mx-auto shadow-xs">
                <div class="w-20 h-20 rounded-full bg-cream-silk text-heritage-burgundy flex items-center justify-center mx-auto mb-4 border border-muted-gold/30">
                    <span class="material-symbols-outlined text-4xl">shopping_bag</span>
                </div>
                <h2 class="font-headline-md text-2xl text-heritage-burgundy mb-2">Your Bag is Empty</h2>
                <p class="font-body-md text-xs text-on-surface-variant mb-6 max-w-xs mx-auto">
                    Explore our handcrafted royal collection of Lachas, Lehengas, and Festive wear to find your perfect look.
                </p>
                <a href="{{ route('collections') }}" class="inline-flex items-center gap-2 bg-heritage-burgundy text-white font-label-caps text-xs uppercase tracking-wider px-8 py-3.5 rounded-xl hover:bg-primary-container transition-all shadow-xs font-bold">
                    <span>Explore Collections</span>
                    <span class="material-symbols-outlined text-sm">arrow_forward</span>
                </a>
            </div>
        @endif
    </div>
</x-layouts.app>
