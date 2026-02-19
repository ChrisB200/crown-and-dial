@extends('layouts.user')

@push('head')
  @vite('resources/css/home.css')
@endpush

@section('page')
  <section class="hero">
    <h2 class="section-title">
      CROWN & DIAL
    </h2>
    <p class="description">
      Crown and Dial is dedicated to offering high-quality, authentic timepieces that blend craftsmanship, style, and
      reliability. We curate watches for every lifestyle, delivering a seamless shopping experience built on trust and
      exceptional service. Our mission is to help customers express their identity through timepieces that endure and
      inspire.

    </p>
    <a href="{{ route('about') }}">
      <button class="accent-button">Find Out More</button>
    </a>
  </section>
  <section class="categories">
    <h2 class="section-title">FEATURED COLLECTION</h2>
    <div class="category-container">
      <a class="category" href="{{ route('watches.category', 'luxury') }}">
        <div class="category-image">
          <img src="{{ asset('images/luxury.png') }}" />
        </div>
        <p><strong>Luxury</strong><br />Watches</p>
      </a>
      <a class="category" href="{{ route('watches.category', 'smart') }}">
        <div class="category-image">
          <img src="{{ asset('images/smart.png') }}" />
        </div>
        <p><strong>Smart</strong><br />Watches</p>
      </a>
      <a class="category" href="{{ route('watches.category', 'sports') }}">
        <div class="category-image">
          <img src="{{ asset('images/sport.png') }}" />
        </div>
        <p><strong>Sports</strong><br />Watches</p>
      </a>
      <a class="category" href="{{ route('watches.category', 'casual') }}">
        <div class="category-image">
          <img src="{{ asset('images/casual.png') }}" />
        </div>
        <p><strong>Casual</strong><br />Watches</p>
      </a>
      <a class="category" href="{{ route('watches.category', 'classic') }}">
        <div class="category-image">
          <img src="{{ asset('images/classic.png') }}" />
        </div>
        <p><strong>Classic</strong><br />Watches</p>
      </a>
    </div>
  </section>
  <section class="reviews">
    <h2 class="section-title">REVIEWS</h2>
    <p>John</p>
    <p>I absolutely love my Crown & Dial Watch – the craftsmanship, quality, and timeless design are everything I hoped
      for.</p>
    <p>It’s truly a watch that stands out and is worth the investment.</p>

    <p>Sarah</p>
    <p>This is my second purchase from Crown & Dial.</p>
    <p>These watches are the perfect gift ideas.</p>
    <p>Will be coming back.</p>
  </section>
@stop
