<x-layouts.admin title="Orders Management - Sonakshi Admin" active="orders">
    <div class="space-y-6 max-w-[1400px]">
        <!-- Header -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <h1 class="font-headline-lg text-heritage-burgundy">Customer Orders</h1>
                <p class="font-body-md text-xs text-on-surface-variant mt-1">Track, process, and manage customer orders and shipment fulfillment.</p>
            </div>
        </div>

        @if(session('success'))
            <div class="p-4 bg-green-50 border border-green-200 text-green-800 text-xs rounded-xl flex items-center gap-2">
                <span class="material-symbols-outlined text-base">check_circle</span>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        <!-- Filter & Search Bar -->
        <div class="bg-surface-container-lowest rounded-2xl border border-border-subtle p-4 shadow-xs flex flex-col md:flex-row items-center justify-between gap-4">
            <!-- Status Tabs -->
            <div class="flex items-center gap-2 overflow-x-auto w-full md:w-auto pb-2 md:pb-0">
                @php
                    $currentStatus = request('status', 'all');
                    $statuses = [
                        'all' => 'All Orders',
                        'processing' => 'Processing',
                        'packed' => 'Packed',
                        'shipped' => 'Shipped',
                        'delivered' => 'Delivered',
                        'cancelled' => 'Cancelled',
                    ];
                @endphp

                @foreach($statuses as $key => $label)
                    <a href="{{ route('admin.orders.index', array_merge(request()->query(), ['status' => $key])) }}"
                        class="px-3.5 py-1.5 rounded-lg text-xs font-label-caps uppercase whitespace-nowrap transition-colors {{ $currentStatus === $key ? 'bg-heritage-burgundy text-white font-bold' : 'text-on-surface-variant hover:bg-surface-container hover:text-charcoal-text' }}">
                        {{ $label }}
                    </a>
                @endforeach
            </div>

            <!-- Search Form -->
            <form action="{{ route('admin.orders.index') }}" method="GET" class="relative w-full md:w-72">
                @if(request('status'))
                    <input type="hidden" name="status" value="{{ request('status') }}" />
                @endif
                <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-on-surface-variant text-base">search</span>
                <input class="w-full bg-warm-ivory/60 border border-border-subtle rounded-xl pl-9 pr-4 py-2 font-body-md text-xs text-charcoal-text focus:border-heritage-burgundy focus:outline-none transition-colors"
                    name="search" value="{{ request('search') }}" placeholder="Search Order # or Name..." type="text" />
            </form>
        </div>

        <!-- Orders Table -->
        <div class="bg-surface-container-lowest rounded-2xl border border-border-subtle shadow-xs overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="border-b border-border-subtle bg-warm-ivory/50 text-[11px] font-label-caps text-on-surface-variant uppercase">
                            <th class="py-3.5 px-5 font-semibold">Order ID</th>
                            <th class="py-3.5 px-5 font-semibold">Customer</th>
                            <th class="py-3.5 px-5 font-semibold">Items</th>
                            <th class="py-3.5 px-5 font-semibold">Total Amount</th>
                            <th class="py-3.5 px-5 font-semibold">Payment</th>
                            <th class="py-3.5 px-5 font-semibold">Fulfillment Status</th>
                            <th class="py-3.5 px-5 font-semibold text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-border-subtle text-xs font-body-md">
                        @forelse($orders as $order)
                            <tr class="hover:bg-cream-silk/40 transition-colors">
                                <td class="py-4 px-5">
                                    <a href="{{ route('admin.orders.show', $order->order_number) }}" class="font-data-tabular font-bold text-heritage-burgundy hover:underline">
                                        {{ $order->order_number }}
                                    </a>
                                    <p class="text-[11px] text-on-surface-variant mt-0.5">{{ $order->created_at->format('M d, Y') }}</p>
                                </td>
                                <td class="py-4 px-5">
                                    <p class="font-semibold text-charcoal-text">{{ $order->customer_name }}</p>
                                    <p class="text-[11px] text-on-surface-variant">{{ $order->customer_email }}</p>
                                </td>
                                <td class="py-4 px-5">
                                    <span class="inline-flex items-center gap-1 font-semibold text-charcoal-text">
                                        {{ $order->items->count() }} {{ Str::plural('item', $order->items->count()) }}
                                    </span>
                                </td>
                                <td class="py-4 px-5 font-data-tabular font-bold text-charcoal-text">
                                    {{ $order->formatted_total }}
                                </td>
                                <td class="py-4 px-5">
                                    <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-label-caps uppercase font-bold {{ $order->payment_status === 'paid' ? 'bg-emerald-50 text-emerald-800 border border-emerald-200' : 'bg-amber-50 text-amber-800 border border-amber-200' }}">
                                        {{ $order->payment_method }} &bull; {{ ucfirst($order->payment_status) }}
                                    </span>
                                </td>
                                <td class="py-4 px-5">
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
                                    <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-label-caps uppercase font-bold border {{ $style }}">
                                        {{ ucfirst($order->status) }}
                                    </span>
                                </td>
                                <td class="py-4 px-5 text-right">
                                    <a href="{{ route('admin.orders.show', $order->order_number) }}"
                                        class="inline-flex items-center gap-1 px-3 py-1.5 bg-cream-silk hover:bg-heritage-burgundy hover:text-white text-heritage-burgundy rounded-lg border border-muted-gold/40 text-xs font-label-caps uppercase font-bold transition-all shadow-2xs">
                                        <span>Inspect</span>
                                        <span class="material-symbols-outlined text-sm">arrow_forward</span>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="py-12 text-center text-on-surface-variant">
                                    <span class="material-symbols-outlined text-4xl text-heritage-burgundy/40 block mb-2">package_2</span>
                                    No customer orders found matching this criteria.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($orders->hasPages())
                <div class="p-4 border-t border-border-subtle">
                    {{ $orders->links() }}
                </div>
            @endif
        </div>
    </div>
</x-layouts.admin>
