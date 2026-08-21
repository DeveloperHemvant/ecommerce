<x-layouts.app title="Order Confirmed - Sonakshi Fashion Hub">
    <div class="max-w-4xl mx-auto py-8 md:py-12 space-y-8">
        <!-- Success Header Banner -->
        <div class="text-center bg-surface-container-lowest rounded-2xl border border-border-subtle p-8 md:p-12 shadow-xs space-y-4">
            <div class="w-20 h-20 rounded-full bg-emerald-50 text-emerald-700 flex items-center justify-center mx-auto border-2 border-emerald-200">
                <span class="material-symbols-outlined text-4xl" style="font-variation-settings: 'FILL' 1;">check_circle</span>
            </div>

            <div>
                <span class="font-label-caps text-xs text-muted-gold uppercase tracking-widest font-bold block mb-1">
                    Order Placed Successfully
                </span>
                <h1 class="font-headline-lg text-3xl text-heritage-burgundy">
                    Thank You for Your Order!
                </h1>
                <p class="font-body-md text-sm text-on-surface-variant mt-2 max-w-md mx-auto">
                    We've received your order. Handcrafters at our atelier are preparing your ensemble for royal dispatch.
                </p>
            </div>

            <!-- Order Number Badge -->
            <div class="inline-flex items-center gap-3 px-5 py-2.5 bg-cream-silk rounded-full border border-muted-gold/40 text-charcoal-text font-title-lg text-sm">
                <span class="text-on-surface-variant">Order Reference:</span>
                <strong class="text-heritage-burgundy font-data-tabular font-bold">{{ $order->order_number }}</strong>
            </div>
        </div>

        <!-- Order Summary Card -->
        <div class="bg-surface-container-lowest rounded-2xl border border-border-subtle p-6 md:p-8 shadow-xs space-y-6">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between border-b border-border-subtle pb-4 gap-2">
                <div>
                    <h2 class="font-headline-md text-lg text-charcoal-text">Ensemble Items Ordered</h2>
                    <p class="text-xs text-on-surface-variant">Date: {{ $order->created_at->format('M d, Y - h:i A') }}</p>
                </div>
                <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-emerald-50 text-emerald-800 text-xs font-label-caps uppercase font-bold rounded-full border border-emerald-200 self-start sm:self-auto">
                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-600"></span>
                    {{ ucfirst($order->status) }}
                </span>
            </div>

            <!-- Items List -->
            <div class="space-y-4 divide-y divide-border-subtle">
                @foreach($order->items as $item)
                    <div class="pt-4 first:pt-0 flex gap-4 items-center">
                        <div class="w-16 h-20 rounded-xl overflow-hidden bg-surface shrink-0 border border-border-subtle">
                            <img src="{{ $item->product_image ?? ($item->product->main_image ?? '') }}" alt="{{ $item->product_name }}" class="w-full h-full object-cover" />
                        </div>
                        <div class="flex-1 min-w-0">
                            <h3 class="font-headline-md text-sm text-charcoal-text truncate">{{ $item->product_name }}</h3>
                            <p class="text-xs text-on-surface-variant mt-0.5">
                                Size: <strong>{{ $item->size ?? 'Standard' }}</strong>
                                @if(!empty($item->color))
                                    &bull; Color: <strong>{{ $item->color }}</strong>
                                @endif
                                &bull; Qty: <strong>{{ $item->quantity }}</strong>
                            </p>
                            <p class="font-data-tabular text-xs font-bold text-heritage-burgundy mt-1">₹{{ number_format($item->total) }}</p>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Price Breakdown -->
            <div class="border-t border-border-subtle pt-4 space-y-2 text-xs font-body-md">
                <div class="flex justify-between text-on-surface-variant">
                    <span>Subtotal</span>
                    <span class="font-data-tabular font-semibold text-charcoal-text">{{ $order->formatted_subtotal }}</span>
                </div>
                @if($order->discount > 0)
                    <div class="flex justify-between text-emerald-800 font-semibold">
                        <span>Discount Applied</span>
                        <span class="font-data-tabular">-{{ $order->formatted_discount }}</span>
                    </div>
                @endif
                <div class="flex justify-between text-on-surface-variant">
                    <span>Insured Shipping</span>
                    <span class="text-emerald-700 font-semibold">FREE</span>
                </div>
                <div class="border-t border-border-subtle pt-3 flex justify-between items-center text-base font-bold text-heritage-burgundy">
                    <span class="text-charcoal-text font-title-lg">Grand Total Paid</span>
                    <span class="font-headline-md text-xl">{{ $order->formatted_total }}</span>
                </div>
            </div>

            <!-- Delivery & Payment Information -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 border-t border-border-subtle pt-6 text-xs">
                <div class="p-4 bg-warm-ivory/50 rounded-xl border border-border-subtle">
                    <span class="font-label-caps uppercase text-on-surface-variant font-bold block mb-1">Delivery Destination</span>
                    <p class="font-semibold text-charcoal-text">{{ $order->customer_name }}</p>
                    <p class="text-on-surface-variant mt-0.5">{{ $order->shipping_address }}, {{ $order->city }}, {{ $order->state }} - {{ $order->postal_code }}</p>
                    <p class="text-on-surface-variant mt-1">Phone: {{ $order->customer_phone }}</p>
                </div>

                <div class="p-4 bg-warm-ivory/50 rounded-xl border border-border-subtle">
                    <span class="font-label-caps uppercase text-on-surface-variant font-bold block mb-1">Payment Method</span>
                    <p class="font-semibold text-charcoal-text">{{ $order->payment_method }}</p>
                    <p class="text-emerald-800 font-semibold mt-0.5">Status: {{ ucfirst($order->payment_status) }}</p>
                    @if($order->transaction_id)
                        <p class="text-on-surface-variant mt-1">Txn ID: <code>{{ $order->transaction_id }}</code></p>
                    @endif
                </div>
            </div>
        </div>

        <!-- Action Links -->
        <div class="flex flex-col sm:flex-row justify-center items-center gap-4">
            <a href="{{ route('collections') }}"
                class="w-full sm:w-auto bg-heritage-burgundy text-white font-label-caps text-xs uppercase tracking-wider px-8 py-4 rounded-xl hover:bg-primary-container transition-all flex items-center justify-center gap-2 font-bold shadow-xs">
                <span class="material-symbols-outlined text-base">shopping_bag</span>
                Continue Shopping
            </a>
            <button type="button" onclick="window.print()"
                class="w-full sm:w-auto border border-border-subtle bg-surface-container-lowest text-charcoal-text font-label-caps text-xs uppercase tracking-wider px-6 py-4 rounded-xl hover:bg-surface-container transition-colors flex items-center justify-center gap-2 font-semibold cursor-pointer">
                <span class="material-symbols-outlined text-base">print</span>
                Print Receipt
            </button>
        </div>
    </div>
</x-layouts.app>
