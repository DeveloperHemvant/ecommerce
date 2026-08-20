<x-layouts.admin title="Categories Management - Sonakshi Admin" active="categories">
    <div class="space-y-6 max-w-[1400px]">
        <!-- Top Bar -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <h1 class="font-headline-lg text-heritage-burgundy">Category Management</h1>
                <p class="font-body-md text-xs text-on-surface-variant mt-1">Manage luxury collections, circular navigation items, and display hierarchies.</p>
            </div>
            <a href="{{ route('admin.categories.create') }}"
                class="bg-heritage-burgundy text-white font-label-caps text-xs uppercase tracking-wider px-5 py-3 rounded-xl hover:bg-primary-container transition-all flex items-center gap-2 shadow-xs shrink-0 self-start sm:self-auto font-bold">
                <span class="material-symbols-outlined text-base">add</span>
                Add New Category
            </a>
        </div>

        @if(session('success'))
            <div class="p-4 bg-green-50 border border-green-200 text-green-800 text-xs rounded-xl flex items-center gap-2">
                <span class="material-symbols-outlined text-base">check_circle</span>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        <!-- Categories Table -->
        <div class="bg-surface-container-lowest rounded-2xl border border-border-subtle shadow-xs overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse text-sm">
                    <thead>
                        <tr class="bg-warm-ivory/50 border-b border-border-subtle text-xs font-label-caps text-on-surface-variant">
                            <th class="px-6 py-4 font-semibold uppercase">Category</th>
                            <th class="px-6 py-4 font-semibold uppercase">Slug</th>
                            <th class="px-6 py-4 font-semibold uppercase text-center">Products</th>
                            <th class="px-6 py-4 font-semibold uppercase text-center">Order</th>
                            <th class="px-6 py-4 font-semibold uppercase text-center">Status</th>
                            <th class="px-6 py-4 font-semibold uppercase text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-border-subtle font-body-md text-xs">
                        @forelse($categories as $category)
                            <tr class="hover:bg-warm-ivory/40 transition-colors">
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="w-12 h-12 rounded-full overflow-hidden bg-cream-silk border border-border-subtle shrink-0">
                                            @if($category->image)
                                                <img src="{{ $category->image }}" alt="{{ $category->name }}" class="w-full h-full object-cover" />
                                            @else
                                                <div class="w-full h-full flex items-center justify-center text-heritage-burgundy">
                                                    <span class="material-symbols-outlined text-base">category</span>
                                                </div>
                                            @endif
                                        </div>
                                        <div>
                                            <p class="font-title-lg text-sm font-semibold text-charcoal-text">{{ $category->name }}</p>
                                            @if($category->description)
                                                <p class="text-on-surface-variant text-[11px] line-clamp-1 max-w-xs">{{ $category->description }}</p>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 font-data-tabular text-on-surface-variant">
                                    <code>{{ $category->slug }}</code>
                                </td>
                                <td class="px-6 py-4 text-center font-data-tabular font-bold text-heritage-burgundy">
                                    {{ $category->products_count }}
                                </td>
                                <td class="px-6 py-4 text-center font-data-tabular">
                                    {{ $category->display_order }}
                                </td>
                                <td class="px-6 py-4 text-center">
                                    @if($category->is_active)
                                        <span class="px-2.5 py-0.5 rounded-full text-[11px] font-semibold bg-emerald-50 text-emerald-800 border border-emerald-200">Active</span>
                                    @else
                                        <span class="px-2.5 py-0.5 rounded-full text-[11px] font-semibold bg-gray-100 text-gray-700 border border-gray-200">Draft</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <div class="flex items-center justify-end gap-2">
                                        <a href="{{ route('admin.categories.edit', $category) }}" class="p-1.5 text-on-surface-variant hover:text-heritage-burgundy hover:bg-surface-container rounded-lg transition-colors" title="Edit">
                                            <span class="material-symbols-outlined text-lg">edit</span>
                                        </a>
                                        <form action="{{ route('admin.categories.destroy', $category) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this category?');" class="inline">
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
                                <td colspan="6" class="px-6 py-8 text-center text-on-surface-variant">
                                    No categories found. Click "Add New Category" to create one.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-layouts.admin>
