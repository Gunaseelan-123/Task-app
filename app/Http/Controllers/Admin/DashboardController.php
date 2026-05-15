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
        ];

        $latestOrders = Order::query()->with('user')->latest()->take(8)->get();
        $topProducts = Product::query()->orderByDesc('rating')->take(5)->get();
        $catalogHealth = [
            'categories' => Category::query()->count(),
            'banners' => Banner::query()->count(),
            'coupons' => Coupon::query()->count(),
        ];

        return view('admin.dashboard', compact('stats', 'latestOrders', 'topProducts', 'catalogHealth'));
    }
}
