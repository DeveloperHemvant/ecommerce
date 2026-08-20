<x-layouts.admin title="Sonakshi Fashion Hub - Admin Dashboard" active="dashboard">
    <!-- Top Header Area -->
    <header class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
        <div>
            <h1 class="font-headline-lg text-heritage-burgundy">Dashboard Overview</h1>
            <p class="font-body-md text-sm text-on-surface-variant mt-1">Welcome back, {{ auth()->user()->name ?? 'Sonakshi Admin' }}. Live platform snapshot &amp; operational metrics.</p>
        </div>
        <div class="flex items-center gap-3">
            <span class="px-3 py-1 bg-surface-container-highest text-on-surface-variant font-label-caps text-xs rounded-full border border-border-subtle flex items-center gap-2">
                <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                LIVE METRICS
            </span>
            <div class="inline-flex rounded-xl border border-border-subtle bg-surface-container-lowest p-1 shadow-xs">
                <a href="{{ route('admin.dashboard', ['range' => 'today']) }}"
                    class="px-3.5 py-1.5 rounded-lg font-data-tabular text-xs transition-colors {{ ($range ?? '7') === 'today' ? 'bg-cream-silk text-heritage-burgundy font-bold shadow-xs' : 'text-on-surface-variant hover:text-heritage-burgundy' }}">
                    Today
                </a>
                <a href="{{ route('admin.dashboard', ['range' => '7']) }}"
                    class="px-3.5 py-1.5 rounded-lg font-data-tabular text-xs transition-colors {{ ($range ?? '7') === '7' ? 'bg-cream-silk text-heritage-burgundy font-bold shadow-xs' : 'text-on-surface-variant hover:text-heritage-burgundy' }}">
                    7 Days
                </a>
                <a href="{{ route('admin.dashboard', ['range' => '30']) }}"
                    class="px-3.5 py-1.5 rounded-lg font-data-tabular text-xs transition-colors {{ ($range ?? '7') === '30' ? 'bg-cream-silk text-heritage-burgundy font-bold shadow-xs' : 'text-on-surface-variant hover:text-heritage-burgundy' }}">
                    30 Days
                </a>
            </div>
        </div>
    </header>

    <!-- KPI Bento Grid (4 Cards) -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- KPI 1: Revenue -->
        <div class="bg-surface-container-lowest p-6 rounded-2xl border border-border-subtle shadow-xs flex flex-col justify-between hover:border-heritage-burgundy/40 transition-colors">
            <div class="flex justify-between items-start mb-4">
                <h3 class="font-label-caps text-xs text-on-surface-variant font-semibold uppercase">Total Revenue</h3>
                <div class="w-9 h-9 rounded-full bg-cream-silk flex items-center justify-center text-heritage-burgundy">
                    <span class="material-symbols-outlined text-lg">payments</span>
                </div>
            </div>
            <div>
                <div class="font-headline-md text-2xl text-on-background mb-1 font-bold">₹{{ number_format($totalRevenue) }}</div>
                <div class="flex items-center gap-1 text-emerald-700 text-xs font-data-tabular font-medium">
                    <span class="material-symbols-outlined text-sm">trending_up</span>
                    <span>₹{{ number_format($rangeRevenue) }} in selected period</span>
                </div>
            </div>
        </div>

        <!-- KPI 2: Total Orders -->
        <div class="bg-surface-container-lowest p-6 rounded-2xl border border-border-subtle shadow-xs flex flex-col justify-between hover:border-heritage-burgundy/40 transition-colors">
            <div class="flex justify-between items-start mb-4">
                <h3 class="font-label-caps text-xs text-on-surface-variant font-semibold uppercase">Total Orders</h3>
                <div class="w-9 h-9 rounded-full bg-cream-silk flex items-center justify-center text-heritage-burgundy">
                    <span class="material-symbols-outlined text-lg">shopping_bag</span>
                </div>
            </div>
            <div>
                <div class="font-headline-md text-2xl text-on-background mb-1 font-bold">{{ number_format($totalOrdersCount) }}</div>
                <div class="flex items-center gap-1 text-emerald-700 text-xs font-data-tabular font-medium">
                    <span class="material-symbols-outlined text-sm">trending_up</span>
                    <span>{{ $rangeOrdersCount }} in selected period</span>
                </div>
            </div>
        </div>

        <!-- KPI 3: Active Customers -->
        <div class="bg-surface-container-lowest p-6 rounded-2xl border border-border-subtle shadow-xs flex flex-col justify-between hover:border-heritage-burgundy/40 transition-colors">
            <div class="flex justify-between items-start mb-4">
                <h3 class="font-label-caps text-xs text-on-surface-variant font-semibold uppercase">Registered Customers</h3>
                <div class="w-9 h-9 rounded-full bg-cream-silk flex items-center justify-center text-heritage-burgundy">
                    <span class="material-symbols-outlined text-lg">group</span>
                </div>
            </div>
            <div>
                <div class="font-headline-md text-2xl text-on-background mb-1 font-bold">{{ number_format($activeCustomersCount) }}</div>
                <div class="flex items-center gap-1 text-emerald-700 text-xs font-data-tabular font-medium">
                    <span class="material-symbols-outlined text-sm">person_check</span>
                    <span>VIP Customer Directory</span>
                </div>
            </div>
        </div>

        <!-- KPI 4: Average Order Value -->
        <div class="bg-surface-container-lowest p-6 rounded-2xl border border-border-subtle shadow-xs flex flex-col justify-between hover:border-heritage-burgundy/40 transition-colors">
            <div class="flex justify-between items-start mb-4">
                <h3 class="font-label-caps text-xs text-on-surface-variant font-semibold uppercase">Avg. Order Value (AOV)</h3>
                <div class="w-9 h-9 rounded-full bg-cream-silk flex items-center justify-center text-heritage-burgundy">
                    <span class="material-symbols-outlined text-lg">monitoring</span>
                </div>
            </div>
            <div>
                <div class="font-headline-md text-2xl text-on-background mb-1 font-bold">₹{{ number_format($avgOrderValue) }}</div>
                <div class="flex items-center gap-1 text-heritage-burgundy text-xs font-data-tabular font-medium">
                    <span class="material-symbols-outlined text-sm">inventory_2</span>
                    <span>{{ $totalProductsCount }} Catalog SKUs active</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts & Alerts Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
        <!-- Revenue Trajectory Chart (2 Cols) -->
        <div class="lg:col-span-2 bg-surface-container-lowest p-6 rounded-2xl border border-border-subtle shadow-xs flex flex-col">
            <div class="flex justify-between items-center mb-6">
                <div>
                    <h3 class="font-title-lg text-base font-semibold text-on-background">Revenue Trajectory</h3>
                    <p class="text-xs text-on-surface-variant">Daily e-commerce checkout sales performance</p>
                </div>
                <span class="font-data-tabular text-xs font-bold text-heritage-burgundy bg-cream-silk px-3 py-1 rounded-full border border-muted-gold/30">
                    Peak: ₹{{ number_format($maxDaily) }}
                </span>
            </div>

            <div class="flex-1 relative min-h-[260px] w-full bg-surface-container-low/40 rounded-xl flex items-center justify-center overflow-hidden border border-border-subtle p-4">
                <div class="absolute inset-0 p-4 flex items-end gap-2 px-8">
                    <!-- Y-axis scale labels -->
                    <div class="absolute left-3 top-4 bottom-8 flex flex-col justify-between text-[10px] text-on-surface-variant font-data-tabular">
                        <span>₹{{ number_format($maxDaily) }}</span>
                        <span>₹{{ number_format($maxDaily * 0.66) }}</span>
                        <span>₹{{ number_format($maxDaily * 0.33) }}</span>
                        <span>0</span>
                    </div>

                    <!-- SVG Dynamic Sales Wave Graph -->
                    <svg class="w-full h-full" preserveAspectRatio="none" viewBox="0 0 100 100">
                        <line class="text-border-subtle/60" stroke="currentColor" stroke-width="0.3" x1="0" x2="100" y1="15" y2="15"></line>
                        <line class="text-border-subtle/60" stroke="currentColor" stroke-width="0.3" x1="0" x2="100" y1="40" y2="40"></line>
                        <line class="text-border-subtle/60" stroke="currentColor" stroke-width="0.3" x1="0" x2="100" y1="65" y2="65"></line>
                        <line class="text-border-subtle/60" stroke="currentColor" stroke-width="0.3" x1="0" x2="100" y1="90" y2="90"></line>
                        
                        <!-- Smooth Line Path from Database -->
                        <path d="{{ $svgLinePath }}"
                            fill="none" stroke="#600018" stroke-width="2.5" stroke-linecap="round" vector-effect="non-scaling-stroke"></path>
                        
                        <!-- Shaded Area Gradient -->
                        <path d="{{ $svgAreaPath }}"
                            fill="url(#revGrad)" opacity="0.25"></path>

                        <defs>
                            <linearGradient id="revGrad" x1="0" x2="0" y1="0" y2="1">
                                <stop offset="0%" stop-color="#600018"></stop>
                                <stop offset="100%" stop-color="#600018" stop-opacity="0"></stop>
                            </linearGradient>
                        </defs>
                    </svg>

                    <!-- X-axis Labels -->
                    <div class="absolute left-12 right-6 bottom-2 flex justify-between text-[11px] text-on-surface-variant font-data-tabular">
                        @foreach($dayLabels as $label)
                            <span>{{ $label }}</span>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        <!-- Low Stock & Category Donut (1 Col) -->
        <div class="flex flex-col gap-6">
            <!-- Low Stock Inventory Alerts -->
            <div class="bg-surface-container-lowest p-6 rounded-2xl border {{ $lowStockProducts->count() > 0 ? 'border-error-container/80' : 'border-border-subtle' }} shadow-xs relative overflow-hidden">
                <div class="flex items-center justify-between mb-3">
                    <div class="flex items-center gap-2 text-error">
                        <span class="material-symbols-outlined text-xl">warning</span>
                        <h3 class="font-title-lg text-sm font-semibold">Low Stock Alerts</h3>
                    </div>
                    <span class="font-data-tabular text-xs font-bold text-error bg-red-50 px-2 py-0.5 rounded-full border border-red-200">
                        {{ $lowStockProducts->count() }} Alert{{ $lowStockProducts->count() === 1 ? '' : 's' }}
                    </span>
                </div>
                
                @if($lowStockProducts->count() > 0)
                    <div class="space-y-2.5">
                        @foreach($lowStockProducts as $lowProd)
                            <div class="flex items-center gap-3 bg-surface-container-low p-2.5 rounded-xl border border-border-subtle">
                                <div class="w-10 h-12 bg-surface rounded-lg overflow-hidden shrink-0 border border-border-subtle">
                                    <img class="w-full h-full object-cover" src="{{ $lowProd->main_image }}" alt="{{ $lowProd->name }}" />
                                </div>
                                <div class="flex-1 min-w-0">
                                    <h4 class="font-body-md text-xs font-semibold text-charcoal-text truncate">{{ $lowProd->name }}</h4>
                                    <p class="font-data-tabular text-[10px] text-on-surface-variant">SKU: {{ $lowProd->sku }}</p>
                                    <span class="font-data-tabular text-xs text-error font-bold inline-block">Only {{ $lowProd->stock }} left</span>
                                </div>
                                <a href="{{ route('admin.products.edit', $lowProd) }}" class="text-xs font-label-caps uppercase text-heritage-burgundy font-bold hover:underline p-1" title="Restock">
                                    Edit
                                </a>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="py-4 text-center text-xs text-emerald-800 bg-emerald-50 rounded-xl border border-emerald-200">
                        <span class="material-symbols-outlined text-xl block mb-1">check_circle</span>
                        All products have healthy inventory levels.
                    </div>
                @endif

                <a href="{{ route('admin.products.index') }}" class="w-full mt-3 py-2 border border-border-subtle rounded-lg text-heritage-burgundy font-label-caps text-xs uppercase hover:bg-surface-container-low transition-colors font-bold text-center block">
                    Manage Full Inventory &rarr;
                </a>
            </div>

            <!-- Orders by Category Dynamic Donut Chart -->
            <div class="bg-surface-container-lowest p-6 rounded-2xl border border-border-subtle shadow-xs flex-1 flex flex-col justify-between">
                <div class="flex justify-between items-center mb-2">
                    <h3 class="font-title-lg text-sm font-semibold text-charcoal-text">Catalog by Category</h3>
                    <span class="text-xs font-label-caps text-on-surface-variant font-bold">{{ $totalCategoryProducts }} Total</span>
                </div>

                <!-- Dynamic Donut Graphic -->
                <div class="flex items-center justify-center relative my-3">
                    <div class="w-28 h-28 rounded-full relative shadow-xs"
                        style="background: {{ $donutGradient }};">
                        <div class="absolute inset-0 m-auto w-16 h-16 bg-surface-container-lowest rounded-full flex flex-col items-center justify-center shadow-xs">
                            <span class="font-headline-md text-sm font-bold text-charcoal-text">{{ $totalCategoryProducts }}</span>
                            <span class="text-[9px] text-on-surface-variant font-label-caps uppercase">Items</span>
                        </div>
                    </div>
                </div>

                <!-- Dynamic Category Legend -->
                <div class="mt-2 space-y-1.5 text-xs font-body-md max-h-[140px] overflow-y-auto pr-1">
                    @foreach($categoryBreakdown as $catItem)
                        <div class="flex justify-between items-center">
                            <div class="flex items-center gap-2">
                                <div class="w-2.5 h-2.5 rounded-sm" style="background-color: {{ $catItem['color'] }};"></div>
                                <span class="text-charcoal-text truncate max-w-[120px]">{{ $catItem['name'] }}</span>
                            </div>
                            <span class="font-data-tabular font-bold text-charcoal-text">{{ $catItem['percentage'] }}%</span>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Customer Orders Table -->
    <div class="bg-surface-container-lowest rounded-2xl border border-border-subtle shadow-xs overflow-hidden">
        <div class="p-5 border-b border-border-subtle flex justify-between items-center bg-warm-ivory/50">
            <h3 class="font-title-lg text-base font-semibold text-heritage-burgundy">Live Customer Orders</h3>
            <a href="{{ route('admin.orders.index') }}" class="text-xs font-label-caps uppercase text-heritage-burgundy hover:underline font-bold">
                View All Orders &rarr;
            </a>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-sm">
                <thead>
                    <tr class="bg-warm-ivory/30 border-b border-border-subtle text-xs font-label-caps text-on-surface-variant">
                        <th class="px-6 py-3.5 font-semibold uppercase">Order ID</th>
                        <th class="px-6 py-3.5 font-semibold uppercase">Customer</th>
                        <th class="px-6 py-3.5 font-semibold uppercase">Date</th>
                        <th class="px-6 py-3.5 font-semibold uppercase text-right">Amount</th>
                        <th class="px-6 py-3.5 font-semibold uppercase">Fulfillment</th>
                        <th class="px-6 py-3.5 font-semibold uppercase text-center">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-border-subtle font-body-md text-xs">
                    @forelse($recentOrders as $ord)
                        <tr class="hover:bg-warm-ivory/50 transition-colors">
                            <td class="px-6 py-4 font-data-tabular font-bold text-heritage-burgundy">
                                <a href="{{ route('admin.orders.show', $ord->order_number) }}" class="hover:underline">#{{ $ord->order_number }}</a>
                            </td>
                            <td class="px-6 py-4 font-medium text-charcoal-text">
                                {{ $ord->customer_name }}
                                <span class="text-[11px] text-on-surface-variant block">{{ $ord->customer_email }}</span>
                            </td>
                            <td class="px-6 py-4 text-on-surface-variant">{{ $ord->created_at->format('M d, Y') }}</td>
                            <td class="px-6 py-4 text-right font-data-tabular font-bold text-charcoal-text">{{ $ord->formatted_total }}</td>
                            <td class="px-6 py-4">
                                @php
                                    $statusStyles = [
                                        'processing' => 'bg-blue-50 text-blue-800 border-blue-200',
                                        'packed' => 'bg-purple-50 text-purple-800 border-purple-200',
                                        'shipped' => 'bg-indigo-50 text-indigo-800 border-indigo-200',
                                        'delivered' => 'bg-emerald-50 text-emerald-800 border-emerald-200',
                                        'cancelled' => 'bg-red-50 text-error border-red-200',
                                    ];
                                    $style = $statusStyles[$ord->status] ?? 'bg-gray-50 text-gray-800 border-gray-200';
                                @endphp
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[11px] font-semibold uppercase font-label-caps border {{ $style }}">
                                    {{ ucfirst($ord->status) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <a href="{{ route('admin.orders.show', $ord->order_number) }}" class="inline-flex items-center gap-1 text-heritage-burgundy hover:underline font-bold font-label-caps text-xs">
                                    <span>Inspect</span>
                                    <span class="material-symbols-outlined text-sm">arrow_forward</span>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center text-on-surface-variant">
                                <span class="material-symbols-outlined text-3xl text-heritage-burgundy/40 block mb-1">shopping_bag</span>
                                No customer orders recorded yet.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-layouts.admin>
