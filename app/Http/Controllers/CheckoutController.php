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
            'shipping-line-1' => ['required', 'string', 'max:255'],
            'shipping-line-2' => ['nullable', 'string', 'max:255'],
            'shipping-postcode' => ['required', 'regex:/^[A-Za-z0-9 ]{5,8}$/'],
            'shipping-city' => ['required', 'string', 'max:100'],
            'billing_same_as_shipping' => ['nullable', 'boolean'],
            'billing-line-1' => ['required_unless:billing_same_as_shipping,1', 'nullable', 'string', 'max:255'],
            'billing-line-2' => ['nullable', 'string', 'max:255'],
            'billing-postcode' => ['required_unless:billing_same_as_shipping,1', 'nullable', 'regex:/^[A-Za-z0-9 ]{5,8}$/'],
            'billing-city' => ['required_unless:billing_same_as_shipping,1', 'nullable', 'string', 'max:100'],
            'card-name' => ['required', 'string', 'max:255'],
            'card-number' => ['required', 'digits:16'],
            'card-expiry' => ['required', 'regex:/^(0[1-9]|1[0-2])\/\d{2}$/'],
            'card-cvv' => ['required', 'digits:3'],
        ]);

        $shipping = Address::create([
            'user_id' => $user->id,
            'line_1' => $request['shipping-line-1'],
            'line_2' => $request['shipping-line-2'],
            'city' => $request['shipping-city'],
            'postcode' => $request['shipping-postcode'],
        ]);

        $useShippingForBilling = $request->boolean('billing_same_as_shipping');

        $billing = Address::create([
            'user_id' => $user->id,
            'line_1' => $useShippingForBilling ? $request['shipping-line-1'] : $request['billing-line-1'],
            'line_2' => $useShippingForBilling ? $request['shipping-line-2'] : $request['billing-line-2'],
            'city' => $useShippingForBilling ? $request['shipping-city'] : $request['billing-city'],
            'postcode' => $useShippingForBilling ? $request['shipping-postcode'] : $request['billing-postcode'],
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
