@extends('layouts.admin')

@section('title', 'Reports')
@section('page_title', 'Reports')

@section('content')
<div class="row">
    <div class="col-lg-6">
        <div class="block">
            <div class="title"><strong>Orders by Status</strong></div>
            <canvas id="ordersByStatus"></canvas>
        </div>
    </div>
    <div class="col-lg-6">
        <div class="block">
            <div class="title"><strong>Inventory Movements</strong></div>
            <canvas id="movementByType"></canvas>
        </div>
    </div>
</div>

<div class="row mt-4">
    <div class="col-lg-12">
        <div class="block">
            <div class="title"><strong>Current Stock Levels</strong></div>
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead><tr><th>Product</th><th>Qty</th><th>Status</th></tr></thead>
                    <tbody>
                    @foreach($stock as $w)
                        @php($qty = $w->inventory?->quantity ?? 0)
                        @php($label = $w->stockStatus($lowThreshold))
                        <tr>
                            <td>{{ $w->name }}</td>
                            <td>{{ $qty }}</td>
                            <td>
                                @if($label === 'in stock')
                                    <span class="badge badge-success">in stock</span>
                                @elseif($label === 'low stock')
                                    <span class="badge badge-warning">low stock</span>
                                @else
                                    <span class="badge badge-danger">out of stock</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
(function () {
    var orders = @json($ordersByStatus);
    var m = @json($movementByType);

    var ctx1 = document.getElementById('ordersByStatus');
    new Chart(ctx1, {
        type: 'doughnut',
        data: {
            labels: Object.keys(orders),
            datasets: [{
                data: Object.values(orders)
            }]
        },
        options: {responsive: true}
    });

    var ctx2 = document.getElementById('movementByType');
    new Chart(ctx2, {
        type: 'bar',
        data: {
            labels: Object.keys(m),
            datasets: [{
                data: Object.values(m)
            }]
        },
        options: {responsive: true}
    });
})();
</script>
@endpush
