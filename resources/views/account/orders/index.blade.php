@extends('layouts.settings')

@push('head')
  @vite('resources/css/account/orders/index.css')
@endpush

@section('page')

  <div class="orders-list">
    <h2>Past Orders</h2>
    <div class="order-row">
      <div class="order-left">
        <div class="order-index">1.</div>

        <div class="order-watch-box">WATCH</div>

        <div class="order-description">
          WATCH DESCRIPTION
        </div>
      </div>

      <button class="accent-button">
        BUY AGAIN
      </button>
    </div>

    <div class="order-row">
      <div class="order-left">
        <div class="order-index">2.</div>

        <div class="order-watch-box">WATCH</div>

        <div class="order-description">
          WATCH DESCRIPTION
        </div>
      </div>

      <button class="accent-button">
        BUY AGAIN
      </button>
    </div>

    <div class="order-row">
      <div class="order-left">
        <div class="order-index">3.</div>

        <div class="order-watch-box">WATCH</div>

        <div class="order-description">
          WATCH DESCRIPTION
        </div>
      </div>

      <button class="accent-button">
        BUY AGAIN
      </button>
    </div>

    <div class="order-row">
      <div class="order-left">
        <div class="order-index">4.</div>

        <div class="order-watch-box">WATCH</div>

        <div class="order-description">
          WATCH DESCRIPTION
        </div>
      </div>

      <button class="accent-button">
        BUY AGAIN
      </button>
    </div>

  </div>

@stop
