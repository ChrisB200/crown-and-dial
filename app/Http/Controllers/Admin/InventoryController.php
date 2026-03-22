<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\InventoryMovement;
use App\Models\Watch;
use App\Models\WatchInventorySize;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class InventoryController extends Controller
{
    public function index(Request $request)
    {
        $q = $request->get('q');
        $status = $request->get('status');
        $lowThreshold = 5;

        $watches = Watch::query()
            ->with('inventorySizes')
            ->when($q, function ($query) use ($q) {
                $query->where('name', 'like', "%{$q}%");
            })
            ->orderBy('name')
            ->get()
            ->filter(function ($watch) use ($status, $lowThreshold) {
                if (! $status) {
                    return true;
                }
                $qty = $watch->totalStockQuantity();
                return match ($status) {
                    'in' => $qty >= $lowThreshold,
                    'low' => $qty > 0 && $qty < $lowThreshold,
                    'out' => $qty <= 0,
                    default => true,
                };
            });

        $allWatches = Watch::withSum('inventorySizes', 'quantity')->get();
        $lowStockCount = $allWatches->filter(function ($w) use ($lowThreshold) {
            $t = (int) ($w->inventory_sizes_sum_quantity ?? 0);

            return $t > 0 && $t < $lowThreshold;
        })->count();

        $outOfStockCount = $allWatches->filter(function ($w) {
            return (int) ($w->inventory_sizes_sum_quantity ?? 0) <= 0;
        })->count();

        return view('admin.inventory.index', compact('watches', 'q', 'status', 'lowThreshold', 'lowStockCount', 'outOfStockCount'));
    }

    public function incoming(Request $request)
    {
        $validated = $request->validate([
            'watch_id' => ['required', 'exists:watches,id'],
            'size' => ['required', 'integer', Rule::in(config('watch_sizes.band', [36, 38, 40, 42]))],
            'quantity' => ['required', 'integer', 'min:1'],
            'note' => ['nullable', 'string', 'max:255'],
        ]);

        DB::transaction(function () use ($validated) {
            $row = WatchInventorySize::query()->firstOrCreate(
                [
                    'watch_id' => $validated['watch_id'],
                    'size' => (int) $validated['size'],
                ],
                ['quantity' => 0]
            );

            $row->quantity = (int) $row->quantity + (int) $validated['quantity'];
            $row->save();

            InventoryMovement::create([
                'watch_id' => $validated['watch_id'],
                'size' => (int) $validated['size'],
                'created_by' => auth()->id(),
                'type' => 'in',
                'quantity' => (int) $validated['quantity'],
                'note' => $validated['note'] ?? 'Incoming stock',
            ]);
        });

        return back()->with('status', 'Incoming stock recorded and inventory updated.');
    }
}
