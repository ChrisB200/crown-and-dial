@extends('layouts.admin')

@section('title', 'Order #'.$order->id)
@section('page_title', 'Order #'.$order->id)

@section('content')
<div class="row">
    <div class="col-lg-8">
        <div class="block">
            <div class="title"><strong>Items</strong></div>
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead><tr><th>Watch</th><th>Qty</th><th>Unit</th></tr></thead>
                    <tbody>
                    @foreach($order->watches as $line)
                        <tr>
                            <td>{{ $line->watch?->name ?? ('Watch#'.$line->watch_id) }}</td>
                            <td>{{ $line->quantity ?? 1 }}</td>
                            <td>£{{ number_format((float)($line->price ?? 0), 2) }}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <div class="block">
            <div class="title"><strong>Shipment</strong></div>
            <p><strong>Order placed:</strong> {{ $order->created_at?->format('Y-m-d H:i') ?? '-' }}</p>
            <p>Status: <span class="badge badge-secondary">{{ $order->status }}</span></p>
            @if($order->shipped_at)
                <p>Shipped at: {{ $order->shipped_at }}</p>
                <p>Carrier: {{ $order->carrier ?? '-' }}</p>
                <p>Tracking: {{ $order->tracking_number ?? '-' }}</p>
            @else
                <form method="POST" action="{{ route('admin.orders.ship', $order) }}">
                    @csrf
                    @method('patch')
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label>Carrier</label>
                            <input class="form-control" name="carrier" type="text">
                        </div>
                        <div class="form-group col-md-6">
                            <label>Tracking Number</label>
                            <input class="form-control" name="tracking_number" type="text">
                        </div>
                    </div>
                    <button class="btn btn-primary" type="submit">Mark as Shipped</button>
                </form>
            @endif
        </div>
    </div>

    <div class="col-lg-4">
        <div class="block">
            <div class="title"><strong>Customer</strong></div>
            <p>{{ $order->user?->name }}<br>{{ $order->user?->email }}</p>
        </div>
        <div class="block">
            <div class="title"><strong>Shipping Address</strong></div>
            @php($a = $order->shipping_address)
            @if($a)
                <p>
                    {{ $a->line_1 }}<br>
                    @if($a->line_2){{ $a->line_2 }}<br>@endif
                    {{ $a->city }}<br>
                    {{ $a->postcode }}
                </p>
            @else
                <p class="text-muted">No shipping address on file.</p>
            @endif
        </div>
        <div class="block">
            <div class="title"><strong>Billing Address</strong></div>
            @php($b = $order->billing_address)
            @if($b)
                <p>
                    {{ $b->line_1 }}<br>
                    @if($b->line_2){{ $b->line_2 }}<br>@endif
                    {{ $b->city }}<br>
                    {{ $b->postcode }}
                </p>
            @else
                <p class="text-muted">No billing address on file.</p>
            @endif
        </div>
        <div class="block">
            <div class="title"><strong>Payment</strong></div>
            @if($order->card)
                @php($num = (string)($order->card->number ?? ''))
                @php($last4 = strlen($num) >= 4 ? substr($num, -4) : $num)
                <p>
                    Cardholder: {{ $order->card->name ?? '-' }}<br>
                    Card: **** **** **** {{ $last4 }}<br>
                    Expiry: {{ $order->card->expiry ?? '-' }}
                </p>
            @else
                <p class="text-muted">No card details on file.</p>
            @endif
        </div>
        <div class="block">
            <div class="title"><strong>Totals</strong></div>
            <p><strong>Total:</strong> £{{ number_format((float)$order->total, 2) }}</p>
        </div>
    </div>
</div>
@endsection
