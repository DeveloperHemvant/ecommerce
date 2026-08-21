<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use App\Models\YouTubeVideo;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * Display the dynamic admin dashboard with live metrics, charts, and orders.
     */
    public function index(Request $request): View
    {
        $range = $request->query('range', '7'); // 'today', '7', '30'

        // 1. Date Range Filtering
        $startDate = match ($range) {
            'today' => Carbon::today(),
            '30' => Carbon::now()->subDays(30),
            default => Carbon::now()->subDays(7),
        };

        // 2. Core KPIs
        $totalRevenue = (float) Order::where('payment_status', 'paid')->sum('total_amount');
        $rangeRevenue = (float) Order::where('payment_status', 'paid')->where('created_at', '>=', $startDate)->sum('total_amount');
        $totalOrdersCount = Order::count();
        $rangeOrdersCount = Order::where('created_at', '>=', $startDate)->count();
        $activeCustomersCount = User::where('role', 'customer')->count();
        $totalProductsCount = Product::count();
        $totalVideosCount = YouTubeVideo::count();

        // Average Order Value (AOV)
        $avgOrderValue = $totalOrdersCount > 0 ? round($totalRevenue / $totalOrdersCount) : 0;

        // 3. Weekly Revenue Chart Data Generation
        $dailyData = [];
        $dayLabels = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i);
            $dayName = $date->format('D');
            $dateString = $date->toDateString();

            $daySum = (float) Order::whereDate('created_at', $dateString)
                ->where('payment_status', 'paid')
                ->sum('total_amount');

            $dailyData[] = $daySum;
            $dayLabels[] = $dayName;
        }

        $maxDaily = max(max($dailyData), 10000);

        // 4. Category Breakdown (for the donut chart)
        $categories = Category::withCount('products')->get();
        $totalCategoryProducts = max(1, $categories->sum('products_count'));
        $categoryBreakdown = [];

        $palette = ['#600018', '#C5B358', '#8B263E', '#D4AF37', '#4A0E17', '#E5D38A'];

        foreach ($categories as $index => $cat) {
            $percentage = round(($cat->products_count / $totalCategoryProducts) * 100);

            $categoryBreakdown[] = [
                'name' => $cat->name,
                'count' => $cat->products_count,
                'percentage' => $percentage,
                'color' => $palette[$index % count($palette)],
            ];
        }

        // 5. Low Stock Alert Products
        $lowStockProducts = Product::whereColumn('stock', '<=', 'low_stock_threshold')
            ->orderBy('stock', 'asc')
            ->take(3)
            ->get();

        // 6. Recent Orders
        $recentOrders = Order::with('items')->latest()->take(6)->get();

        return view('admin.dashboard', compact(
            'range',
            'totalRevenue',
            'rangeRevenue',
            'totalOrdersCount',
            'rangeOrdersCount',
            'activeCustomersCount',
            'totalProductsCount',
            'totalVideosCount',
            'avgOrderValue',
            'dayLabels',
            'dailyData',
            'maxDaily',
            'categoryBreakdown',
            'totalCategoryProducts',
            'lowStockProducts',
            'recentOrders'
        ));
    }
}
