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
            <strong>Products:</strong>
            @if($order->watches->isNotEmpty())
              {{ $order->watches->pluck('name')->filter()->join(', ') }}
            @else
              No products found
            @endif
            <br>
            <strong>Status:</strong> {{ ucfirst($order->status) }}<br>
            <strong>Total:</strong> £{{ number_format((float) $order->total, 2) }}<br>
            <strong>Placed:</strong> {{ $order->created_at?->format('Y-m-d H:i') }}

            <details style="margin-top: 12px;">
              <summary style="cursor: pointer; font-weight: 600;">View order details</summary>

              <div style="margin-top: 10px; line-height: 1.8;">
                <div><strong>Order ID:</strong> #{{ $order->id }}</div>
                <div><strong>Products:</strong></div>
                <ul style="margin: 6px 0 10px 18px; padding: 0;">
                  @forelse($order->watches as $watch)
                    <li>
                      {{ $watch->name ?? 'Unnamed product' }}
                      @if(!is_null($watch->pivot->quantity ?? null))
                        × {{ $watch->pivot->quantity }}
                      @endif
                    </li>
                  @empty
                    <li>No products found</li>
                  @endforelse
                </ul>
                <div><strong>Courier:</strong> {{ $order->carrier ?? $order->shipping_courier ?? 'Not assigned yet' }}</div>
                <div><strong>Tracking Number:</strong> {{ $order->tracking_number ?? $order->tracking ?? 'Not available yet' }}</div>
                <div><strong>Status:</strong> {{ ucfirst($order->status) }}</div>
                <div><strong>Total:</strong> £{{ number_format((float) $order->total, 2) }}</div>
                <div><strong>Placed:</strong> {{ $order->created_at?->format('Y-m-d H:i') }}</div>
              </div>
            </details>
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
