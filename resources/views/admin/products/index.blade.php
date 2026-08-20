<x-layouts.admin title="Products & Inventory - Sonakshi Admin" active="products">
    <div class="space-y-6 max-w-[1400px]">
        <!-- Top Bar -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <h1 class="font-headline-lg text-heritage-burgundy">Products &amp; Inventory</h1>
                <p class="font-body-md text-xs text-on-surface-variant mt-1">Manage luxury stock, pricing, SKUs, discounts, size options, and catalog details.</p>
            </div>
            <a href="{{ route('admin.products.create') }}"
                class="bg-heritage-burgundy text-white font-label-caps text-xs uppercase tracking-wider px-5 py-3 rounded-xl hover:bg-primary-container transition-all flex items-center gap-2 shadow-xs shrink-0 self-start sm:self-auto font-bold">
                <span class="material-symbols-outlined text-base">add</span>
                Add New Product
            </a>
        </div>

        @if(session('success'))
            <div class="p-4 bg-green-50 border border-green-200 text-green-800 text-xs rounded-xl flex items-center gap-2">
                <span class="material-symbols-outlined text-base">check_circle</span>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        <!-- Filter Bar -->
        <div class="bg-surface-container-lowest p-4 rounded-2xl border border-border-subtle shadow-xs flex flex-col md:flex-row gap-4 justify-between items-center">
            <form action="{{ route('admin.products.index') }}" method="GET" class="flex flex-wrap items-center gap-3 w-full md:w-auto">
                <div class="relative min-w-[240px]">
                    <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-on-surface-variant text-base">search</span>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by name or SKU..."
                        class="w-full bg-warm-ivory/60 border border-border-subtle rounded-xl pl-9 pr-3 py-2 text-xs font-body-md text-charcoal-text focus:outline-none focus:border-heritage-burgundy" />
                </div>

                <select name="category_id" onchange="this.form.submit()"
                    class="bg-warm-ivory/60 border border-border-subtle rounded-xl px-3 py-2 text-xs font-body-md text-charcoal-text focus:outline-none focus:border-heritage-burgundy">
                    <option value="">All Categories</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" {{ request('category_id') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                    @endforeach
                </select>

                <button type="submit" class="px-3 py-2 bg-cream-silk border border-border-subtle rounded-xl text-xs font-label-caps uppercase text-heritage-burgundy font-bold hover:bg-heritage-burgundy hover:text-white transition-colors">
                    Filter
                </button>

                @if(request('search') || request('category_id'))
                    <a href="{{ route('admin.products.index') }}" class="text-xs text-error hover:underline ml-1 font-medium">Clear</a>
                @endif
            </form>

            <span class="font-data-tabular text-xs text-on-surface-variant shrink-0">
                Total Products: <strong>{{ $products->total() }}</strong>
            </span>
        </div>

        <!-- Products Table -->
        <div class="bg-surface-container-lowest rounded-2xl border border-border-subtle shadow-xs overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse text-sm">
                    <thead>
                        <tr class="bg-warm-ivory/50 border-b border-border-subtle text-xs font-label-caps text-on-surface-variant">
                            <th class="px-6 py-4 font-semibold uppercase">Product</th>
                            <th class="px-6 py-4 font-semibold uppercase">SKU</th>
                            <th class="px-6 py-4 font-semibold uppercase">Category</th>
                            <th class="px-6 py-4 font-semibold uppercase text-right">Price</th>
                            <th class="px-6 py-4 font-semibold uppercase text-center">Stock</th>
                            <th class="px-6 py-4 font-semibold uppercase text-center">Status</th>
                            <th class="px-6 py-4 font-semibold uppercase text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-border-subtle font-body-md text-xs">
                        @forelse($products as $product)
                            <tr class="hover:bg-warm-ivory/40 transition-colors">
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3.5">
                                        <div class="w-12 h-16 rounded-lg overflow-hidden bg-surface border border-border-subtle shrink-0">
                                            <img src="{{ $product->main_image }}" alt="{{ $product->name }}" class="w-full h-full object-cover" />
                                        </div>
                                        <div>
                                            <a href="{{ route('product.detail', $product->slug) }}" target="_blank" class="font-title-lg text-sm font-semibold text-charcoal-text hover:text-heritage-burgundy transition-colors line-clamp-1">
                                                {{ $product->name }}
                                            </a>
                                            <div class="flex items-center gap-2 mt-0.5">
                                                @if($product->is_featured)
                                                    <span class="bg-cream-silk text-heritage-burgundy font-label-caps text-[9px] px-1.5 py-0.2 rounded border border-muted-gold/40 font-bold">Featured</span>
                                                @endif
                                                @if($product->discount_percentage)
                                                    <span class="text-emerald-700 font-label-caps text-[10px] font-bold">{{ $product->discount_percentage }}% OFF</span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 font-data-tabular font-semibold text-on-surface-variant">
                                    <code>{{ $product->sku }}</code>
                                </td>
                                <td class="px-6 py-4 text-on-surface-variant font-medium">
                                    {{ $product->category->name ?? 'Uncategorized' }}
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <span class="font-data-tabular font-bold text-heritage-burgundy">{{ $product->formatted_price }}</span>
                                    @if($product->formatted_compare_price)
                                        <span class="block text-[11px] text-on-surface-variant line-through">{{ $product->formatted_compare_price }}</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-center">
                                    @if($product->stock <= 0)
                                        <span class="px-2.5 py-0.5 rounded-full text-[11px] font-semibold bg-red-50 text-error border border-red-200">Out of Stock (0)</span>
                                    @elseif($product->is_low_stock)
                                        <span class="px-2.5 py-0.5 rounded-full text-[11px] font-semibold bg-amber-50 text-amber-800 border border-amber-200">Low: {{ $product->stock }} left</span>
                                    @else
                                        <span class="px-2.5 py-0.5 rounded-full text-[11px] font-semibold bg-emerald-50 text-emerald-800 border border-emerald-200">{{ $product->stock }} in stock</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-center">
                                    @if($product->is_active)
                                        <span class="text-xs font-semibold text-emerald-700">Published</span>
                                    @else
                                        <span class="text-xs font-semibold text-gray-500">Draft</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <div class="flex items-center justify-end gap-1.5">
                                        <a href="{{ route('product.detail', $product->slug) }}" target="_blank" class="p-1.5 text-on-surface-variant hover:text-heritage-burgundy hover:bg-surface-container rounded-lg transition-colors" title="View Live">
                                            <span class="material-symbols-outlined text-lg">visibility</span>
                                        </a>
                                        <a href="{{ route('admin.products.edit', $product) }}" class="p-1.5 text-on-surface-variant hover:text-heritage-burgundy hover:bg-surface-container rounded-lg transition-colors" title="Edit">
                                            <span class="material-symbols-outlined text-lg">edit</span>
                                        </a>
                                        <form action="{{ route('admin.products.destroy', $product) }}" method="POST" onsubmit="return confirm('Delete product {{ $product->name }}?');" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="p-1.5 text-on-surface-variant hover:text-error hover:bg-surface-container rounded-lg transition-colors cursor-pointer" title="Delete">
                                                <span class="material-symbols-outlined text-lg">delete</span>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-12 text-center text-on-surface-variant">
                                    No products found matching your filter criteria.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($products->hasPages())
                <div class="p-4 border-t border-border-subtle">
                    {{ $products->links() }}
                </div>
            @endif
        </div>
    </div>
</x-layouts.admin>
