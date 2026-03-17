<?php

namespace App\Http\Controllers;

use App\Models\Address;
use App\Models\BasketItem;
use App\Models\Card;
use App\Models\Order;
use App\Models\WatchOrder;
use Illuminate\Http\Request;

class CheckoutController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $user = $request->user();

        $basket = BasketItem::where('user_id', $user->id)
            ->latest()
            ->with('watch')
            ->get();

        $total = $basket->sum(function ($item) {
            return ($item->watch->price ?? 0) * $item->quantity;
        });

        if (count($basket) === 0) {
            return redirect()->route('basket.index')->with('error', 'There are no items in your basket');
        }

        return view('checkout.index', compact('basket', 'total'));
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
    public function store(Request $request)
    {
        $user = $request->user();

        $request->validate([
            'shipping-line-1' => 'required|string',
            'shipping-postcode' => 'required|string',
            'shipping-city' => 'required|string',
            'billing-line-1' => 'required|string',
            'billing-postcode' => 'required|string',
            'billing-city' => 'required|string',
            'card-name' => 'required|string',
            'card-number' => 'required|string',
            'card-expiry' => 'required|string',
            'card-cvv' => 'required|string',
        ]);

        $shipping = Address::create([
            'user_id' => $user->id,
            'line_1' => $request['shipping-line-1'],
            'line_2' => $request['shipping-line-2'],
            'city' => $request['shipping-city'],
            'postcode' => $request['shipping-postcode'],
        ]);

        $billing = Address::create([
            'user_id' => $user->id,
            'line_1' => $request['billing-line-1'],
            'line_2' => $request['billing-line-2'],
            'city' => $request['billing-city'],
            'postcode' => $request['billing-postcode'],
        ]);

        $card = Card::create([
            'user_id' => $user->id,
            'name' => $request['card-name'],
            'number' => $request['card-number'],
            'expiry' => $request['card-expiry'],
            'cvv' => $request['card-cvv'],
        ]);

        $basketItems = BasketItem::where('user_id', $user->id)
            ->with('watch')
            ->get();

        if ($basketItems->isEmpty()) {
            return redirect()->route('basket.index')->withErrors('Your basket is empty.');
        }

        $total = $basketItems->sum(fn ($item) => ($item->watch->price ?? 0) * $item->quantity);

        $order = Order::create([
            'status' => 'pending',
            'user_id' => $user->id,
            'shipping_address_id' => $shipping->id,
            'billing_address_id' => $billing->id,
            'card_id' => $card->id,
            'total' => $total,
        ]);

        foreach ($basketItems as $item) {
            WatchOrder::create([
                'order_id' => $order->id,
                'watch_id' => $item->watch_id,
                'size' => $item->size,
                'quantity' => $item->quantity,
            ]);
        }

        BasketItem::where('user_id', $user->id)->delete();

        return redirect()->route('checkout.show', $order)->with('success', 'Order placed successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Order $order)
    {
        $order->load('watches');

        return view('checkout.show', compact('order'));
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
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
