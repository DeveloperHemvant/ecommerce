<x-layouts.admin title="Coupon & Promo Codes - Sonakshi Admin" active="coupons">
    <div class="space-y-6">
        <!-- Header -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <h1 class="font-headline-lg text-heritage-burgundy">Promotional Coupons</h1>
                <p class="font-body-md text-xs text-on-surface-variant">Create and manage campaign discount codes, percentage rates, min order amounts, and usage caps.</p>
            </div>
            <a href="{{ route('admin.coupons.create') }}" class="bg-heritage-burgundy text-white font-label-caps text-xs uppercase tracking-wider px-5 py-3 rounded-xl hover:bg-primary-container transition-all flex items-center gap-2 font-bold self-start sm:self-auto shadow-xs">
                <span class="material-symbols-outlined text-base">add</span>
                Create Coupon
            </a>
        </div>

        @if(session('success'))
            <div class="p-4 bg-green-50 border border-green-200 text-green-800 text-xs rounded-xl flex items-center gap-2">
                <span class="material-symbols-outlined text-base">check_circle</span>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        <!-- Coupons Table -->
        <div class="bg-surface-container-lowest rounded-2xl border border-border-subtle shadow-xs overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse text-xs">
                    <thead>
                        <tr class="bg-warm-ivory/60 border-b border-border-subtle font-label-caps text-on-surface-variant">
                            <th class="px-6 py-3.5 uppercase font-semibold">Code</th>
                            <th class="px-6 py-3.5 uppercase font-semibold">Discount Type &amp; Value</th>
                            <th class="px-6 py-3.5 uppercase font-semibold">Min Order</th>
                            <th class="px-6 py-3.5 uppercase font-semibold text-center">Usage</th>
                            <th class="px-6 py-3.5 uppercase font-semibold">Status</th>
                            <th class="px-6 py-3.5 uppercase font-semibold text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-border-subtle font-body-md">
                        @forelse($coupons as $coupon)
                            <tr class="hover:bg-warm-ivory/40 transition-colors">
                                <td class="px-6 py-4">
                                    <span class="font-data-tabular font-bold text-sm text-heritage-burgundy bg-cream-silk px-2.5 py-1 rounded-lg border border-muted-gold/40">
                                        {{ $coupon->code }}
                                    </span>
                                    @if($coupon->description)
                                        <p class="text-[11px] text-on-surface-variant mt-1">{{ $coupon->description }}</p>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    @if($coupon->type === 'percent')
                                        <span class="font-bold text-charcoal-text">{{ (int)$coupon->value }}% OFF</span>
                                        @if($coupon->max_discount_amount)
                                            <span class="text-[10px] text-on-surface-variant block">Up to ₹{{ number_format((float)$coupon->max_discount_amount) }}</span>
                                        @endif
                                    @else
                                        <span class="font-bold text-charcoal-text">₹{{ number_format((float)$coupon->value) }} Flat OFF</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 font-data-tabular">
                                    {{ $coupon->min_order_amount > 0 ? '₹' . number_format((float)$coupon->min_order_amount) : 'None' }}
                                </td>
                                <td class="px-6 py-4 text-center font-data-tabular">
                                    {{ $coupon->used_count }} / {{ $coupon->usage_limit ?? '&infin;' }}
                                </td>
                                <td class="px-6 py-4">
                                    <span class="px-2.5 py-0.5 rounded-full text-[10px] font-label-caps uppercase font-bold border {{ $coupon->is_active ? 'bg-emerald-50 text-emerald-800 border-emerald-200' : 'bg-gray-100 text-gray-600 border-gray-200' }}">
                                        {{ $coupon->is_active ? 'Active' : 'Inactive' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <div class="flex items-center justify-center gap-2">
                                        <a href="{{ route('admin.coupons.edit', $coupon) }}" class="p-1 text-on-surface-variant hover:text-heritage-burgundy transition-colors" title="Edit">
                                            <span class="material-symbols-outlined text-lg">edit</span>
                                        </a>
                                        <form action="{{ route('admin.coupons.destroy', $coupon) }}" method="POST" onsubmit="return confirm('Delete this coupon code?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="p-1 text-on-surface-variant hover:text-error transition-colors cursor-pointer" title="Delete">
                                                <span class="material-symbols-outlined text-lg">delete</span>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-12 text-center text-on-surface-variant">
                                    <span class="material-symbols-outlined text-3xl text-heritage-burgundy/40 block mb-1">confirmation_number</span>
                                    No promotional coupons created yet.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($coupons->hasPages())
                <div class="p-4 border-t border-border-subtle">
                    {{ $coupons->links() }}
                </div>
            @endif
        </div>
    </div>
</x-layouts.admin>
