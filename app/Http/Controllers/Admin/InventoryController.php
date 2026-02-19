<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Inventory;
use App\Models\InventoryMovement;
use App\Models\Watch;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class InventoryController extends Controller
{
    public function index(Request $request)
    {
        $q = $request->get('q');
        $status = $request->get('status');
        $lowThreshold = 5;

        $watches = Watch::query()
            ->with('inventory')
            ->when($q, function ($query) use ($q) {
                $query->where('name', 'like', "%{$q}%");
            })
            ->get()
            ->filter(function ($watch) use ($status, $lowThreshold) {
                if (!$status) return true;
                $qty = $watch->inventory?->quantity ?? 0;
                return match ($status) {
                    'in' => $qty >= $lowThreshold,
                    'low' => $qty > 0 && $qty < $lowThreshold,
                    'out' => $qty <= 0,
                    default => true,
                };
            });

        $lowStockCount = Inventory::query()->where('quantity', '>', 0)->where('quantity', '<', $lowThreshold)->count();
        $outOfStockCount = Inventory::query()->where('quantity', '<=', 0)->count();

        return view('admin.inventory.index', compact('watches', 'q', 'status', 'lowThreshold', 'lowStockCount', 'outOfStockCount'));
    }

    public function incoming(Request $request)
    {
        $validated = $request->validate([
            'watch_id' => ['required', 'exists:watches,id'],
            'quantity' => ['required', 'integer', 'min:1'],
            'note' => ['nullable', 'string', 'max:255'],
        ]);

        DB::transaction(function () use ($validated) {
            $inventory = Inventory::query()->firstOrCreate(
                ['watch_id' => $validated['watch_id']],
                ['quantity' => 0]
            );

            $inventory->quantity = (int)$inventory->quantity + (int)$validated['quantity'];
            $inventory->save();

            InventoryMovement::create([
                'watch_id' => $validated['watch_id'],
                'created_by' => auth()->id(),
                'type' => 'in',
                'quantity' => (int)$validated['quantity'],
                'note' => $validated['note'] ?? 'Incoming stock',
            ]);
        });

        return back()->with('status', 'Incoming stock recorded and inventory updated.');
    }
}
