@extends('layouts.user')

@push('head')
  @vite('resources/css/checkout/index.css')
@endpush

@section('page')
  <h1 class="section-title">CHECKOUT</h1>
  @if ($errors->any())
    <div class="alert alert-error">
      <ul>
        @foreach ($errors->all() as $err)
          <li>{{ $err }}</li>
        @endforeach
      </ul>
    </div>
  @endif
  <section class="content">
    <form method="POST" action="{{ route('checkout.store') }}">
      @csrf
      <div class="form">
        <h2 class="form-title">Shipping Address</h2>
        <div class="form-row">
          <label for="shipping-line-1">Address Line 1 <span class="asterisk">*</span></label>
          <input id="shipping-line-1" type="text" name="shipping-line-1" value="{{ old('shipping-line-1') }}" required />
          @error('shipping-line-1')
            <p style="color: var(--error);">{{ $message }}</p>
          @enderror
        </div>
        <div class="form-row">
          <label for="shipping-line-2">Address Line 2</label>
          <input id="shipping-line-2" type="text" name="shipping-line-2" value="{{ old('shipping-line-2') }}" />
          @error('shipping-line-2')
            <p style="color: var(--error);">{{ $message }}</p>
          @enderror
        </div>
        <div class="form-span">
          <div class="form-row">
            <label for="shipping-postcode">Postcode <span class="asterisk">*</span> </label>
            <input id="shipping-postcode" type="text" name="shipping-postcode" value="{{ old('shipping-postcode') }}" minlength="5" maxlength="8" pattern="[A-Za-z0-9 ]{5,8}" required />
            @error('shipping-postcode')
              <p style="color: var(--error);">{{ $message }}</p>
            @enderror
            <p id="shipping-postcode-live-error" style="color: var(--error); display: none;"></p>
          </div>
          <div class="form-row">
            <label for="shipping-city">City / District <span class="asterisk">*</span></label>
            <input id="shipping-city" type="text" name="shipping-city" value="{{ old('shipping-city') }}" required />
            @error('shipping-city')
              <p style="color: var(--error);">{{ $message }}</p>
            @enderror
          </div>
        </div>
      </div>
      <div class="form">
        <h2 class="form-title">Card Details</h2>
        <div class="form-row">
          <label for="card-name">Cardholder Name <span class="asterisk">*</span></label>
          <input id="card-name" type="text" name="card-name" value="{{ old('card-name') }}" required />
          @error('card-name')
            <p style="color: var(--error);">{{ $message }}</p>
          @enderror
        </div>
        <div class="form-row">
          <label for="card-number">Card Number <span class="asterisk">*</span></label>
          <input id="card-number" type="text" name="card-number" value="{{ old('card-number') }}" inputmode="numeric" minlength="16" maxlength="16" pattern="[0-9]{16}" required />
          @error('card-number')
            <p style="color: var(--error);">{{ $message }}</p>
          @enderror
          <p id="card-number-live-error" style="color: var(--error); display: none;"></p>
        </div>
        <div class="form-span">
          <div class="form-row">
            <label for="card-expiry">Expiry Date <span class="asterisk">*</span></label>
            <input id="card-expiry" type="text" name="card-expiry" value="{{ old('card-expiry') }}" inputmode="numeric" placeholder="MM/YY" minlength="5" maxlength="5" pattern="(0[1-9]|1[0-2])/[0-9]{2}" required />
            @error('card-expiry')
              <p style="color: var(--error);">{{ $message }}</p>
            @enderror
            <p id="card-expiry-live-error" style="color: var(--error); display: none;"></p>
          </div>
          <div class="form-row">
            <label for="card-cvv">CVV <span class="asterisk">*</span></label>
            <input id="card-cvv" type="text" name="card-cvv" value="{{ old('card-cvv') }}" inputmode="numeric" minlength="3" maxlength="3" pattern="[0-9]{3}" required />
            @error('card-cvv')
              <p style="color: var(--error);">{{ $message }}</p>
            @enderror
            <p id="card-cvv-live-error" style="color: var(--error); display: none;"></p>
          </div>
        </div>
      </div>
      <div class="form">
        <h2 class="form-title">Billing Address</h2>
        <div class="form-row">
          <label for="billing_same_as_shipping">
            <input
              id="billing_same_as_shipping"
              type="checkbox"
              name="billing_same_as_shipping"
              value="1"
              {{ old('billing_same_as_shipping') ? 'checked' : '' }}
            />
            Billing address is the same as shipping
          </label>
        </div>
        <div class="form-row">
          <label for="billing-line-1">Address Line 1 <span class="asterisk">*</span></label>
          <input id="billing-line-1" type="text" name="billing-line-1" value="{{ old('billing-line-1') }}" required />
          @error('billing-line-1')
            <p style="color: var(--error);">{{ $message }}</p>
          @enderror
        </div>
        <div class="form-row">
          <label for="billing-line-2">Address Line 2</label>
          <input id="billing-line-2" type="text" name="billing-line-2" value="{{ old('billing-line-2') }}" />
          @error('billing-line-2')
            <p style="color: var(--error);">{{ $message }}</p>
          @enderror
        </div>
        <div class="form-span">
          <div class="form-row">
            <label for="billing-postcode">Postcode <span class="asterisk">*</span></label>
            <input id="billing-postcode" type="text" name="billing-postcode" value="{{ old('billing-postcode') }}" minlength="5" maxlength="8" pattern="[A-Za-z0-9 ]{5,8}" required />
            @error('billing-postcode')
              <p style="color: var(--error);">{{ $message }}</p>
            @enderror
            <p id="billing-postcode-live-error" style="color: var(--error); display: none;"></p>
          </div>
          <div class="form-row">
            <label for="billing-city">City / District <span class="asterisk">*</span></label>
            <input id="billing-city" type="text" name="billing-city" value="{{ old('billing-city') }}" required />
            @error('billing-city')
              <p style="color: var(--error);">{{ $message }}</p>
            @enderror
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
              <img class="watch-image" src="{{ $item->watch->firstImage->url }}" />
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
    const billingSameCheckbox = document.getElementById('billing_same_as_shipping');
    const shippingFields = {
      line1: document.getElementById('shipping-line-1'),
      line2: document.getElementById('shipping-line-2'),
      postcode: document.getElementById('shipping-postcode'),
      city: document.getElementById('shipping-city')
    };
    const billingFields = {
      line1: document.getElementById('billing-line-1'),
      line2: document.getElementById('billing-line-2'),
      postcode: document.getElementById('billing-postcode'),
      city: document.getElementById('billing-city')
    };

    function syncBillingState() {
      const same = billingSameCheckbox.checked;

      if (same) {
        billingFields.line1.value = shippingFields.line1.value;
        billingFields.line2.value = shippingFields.line2.value;
        billingFields.postcode.value = shippingFields.postcode.value;
        billingFields.city.value = shippingFields.city.value;
      }

      billingFields.line1.readOnly = same;
      billingFields.line2.readOnly = same;
      billingFields.postcode.readOnly = same;
      billingFields.city.readOnly = same;
    }

    billingSameCheckbox.addEventListener('change', syncBillingState);
    Object.values(shippingFields).forEach(field => {
      field.addEventListener('input', () => {
        if (billingSameCheckbox.checked) {
          syncBillingState();
        }
      });
    });
    syncBillingState();

    const cardNumber = document.getElementById('card-number');
    const cardExpiry = document.getElementById('card-expiry');
    const cardCvv = document.getElementById('card-cvv');
    const shippingPostcode = document.getElementById('shipping-postcode');
    const billingPostcode = document.getElementById('billing-postcode');

    const setLiveError = (field, messageElementId, message) => {
      const errorEl = document.getElementById(messageElementId);
      if (message) {
        field.setCustomValidity(message);
        errorEl.textContent = message;
        errorEl.style.display = 'block';
      } else {
        field.setCustomValidity('');
        errorEl.textContent = '';
        errorEl.style.display = 'none';
      }
    };

    cardNumber.addEventListener('input', () => {
      cardNumber.value = cardNumber.value.replace(/\D/g, '').slice(0, 16);
      const msg = cardNumber.value.length === 16 ? '' : 'Card number must be exactly 16 digits.';
      setLiveError(cardNumber, 'card-number-live-error', msg);
    });

    cardExpiry.addEventListener('input', () => {
      const digits = cardExpiry.value.replace(/\D/g, '').slice(0, 4);
      cardExpiry.value = digits.length >= 3 ? `${digits.slice(0, 2)}/${digits.slice(2)}` : digits;
      const validFormat = /^(0[1-9]|1[0-2])\/\d{2}$/.test(cardExpiry.value);
      const msg = validFormat ? '' : 'Expiry must be in MM/YY format.';
      setLiveError(cardExpiry, 'card-expiry-live-error', msg);
    });

    cardCvv.addEventListener('input', () => {
      cardCvv.value = cardCvv.value.replace(/\D/g, '').slice(0, 3);
      const msg = cardCvv.value.length === 3 ? '' : 'CVV must be exactly 3 digits.';
      setLiveError(cardCvv, 'card-cvv-live-error', msg);
    });

    const validatePostcode = (field, errorId) => {
      field.value = field.value.toUpperCase().slice(0, 8);
      const valid = /^[A-Z0-9 ]{5,8}$/.test(field.value);
      const msg = valid ? '' : 'Postcode must be 5-8 letters/numbers.';
      setLiveError(field, errorId, msg);
    };

    shippingPostcode.addEventListener('input', () => validatePostcode(shippingPostcode, 'shipping-postcode-live-error'));
    billingPostcode.addEventListener('input', () => {
      if (!billingSameCheckbox.checked) {
        validatePostcode(billingPostcode, 'billing-postcode-live-error');
      } else {
        setLiveError(billingPostcode, 'billing-postcode-live-error', '');
      }
    });

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
