<x-layouts.admin title="Create Coupon - Sonakshi Admin" active="coupons">
    <div class="max-w-2xl mx-auto space-y-6">
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.coupons.index') }}" class="text-on-surface-variant hover:text-heritage-burgundy transition-colors p-1">
                <span class="material-symbols-outlined text-xl">arrow_back</span>
            </a>
            <div>
                <h1 class="font-headline-lg text-heritage-burgundy">Add Promotional Coupon</h1>
                <p class="font-body-md text-xs text-on-surface-variant">Create a discount promo code for festive campaigns and customer checkouts.</p>
            </div>
        </div>

        @if($errors->any())
            <div class="p-4 bg-red-50 border border-red-200 text-error text-xs rounded-xl flex items-center gap-2">
                <span class="material-symbols-outlined text-base">error</span>
                <span>{{ $errors->first() }}</span>
            </div>
        @endif

        <form action="{{ route('admin.coupons.store') }}" method="POST" class="space-y-6">
            @csrf

            <div class="bg-surface-container-lowest rounded-2xl border border-border-subtle p-6 md:p-8 shadow-xs space-y-5">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                    <div>
                        <label class="block font-label-caps text-xs text-on-surface-variant uppercase mb-1.5 font-semibold" for="code">
                            Promo Code <span class="text-error">*</span>
                        </label>
                        <input class="w-full bg-warm-ivory/60 border border-border-subtle rounded-xl px-4 py-3 font-body-md text-sm text-charcoal-text focus:border-heritage-burgundy focus:outline-none transition-colors uppercase font-data-tabular"
                            id="code" name="code" value="{{ old('code') }}" placeholder="e.g. FESTIVE20" required type="text" />
                    </div>

                    <div>
                        <label class="block font-label-caps text-xs text-on-surface-variant uppercase mb-1.5 font-semibold" for="type">
                            Discount Type <span class="text-error">*</span>
                        </label>
                        <select class="w-full bg-warm-ivory/60 border border-border-subtle rounded-xl px-4 py-3 font-body-md text-sm text-charcoal-text focus:border-heritage-burgundy focus:outline-none transition-colors"
                            id="type" name="type" required>
                            <option value="percent" {{ old('type') == 'percent' ? 'selected' : '' }}>Percentage (%) Discount</option>
                            <option value="fixed" {{ old('type') == 'fixed' ? 'selected' : '' }}>Fixed Amount (₹) Discount</option>
                        </select>
                    </div>

                    <div>
                        <label class="block font-label-caps text-xs text-on-surface-variant uppercase mb-1.5 font-semibold" for="value">
                            Discount Value <span class="text-error">*</span>
                        </label>
                        <input class="w-full bg-warm-ivory/60 border border-border-subtle rounded-xl px-4 py-3 font-body-md text-sm text-charcoal-text focus:border-heritage-burgundy focus:outline-none transition-colors font-data-tabular"
                            id="value" name="value" value="{{ old('value') }}" placeholder="e.g. 15 for 15% or 2000 for ₹2000" required step="0.01" type="number" />
                    </div>

                    <div>
                        <label class="block font-label-caps text-xs text-on-surface-variant uppercase mb-1.5 font-semibold" for="min_order_amount">
                            Min. Cart Value (₹)
                        </label>
                        <input class="w-full bg-warm-ivory/60 border border-border-subtle rounded-xl px-4 py-3 font-body-md text-sm text-charcoal-text focus:border-heritage-burgundy focus:outline-none transition-colors font-data-tabular"
                            id="min_order_amount" name="min_order_amount" value="{{ old('min_order_amount', 0) }}" placeholder="e.g. 10000" step="0.01" type="number" />
                    </div>

                    <div>
                        <label class="block font-label-caps text-xs text-on-surface-variant uppercase mb-1.5 font-semibold" for="max_discount_amount">
                            Max Discount Cap (₹) (Optional)
                        </label>
                        <input class="w-full bg-warm-ivory/60 border border-border-subtle rounded-xl px-4 py-3 font-body-md text-sm text-charcoal-text focus:border-heritage-burgundy focus:outline-none transition-colors font-data-tabular"
                            id="max_discount_amount" name="max_discount_amount" value="{{ old('max_discount_amount') }}" placeholder="e.g. 5000" step="0.01" type="number" />
                    </div>

                    <div>
                        <label class="block font-label-caps text-xs text-on-surface-variant uppercase mb-1.5 font-semibold" for="usage_limit">
                            Usage Limit (Total Redemptions)
                        </label>
                        <input class="w-full bg-warm-ivory/60 border border-border-subtle rounded-xl px-4 py-3 font-body-md text-sm text-charcoal-text focus:border-heritage-burgundy focus:outline-none transition-colors font-data-tabular"
                            id="usage_limit" name="usage_limit" value="{{ old('usage_limit') }}" placeholder="e.g. 100 (Leave blank for unlimited)" type="number" />
                    </div>
                </div>

                <div>
                    <label class="block font-label-caps text-xs text-on-surface-variant uppercase mb-1.5 font-semibold" for="description">
                        Campaign Description
                    </label>
                    <input class="w-full bg-warm-ivory/60 border border-border-subtle rounded-xl px-4 py-3 font-body-md text-sm text-charcoal-text focus:border-heritage-burgundy focus:outline-none transition-colors"
                        id="description" name="description" value="{{ old('description') }}" placeholder="e.g. Wedding Season Grand 15% Savings" type="text" />
                </div>

                <div class="pt-2 border-t border-border-subtle">
                    <label class="flex items-center gap-2 cursor-pointer text-xs font-title-lg">
                        <input type="checkbox" name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }}
                            class="rounded border-border-subtle text-heritage-burgundy focus:ring-heritage-burgundy" />
                        <span class="text-charcoal-text font-semibold">Active &amp; Ready for Checkout</span>
                    </label>
                </div>
            </div>

            <div class="flex justify-end gap-3">
                <a href="{{ route('admin.coupons.index') }}" class="px-5 py-3 border border-border-subtle rounded-xl text-xs font-label-caps uppercase text-on-surface-variant hover:text-charcoal-text">
                    Cancel
                </a>
                <button type="submit" class="bg-heritage-burgundy text-white font-label-caps text-xs uppercase tracking-wider px-8 py-3.5 rounded-xl hover:bg-primary-container transition-all flex items-center gap-2 font-bold cursor-pointer shadow-sm">
                    <span class="material-symbols-outlined text-base">save</span>
                    Create Coupon
                </button>
            </div>
        </form>
    </div>
</x-layouts.admin>
