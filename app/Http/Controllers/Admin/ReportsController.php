<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Inventory;
use App\Models\InventoryMovement;
use App\Models\Order;
use App\Models\Watch;
use Illuminate\Support\Facades\DB;

class ReportsController extends Controller
{
    public function index()
    {
        $lowThreshold = 5;
        $stock = Watch::query()->with('inventory')->orderBy('name')->get();

        $ordersByStatus = Order::query()
            ->select('status', DB::raw('count(*) as c'))
            ->groupBy('status')
            ->pluck('c', 'status');

        $movementByType = InventoryMovement::query()
            ->select('type', DB::raw('sum(quantity) as q'))
            ->groupBy('type')
            ->pluck('q', 'type');

        $outOfStockCount = Inventory::query()->where('quantity', '<=', 0)->count();
        $lowStockCount = Inventory::query()->where('quantity', '>', 0)->where('quantity', '<', $lowThreshold)->count();

        return view('admin.reports.index', compact('stock', 'ordersByStatus', 'movementByType', 'outOfStockCount', 'lowStockCount', 'lowThreshold'));
    }
}
