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
            ->with("watch")
            ->get();

        $total = $basket->sum(function ($item) {
            return $item->watch->price * $item->quantity;
        });

        return view("basket.index", compact("basket", "total"));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, Watch $watch)
    {
        $request->validate([
            'size' => 'required',
        ]);

        $userId = $request->user()->id;
        $size = $request->size;

        $existingItem = BasketItem::where("user_id", $userId)
            ->where("watch_id", $watch->id)
            ->where("size", $size)
            ->first();

        if ($existingItem) {
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
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, BasketItem $item)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1'
        ]);

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
