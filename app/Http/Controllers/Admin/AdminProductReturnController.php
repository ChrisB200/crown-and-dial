<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\InventoryMovement;
use App\Models\ProductReturn;
use App\Models\WatchInventorySize;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminProductReturnController extends Controller
{
    public function index(Request $request)
    {
        $rawStatus = $request->get('status');
        if ($rawStatus === null) {
            $rawStatus = ProductReturn::STATUS_PENDING;
        }

        $returns = ProductReturn::query()
            ->with(['user', 'order', 'watchOrder.watch'])
            ->when($rawStatus !== '', fn ($q) => $q->where('status', $rawStatus))
            ->orderByDesc('created_at')
            ->paginate(20)
            ->withQueryString();

        return view('admin.returns.index', [
            'returns' => $returns,
            'status' => $rawStatus,
        ]);
    }

    public function approve(Request $request, ProductReturn $productReturn)
    {
        abort_unless($productReturn->status === ProductReturn::STATUS_PENDING, 400);

        $request->validate([
            'admin_notes' => ['nullable', 'string', 'max:2000'],
        ]);

        DB::transaction(function () use ($productReturn, $request) {
            $line = $productReturn->watchOrder;
            $line->loadMissing('watch');

            $row = WatchInventorySize::query()->firstOrCreate(
                [
                    'watch_id' => $line->watch_id,
                    'size' => (int) $line->size,
                ],
                ['quantity' => 0]
            );

            $row->quantity = (int) $row->quantity + (int) $productReturn->quantity;
            $row->save();

            InventoryMovement::create([
                'watch_id' => $line->watch_id,
                'size' => (int) $line->size,
                'created_by' => auth()->id(),
                'order_id' => $productReturn->order_id,
                'type' => 'in',
                'quantity' => (int) $productReturn->quantity,
                'note' => 'Return #'.$productReturn->id.' approved — stock restored',
            ]);

            $productReturn->status = ProductReturn::STATUS_APPROVED;
            $productReturn->admin_notes = $request->input('admin_notes');
            $productReturn->processed_by = auth()->id();
            $productReturn->processed_at = now();
            $productReturn->save();
        });

        return redirect()->route('admin.returns.index')->with('status', 'Return approved and inventory updated.');
    }

    public function reject(Request $request, ProductReturn $productReturn)
    {
        abort_unless($productReturn->status === ProductReturn::STATUS_PENDING, 400);

        $request->validate([
            'admin_notes' => ['required', 'string', 'max:2000'],
        ]);

        $productReturn->status = ProductReturn::STATUS_REJECTED;
        $productReturn->admin_notes = $request->input('admin_notes');
        $productReturn->processed_by = auth()->id();
        $productReturn->processed_at = now();
        $productReturn->save();

        return redirect()->route('admin.returns.index')->with('status', 'Return rejected.');
    }
}
