<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\InventoryMovement;
use App\Models\Order;
use App\Models\Watch;
use Illuminate\Support\Facades\DB;

class ReportsController extends Controller
{
    public function index()
    {
        $lowThreshold = 5;
        $stock = Watch::query()->with('inventorySizes')->orderBy('name')->get();

        $ordersByStatus = Order::query()
            ->select('status', DB::raw('count(*) as c'))
            ->groupBy('status')
            ->pluck('c', 'status');

        $movementByType = InventoryMovement::query()
            ->select('type', DB::raw('sum(quantity) as q'))
            ->groupBy('type')
            ->pluck('q', 'type');

        $watchesWithSum = Watch::withSum('inventorySizes', 'quantity')->get();

        $outOfStockCount = $watchesWithSum->filter(function ($w) {
            return (int) ($w->inventory_sizes_sum_quantity ?? 0) <= 0;
        })->count();

        $lowStockCount = $watchesWithSum->filter(function ($w) use ($lowThreshold) {
            $t = (int) ($w->inventory_sizes_sum_quantity ?? 0);

            return $t > 0 && $t < $lowThreshold;
        })->count();

        return view('admin.reports.index', compact('stock', 'ordersByStatus', 'movementByType', 'outOfStockCount', 'lowStockCount', 'lowThreshold'));
    }
}
