@extends('layouts.user')

@push('head')
  @vite('resources/css/checkout/index.css')
@endpush

@section('page')
  <h1 class="section-title">CHECKOUT</h1>
  <section class="content">
    <form method="POST" action="{{ route('checkout.store') }}">
      @csrf
      <div class="form">
        <h2 class="form-title">Shipping Address</h2>
        <div class="form-row">
          <label for="shipping-line-1">Address Line 1 <span class="asterisk">*</span></label>
          <input id="shipping-line-1" type="text" name="shipping-line-1" required />
        </div>
        <div class="form-row">
          <label for="shipping-line-2">Address Line 2</label>
          <input id="shipping-line-2" type="text" name="shipping-line-2" />
        </div>
        <div class="form-span">
          <div class="form-row">
            <label for="shipping-postcode">Postcode <span class="asterisk">*</span> </label>
            <input id="shipping-postcode" type="text" name="shipping-postcode" required />
          </div>
          <div class="form-row">
            <label for="shipping-city">City / District <span class="asterisk">*</span></label>
            <input id="shipping-city" type="text" name="shipping-city" required />
          </div>
        </div>
      </div>
      <div class="form">
        <h2 class="form-title">Card Details</h2>
        <div class="form-row">
          <label for="card-name">Cardholder Name <span class="asterisk">*</span></label>
          <input id="card-name" type="text" name="card-name" required />
        </div>
        <div class="form-row">
          <label for="card-number">Card Number <span class="asterisk">*</span></label>
          <input id="card-number" type="text" name="card-number" required />
        </div>
        <div class="form-span">
          <div class="form-row">
            <label for="card-expiry">Expiry Date <span class="asterisk">*</span></label>
            <input id="card-expiry" type="month" name="card-expiry" required />
          </div>
          <div class="form-row">
            <label for="card-cvv">CVV <span class="asterisk">*</span></label>
            <input id="card-cvv" type="text" name="card-cvv" required />
          </div>
        </div>
      </div>
      <div class="form">
        <h2 class="form-title">Billing Address</h2>
        <div class="form-row">
          <label for="billing-line-1">Address Line 1 <span class="asterisk">*</span></label>
          <input id="billing-line-1" type="text" name="billing-line-1" required />
        </div>
        <div class="form-row">
          <label for="billing-line-2">Address Line 2</label>
          <input id="billing-line-2" type="text" name="billing-line-2" />
        </div>
        <div class="form-span">
          <div class="form-row">
            <label for="billing-postcode">Postcode <span class="asterisk">*</span></label>
            <input id="billing-postcode" type="text" name="billing-postcode" required />
          </div>
          <div class="form-row">
            <label for="billing-city">City / District <span class="asterisk">*</span></label>
            <input id="billing-city" type="text" name="billing-city" required />
          </div>
        </div>
      </div>
      <button class="accent-button">
        Purchase
      </button>
    </form>

    <div class="basket">
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

              <p class="watch-price">£{{ number_format($item->watch->price) }}</p>
            </div>

          </div>
        @endforeach
      </div>
      <div class="order-summary">
        <div class="order-total">
          <p>Total</p>
          <p id="basket-total">£{{ number_format($total, 2) }}</p>
        </div>
      </div>
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

    // Auto-save quantity change
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
