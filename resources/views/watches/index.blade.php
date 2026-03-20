@extends('layouts.user')

@push('head')
  @vite('resources/css/watches/index.css')
@endpush

@section('page')
  <section class="hero">
    <h2 class="section-title">
      OUR CATALOG
    </h2>
    <p class="description">
      Discover our curated collection of exceptional timepieces, crafted to suit every style and moment. From refined
      classics to bold modern designs, each watch reflects precision, quality, and character. Explore our catalog to find
      the perfect piece that elevates your look and stands the test of time.
    </p>
  </section>
  <section class="watch-section">
    <div class="watch-header">
      <h2 class="section-title">
        {{ $categoryName }} WATCHES
      </h2>
      @if ($search)
        <p>Searching for results: <strong>{{ $search }}</strong></p>
      @endif

      @php
        $priceFilterQuery = request()->except(['min_price', 'max_price']);
      @endphp

      <form method="GET" action="{{ url()->current() }}">
        <input type="hidden" name="q" value="{{ request('q') }}">

        <div style="display: flex; gap: 1rem; justify-content: center; align-items: end; flex-wrap: wrap;">
          <div style="display: flex; flex-direction: column; gap: 0.5rem;">
            <label for="min_price">Min price</label>
            <input
              id="min_price"
              type="number"
              step="0.01"
              name="min_price"
              value="{{ request('min_price') }}"
              placeholder="0.00"
            >
          </div>

          <div style="display: flex; flex-direction: column; gap: 0.5rem;">
            <label for="max_price">Max price</label>
            <input
              id="max_price"
              type="number"
              step="0.01"
              name="max_price"
              value="{{ request('max_price') }}"
              placeholder="0.00"
            >
          </div>

          <button class="accent-button" type="submit" style="padding: 0.75rem 1.5rem;">
            Filter
          </button>

          <a
            class="secondary-button"
            href="{{ url()->current() . (count($priceFilterQuery) ? ('?' . http_build_query($priceFilterQuery)) : '') }}"
            style="display: inline-flex; align-items: center; justify-content: center; text-decoration: none; padding: 0.75rem 1.5rem;"
          >
            Clear
          </a>
        </div>
      </form>

      @if (count($watches) === 0)
        <p>Sorry, we could not find any watches matching this criteria</p>
      @endif
    </div>
    <div class="watches" id="watches">
      @foreach ($watches as $watch)
        <article class="watch-card">
          <a class="watch" href="{{ route('watches.show', compact('watch')) }}">
            <div class="watch-image-container">
              <img class="watch-image" src="{{ $watch->firstImage->url }}" alt="{{ $watch->name }}" />
            </div>
            <div class="watch-content">
              <p class="watch-brand">{{ strtoupper($watch->brand->name) }}</p>
              <p class="watch-name">{{ $watch->name }}</p>
              <p class="watch-price">£{{ number_format($watch->price) }}</p>
            </div>
          </a>
          @auth
            <div class="watch-card-actions">
              @if (in_array($watch->id, $wishlistIds ?? []))
                <form method="POST" action="{{ route('wishlist.destroy', $watch) }}">
                  @csrf
                  @method('DELETE')
                  <button type="submit" class="secondary-button wishlist-button">Remove from Wishlist</button>
                </form>
              @else
                <form method="POST" action="{{ route('wishlist.store', $watch) }}">
                  @csrf
                  <button type="submit" class="secondary-button wishlist-button">Add to Wishlist</button>
                </form>
              @endif
            </div>
          @endauth
        </article>
      @endforeach
    </div>
    <div class="pagination">
      @if ($watches->previousPageUrl())
        <a href="{{ $watches->previousPageUrl() }}" class="btn"><strong>Previous</strong></a>
      @else
        <span class="btn disabled">Previous</span>
      @endif

      <span>Page {{ $watches->currentPage() }} of {{ $watches->lastPage() }}</span>

      @if ($watches->nextPageUrl())
        <a href="{{ $watches->nextPageUrl() }}" class="btn"><strong>Next</strong></a>
      @else
        <span class="btn disabled">Next</span>
      @endif
    </div>
  </section>
@stop
