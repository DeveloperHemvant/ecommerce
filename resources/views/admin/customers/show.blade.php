<x-layouts.admin title="Customer: {{ $customer->name }} - Sonakshi Admin" active="customers">
    <div class="space-y-6 max-w-[1400px]">
        <!-- Top Navigation -->
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.customers.index') }}" class="text-on-surface-variant hover:text-heritage-burgundy transition-colors p-1">
                <span class="material-symbols-outlined text-xl">arrow_back</span>
            </a>
            <div>
                <h1 class="font-headline-lg text-heritage-burgundy">{{ $customer->name }}</h1>
                <p class="font-body-md text-xs text-on-surface-variant">Customer Profile &bull; Member since {{ $customer->created_at->format('F Y') }}</p>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
            <!-- Left Info Card (4 Cols) -->
            <div class="lg:col-span-4 space-y-6">
                <div class="bg-surface-container-lowest rounded-2xl border border-border-subtle p-6 shadow-xs space-y-5">
                    <div class="flex items-center gap-4 border-b border-border-subtle pb-5">
                        <div class="w-14 h-14 rounded-full bg-cream-silk text-heritage-burgundy flex items-center justify-center font-bold font-headline-md text-xl border-2 border-muted-gold/40">
                            {{ strtoupper(substr($customer->name, 0, 2)) }}
                        </div>
                        <div>
                            <h2 class="font-headline-md text-lg text-charcoal-text">{{ $customer->name }}</h2>
                            <p class="text-xs text-on-surface-variant">{{ $customer->email }}</p>
                        </div>
                    </div>

                    <div class="space-y-3 text-xs">
                        <div class="flex justify-between">
                            <span class="text-on-surface-variant">Phone:</span>
                            <span class="font-semibold text-charcoal-text">{{ $customer->phone ?? 'Not provided' }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-on-surface-variant">Total Orders Placed:</span>
                            <span class="font-bold font-data-tabular text-charcoal-text">{{ $customer->orders->count() }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-on-surface-variant">Lifetime Spending:</span>
                            <span class="font-bold font-data-tabular text-heritage-burgundy">{{ $customer->formatted_lifetime_spend }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-on-surface-variant">Account Registered:</span>
                            <span class="text-charcoal-text">{{ $customer->created_at->format('M d, Y') }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Orders History (8 Cols) -->
            <div class="lg:col-span-8 space-y-6">
                <div class="bg-surface-container-lowest rounded-2xl border border-border-subtle shadow-xs overflow-hidden">
                    <div class="p-5 border-b border-border-subtle flex justify-between items-center bg-warm-ivory/40">
                        <h3 class="font-headline-md text-base text-charcoal-text">Purchase &amp; Order History</h3>
                        <span class="text-xs font-label-caps uppercase text-on-surface-variant font-bold">{{ $customer->orders->count() }} {{ Str::plural('order', $customer->orders->count()) }}</span>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="border-b border-border-subtle text-[11px] font-label-caps text-on-surface-variant uppercase bg-surface-container-low">
                                    <th class="py-3 px-5">Order ID</th>
                                    <th class="py-3 px-5">Date</th>
                                    <th class="py-3 px-5">Items</th>
                                    <th class="py-3 px-5">Total</th>
                                    <th class="py-3 px-5">Status</th>
                                    <th class="py-3 px-5 text-right">Action</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-border-subtle text-xs font-body-md">
                                @forelse($customer->orders as $ord)
                                    <tr class="hover:bg-cream-silk/40 transition-colors">
                                        <td class="py-4 px-5">
                                            <a href="{{ route('admin.orders.show', $ord->order_number) }}" class="font-data-tabular font-bold text-heritage-burgundy hover:underline">
                                                {{ $ord->order_number }}
                                            </a>
                                        </td>
                                        <td class="py-4 px-5 text-on-surface-variant">
                                            {{ $ord->created_at->format('M d, Y') }}
                                        </td>
                                        <td class="py-4 px-5 font-semibold text-charcoal-text">
                                            {{ $ord->items->count() }} items
                                        </td>
                                        <td class="py-4 px-5 font-data-tabular font-bold text-heritage-burgundy">
                                            {{ $ord->formatted_total }}
                                        </td>
                                        <td class="py-4 px-5">
                                            <span class="px-2.5 py-0.5 rounded-full text-[10px] font-label-caps uppercase font-bold bg-cream-silk border border-muted-gold/40 text-charcoal-text">
                                                {{ ucfirst($ord->status) }}
                                            </span>
                                        </td>
                                        <td class="py-4 px-5 text-right">
                                            <a href="{{ route('admin.orders.show', $ord->order_number) }}" class="text-heritage-burgundy hover:underline font-bold font-label-caps text-xs">
                                                Inspect &rarr;
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="py-8 text-center text-on-surface-variant">
                                            No orders placed yet by this customer.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layouts.admin>
