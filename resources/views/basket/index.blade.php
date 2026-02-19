@extends('layouts.user')

@push('head')
  @vite('resources/css/basket/index.css')
@endpush

@section('page')
  @if (session('error'))
    <div class="alert alert-error">
      {{ session('error') }}
    </div>
  @endif

  @if ($errors->any())
    <div class="alert alert-error">
      <ul>
        @foreach ($errors->all() as $err)
          <li>{{ $err }}</li>
        @endforeach
      </ul>
    </div>
  @endif
  <section>
    <h2 class="section-title">YOUR SHOPPING BAG</h2>

    <div class="watches">
      @foreach ($basket as $item)
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
                value="{{ $item->quantity }}" data-id="{{ $item->id }}" />
            </div>

            <p class="watch-price">£{{ $item->watch->price }}</p>
          </div>

        </div>
      @endforeach
    </div>
    <div class="order-summary">
      <h3>Order Summary</h3>
      <div class="order-total">
        <p>Total</p>
        <p id="basket-total">£{{ number_format($total, 2) }}</p>
      </div>
      <a class="button-container" href="{{ route('checkout.index') }}">
        <button class="accent-button">Checkout</button>
      </a>
    </div>
  </section>
  <script>
    function updateTotal() {
      let total = 0;

      document.querySelectorAll('.watch-quantity').forEach(input => {
        const price = parseFloat(input.dataset.price);
        const quantity = parseInt(input.value);
        total += price * quantity;
      });

      document.getElementById('basket-total').textContent = '£' + total.toFixed(2);
    }

    // auto-save quantity change
    document.querySelectorAll('.watch-quantity').forEach(input => {
      input.addEventListener('input', e => {
        updateTotal();

        const itemId = input.dataset.id;
        const quantity = input.value;

        fetch(`/basket/${itemId}`, {
          method: 'PATCH',
          headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
          },
          body: JSON.stringify({
            quantity
          })
        });
      });
    });

    document.addEventListener("DOMContentLoaded", updateTotal)
  </script>
@stop
