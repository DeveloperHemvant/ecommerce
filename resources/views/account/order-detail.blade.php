<x-layouts.app title="Order #{{ $order->order_number }} - Sonakshi Fashion Hub">
    <div class="max-w-6xl mx-auto py-6 md:py-10 space-y-8 print:p-0 print:m-0">
        <!-- Top Action Bar -->
        <div class="flex items-center justify-between gap-4 print:hidden">
            <a href="{{ route('account.orders') }}" class="inline-flex items-center gap-1 text-xs font-label-caps uppercase text-on-surface-variant hover:text-heritage-burgundy transition-colors font-bold">
                <span class="material-symbols-outlined text-sm">arrow_back</span>
                <span>Back to My Orders</span>
            </a>
            <div class="flex items-center gap-3">
                @if($order->tracking_number)
                    <a href="{{ route('track.order', ['order' => $order->order_number]) }}" class="px-4 py-2 bg-cream-silk text-heritage-burgundy rounded-xl border border-muted-gold/40 text-xs font-label-caps uppercase font-bold hover:bg-heritage-burgundy hover:text-white transition-colors">
                        Live Tracking Link
                    </a>
                @endif
                <button onclick="window.print()" class="px-4 py-2 bg-surface-container text-charcoal-text rounded-xl border border-border-subtle text-xs font-label-caps uppercase font-bold hover:bg-surface-container-high transition-colors flex items-center gap-1.5 cursor-pointer">
                    <span class="material-symbols-outlined text-sm">print</span>
                    <span>Print Order Invoice</span>
                </button>
            </div>
        </div>

        <!-- Printable Invoice Container -->
        <div class="bg-surface-container-lowest rounded-2xl border border-border-subtle p-6 md:p-10 shadow-xs space-y-8 print:border-none print:shadow-none print:p-0">
            <!-- Header & Brand -->
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 border-b border-border-subtle pb-6">
                <div>
                    <span class="font-label-caps text-xs uppercase tracking-widest text-muted-gold font-bold">Luxury Ethnic Atelier</span>
                    <h1 class="font-headline-lg text-2xl text-heritage-burgundy">Sonakshi Fashion Hub</h1>
                    <p class="font-body-md text-xs text-on-surface-variant mt-0.5">Order Invoice &bull; #{{ $order->order_number }}</p>
                </div>
                <div class="text-left sm:text-right text-xs text-on-surface-variant space-y-1">
                    <p class="font-data-tabular">Date: <strong>{{ $order->created_at->format('M d, Y, h:i A') }}</strong></p>
                    <p>Status: <strong class="uppercase text-heritage-burgundy font-bold">{{ $order->status }}</strong></p>
                    <p>Payment: <strong class="uppercase text-charcoal-text">{{ $order->payment_method }} ({{ $order->payment_status }})</strong></p>
                </div>
            </div>

            <!-- Fulfillment Progress Timeline -->
            @php
                $statusSteps = ['processing' => 1, 'packed' => 2, 'shipped' => 3, 'delivered' => 4];
                $currentStep = $statusSteps[$order->status] ?? 1;
            @endphp
            <div class="p-6 bg-warm-ivory/50 rounded-2xl border border-border-subtle print:hidden space-y-4">
                <h3 class="font-title-lg text-xs font-bold uppercase text-heritage-burgundy tracking-wider">Order Journey</h3>
                
                <div class="relative flex items-center justify-between">
                    <div class="absolute left-0 top-1/2 -translate-y-1/2 h-1 bg-border-subtle w-full z-0"></div>
                    <div class="absolute left-0 top-1/2 -translate-y-1/2 h-1 bg-heritage-burgundy z-0 transition-all"
                        style="width: {{ ($currentStep - 1) * 33.33 }}%;"></div>

                    <!-- Step 1: Placed -->
                    <div class="relative z-10 text-center flex flex-col items-center">
                        <div class="w-8 h-8 rounded-full {{ $currentStep >= 1 ? 'bg-heritage-burgundy text-white' : 'bg-surface border border-border-subtle text-on-surface-variant' }} flex items-center justify-center text-xs font-bold shadow-xs">
                            <span class="material-symbols-outlined text-sm">receipt_long</span>
                        </div>
                        <span class="text-[11px] font-bold text-charcoal-text mt-1.5">Placed</span>
                    </div>

                    <!-- Step 2: Tailoring / Packed -->
                    <div class="relative z-10 text-center flex flex-col items-center">
                        <div class="w-8 h-8 rounded-full {{ $currentStep >= 2 ? 'bg-heritage-burgundy text-white' : 'bg-surface border border-border-subtle text-on-surface-variant' }} flex items-center justify-center text-xs font-bold shadow-xs">
                            <span class="material-symbols-outlined text-sm">inventory</span>
                        </div>
                        <span class="text-[11px] font-bold text-charcoal-text mt-1.5">Packed &amp; QC</span>
                    </div>

                    <!-- Step 3: Shipped -->
                    <div class="relative z-10 text-center flex flex-col items-center">
                        <div class="w-8 h-8 rounded-full {{ $currentStep >= 3 ? 'bg-heritage-burgundy text-white' : 'bg-surface border border-border-subtle text-on-surface-variant' }} flex items-center justify-center text-xs font-bold shadow-xs">
                            <span class="material-symbols-outlined text-sm">local_shipping</span>
                        </div>
                        <span class="text-[11px] font-bold text-charcoal-text mt-1.5">Shipped</span>
                    </div>

                    <!-- Step 4: Delivered -->
                    <div class="relative z-10 text-center flex flex-col items-center">
                        <div class="w-8 h-8 rounded-full {{ $currentStep >= 4 ? 'bg-heritage-burgundy text-white' : 'bg-surface border border-border-subtle text-on-surface-variant' }} flex items-center justify-center text-xs font-bold shadow-xs">
                            <span class="material-symbols-outlined text-sm">home</span>
                        </div>
                        <span class="text-[11px] font-bold text-charcoal-text mt-1.5">Delivered</span>
                    </div>
                </div>

                @if($order->courier_name || $order->tracking_number)
                    <div class="mt-4 pt-3 border-t border-border-subtle text-xs flex flex-wrap items-center justify-between gap-2 text-on-surface-variant">
                        <span>Courier Partner: <strong class="text-charcoal-text font-semibold">{{ $order->courier_name ?? 'Standard Insured Air Cargo' }}</strong></span>
                        <span>AWB / Tracking Number: <strong class="text-heritage-burgundy font-bold font-data-tabular">{{ $order->tracking_number ?? 'In Transit' }}</strong></span>
                        @if($order->tracking_url)
                            <a href="{{ $order->tracking_url }}" target="_blank" class="text-heritage-burgundy underline font-bold">Track on Courier Website &rarr;</a>
                        @endif
                    </div>
                @endif
            </div>

            <!-- Customer & Delivery Details Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 text-xs font-body-md border-b border-border-subtle pb-6">
                <div>
                    <h4 class="font-title-lg text-xs font-bold uppercase text-heritage-burgundy mb-2 tracking-wider">Billed &amp; Shipped To</h4>
                    <p class="font-semibold text-charcoal-text text-sm">{{ $order->customer_name }}</p>
                    <p class="text-on-surface-variant mt-1">{{ $order->shipping_address }}</p>
                    <p class="text-on-surface-variant">{{ $order->city }}, {{ $order->state }} - {{ $order->postal_code }}</p>
                    <p class="text-on-surface-variant">{{ $order->country }}</p>
                    <p class="text-on-surface-variant mt-1.5">Phone: {{ $order->customer_phone ?? 'N/A' }}</p>
                    <p class="text-on-surface-variant">Email: {{ $order->customer_email }}</p>
                </div>

                <div class="space-y-2 md:text-right">
                    <h4 class="font-title-lg text-xs font-bold uppercase text-heritage-burgundy mb-2 tracking-wider">Payment Information</h4>
                    <p>Method: <strong class="uppercase text-charcoal-text">{{ $order->payment_method }}</strong></p>
                    <p>Payment Status: <span class="px-2 py-0.5 rounded bg-emerald-50 text-emerald-800 font-bold uppercase text-[10px]">{{ $order->payment_status }}</span></p>
                    <p class="font-data-tabular">Txn ID: <span class="font-mono text-on-surface-variant">{{ $order->transaction_id ?? 'TXN-'.substr(md5($order->id), 0, 10) }}</span></p>
                    @if($order->estimated_delivery)
                        <p class="text-emerald-800 font-semibold mt-2">Estimated Delivery: {{ $order->estimated_delivery->format('M d, Y') }}</p>
                    @endif
                </div>
            </div>

            <!-- Itemized Products Table -->
            <div class="space-y-4">
                <h4 class="font-title-lg text-xs font-bold uppercase text-heritage-burgundy tracking-wider">Ordered Garments &amp; Custom Specs</h4>
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs border-collapse">
                        <thead>
                            <tr class="bg-warm-ivory/60 border-y border-border-subtle font-label-caps text-on-surface-variant">
                                <th class="py-3 px-4 uppercase font-semibold">Garment</th>
                                <th class="py-3 px-4 uppercase font-semibold">SKU &amp; Size</th>
                                <th class="py-3 px-4 uppercase font-semibold text-right">Price</th>
                                <th class="py-3 px-4 uppercase font-semibold text-center">Qty</th>
                                <th class="py-3 px-4 uppercase font-semibold text-right">Total</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-border-subtle">
                            @foreach($order->items as $item)
                                <tr>
                                    <td class="py-4 px-4">
                                        <div class="flex items-center gap-3">
                                            <div class="w-12 h-16 rounded overflow-hidden bg-surface shrink-0 border border-border-subtle">
                                                <img src="{{ $item->product_image ?? ($item->product->main_image ?? '') }}" alt="{{ $item->product_name }}" class="w-full h-full object-cover" />
                                            </div>
                                            <div>
                                                <p class="font-bold text-charcoal-text">{{ $item->product_name }}</p>
                                                @if($item->product)
                                                    <a href="{{ route('product.detail', $item->product->slug) }}" class="text-[11px] text-heritage-burgundy hover:underline print:hidden">View Product &rarr;</a>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                    <td class="py-4 px-4">
                                        <p class="font-mono text-on-surface-variant">{{ $item->product_sku }}</p>
                                        <span class="inline-block px-2 py-0.5 rounded bg-cream-silk border border-muted-gold/30 text-heritage-burgundy font-bold text-[10px] uppercase mt-1">
                                            Size: {{ $item->size }}
                                        </span>

                                        <!-- Render Custom Measurements if Present -->
                                        @if(!empty($item->custom_measurements) && is_array($item->custom_measurements))
                                            <div class="mt-2 p-2 bg-amber-50 rounded-lg border border-amber-200 text-[10px] space-y-0.5">
                                                <p class="font-bold text-amber-900 uppercase">Custom Atelier Measurements:</p>
                                                @foreach($item->custom_measurements as $mKey => $mVal)
                                                    <p class="text-amber-800"><span class="capitalize">{{ str_replace('_', ' ', $mKey) }}</span>: <strong>{{ $mVal }} in</strong></p>
                                                @endforeach
                                            </div>
                                        @endif
                                    </td>
                                    <td class="py-4 px-4 text-right font-data-tabular">{{ $item->formatted_price }}</td>
                                    <td class="py-4 px-4 text-center font-data-tabular font-bold">{{ $item->quantity }}</td>
                                    <td class="py-4 px-4 text-right font-data-tabular font-bold text-charcoal-text">{{ $item->formatted_total }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Financial Totals Breakdown -->
            <div class="flex justify-end pt-4 border-t border-border-subtle">
                <div class="w-full sm:w-64 space-y-2 text-xs font-body-md">
                    <div class="flex justify-between text-on-surface-variant">
                        <span>Subtotal:</span>
                        <span class="font-data-tabular font-bold text-charcoal-text">{{ $order->formatted_subtotal }}</span>
                    </div>
                    @if($order->discount > 0)
                        <div class="flex justify-between text-emerald-800">
                            <span>Promotional Discount:</span>
                            <span class="font-data-tabular font-bold">-{{ $order->formatted_discount }}</span>
                        </div>
                    @endif
                    <div class="flex justify-between text-on-surface-variant">
                        <span>Shipping &amp; Insurance:</span>
                        <span class="font-data-tabular font-bold text-emerald-800">FREE</span>
                    </div>
                    <div class="flex justify-between text-sm font-headline-md font-bold text-heritage-burgundy pt-2 border-t border-border-subtle">
                        <span>Grand Total:</span>
                        <span class="font-data-tabular">{{ $order->formatted_total }}</span>
                    </div>
                </div>
            </div>

            <!-- Footer Message -->
            <div class="pt-6 border-t border-border-subtle text-center text-xs text-on-surface-variant">
                <p class="font-serif italic text-heritage-burgundy">Thank you for choosing Sonakshi Fashion Hub. Handcrafted with timeless Banarasi heritage.</p>
                <p class="text-[10px] mt-1">For bespoke alterations or inquiries, email atelier@sonakshifashionhub.com or WhatsApp +91 98765 43210.</p>
            </div>
        </div>
    </div>
</x-layouts.app>
