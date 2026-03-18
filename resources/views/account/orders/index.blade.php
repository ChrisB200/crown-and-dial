@extends('layouts.settings')

@push('head')
    @vite('resources/css/account/orders/index.css')
@endpush

@section('page')

<div class="orders-list">
    <h2>Past Orders</h2>

    @forelse($orders as $order)

        <div class="order-row">

            {{-- ===== Header ===== --}}
            <div class="order-header">
                <div>
                    <strong>ORDER #{{ $order->id }}</strong><br>
                    <small>{{ $order->created_at?->format('Y-m-d H:i') }}</small>
                </div>

                <div class="order-summary">
                    <div><strong>{{ ucfirst($order->status) }}</strong></div>
                    <div>£{{ number_format((float) $order->total, 2) }}</div>
                </div>
            </div>

            {{-- ===== Products ===== --}}
            <div class="order-products">
                @foreach($order->watches as $watch)

                    @php
                        $userReview = $watch->reviews?->firstWhere('user_id', auth()->id());
                    @endphp

                    <div class="order-product">

                        {{-- Image --}}
                        <a href="{{ route('watches.show', $watch->id) }}">
                            <img
                                src="{{ $watch->firstImage?->url }}"
                                class="order-product-image"
                                alt="{{ $watch->name }}"
                            />
                        </a>

                        {{-- Info --}}
                        <div class="order-product-info">
                            <p class="product-name">{{ $watch->name }}</p>

                            <p class="product-price">
                                £{{ number_format($watch->price, 2) }}
                            </p>

                            <div class="product-actions">

                                {{-- Review button --}}
                                <button
                                    type="button"
                                    class="secondary-button review-btn"
                                    data-watch-id="{{ $watch->id }}"
                                    data-watch-name="{{ $watch->name }}"
                                    data-comment="{{ e($userReview?->comment) }}"
                                    data-rating="{{ $userReview?->rating }}"
                                >
                                    {{ $userReview ? 'Edit Review' : 'Leave Review' }}
                                </button>

                                {{-- Buy again --}}
                                <form method="POST" action="{{ route('basket.store', $watch->id) }}">
                                    @csrf
                                    <button type="submit" class="accent-button">
                                        Buy Again
                                    </button>
                                </form>

                            </div>
                        </div>

                    </div>

                @endforeach
            </div>

        </div>

    @empty
        <div class="order-row">
            <p>You have not placed any orders yet.</p>
        </div>
    @endforelse

</div>

{{-- ===== REVIEW MODAL ===== --}}
<div id="reviewModal" class="modal hidden">
    <div class="modal-content">

        <form class="review-form" method="POST" action="{{ route('reviews.store') }}">
            @csrf

            <input type="hidden" name="watch_id" id="reviewWatchId">

            <h3 id="reviewTitle">Leave a review</h3>

            <textarea
                name="comment"
                placeholder="Write your review..."
                required
            ></textarea>

            <select name="rating" required>
                <option value="">Rating</option>
                <option value="5">5 ★</option>
                <option value="4">4 ★</option>
                <option value="3">3 ★</option>
                <option value="2">2 ★</option>
                <option value="1">1 ★</option>
            </select>

            <button type="submit" class="accent-button">
                Submit Review
            </button>

        </form>

    </div>
</div>

{{-- ===== JS ===== --}}
<script>
    const modal = document.getElementById('reviewModal');
    const watchIdInput = document.getElementById('reviewWatchId');
    const title = document.getElementById('reviewTitle');
    const commentInput = document.querySelector('textarea[name="comment"]');
    const ratingSelect = document.querySelector('select[name="rating"]');

    document.querySelectorAll('.review-btn').forEach(btn => {
        btn.addEventListener('click', () => {

            watchIdInput.value = btn.dataset.watchId;

            const hasReview = btn.dataset.comment;

            title.textContent = hasReview
                ? `Edit Review for ${btn.dataset.watchName}`
                : `Leave a Review for ${btn.dataset.watchName}`;

            // Autofill
            commentInput.value = btn.dataset.comment || '';
            ratingSelect.value = btn.dataset.rating || '';

            modal.classList.remove('hidden');
        });
    });

    // Close modal + reset
    modal.addEventListener('click', (e) => {
        if (e.target === modal) {
            modal.classList.add('hidden');

            commentInput.value = '';
            ratingSelect.value = '';
        }
    });
</script>

@endsection
