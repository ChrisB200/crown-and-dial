<?php

namespace App\Http\Controllers;

use App\Models\BasketItem;
use App\Models\Watch;
use Illuminate\Http\Request;

class BasketController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $user = $request->user();

        $basket = BasketItem::where("user_id", $user->id)
            ->with("watch.inventorySizes")
            ->get();

        $total = $basket->sum(function ($item) {
            return $item->watch->price * $item->quantity;
        });

        return view("basket.index", compact("basket", "total"));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, Watch $watch)
    {
        $request->validate([
            'size' => 'required',
        ]);

        $watch->load('inventorySizes');
        $size = (int) $request->size;
        $availableQty = $watch->quantityForSize($size);

        if ($availableQty <= 0) {
            return back()->with('error', 'This size is currently out of stock.');
        }

        $userId = $request->user()->id;

        $existingItem = BasketItem::where("user_id", $userId)
            ->where("watch_id", $watch->id)
            ->where("size", $size)
            ->first();

        if ($existingItem) {
            if (($existingItem->quantity + 1) > $availableQty) {
                return back()->with('error', 'Cannot add more of this watch in the selected size. Not enough stock available.');
            }

            $existingItem->quantity += 1;
            $existingItem->save();
        } else {
            BasketItem::create([
                "user_id" => $userId,
                "watch_id" => $watch->id,
                "size" => $size,
                "quantity" => 1,
            ]);
        }

        return redirect()->route("basket.index")->with("success", "Item added to the basket");
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, BasketItem $item)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1'
        ]);

        $item->load('watch.inventorySizes');
        $availableQty = $item->watch->quantityForSize((int) $item->size);

        if ($request->quantity > $availableQty) {
            return response()->json([
                'success' => false,
                'message' => 'Requested quantity exceeds available stock for this size.',
            ], 422);
        }

        $item->quantity = $request->quantity;
        $item->save();

        return response()->json(['success' => true]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(BasketItem $item)
    {
        $item->delete();

        return redirect()->route('basket.index')->with('success', 'Item removed from basket');
    }
}
