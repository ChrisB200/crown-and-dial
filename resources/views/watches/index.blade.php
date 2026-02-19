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
      @if (count($watches) === 0)
        <p>Sorry, we could not find any watches matching this criteria</p>
      @endif
    </div>
    <div class="watches" id="watches">
      @foreach ($watches as $watch)
        <a class="watch" href="{{ route('watches.show', compact('watch')) }}">
          <div class="watch-image-container">
            <img class="watch-image" src="{{ asset('storage/' . $watch->image_path) }}" />
          </div>
          <div class="watch-content">
            <p class="watch-brand">{{ strtoupper($watch->brand->name) }}</p>
            <p class="watch-name">{{ $watch->name }}</p>
            <p class="watch-price">£{{ number_format($watch->price) }}</p>
          </div>
        </a>
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
