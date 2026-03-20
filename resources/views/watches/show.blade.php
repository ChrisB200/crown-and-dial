@extends('layouts.user')

@push('head')
  @vite('resources/css/watches/show.css')
@endpush

@section('page')
  <div class="watch-container">
  <div>
    <section class="watch">
      <div class="watch-images">
        <div id="imageModal" class="image-modal hidden">
          <img id="modalImage" />
        </div>
        <div class="watch-image-container">
          <img class="watch-image" src="{{ $watch->firstImage->url }}" />
        </div>
        <div class="watch-thumbnails">
          @foreach ($watch->images as $image)
          <div class="thumbnail">
            <img class="thumbnail-image" src="{{ $image->url}}"/>
          </div>
          @endforeach
        </div>
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
          <div class="watch-actions">
            <form method="POST" action="{{ route('basket.store', $watch->id) }}">
              @csrf
              <input type="hidden" name="size" id="selected-size">
              <button class="accent-button" type="submit">Add to Bag</button>
            </form>

            @auth
              @if ($inWishlist)
                <form method="POST" action="{{ route('wishlist.destroy', $watch) }}">
                  @csrf
                  @method('DELETE')
                  <button class="secondary-button" type="submit">Remove from Wishlist</button>
                </form>
              @else
                <form method="POST" action="{{ route('wishlist.store', $watch) }}">
                  @csrf
                  <button class="secondary-button" type="submit">Add to Wishlist</button>
                </form>
              @endif
            @endauth
          </div>
        </div>
        <div class="watch-bottom">
          <p><strong>Description</strong></p>
          <hr />
          <p>{{ $watch->description }}</p>
        </div>
      </div>
    </section>
  </div>
  <section class="watch-reviews-section">

    <div class="watch-reviews">
      <p><strong>Customer Reviews</strong></p>
      <hr />

      @forelse($watch->reviews as $review)

        <div class="review">

          <div class="review-top">
            <span class="review-user">
              {{ $review->user->name }}
              @if($review->user_id === auth()->id())
                <span class="you-badge">(You)</span>
              @endif
            </span>

            <span class="review-rating">
              @for ($i = 1; $i <= 5; $i++)
                {{ $i <= $review->rating ? '★' : '☆' }}
              @endfor
            </span>
          </div>

          <p class="review-comment">
            {{ $review->comment }}
          </p>

          <small class="review-date">
            {{ $review->created_at->format('M d, Y') }}
          </small>

        </div>

      @empty
        <p class="no-reviews">No reviews yet.</p>
      @endforelse

    </div>

  </section>
  </div>
  <script>
    document.querySelectorAll('.size').forEach(button => {
      button.addEventListener('click', () => {

        document.querySelectorAll('.size').forEach(b => b.classList.remove('accent-button'));

        button.classList.add('accent-button');

        document.getElementById('selected-size').value = button.dataset.size;

      });
    });
    const modal = document.getElementById('imageModal');
    const modalImage = document.getElementById('modalImage');
    const mainImage = document.querySelector('.watch-image');

    // Thumbnails → update main image
    document.querySelectorAll('.thumbnail img').forEach(img => {
        img.addEventListener('click', () => {
            mainImage.src = img.src;
        });
    });

    // Main image → open modal
    mainImage.addEventListener('click', () => {
        modalImage.src = mainImage.src;
        modal.classList.remove('hidden');
    });

    // Close modal
    modal.addEventListener('click', () => {
        modal.classList.add('hidden');
    });
  </script>
@stop
