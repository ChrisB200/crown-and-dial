<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Inventory;
use App\Models\Order;
use App\Models\User;
use App\Models\Watch;

class AdminDashboardController extends Controller
{
    public function index()
    {
        $newCustomers = User::query()->where('is_admin', false)->orderByDesc('created_at')->take(5)->get();
        $recentOrders = Order::query()->with('user')->orderByDesc('created_at')->take(5)->get();

        $lowThreshold = 5;
        $lowStockCount = Inventory::query()->where('quantity', '>', 0)->where('quantity', '<', $lowThreshold)->count();
        $outOfStockCount = Inventory::query()->where('quantity', '<=', 0)->count();
        $totalProducts = Watch::query()->count();

        return view('admin.dashboard', compact('newCustomers', 'recentOrders', 'lowStockCount', 'outOfStockCount', 'totalProducts'));
    }
}
