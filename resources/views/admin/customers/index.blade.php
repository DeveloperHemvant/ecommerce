<x-layouts.admin title="Customer Directory - Sonakshi Admin" active="customers">
    <div class="space-y-6 max-w-[1400px]">
        <!-- Header -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <h1 class="font-headline-lg text-heritage-burgundy">Customer Directory</h1>
                <p class="font-body-md text-xs text-on-surface-variant mt-1">Manage registered customers, view purchasing activity, and track VIP lifetime spending.</p>
            </div>
        </div>

        <!-- Filter & Search Bar -->
        <div class="bg-surface-container-lowest rounded-2xl border border-border-subtle p-4 shadow-xs flex justify-between items-center">
            <form action="{{ route('admin.customers.index') }}" method="GET" class="relative w-full max-w-md">
                <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-on-surface-variant text-base">search</span>
                <input class="w-full bg-warm-ivory/60 border border-border-subtle rounded-xl pl-9 pr-4 py-2 font-body-md text-xs text-charcoal-text focus:border-heritage-burgundy focus:outline-none transition-colors"
                    name="search" value="{{ request('search') }}" placeholder="Search customer by name, email, or phone..." type="text" />
            </form>
        </div>

        <!-- Customers Table -->
        <div class="bg-surface-container-lowest rounded-2xl border border-border-subtle shadow-xs overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="border-b border-border-subtle bg-warm-ivory/50 text-[11px] font-label-caps text-on-surface-variant uppercase">
                            <th class="py-3.5 px-5 font-semibold">Customer</th>
                            <th class="py-3.5 px-5 font-semibold">Contact Info</th>
                            <th class="py-3.5 px-5 font-semibold">Registered Date</th>
                            <th class="py-3.5 px-5 font-semibold">Total Orders</th>
                            <th class="py-3.5 px-5 font-semibold">Lifetime Spend</th>
                            <th class="py-3.5 px-5 font-semibold text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-border-subtle text-xs font-body-md">
                        @forelse($customers as $cust)
                            <tr class="hover:bg-cream-silk/40 transition-colors">
                                <td class="py-4 px-5">
                                    <div class="flex items-center gap-3">
                                        <div class="w-9 h-9 rounded-full bg-cream-silk text-heritage-burgundy flex items-center justify-center font-bold font-label-caps text-xs border border-muted-gold/40 shrink-0">
                                            {{ strtoupper(substr($cust->name, 0, 2)) }}
                                        </div>
                                        <div>
                                            <a href="{{ route('admin.customers.show', $cust) }}" class="font-semibold text-charcoal-text hover:text-heritage-burgundy transition-colors">
                                                {{ $cust->name }}
                                            </a>
                                            <span class="text-[10px] font-label-caps text-on-surface-variant block uppercase">Registered Customer</span>
                                        </div>
                                    </div>
                                </td>
                                <td class="py-4 px-5">
                                    <p class="text-charcoal-text">{{ $cust->email }}</p>
                                    <p class="text-[11px] text-on-surface-variant">{{ $cust->phone ?? 'No phone provided' }}</p>
                                </td>
                                <td class="py-4 px-5 text-on-surface-variant">
                                    {{ $cust->created_at->format('M d, Y') }}
                                </td>
                                <td class="py-4 px-5 font-bold font-data-tabular">
                                    {{ $cust->orders_count }} {{ Str::plural('order', $cust->orders_count) }}
                                </td>
                                <td class="py-4 px-5 font-bold font-data-tabular text-heritage-burgundy">
                                    {{ $cust->formatted_lifetime_spend }}
                                </td>
                                <td class="py-4 px-5 text-right">
                                    <a href="{{ route('admin.customers.show', $cust) }}"
                                        class="inline-flex items-center gap-1 px-3 py-1.5 bg-cream-silk hover:bg-heritage-burgundy hover:text-white text-heritage-burgundy rounded-lg border border-muted-gold/40 text-xs font-label-caps uppercase font-bold transition-all shadow-2xs">
                                        <span>Profile</span>
                                        <span class="material-symbols-outlined text-sm">arrow_forward</span>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="py-12 text-center text-on-surface-variant">
                                    <span class="material-symbols-outlined text-4xl text-heritage-burgundy/40 block mb-2">person_off</span>
                                    No registered customers found.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($customers->hasPages())
                <div class="p-4 border-t border-border-subtle">
                    {{ $customers->links() }}
                </div>
            @endif
        </div>
    </div>
</x-layouts.admin>
