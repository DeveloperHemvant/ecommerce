<x-layouts.admin title="Order #{{ $order->order_number }} Details - Sonakshi Admin" active="orders">
    <div class="space-y-6 max-w-[1400px]">
        <!-- Top Navigation & Actions -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div class="flex items-center gap-3">
                <a href="{{ route('admin.orders.index') }}" class="text-on-surface-variant hover:text-heritage-burgundy transition-colors p-1" title="Back to Orders">
                    <span class="material-symbols-outlined text-xl">arrow_back</span>
                </a>
                <div>
                    <div class="flex items-center gap-3">
                        <h1 class="font-headline-lg text-heritage-burgundy">Order #{{ $order->order_number }}</h1>
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
                        <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-xs font-label-caps uppercase font-bold border {{ $style }}">
                            {{ ucfirst($order->status) }}
                        </span>
                    </div>
                    <p class="font-body-md text-xs text-on-surface-variant mt-0.5">Placed on {{ $order->created_at->format('M d, Y \a\t h:i A') }}</p>
                </div>
            </div>

            <div class="flex items-center gap-3">
                <button type="button" onclick="window.print()" class="px-4 py-2.5 bg-surface-container border border-border-subtle rounded-xl text-xs font-label-caps uppercase font-bold text-charcoal-text hover:bg-surface-container-high transition-colors flex items-center gap-1.5 cursor-pointer">
                    <span class="material-symbols-outlined text-base">print</span>
                    <span>Print Invoice</span>
                </button>
            </div>
        </div>

        @if(session('success'))
            <div class="p-4 bg-green-50 border border-green-200 text-green-800 text-xs rounded-xl flex items-center gap-2">
                <span class="material-symbols-outlined text-base">check_circle</span>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
            <!-- Left Column: Items & Fulfillment (8 Cols) -->
            <div class="lg:col-span-8 space-y-6">
                <!-- Status & Courier Dispatch Updater Card -->
                <div class="bg-surface-container-lowest rounded-2xl border border-border-subtle p-6 shadow-xs space-y-4">
                    <h2 class="font-title-lg text-sm font-bold text-heritage-burgundy uppercase font-label-caps border-b border-border-subtle pb-3">
                        Fulfillment &amp; Courier Dispatch Details
                    </h2>

                    <form action="{{ route('admin.orders.status', $order) }}" method="POST" class="space-y-4">
                        @csrf
                        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-3">
                            <div>
                                <label class="block text-[11px] font-label-caps uppercase text-on-surface-variant font-semibold mb-1">Status</label>
                                <select name="status" class="w-full bg-warm-ivory/60 border border-border-subtle rounded-xl px-3 py-2 text-xs text-charcoal-text focus:border-heritage-burgundy focus:outline-none">
                                    <option value="processing" {{ $order->status === 'processing' ? 'selected' : '' }}>Processing (Tailoring)</option>
                                    <option value="packed" {{ $order->status === 'packed' ? 'selected' : '' }}>Packed (QC Inspected)</option>
                                    <option value="shipped" {{ $order->status === 'shipped' ? 'selected' : '' }}>Shipped (Dispatched)</option>
                                    <option value="delivered" {{ $order->status === 'delivered' ? 'selected' : '' }}>Delivered</option>
                                    <option value="cancelled" {{ $order->status === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                                </select>
                            </div>

                            <div>
                                <label class="block text-[11px] font-label-caps uppercase text-on-surface-variant font-semibold mb-1">Courier Partner</label>
                                <input type="text" name="courier_name" value="{{ $order->courier_name }}" placeholder="e.g. BlueDart Express"
                                    class="w-full bg-warm-ivory/60 border border-border-subtle rounded-xl px-3 py-2 text-xs text-charcoal-text focus:border-heritage-burgundy focus:outline-none" />
                            </div>

                            <div>
                                <label class="block text-[11px] font-label-caps uppercase text-on-surface-variant font-semibold mb-1">AWB / Tracking Number</label>
                                <input type="text" name="tracking_number" value="{{ $order->tracking_number }}" placeholder="e.g. AWB-9948291"
                                    class="w-full bg-warm-ivory/60 border border-border-subtle rounded-xl px-3 py-2 text-xs text-charcoal-text focus:border-heritage-burgundy focus:outline-none font-mono" />
                            </div>

                            <div>
                                <label class="block text-[11px] font-label-caps uppercase text-on-surface-variant font-semibold mb-1">Tracking URL</label>
                                <input type="url" name="tracking_url" value="{{ $order->tracking_url }}" placeholder="https://..."
                                    class="w-full bg-warm-ivory/60 border border-border-subtle rounded-xl px-3 py-2 text-xs text-charcoal-text focus:border-heritage-burgundy focus:outline-none" />
                            </div>
                        </div>

                        <div class="flex justify-end">
                            <button type="submit" class="bg-heritage-burgundy text-white font-label-caps text-xs uppercase tracking-wider px-6 py-2.5 rounded-xl hover:bg-primary-container transition-all font-bold cursor-pointer shadow-xs">
                                Update Fulfillment &amp; Tracking
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Items Ordered Table with Custom Fit Specs -->
                <div class="bg-surface-container-lowest rounded-2xl border border-border-subtle shadow-xs overflow-hidden">
                    <div class="p-5 border-b border-border-subtle flex justify-between items-center bg-warm-ivory/40">
                        <h3 class="font-headline-md text-base text-charcoal-text">Ordered Line Items ({{ $order->items->count() }})</h3>
                        <span class="text-xs font-label-caps uppercase text-on-surface-variant font-bold">Standard Free Delivery</span>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="border-b border-border-subtle text-[11px] font-label-caps text-on-surface-variant uppercase bg-surface-container-low">
                                    <th class="py-3 px-5">Product Ensemble</th>
                                    <th class="py-3 px-5">SKU &amp; Tailoring</th>
                                    <th class="py-3 px-5">Size / Color</th>
                                    <th class="py-3 px-5">Price</th>
                                    <th class="py-3 px-5">Qty</th>
                                    <th class="py-3 px-5 text-right">Total</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-border-subtle text-xs font-body-md">
                                @foreach($order->items as $item)
                                    <tr>
                                        <td class="py-4 px-5">
                                            <div class="flex items-center gap-3">
                                                <div class="w-12 h-16 rounded-lg overflow-hidden bg-surface shrink-0 border border-border-subtle">
                                                    <img src="{{ $item->product_image ?? ($item->product->main_image ?? '') }}" alt="{{ $item->product_name }}" class="w-full h-full object-cover" />
                                                </div>
                                                <div class="min-w-0">
                                                    <p class="font-semibold text-charcoal-text truncate">{{ $item->product_name }}</p>
                                                    @if($item->product)
                                                        <a href="{{ route('product.detail', $item->product->slug) }}" target="_blank" class="text-[11px] text-heritage-burgundy hover:underline">
                                                            View Product Page &rarr;
                                                        </a>
                                                    @endif
                                                </div>
                                            </div>
                                        </td>
                                        <td class="py-4 px-5 font-data-tabular">
                                            <code>{{ $item->product_sku ?? 'N/A' }}</code>

                                            @if(!empty($item->custom_measurements) && is_array($item->custom_measurements))
                                                <div class="mt-2 p-2 bg-amber-50 rounded-lg border border-amber-200 text-[10px] space-y-0.5 max-w-xs">
                                                    <p class="font-bold text-amber-900 uppercase">Atelier Custom Specs:</p>
                                                    @foreach($item->custom_measurements as $mKey => $mVal)
                                                        <p class="text-amber-800"><span class="capitalize">{{ str_replace('_', ' ', $mKey) }}</span>: <strong>{{ $mVal }} in</strong></p>
                                                    @endforeach
                                                </div>
                                            @endif
                                        </td>
                                        <td class="py-4 px-5">
                                            <span class="font-bold text-charcoal-text">{{ $item->size ?? 'Standard' }}</span>
                                            @if($item->color)
                                                <span class="text-on-surface-variant block text-[11px]">{{ $item->color }}</span>
                                            @endif
                                        </td>
                                        <td class="py-4 px-5 font-data-tabular">
                                            {{ $item->formatted_price }}
                                        </td>
                                        <td class="py-4 px-5 font-bold">
                                            {{ $item->quantity }}
                                        </td>
                                        <td class="py-4 px-5 font-data-tabular font-bold text-right text-heritage-burgundy">
                                            {{ $item->formatted_total }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Totals Breakdown -->
                    <div class="p-6 bg-warm-ivory/30 border-t border-border-subtle space-y-2 text-xs font-body-md">
                        <div class="flex justify-between text-on-surface-variant">
                            <span>Subtotal</span>
                            <span class="font-data-tabular font-semibold text-charcoal-text">{{ $order->formatted_subtotal }}</span>
                        </div>
                        @if($order->discount > 0)
                            <div class="flex justify-between text-emerald-800 font-semibold">
                                <span>Promotional Discount</span>
                                <span class="font-data-tabular">-{{ $order->formatted_discount }}</span>
                            </div>
                        @endif
                        <div class="flex justify-between text-on-surface-variant">
                            <span>Shipping &amp; Insurance</span>
                            <span class="text-emerald-700 font-semibold">FREE</span>
                        </div>
                        <div class="border-t border-border-subtle pt-3 flex justify-between items-center text-sm font-bold">
                            <span class="text-charcoal-text font-title-lg">Grand Total</span>
                            <span class="font-headline-md text-lg text-heritage-burgundy font-bold">{{ $order->formatted_total }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Column: Customer & Delivery Cards (4 Cols) -->
            <div class="lg:col-span-4 space-y-6">
                <!-- Customer Profile Card -->
                <div class="bg-surface-container-lowest rounded-2xl border border-border-subtle p-6 shadow-xs space-y-4">
                    <div class="flex justify-between items-center border-b border-border-subtle pb-3">
                        <h3 class="font-title-lg text-xs font-bold text-heritage-burgundy uppercase font-label-caps">Customer Profile</h3>
                        @if($order->user)
                            <a href="{{ route('admin.customers.show', $order->user) }}" class="text-xs font-label-caps text-heritage-burgundy hover:underline font-bold">View History &rarr;</a>
                        @endif
                    </div>

                    <div class="space-y-2 text-xs">
                        <p class="font-bold text-charcoal-text text-sm">{{ $order->customer_name }}</p>
                        <p class="text-on-surface-variant flex items-center gap-1.5">
                            <span class="material-symbols-outlined text-sm">mail</span>
                            {{ $order->customer_email }}
                        </p>
                        <p class="text-on-surface-variant flex items-center gap-1.5">
                            <span class="material-symbols-outlined text-sm">call</span>
                            {{ $order->customer_phone }}
                        </p>
                    </div>
                </div>

                <!-- Shipping Destination Card -->
                <div class="bg-surface-container-lowest rounded-2xl border border-border-subtle p-6 shadow-xs space-y-3">
                    <h3 class="font-title-lg text-xs font-bold text-heritage-burgundy uppercase font-label-caps border-b border-border-subtle pb-3">
                        Shipping Address
                    </h3>
                    <div class="text-xs text-on-surface-variant space-y-1 leading-relaxed">
                        <p class="font-semibold text-charcoal-text">{{ $order->customer_name }}</p>
                        <p>{{ $order->shipping_address }}</p>
                        <p>{{ $order->city }}, {{ $order->state }} - {{ $order->postal_code }}</p>
                        <p class="font-semibold text-charcoal-text">{{ $order->country }}</p>
                    </div>
                </div>

                <!-- Payment Details Card -->
                <div class="bg-surface-container-lowest rounded-2xl border border-border-subtle p-6 shadow-xs space-y-3">
                    <h3 class="font-title-lg text-xs font-bold text-heritage-burgundy uppercase font-label-caps border-b border-border-subtle pb-3">
                        Payment Information
                    </h3>
                    <div class="text-xs space-y-2">
                        <div class="flex justify-between items-center">
                            <span class="text-on-surface-variant">Method:</span>
                            <span class="font-semibold text-charcoal-text">{{ $order->payment_method }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-on-surface-variant">Status:</span>
                            <span class="px-2 py-0.5 rounded text-[11px] font-bold {{ $order->payment_status === 'paid' ? 'bg-emerald-50 text-emerald-800' : 'bg-amber-50 text-amber-800' }}">
                                {{ ucfirst($order->payment_status) }}
                            </span>
                        </div>
                        @if($order->transaction_id)
                            <div class="flex justify-between items-center">
                                <span class="text-on-surface-variant">Txn ID:</span>
                                <code class="font-data-tabular">{{ $order->transaction_id }}</code>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layouts.admin>
