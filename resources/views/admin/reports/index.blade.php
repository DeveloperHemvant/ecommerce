<x-layouts.admin title="Financial Reports - Sonakshi Admin" active="reports">
    <!-- Top Header Area -->
    <header class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
        <div>
            <h1 class="font-headline-lg text-heritage-burgundy">Financial Reports</h1>
            <p class="font-body-md text-sm text-on-surface-variant mt-1">Revenue, profit, and order volume — broken down by week, month, or year.</p>
        </div>
        <div class="inline-flex rounded-xl border border-border-subtle bg-surface-container-lowest p-1 shadow-xs">
            <a href="{{ route('admin.reports.index', ['period' => 'week']) }}"
                class="px-4 py-1.5 rounded-lg font-data-tabular text-xs transition-colors {{ $period === 'week' ? 'bg-cream-silk text-heritage-burgundy font-bold shadow-xs' : 'text-on-surface-variant hover:text-heritage-burgundy' }}">
                Weekly
            </a>
            <a href="{{ route('admin.reports.index', ['period' => 'month']) }}"
                class="px-4 py-1.5 rounded-lg font-data-tabular text-xs transition-colors {{ $period === 'month' ? 'bg-cream-silk text-heritage-burgundy font-bold shadow-xs' : 'text-on-surface-variant hover:text-heritage-burgundy' }}">
                Monthly
            </a>
            <a href="{{ route('admin.reports.index', ['period' => 'year']) }}"
                class="px-4 py-1.5 rounded-lg font-data-tabular text-xs transition-colors {{ $period === 'year' ? 'bg-cream-silk text-heritage-burgundy font-bold shadow-xs' : 'text-on-surface-variant hover:text-heritage-burgundy' }}">
                Yearly
            </a>
        </div>
    </header>

    @if($costCoverage['total'] > 0 && $costCoverage['percent'] < 100)
        <div class="mb-6 p-4 bg-amber-50 border border-amber-200 text-amber-900 text-xs rounded-xl flex items-center justify-between gap-3">
            <span class="flex items-center gap-2">
                <span class="material-symbols-outlined text-base">info</span>
                <span>Profit is calculated from <strong>{{ $costCoverage['percent'] }}%</strong> of items sold in this window ({{ $costCoverage['known'] }} of {{ $costCoverage['total'] }}) — the rest have no Cost Price set, so the profit figure below is an undercount.</span>
            </span>
            <a href="{{ route('admin.products.index') }}" class="shrink-0 font-bold underline hover:no-underline">Set Cost Prices</a>
        </div>
    @endif

    @php
        $periodNoun = match($period) { 'week' => 'this week', 'year' => 'this year', default => 'this month' };
        $deltaChip = function (?float $change) {
            if ($change === null) {
                return ['text' => 'No prior data', 'class' => 'text-on-surface-variant', 'icon' => 'remove'];
            }
            if ($change > 0) {
                return ['text' => '+'.number_format($change, 1).'% vs last period', 'class' => 'text-emerald-700', 'icon' => 'trending_up'];
            }
            if ($change < 0) {
                return ['text' => number_format($change, 1).'% vs last period', 'class' => 'text-error', 'icon' => 'trending_down'];
            }
            return ['text' => 'No change vs last period', 'class' => 'text-on-surface-variant', 'icon' => 'trending_flat'];
        };
    @endphp

    <!-- KPI Bento Grid (4 Cards) -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- KPI 1: Revenue -->
        <div class="bg-surface-container-lowest p-6 rounded-2xl border border-border-subtle shadow-xs flex flex-col justify-between hover:border-heritage-burgundy/40 transition-colors">
            <div class="flex justify-between items-start mb-4">
                <h3 class="font-label-caps text-xs text-on-surface-variant font-semibold uppercase">Revenue &bull; {{ $periodNoun }}</h3>
                <div class="w-9 h-9 rounded-full bg-cream-silk flex items-center justify-center text-heritage-burgundy">
                    <span class="material-symbols-outlined text-lg">payments</span>
                </div>
            </div>
            <div>
                <div class="font-headline-md text-2xl text-on-background mb-1 font-bold">₹{{ number_format($summary['revenue']) }}</div>
                @php $chip = $deltaChip($summary['revenue_change']); @endphp
                <div class="flex items-center gap-1 {{ $chip['class'] }} text-xs font-data-tabular font-medium">
                    <span class="material-symbols-outlined text-sm">{{ $chip['icon'] }}</span>
                    <span>{{ $chip['text'] }}</span>
                </div>
            </div>
        </div>

        <!-- KPI 2: Profit -->
        <div class="bg-surface-container-lowest p-6 rounded-2xl border border-border-subtle shadow-xs flex flex-col justify-between hover:border-heritage-burgundy/40 transition-colors">
            <div class="flex justify-between items-start mb-4">
                <h3 class="font-label-caps text-xs text-on-surface-variant font-semibold uppercase">Gross Profit &bull; {{ $periodNoun }}</h3>
                <div class="w-9 h-9 rounded-full bg-cream-silk flex items-center justify-center text-heritage-burgundy">
                    <span class="material-symbols-outlined text-lg">savings</span>
                </div>
            </div>
            <div>
                <div class="font-headline-md text-2xl text-on-background mb-1 font-bold">₹{{ number_format($summary['profit']) }}</div>
                @php $chip = $deltaChip($summary['profit_change']); @endphp
                <div class="flex items-center gap-1 {{ $chip['class'] }} text-xs font-data-tabular font-medium">
                    <span class="material-symbols-outlined text-sm">{{ $chip['icon'] }}</span>
                    <span>{{ $chip['text'] }}</span>
                </div>
            </div>
        </div>

        <!-- KPI 3: Paid Orders -->
        <div class="bg-surface-container-lowest p-6 rounded-2xl border border-border-subtle shadow-xs flex flex-col justify-between hover:border-heritage-burgundy/40 transition-colors">
            <div class="flex justify-between items-start mb-4">
                <h3 class="font-label-caps text-xs text-on-surface-variant font-semibold uppercase">Paid Orders &bull; {{ $periodNoun }}</h3>
                <div class="w-9 h-9 rounded-full bg-cream-silk flex items-center justify-center text-heritage-burgundy">
                    <span class="material-symbols-outlined text-lg">shopping_bag</span>
                </div>
            </div>
            <div>
                <div class="font-headline-md text-2xl text-on-background mb-1 font-bold">{{ number_format($summary['orders']) }}</div>
                @php $chip = $deltaChip($summary['orders_change']); @endphp
                <div class="flex items-center gap-1 {{ $chip['class'] }} text-xs font-data-tabular font-medium">
                    <span class="material-symbols-outlined text-sm">{{ $chip['icon'] }}</span>
                    <span>{{ $chip['text'] }}</span>
                </div>
            </div>
        </div>

        <!-- KPI 4: AOV -->
        <div class="bg-surface-container-lowest p-6 rounded-2xl border border-border-subtle shadow-xs flex flex-col justify-between hover:border-heritage-burgundy/40 transition-colors">
            <div class="flex justify-between items-start mb-4">
                <h3 class="font-label-caps text-xs text-on-surface-variant font-semibold uppercase">Avg. Order Value</h3>
                <div class="w-9 h-9 rounded-full bg-cream-silk flex items-center justify-center text-heritage-burgundy">
                    <span class="material-symbols-outlined text-lg">monitoring</span>
                </div>
            </div>
            <div>
                <div class="font-headline-md text-2xl text-on-background mb-1 font-bold">₹{{ number_format($summary['aov']) }}</div>
                @php $chip = $deltaChip($summary['aov_change']); @endphp
                <div class="flex items-center gap-1 {{ $chip['class'] }} text-xs font-data-tabular font-medium">
                    <span class="material-symbols-outlined text-sm">{{ $chip['icon'] }}</span>
                    <span>{{ $chip['text'] }}</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Revenue vs Profit Trend Chart -->
    <div class="bg-surface-container-lowest p-6 rounded-2xl border border-border-subtle shadow-xs flex flex-col mb-8">
        <div class="flex justify-between items-center mb-6">
            <div>
                <h3 class="font-title-lg text-base font-semibold text-on-background">Revenue vs. Profit</h3>
                <p class="text-xs text-on-surface-variant">{{ ucfirst($period) }}ly trend, oldest to most recent</p>
            </div>
        </div>
        <div class="flex-1 relative min-h-[280px] w-full bg-surface-container-low/40 rounded-xl overflow-hidden border border-border-subtle p-4">
            <canvas id="trendChart" height="240"></canvas>
        </div>
    </div>

    <!-- Period Breakdown Table -->
    <div class="bg-surface-container-lowest rounded-2xl border border-border-subtle shadow-xs overflow-hidden mb-8">
        <div class="p-5 border-b border-border-subtle bg-warm-ivory/50">
            <h3 class="font-title-lg text-base font-semibold text-heritage-burgundy">{{ ucfirst($period) }}ly Breakdown</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-sm">
                <thead>
                    <tr class="bg-warm-ivory/30 border-b border-border-subtle text-xs font-label-caps text-on-surface-variant">
                        <th class="px-6 py-3.5 font-semibold uppercase">Period</th>
                        <th class="px-6 py-3.5 font-semibold uppercase text-right">Revenue</th>
                        <th class="px-6 py-3.5 font-semibold uppercase text-right">Profit</th>
                        <th class="px-6 py-3.5 font-semibold uppercase text-right">Orders Placed</th>
                        <th class="px-6 py-3.5 font-semibold uppercase text-right">Paid Orders</th>
                        <th class="px-6 py-3.5 font-semibold uppercase text-right">AOV</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-border-subtle font-body-md text-xs">
                    @foreach(array_reverse($buckets) as $bucket)
                        <tr class="hover:bg-warm-ivory/50 transition-colors">
                            <td class="px-6 py-3.5 font-semibold text-charcoal-text">{{ $bucket['label'] }}</td>
                            <td class="px-6 py-3.5 text-right font-data-tabular font-bold text-charcoal-text">₹{{ number_format($bucket['revenue']) }}</td>
                            <td class="px-6 py-3.5 text-right font-data-tabular text-heritage-burgundy font-bold">₹{{ number_format($bucket['profit']) }}</td>
                            <td class="px-6 py-3.5 text-right font-data-tabular text-on-surface-variant">{{ number_format($bucket['orders_placed']) }}</td>
                            <td class="px-6 py-3.5 text-right font-data-tabular text-on-surface-variant">{{ number_format($bucket['orders_paid']) }}</td>
                            <td class="px-6 py-3.5 text-right font-data-tabular text-on-surface-variant">₹{{ number_format($bucket['aov']) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Payment Methods, Order Status & Top Products -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Payment Method Breakdown -->
        <div class="bg-surface-container-lowest p-6 rounded-2xl border border-border-subtle shadow-xs">
            <h3 class="font-title-lg text-sm font-semibold text-charcoal-text mb-4">Payment Methods</h3>
            @php $maxPaymentRevenue = max(1, $paymentBreakdown->max('revenue') ?? 1); @endphp
            <div class="space-y-3.5">
                @forelse($paymentBreakdown as $row)
                    <div>
                        <div class="flex justify-between items-baseline text-xs mb-1">
                            <span class="font-semibold text-charcoal-text">{{ $row->payment_method }}</span>
                            <span class="font-data-tabular text-on-surface-variant">₹{{ number_format($row->revenue) }} &bull; {{ $row->orders }} {{ Str::plural('order', $row->orders) }}</span>
                        </div>
                        <div class="h-2 bg-surface-container-low rounded-full overflow-hidden">
                            <div class="h-full bg-heritage-burgundy rounded-full" style="width: {{ round(($row->revenue / $maxPaymentRevenue) * 100) }}%"></div>
                        </div>
                    </div>
                @empty
                    <p class="text-xs text-on-surface-variant italic py-4 text-center">No paid orders in this window yet.</p>
                @endforelse
            </div>
        </div>

        <!-- Order Status Breakdown -->
        <div class="bg-surface-container-lowest p-6 rounded-2xl border border-border-subtle shadow-xs">
            <h3 class="font-title-lg text-sm font-semibold text-charcoal-text mb-4">Order Status</h3>
            @php
                $statusStyles = [
                    'processing' => 'bg-blue-50 text-blue-800 border-blue-200',
                    'packed' => 'bg-purple-50 text-purple-800 border-purple-200',
                    'shipped' => 'bg-indigo-50 text-indigo-800 border-indigo-200',
                    'delivered' => 'bg-emerald-50 text-emerald-800 border-emerald-200',
                    'cancelled' => 'bg-red-50 text-error border-red-200',
                ];
            @endphp
            <div class="space-y-2.5">
                @forelse($statusBreakdown as $row)
                    <div class="flex items-center justify-between">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[11px] font-semibold uppercase font-label-caps border {{ $statusStyles[$row->status] ?? 'bg-gray-50 text-gray-800 border-gray-200' }}">
                            {{ ucfirst($row->status) }}
                        </span>
                        <span class="font-data-tabular text-xs font-bold text-charcoal-text">{{ number_format($row->orders) }}</span>
                    </div>
                @empty
                    <p class="text-xs text-on-surface-variant italic py-4 text-center">No orders in this window yet.</p>
                @endforelse
            </div>
        </div>

        <!-- Top Products -->
        <div class="bg-surface-container-lowest p-6 rounded-2xl border border-border-subtle shadow-xs">
            <h3 class="font-title-lg text-sm font-semibold text-charcoal-text mb-4">Top Products</h3>
            <div class="space-y-3">
                @forelse($topProducts as $index => $row)
                    <div class="flex items-center gap-3">
                        <span class="w-6 h-6 rounded-full bg-cream-silk text-heritage-burgundy text-[11px] font-bold flex items-center justify-center shrink-0">{{ $index + 1 }}</span>
                        <div class="min-w-0 flex-1">
                            <p class="text-xs font-semibold text-charcoal-text truncate">{{ $row->product_name }}</p>
                            <p class="text-[11px] text-on-surface-variant font-data-tabular">{{ $row->units }} sold</p>
                        </div>
                        <span class="font-data-tabular text-xs font-bold text-heritage-burgundy shrink-0">₹{{ number_format($row->revenue) }}</span>
                    </div>
                @empty
                    <p class="text-xs text-on-surface-variant italic py-4 text-center">No sales in this window yet.</p>
                @endforelse
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.4/dist/chart.umd.min.js"></script>
    <script>
        (function () {
            var ctx = document.getElementById('trendChart');
            if (!ctx) return;

            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: @json(collect($buckets)->pluck('label')),
                    datasets: [
                        {
                            label: 'Revenue',
                            data: @json(collect($buckets)->pluck('revenue')),
                            backgroundColor: 'rgba(96, 0, 24, 0.75)',
                            borderRadius: 4,
                        },
                        {
                            label: 'Profit',
                            data: @json(collect($buckets)->pluck('profit')),
                            backgroundColor: 'rgba(197, 179, 88, 0.85)',
                            borderRadius: 4,
                        },
                    ],
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { position: 'top', labels: { boxWidth: 10, font: { size: 11 } } },
                        tooltip: {
                            callbacks: {
                                label: function (item) { return item.dataset.label + ': ₹' + item.raw.toLocaleString('en-IN'); },
                            },
                        },
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                callback: function (value) { return '₹' + value.toLocaleString('en-IN'); },
                            },
                            grid: { color: 'rgba(138, 113, 114, 0.15)' },
                        },
                        x: { grid: { display: false } },
                    },
                },
            });
        })();
    </script>
</x-layouts.admin>
