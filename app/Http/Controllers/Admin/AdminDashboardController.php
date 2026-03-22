<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Message;
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

        $watchesWithSum = Watch::withSum('inventorySizes', 'quantity')->get();

        $lowStockCount = $watchesWithSum->filter(function ($w) use ($lowThreshold) {
            $t = (int) ($w->inventory_sizes_sum_quantity ?? 0);

            return $t > 0 && $t < $lowThreshold;
        })->count();

        $outOfStockCount = $watchesWithSum->filter(function ($w) {
            return (int) ($w->inventory_sizes_sum_quantity ?? 0) <= 0;
        })->count();

        $totalProducts = Watch::query()->count();

        $recentMessages = Message::query()
            ->with('user')
            ->orderByDesc('created_at')
            ->limit(6)
            ->get();

        return view('admin.dashboard', compact(
            'newCustomers',
            'recentOrders',
            'lowStockCount',
            'outOfStockCount',
            'totalProducts',
            'recentMessages'
        ));
    }
}
