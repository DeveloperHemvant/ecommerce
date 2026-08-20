<x-layouts.admin title="Customer Reviews Moderation - Sonakshi Admin" active="reviews">
    <div class="space-y-6">
        <!-- Header -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <h1 class="font-headline-lg text-heritage-burgundy">Customer Reviews &amp; Photos</h1>
                <p class="font-body-md text-xs text-on-surface-variant">Moderate customer testimonials, star ratings, and uploaded bride &amp; festive outfit photos.</p>
            </div>
        </div>

        @if(session('success'))
            <div class="p-4 bg-green-50 border border-green-200 text-green-800 text-xs rounded-xl flex items-center gap-2">
                <span class="material-symbols-outlined text-base">check_circle</span>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        <!-- Filter Tabs -->
        <div class="flex items-center gap-2 border-b border-border-subtle pb-3">
            <a href="{{ route('admin.reviews.index') }}" class="px-3.5 py-1.5 rounded-lg text-xs font-label-caps uppercase font-bold {{ !request('status') ? 'bg-heritage-burgundy text-white' : 'text-on-surface-variant hover:bg-surface-container' }}">
                All Reviews
            </a>
            <a href="{{ route('admin.reviews.index', ['status' => 'approved']) }}" class="px-3.5 py-1.5 rounded-lg text-xs font-label-caps uppercase font-bold {{ request('status') === 'approved' ? 'bg-heritage-burgundy text-white' : 'text-on-surface-variant hover:bg-surface-container' }}">
                Approved
            </a>
            <a href="{{ route('admin.reviews.index', ['status' => 'pending']) }}" class="px-3.5 py-1.5 rounded-lg text-xs font-label-caps uppercase font-bold {{ request('status') === 'pending' ? 'bg-heritage-burgundy text-white' : 'text-on-surface-variant hover:bg-surface-container' }}">
                Hidden / Pending
            </a>
        </div>

        <!-- Reviews Table -->
        <div class="bg-surface-container-lowest rounded-2xl border border-border-subtle shadow-xs overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse text-xs">
                    <thead>
                        <tr class="bg-warm-ivory/60 border-b border-border-subtle font-label-caps text-on-surface-variant">
                            <th class="px-6 py-3.5 uppercase font-semibold">Customer &amp; Product</th>
                            <th class="px-6 py-3.5 uppercase font-semibold">Rating &amp; Review</th>
                            <th class="px-6 py-3.5 uppercase font-semibold">Uploaded Photos</th>
                            <th class="px-6 py-3.5 uppercase font-semibold">Date</th>
                            <th class="px-6 py-3.5 uppercase font-semibold">Status</th>
                            <th class="px-6 py-3.5 uppercase font-semibold text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-border-subtle font-body-md">
                        @forelse($reviews as $rev)
                            <tr class="hover:bg-warm-ivory/40 transition-colors">
                                <td class="px-6 py-4">
                                    <p class="font-bold text-charcoal-text">{{ $rev->customer_name }}</p>
                                    @if($rev->is_verified_buyer)
                                        <span class="inline-flex items-center gap-0.5 text-[10px] text-emerald-800 font-bold font-label-caps uppercase">
                                            <span class="material-symbols-outlined text-xs">verified</span>
                                            Verified Buyer
                                        </span>
                                    @endif
                                    @if($rev->product)
                                        <a href="{{ route('product.detail', $rev->product->slug) }}" target="_blank" class="block text-[11px] text-heritage-burgundy hover:underline mt-1">
                                            {{ $rev->product->name }} &rarr;
                                        </a>
                                    @endif
                                </td>
                                <td class="px-6 py-4 max-w-sm">
                                    <div class="flex text-amber-500 text-sm mb-1">
                                        @for($s = 1; $s <= 5; $s++)
                                            <span class="material-symbols-outlined text-sm font-fill {{ $s <= $rev->rating ? 'text-amber-500' : 'text-gray-300' }}">star</span>
                                        @endfor
                                    </div>
                                    @if($rev->title)
                                        <p class="font-bold text-charcoal-text text-xs">{{ $rev->title }}</p>
                                    @endif
                                    <p class="text-on-surface-variant text-[11px] line-clamp-2 mt-0.5">{{ $rev->comment }}</p>
                                </td>
                                <td class="px-6 py-4">
                                    @if(!empty($rev->photos))
                                        <div class="flex items-center gap-1.5">
                                            @foreach($rev->photos as $p)
                                                <a href="{{ $p }}" target="_blank" class="w-10 h-12 rounded-lg overflow-hidden border border-border-subtle shrink-0 hover:scale-110 transition-transform">
                                                    <img src="{{ $p }}" class="w-full h-full object-cover" />
                                                </a>
                                            @endforeach
                                        </div>
                                    @else
                                        <span class="text-[11px] text-on-surface-variant italic">No photos</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-on-surface-variant font-data-tabular">
                                    {{ $rev->created_at->format('M d, Y') }}
                                </td>
                                <td class="px-6 py-4">
                                    <span class="px-2.5 py-0.5 rounded-full text-[10px] font-label-caps uppercase font-bold border {{ $rev->is_approved ? 'bg-emerald-50 text-emerald-800 border-emerald-200' : 'bg-amber-50 text-amber-800 border-amber-200' }}">
                                        {{ $rev->is_approved ? 'Published' : 'Hidden' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <div class="flex items-center justify-center gap-2">
                                        <form action="{{ route('admin.reviews.toggle', $rev) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="px-2.5 py-1 text-[11px] font-label-caps uppercase font-bold rounded-lg border border-border-subtle hover:bg-surface-container transition-colors cursor-pointer">
                                                {{ $rev->is_approved ? 'Hide' : 'Approve' }}
                                            </button>
                                        </form>
                                        <form action="{{ route('admin.reviews.destroy', $rev) }}" method="POST" onsubmit="return confirm('Delete this review permanently?');">
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
                                    <span class="material-symbols-outlined text-3xl text-heritage-burgundy/40 block mb-1">rate_review</span>
                                    No customer reviews found.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($reviews->hasPages())
                <div class="p-4 border-t border-border-subtle">
                    {{ $reviews->links() }}
                </div>
            @endif
        </div>
    </div>
</x-layouts.admin>
