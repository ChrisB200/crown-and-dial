<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\ProductReturn;
use App\Models\WatchOrder;
use Illuminate\Http\Request;

class ProductReturnController extends Controller
{
    public function store(Request $request, Order $order)
    {
        abort_unless($order->user_id === $request->user()->id, 403);

        $validated = $request->validate([
            'watch_order_id' => ['required', 'integer', 'exists:watch_orders,id'],
            'quantity' => ['required', 'integer', 'min:1'],
            'reason' => ['required', 'string', 'max:2000'],
        ]);

        if ($order->status !== 'shipped') {
            return back()->with('error', 'Returns are only available after your order has been shipped.');
        }

        $line = WatchOrder::query()
            ->where('id', $validated['watch_order_id'])
            ->where('order_id', $order->id)
            ->firstOrFail();

        $alreadyReturned = (int) ProductReturn::query()
            ->where('watch_order_id', $line->id)
            ->whereIn('status', [
                ProductReturn::STATUS_PENDING,
                ProductReturn::STATUS_APPROVED,
            ])
            ->sum('quantity');

        $remaining = (int) $line->quantity - $alreadyReturned;

        if ($remaining <= 0) {
            return back()->with('error', 'You have already returned the maximum quantity for this line item.');
        }

        if ($validated['quantity'] > $remaining) {
            return back()->with('error', "You can only return up to {$remaining} unit(s) for this item.");
        }

        ProductReturn::create([
            'user_id' => $request->user()->id,
            'order_id' => $order->id,
            'watch_order_id' => $line->id,
            'quantity' => $validated['quantity'],
            'reason' => $validated['reason'],
            'status' => ProductReturn::STATUS_PENDING,
        ]);

        return back()->with('success', 'Return request submitted. We will review it shortly.');
    }
}
