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
        @foreach ($order->watches as $item)
          <div class="watch">
            <form action="{{ route('basket.destroy', $item->id) }}" method="POST" class="exit">
              @csrf
              @method('DELETE')
              <button class="exit">X</button>
            </form>
            <div class="watch-image-container">
              <img class="watch-image" src="{{ asset('storage/' . $item->watch->image_path) }}" />
            </div>

            <div class="watch-info">
              <p class="watch-brand">{{ strtoupper($item->watch->brand->name) }}</p>
              <p class="watch-name">{{ $item->watch->name }}: {{ $item->size }}mm</p>

              <div class="quantity">
                <p>QTY</p>
                <input class="watch-quantity" data-price="{{ $item->watch->price }}" min="1" type="number"
                  value="{{ $item->quantity }}" data-id="{{ $item->id }}" disabled />
              </div>

              <p class="watch-price">£{{ number_format($item->watch->price, 2) }}</p>
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
