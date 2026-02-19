<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Inventory;
use App\Models\InventoryMovement;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminOrderController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->get('status');
        $orders = Order::query()
            ->with('user')
            ->when($status, fn($q) => $q->where('status', $status))
            ->orderByDesc('created_at')
            ->paginate(15)
            ->withQueryString();

        return view('admin.orders.index', compact('orders', 'status'));
    }

    public function show(Order $order)
    {
        $order->load(['user', 'shipping_address', 'billing_address', 'card', 'watches.watch']);
        return view('admin.orders.show', compact('order'));
    }

    public function ship(Request $request, Order $order)
    {
        $request->validate([
            'carrier' => ['nullable', 'string', 'max:255'],
            'tracking_number' => ['nullable', 'string', 'max:255'],
        ]);

        if (!in_array($order->status, ['paid', 'pending'])) {
            return back()->withErrors(['status' => 'Order is not eligible to be shipped.']);
        }

        DB::transaction(function () use ($order, $request) {
            $order->status = 'shipped';
            $order->shipped_at = now();
            $order->carrier = $request->carrier;
            $order->tracking_number = $request->tracking_number;
            $order->save();

            foreach ($order->watches as $line) {
                $watchId = $line->watch_id;
                $qty = (int)($line->quantity ?? 1);

                $inventory = Inventory::query()->firstOrCreate(['watch_id' => $watchId], ['quantity' => 0]);
                $inventory->quantity = max(0, (int)$inventory->quantity - $qty);
                $inventory->save();

                InventoryMovement::create([
                    'watch_id' => $watchId,
                    'created_by' => auth()->id(),
                    'order_id' => $order->id,
                    'type' => 'out',
                    'quantity' => $qty,
                    'note' => 'Order shipped',
                ]);
            }
        });

        return redirect()->route('admin.orders.show', $order)->with('status', 'Order marked as shipped and inventory updated.');
    }
}
