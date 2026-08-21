<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Carbon\Carbon;
use Carbon\CarbonImmutable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class ReportController extends Controller
{
    /**
     * Financial reporting: revenue, profit, and order volume broken down by
     * week, month, or year, with period-over-period comparison plus payment
     * method, order status, and top-product breakdowns for the same window.
     */
    public function index(Request $request): View
    {
        $period = in_array($request->query('period'), ['week', 'month', 'year'], true)
            ? $request->query('period')
            : 'month';

        $bucketCount = match ($period) {
            'week' => 8,
            'year' => 5,
            default => 12,
        };

        $buckets = $this->buildBuckets($period, $bucketCount);
        $windowStart = $buckets[0]['start'];
        $windowEnd = $buckets[count($buckets) - 1]['end'];

        foreach ($buckets as &$bucket) {
            $bucket['revenue'] = (float) Order::where('payment_status', 'paid')
                ->whereBetween('created_at', [$bucket['start'], $bucket['end']])
                ->sum('total_amount');

            $bucket['profit'] = (float) $this->profitQuery($bucket['start'], $bucket['end'])->value('profit') ?? 0.0;

            $bucket['orders_placed'] = Order::whereBetween('created_at', [$bucket['start'], $bucket['end']])->count();
            $bucket['orders_paid'] = Order::where('payment_status', 'paid')
                ->whereBetween('created_at', [$bucket['start'], $bucket['end']])
                ->count();

            $bucket['aov'] = $bucket['orders_paid'] > 0 ? $bucket['revenue'] / $bucket['orders_paid'] : 0.0;
        }
        unset($bucket);

        $current = $buckets[count($buckets) - 1];
        $previous = $buckets[count($buckets) - 2] ?? null;

        $summary = [
            'revenue' => $current['revenue'],
            'profit' => $current['profit'],
            'orders' => $current['orders_paid'],
            'aov' => $current['aov'],
            'revenue_change' => $this->percentChange($previous['revenue'] ?? null, $current['revenue']),
            'profit_change' => $this->percentChange($previous['profit'] ?? null, $current['profit']),
            'orders_change' => $this->percentChange($previous['orders_paid'] ?? null, $current['orders_paid']),
            'aov_change' => $this->percentChange($previous['aov'] ?? null, $current['aov']),
        ];

        // Cost-data transparency: profit is only ever computed from order
        // items that have a cost_price snapshot, so admins need to see how
        // much of the window's sales that actually covers.
        $costCoverage = $this->costCoverage($windowStart, $windowEnd);

        $paymentBreakdown = Order::where('payment_status', 'paid')
            ->whereBetween('created_at', [$windowStart, $windowEnd])
            ->select('payment_method', DB::raw('COUNT(*) as orders'), DB::raw('SUM(total_amount) as revenue'))
            ->groupBy('payment_method')
            ->orderByDesc('revenue')
            ->get();

        $statusBreakdown = Order::whereBetween('created_at', [$windowStart, $windowEnd])
            ->select('status', DB::raw('COUNT(*) as orders'))
            ->groupBy('status')
            ->orderByDesc('orders')
            ->get();

        // Plain query builder, not Eloquent::join() — see profitQuery() for why
        // hydrating aggregate rows into OrderItem models is unsafe here.
        $topProducts = DB::table('order_items')
            ->join('orders', 'orders.id', '=', 'order_items.order_id')
            ->where('orders.payment_status', 'paid')
            ->whereBetween('orders.created_at', [$windowStart, $windowEnd])
            ->select(
                'order_items.product_name',
                DB::raw('SUM(order_items.total) as revenue'),
                DB::raw('SUM(order_items.quantity) as units')
            )
            ->groupBy('order_items.product_name')
            ->orderByDesc('revenue')
            ->limit(5)
            ->get();

        return view('admin.reports.index', [
            'period' => $period,
            'buckets' => $buckets,
            'summary' => $summary,
            'costCoverage' => $costCoverage,
            'paymentBreakdown' => $paymentBreakdown,
            'statusBreakdown' => $statusBreakdown,
            'topProducts' => $topProducts,
        ]);
    }

    /**
     * Build the trailing set of period buckets, oldest first, ending with
     * the current (in-progress) period.
     *
     * @return list<array{label: string, start: CarbonImmutable, end: CarbonImmutable}>
     */
    private function buildBuckets(string $period, int $count): array
    {
        $buckets = [];

        for ($i = $count - 1; $i >= 0; $i--) {
            [$start, $end, $label] = match ($period) {
                'week' => [
                    CarbonImmutable::now()->subWeeks($i)->startOfWeek(),
                    CarbonImmutable::now()->subWeeks($i)->endOfWeek(),
                    CarbonImmutable::now()->subWeeks($i)->startOfWeek()->format('M j'),
                ],
                'year' => [
                    CarbonImmutable::now()->subYears($i)->startOfYear(),
                    CarbonImmutable::now()->subYears($i)->endOfYear(),
                    CarbonImmutable::now()->subYears($i)->format('Y'),
                ],
                default => [
                    CarbonImmutable::now()->subMonths($i)->startOfMonth(),
                    CarbonImmutable::now()->subMonths($i)->endOfMonth(),
                    CarbonImmutable::now()->subMonths($i)->format('M Y'),
                ],
            };

            $buckets[] = ['label' => $label, 'start' => $start, 'end' => $end];
        }

        return $buckets;
    }

    /**
     * Deliberately uses the plain query builder (DB::table), not the Eloquent
     * OrderItem builder: hydrating a bare SUM(...) row into an OrderItem model
     * would trigger OrderItem::getProfitAttribute(), which reads price/cost_price
     * off that (mostly empty) hydrated model and silently returns null instead
     * of this query's actual aggregate.
     */
    private function profitQuery(Carbon|CarbonImmutable $start, Carbon|CarbonImmutable $end)
    {
        return DB::table('order_items')
            ->join('orders', 'orders.id', '=', 'order_items.order_id')
            ->where('orders.payment_status', 'paid')
            ->whereBetween('orders.created_at', [$start, $end])
            ->whereNotNull('order_items.cost_price')
            ->selectRaw('SUM((order_items.price - order_items.cost_price) * order_items.quantity) as profit');
    }

    /**
     * @return array{known: int, total: int, percent: int}
     */
    private function costCoverage(Carbon|CarbonImmutable $start, Carbon|CarbonImmutable $end): array
    {
        $base = DB::table('order_items')
            ->join('orders', 'orders.id', '=', 'order_items.order_id')
            ->where('orders.payment_status', 'paid')
            ->whereBetween('orders.created_at', [$start, $end]);

        $total = (clone $base)->count();
        $known = (clone $base)->whereNotNull('order_items.cost_price')->count();

        return [
            'known' => $known,
            'total' => $total,
            'percent' => $total > 0 ? (int) round(($known / $total) * 100) : 0,
        ];
    }

    private function percentChange(?float $previous, float $current): ?float
    {
        if ($previous === null || $previous == 0.0) {
            return null;
        }

        return round((($current - $previous) / $previous) * 100, 1);
    }
}
