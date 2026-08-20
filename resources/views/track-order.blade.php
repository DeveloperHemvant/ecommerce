<x-layouts.app title="Live Order Tracking - Sonakshi Fashion Hub">
    <div class="max-w-4xl mx-auto py-8 md:py-14 space-y-10">
        <!-- Banner Header -->
        <div class="text-center space-y-2">
            <span class="font-label-caps text-xs uppercase tracking-widest text-muted-gold font-bold">Atelier Courier Dispatch</span>
            <h1 class="font-headline-lg text-3xl md:text-4xl text-heritage-burgundy">Track Your Heritage Order</h1>
            <p class="font-body-md text-xs sm:text-sm text-on-surface-variant max-w-lg mx-auto">Enter your Order Reference Number (e.g. ORD-8829) and registered phone number to track live atelier tailoring and courier dispatch.</p>
        </div>

        <!-- Tracking Search Form -->
        <div class="bg-surface-container-lowest rounded-2xl border border-border-subtle p-6 md:p-8 shadow-xs max-w-2xl mx-auto">
            <form action="{{ route('track.order') }}" method="GET" class="space-y-4">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block font-label-caps text-xs text-on-surface-variant uppercase mb-1.5 font-semibold" for="order">
                            Order Number <span class="text-error">*</span>
                        </label>
                        <input class="w-full bg-warm-ivory/60 border border-border-subtle rounded-xl px-4 py-3 font-body-md text-sm text-charcoal-text focus:border-heritage-burgundy focus:outline-none transition-colors uppercase font-data-tabular"
                            id="order" name="order" value="{{ $orderQuery ?? '' }}" placeholder="ORD-8829" required type="text" />
                    </div>

                    <div>
                        <label class="block font-label-caps text-xs text-on-surface-variant uppercase mb-1.5 font-semibold" for="phone">
                            Phone / WhatsApp (Optional)
                        </label>
                        <input class="w-full bg-warm-ivory/60 border border-border-subtle rounded-xl px-4 py-3 font-body-md text-sm text-charcoal-text focus:border-heritage-burgundy focus:outline-none transition-colors"
                            id="phone" name="phone" value="{{ $phoneQuery ?? '' }}" placeholder="+91 98765 43210" type="tel" />
                    </div>
                </div>

                <button type="submit" class="w-full bg-heritage-burgundy text-white font-label-caps text-xs uppercase tracking-wider py-3.5 rounded-xl hover:bg-primary-container transition-all flex items-center justify-center gap-2 font-bold cursor-pointer shadow-sm">
                    <span class="material-symbols-outlined text-base">search</span>
                    Track Order Live
                </button>
            </form>
        </div>

        @if($error)
            <div class="max-w-2xl mx-auto p-4 bg-red-50 border border-red-200 text-error text-xs rounded-xl flex items-center gap-2">
                <span class="material-symbols-outlined text-base">error</span>
                <span>{{ $error }}</span>
            </div>
        @endif

        <!-- Order Tracking Results Card -->
        @if($order)
            @php
                $statusSteps = ['processing' => 1, 'packed' => 2, 'shipped' => 3, 'delivered' => 4];
                $currentStep = $statusSteps[$order->status] ?? 1;
            @endphp
            <div class="bg-surface-container-lowest rounded-2xl border border-border-subtle p-6 md:p-10 shadow-xs space-y-8 max-w-3xl mx-auto">
                <!-- Status Header -->
                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3 border-b border-border-subtle pb-6">
                    <div>
                        <span class="font-label-caps text-[10px] uppercase text-muted-gold font-bold">Confirmed Order</span>
                        <h2 class="font-headline-md text-xl text-heritage-burgundy">#{{ $order->order_number }}</h2>
                        <p class="text-xs text-on-surface-variant mt-0.5">Recipient: <strong>{{ $order->customer_name }}</strong> &bull; {{ $order->city }}, {{ $order->state }}</p>
                    </div>

                    <div class="text-left sm:text-right">
                        @php
                            $statusStyles = [
                                'processing' => 'bg-blue-50 text-blue-800 border-blue-200',
                                'packed' => 'bg-purple-50 text-purple-800 border-purple-200',
                                'shipped' => 'bg-indigo-50 text-indigo-800 border-indigo-200',
                                'delivered' => 'bg-emerald-50 text-emerald-800 border-emerald-200',
                                'cancelled' => 'bg-red-50 text-error border-red-200',
                            ];
                            $style = $statusStyles[$order->status] ?? 'bg-gray-50 text-gray-800 border-gray-200';
                        @endphp
                        <span class="inline-block px-3 py-1 rounded-full text-xs font-label-caps uppercase font-bold border {{ $style }}">
                            {{ ucfirst($order->status) }}
                        </span>
                        <p class="text-[11px] text-on-surface-variant font-data-tabular mt-1">Placed: {{ $order->created_at->format('M d, Y') }}</p>
                    </div>
                </div>

                <!-- Timeline -->
                <div class="space-y-4">
                    <h3 class="font-title-lg text-xs font-bold uppercase text-heritage-burgundy tracking-wider">Live Transit Milestones</h3>
                    <div class="relative flex items-center justify-between px-2">
                        <div class="absolute left-4 right-4 top-1/2 -translate-y-1/2 h-1 bg-border-subtle z-0"></div>
                        <div class="absolute left-4 top-1/2 -translate-y-1/2 h-1 bg-heritage-burgundy z-0 transition-all"
                            style="width: calc({{ ($currentStep - 1) * 33.33 }}% - 8px);"></div>

                        <!-- 1 -->
                        <div class="relative z-10 text-center flex flex-col items-center">
                            <div class="w-9 h-9 rounded-full {{ $currentStep >= 1 ? 'bg-heritage-burgundy text-white' : 'bg-surface border border-border-subtle text-on-surface-variant' }} flex items-center justify-center text-xs font-bold shadow-xs">
                                <span class="material-symbols-outlined text-sm">receipt_long</span>
                            </div>
                            <span class="text-[11px] font-bold text-charcoal-text mt-2">Order Confirmed</span>
                        </div>

                        <!-- 2 -->
                        <div class="relative z-10 text-center flex flex-col items-center">
                            <div class="w-9 h-9 rounded-full {{ $currentStep >= 2 ? 'bg-heritage-burgundy text-white' : 'bg-surface border border-border-subtle text-on-surface-variant' }} flex items-center justify-center text-xs font-bold shadow-xs">
                                <span class="material-symbols-outlined text-sm">inventory</span>
                            </div>
                            <span class="text-[11px] font-bold text-charcoal-text mt-2">Quality &amp; Pack</span>
                        </div>

                        <!-- 3 -->
                        <div class="relative z-10 text-center flex flex-col items-center">
                            <div class="w-9 h-9 rounded-full {{ $currentStep >= 3 ? 'bg-heritage-burgundy text-white' : 'bg-surface border border-border-subtle text-on-surface-variant' }} flex items-center justify-center text-xs font-bold shadow-xs">
                                <span class="material-symbols-outlined text-sm">local_shipping</span>
                            </div>
                            <span class="text-[11px] font-bold text-charcoal-text mt-2">Dispatched Air</span>
                        </div>

                        <!-- 4 -->
                        <div class="relative z-10 text-center flex flex-col items-center">
                            <div class="w-9 h-9 rounded-full {{ $currentStep >= 4 ? 'bg-heritage-burgundy text-white' : 'bg-surface border border-border-subtle text-on-surface-variant' }} flex items-center justify-center text-xs font-bold shadow-xs">
                                <span class="material-symbols-outlined text-sm">home</span>
                            </div>
                            <span class="text-[11px] font-bold text-charcoal-text mt-2">Delivered</span>
                        </div>
                    </div>
                </div>

                <!-- Courier Information Box -->
                <div class="p-4 bg-warm-ivory/60 rounded-xl border border-border-subtle text-xs space-y-2">
                    <div class="flex flex-wrap items-center justify-between gap-2">
                        <div>
                            <span class="text-on-surface-variant">Courier Service:</span>
                            <span class="font-bold text-charcoal-text ml-1">{{ $order->courier_name ?? 'BlueDart Express / Insured Air' }}</span>
                        </div>
                        <div>
                            <span class="text-on-surface-variant">Airway Bill (AWB):</span>
                            <span class="font-mono font-bold text-heritage-burgundy ml-1">{{ $order->tracking_number ?? 'AWB-' . substr(md5($order->id), 0, 8) }}</span>
                        </div>
                    </div>
                    @if($order->tracking_url)
                        <div class="pt-2 border-t border-border-subtle/60">
                            <a href="{{ $order->tracking_url }}" target="_blank" class="text-heritage-burgundy font-bold underline inline-flex items-center gap-1">
                                <span>Track on Carrier Dispatch Portal</span>
                                <span class="material-symbols-outlined text-xs">open_in_new</span>
                            </a>
                        </div>
                    @endif
                </div>

                <!-- Items in this parcel -->
                <div class="space-y-3 pt-2">
                    <h4 class="font-title-lg text-xs font-bold uppercase text-heritage-burgundy">Items in this Package</h4>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        @foreach($order->items as $item)
                            <div class="flex items-center gap-3 p-3 bg-surface-container-low rounded-xl border border-border-subtle">
                                <div class="w-12 h-16 rounded overflow-hidden bg-surface shrink-0 border border-border-subtle">
                                    <img src="{{ $item->product_image ?? ($item->product->main_image ?? '') }}" alt="{{ $item->product_name }}" class="w-full h-full object-cover" />
                                </div>
                                <div class="min-w-0 flex-1 text-xs">
                                    <p class="font-semibold text-charcoal-text truncate">{{ $item->product_name }}</p>
                                    <p class="text-[11px] text-on-surface-variant">Size: <strong>{{ $item->size }}</strong> &bull; Qty: {{ $item->quantity }}</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        @endif
    </div>
</x-layouts.app>
