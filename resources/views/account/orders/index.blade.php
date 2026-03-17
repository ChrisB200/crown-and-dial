@extends('layouts.settings')

@push('head')
  @vite('resources/css/account/orders/index.css')
@endpush

@section('page')

  <div class="orders-list">
    <h2>Past Orders</h2>

    @forelse($orders as $order)
      <div class="order-row">
        <div class="order-left">
          <div class="order-index">{{ $loop->iteration }}.</div>

          <div class="order-watch-box">ORDER #{{ $order->id }}</div>

          <div class="order-description">
            Status: {{ ucfirst($order->status) }}<br>
            Total: £{{ number_format((float) $order->total, 2) }}<br>
            Placed: {{ $order->created_at?->format('Y-m-d H:i') }}
          </div>
        </div>

        <a href="{{ route('watches.index') }}" class="accent-button">
          BUY AGAIN
        </a>
      </div>
    @empty
      <div class="order-row">
        <div class="order-left">
          <div class="order-description">
            You have not placed any orders yet.
          </div>
        </div>
      </div>
    @endforelse

  </div>

@stop
