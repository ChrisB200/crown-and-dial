@extends('layouts.settings')

@push('head')
    @vite('resources/css/account/wishlist/index.css')
@endpush

@section('page')
<div class="wishlist-page">
    <div class="wishlist-header">
        <div>
            <p class="eyebrow">Saved for later</p>
            <h2>My Wishlist</h2>
            <p class="wishlist-copy">Keep track of the timepieces you love and come back whenever you are ready.</p>
        </div>
    </div>

    @if (session('status'))
        <div class="wishlist-alert">{{ session('status') }}</div>
    @endif

    @if ($watches->count())
        <div class="wishlist-grid">
            @foreach ($watches as $watch)
                <article class="wishlist-card">
                    <a class="wishlist-image-link" href="{{ route('watches.show', $watch) }}">
                        <div class="wishlist-image-container">
                            <img
                                class="wishlist-image"
                                src="{{ $watch->firstImage?->url }}"
                                alt="{{ $watch->name }}"
                            />
                        </div>
                    </a>

                    <div class="wishlist-content">
                        <p class="wishlist-brand">{{ strtoupper($watch->brand->name) }}</p>
                        <a class="wishlist-name" href="{{ route('watches.show', $watch) }}">{{ $watch->name }}</a>
                        <p class="wishlist-price">£{{ number_format((float) $watch->price, 2) }}</p>

                        <div class="wishlist-actions">
                            <form method="POST" action="{{ route('basket.store', $watch) }}">
                                @csrf
                                <button class="accent-button" type="submit">Add to Bag</button>
                            </form>

                            <form method="POST" action="{{ route('wishlist.destroy', $watch) }}">
                                @csrf
                                @method('DELETE')
                                <button class="secondary-button" type="submit">Remove</button>
                            </form>
                        </div>
                    </div>
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
    @else
        <div class="wishlist-empty">
            <p class="eyebrow">Your collection is empty</p>
            <h3>No watches in your wishlist yet.</h3>
            <p>Browse our catalog and save the pieces that catch your eye.</p>
            <a href="{{ route('watches.index') }}" class="accent-link">Explore Watches</a>
        </div>
    @endif
</div>
@endsection
