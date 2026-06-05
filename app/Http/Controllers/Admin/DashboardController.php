<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use App\Models\Category;
use App\Models\Coupon;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __invoke(): View
    {
        $stats = [
            'revenue' => Order::query()->sum('grand_total'),
            'orders' => Order::query()->count(),
            'users' => User::query()->count(),
            'products' => Product::query()->count(),
            'low_stock' => Product::query()->where('stock', '<', 10)->count(),
            'new_customers' => User::query()->where('created_at', '>=', now()->subDays(30))->count(),
        ];

        $latestOrders = Order::query()->with('user')->latest()->take(8)->get();
        $topProducts = Product::query()->orderByDesc('rating')->take(5)->get();
        $catalogHealth = [
            'categories' => Category::query()->count(),
            'banners' => Banner::query()->count(),
            'coupons' => Coupon::query()->count(),
        ];

        $salesSummary = [
            'revenue_last_30_days' => Order::query()->where('placed_at', '>=', now()->subDays(30))->sum('grand_total'),
            'orders_last_30_days' => Order::query()->where('placed_at', '>=', now()->subDays(30))->count(),
            'average_order_value' => Order::query()->count() ? round(Order::query()->sum('grand_total') / Order::query()->count(), 2) : 0,
        ];

        $salesByPayment = Order::query()
            ->selectRaw('payment_method, SUM(grand_total) as total')
            ->groupBy('payment_method')
            ->pluck('total', 'payment_method')
            ->toArray();

        return view('admin.dashboard', compact('stats', 'latestOrders', 'topProducts', 'catalogHealth', 'salesSummary', 'salesByPayment'));
    }
}
