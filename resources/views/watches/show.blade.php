@extends('layouts.user')

@push('head')
  @vite('resources/css/watches/show.css')
@endpush

@section('page')
  <div class="watch-container">
    <section class="watch">
      <div class="watch-image-container">
        <img class="watch-image" src="{{ asset('storage/' . $watch->image_path) }}" />
      </div>
      <div class="watch-content">
        <div class="watch-top">

          <div class="watch-important">
            <h1 class="watch-brand">{{ strtoupper($watch->brand->name) }}</h1>
            <p class="watch-name">{{ $watch->name }}</p>
            <p class="watch-price">£{{ number_format($watch->price) }}</p>
          </div>
          <div class="watch-sizes">
            <p>Size</p>
            <div class="watch-buttons">
              <button class="size" data-size="36">36mm</button>
              <button class="size" data-size="38">38mm</button>
              <button class="size" data-size="40">40mm</button>
              <button class="size" data-size="42">42mm</button>
            </div>
          </div>
          <form method="POST" action="{{ route('basket.store', $watch->id) }}">
            @csrf
            <input type="hidden" name="size" id="selected-size">
            <button class="accent-button" type="submit">Add to Bag</button>
          </form>
        </div>
        <div class="watch-bottom">
          <p><strong>Description</strong></p>
          <hr />
          <p>{{ $watch->description }}</p>
        </div>
      </div>
    </section>
  </div>
  <script>
    document.querySelectorAll('.size').forEach(button => {
      button.addEventListener('click', () => {

        // Remove active class from ALL size buttons
        document.querySelectorAll('.size').forEach(b => b.classList.remove('accent-button'));

        // Add active class ONLY to the one clicked
        button.classList.add('accent-button');

        // Update hidden input (for Add to Bag)
        document.getElementById('selected-size').value = button.dataset.size;

      });
    });
  </script>
@stop
