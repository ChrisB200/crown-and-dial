@extends('layouts.user')

@push('head')
  @vite('resources/css/checkout/show.css')
@endpush

@section('page')
  <div class="container">

    <div class="thanks-section">
      <h1>Successfully placed your order</h1>
      <p>Your order has been placed and is currently being processed by our team</p>
    </div>
    <div class="basket">
      <div class="watches">
        @foreach ($order->watchOrders as $line)
          @php($item = $line->watch)
          <div class="watch">
            <div class="watch-image-container">
              <img class="watch-image" src="{{ $item?->firstImage?->url }}" />
            </div>

            <div class="watch-info">
              <p class="watch-brand">{{ strtoupper($item?->brand?->name ?? '') }}</p>
              <p class="watch-name">{{ $item?->name }} — {{ $line->size }}mm</p>

              <div class="quantity">
                <p>QTY</p>
                <input class="watch-quantity" data-price="{{ $item?->price }}" min="1" type="number"
                  value="{{ $line->quantity }}" data-id="{{ $item?->id }}" disabled />
              </div>

              <p class="watch-price">£{{ number_format((float) ($item?->price ?? 0), 2) }}</p>
            </div>

          </div>
        @endforeach
      </div>
      <div class="order-summary">
        <div class="order-total">
          <p>Total</p>
          <p id="basket-total">£{{ number_format($order->total, 2) }}</p>
        </div>
      </div>
    </div>
  </div>
@endsection
