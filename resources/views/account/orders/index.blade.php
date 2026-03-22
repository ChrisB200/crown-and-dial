@extends('layouts.settings')

@push('head')
    @vite('resources/css/account/orders/index.css')
@endpush

@section('page')

<div class="orders-list">
    <h2>Past Orders</h2>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if (session('error'))
        <div class="alert alert-error">{{ session('error') }}</div>
    @endif

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
                @foreach($order->watchOrders as $line)
                    @if($line->watch)
                    @php
                        $watch = $line->watch;
                        $userReview = $watch->reviews?->firstWhere('user_id', auth()->id());
                        $returnCommitted = (int) $line->productReturns
                            ->whereIn('status', [\App\Models\ProductReturn::STATUS_PENDING, \App\Models\ProductReturn::STATUS_APPROVED])
                            ->sum('quantity');
                        $returnable = max(0, (int) $line->quantity - $returnCommitted);
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
                            <p class="product-name">{{ $watch->name }} <span class="product-meta">({{ $line->size }}mm × {{ $line->quantity }})</span></p>

                            <p class="product-price">
                                £{{ number_format($watch->price, 2) }}
                            </p>

                            @foreach($line->productReturns as $pr)
                                <p class="return-status"><small>Return #{{ $pr->id }}: <strong>{{ $pr->status }}</strong>
                                    @if($pr->admin_notes) — {{ $pr->admin_notes }} @endif
                                </small></p>
                            @endforeach

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

                                {{-- Buy again (same size) --}}
                                <form method="POST" action="{{ route('basket.store', $watch->id) }}">
                                    @csrf
                                    <input type="hidden" name="size" value="{{ $line->size }}">
                                    <button type="submit" class="accent-button">
                                        Buy Again
                                    </button>
                                </form>

                            </div>

                            @if($order->status === 'shipped' && $returnable > 0)
                                <form class="return-request-form" method="POST" action="{{ route('account.returns.store', $order) }}">
                                    @csrf
                                    <input type="hidden" name="watch_order_id" value="{{ $line->id }}">
                                    <label class="return-label">Request a return</label>
                                    <input type="number" name="quantity" min="1" max="{{ $returnable }}" value="1" required class="return-qty">
                                    <textarea name="reason" rows="2" placeholder="Reason for return" required class="return-reason"></textarea>
                                    <button type="submit" class="secondary-button">Submit return request</button>
                                </form>
                            @endif
                        </div>

                    </div>

                    @endif
                @endforeach
            </div>

        </div>

    @empty
        <div class="account-empty-state">
            <p class="eyebrow">No order history yet</p>
            <h3>No orders in your account.</h3>
            <p>When you place your first order, it will appear here with full order details.</p>
            <a href="{{ route('watches.index') }}" class="accent-link">Explore Watches</a>
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
