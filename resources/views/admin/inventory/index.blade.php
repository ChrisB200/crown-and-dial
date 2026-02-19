@extends('layouts.admin')

@section('title', 'Inventory')
@section('page_title', 'Inventory')

@section('content')
@if($outOfStockCount > 0 || $lowStockCount > 0)
    <div class="alert alert-warning">
        <strong>Inventory Alert:</strong>
        @if($outOfStockCount > 0)
            {{ $outOfStockCount }} product(s) out of stock.
        @endif
        @if($lowStockCount > 0)
            {{ $lowStockCount }} product(s) low stock (below {{ $lowThreshold }}).
        @endif
    </div>
@endif

<div class="row mb-3">
    <div class="col-md-8">
        <form class="form-inline" method="GET">
            <input class="form-control mr-2" type="text" name="q" value="{{ $q }}" placeholder="Search products"/>
            <select name="status" class="form-control mr-2">
                <option value="">All</option>
                <option value="in" {{ $status==='in' ? 'selected' : '' }}>In Stock</option>
                <option value="low" {{ $status==='low' ? 'selected' : '' }}>Low Stock</option>
                <option value="out" {{ $status==='out' ? 'selected' : '' }}>Out of Stock</option>
            </select>
            <button class="btn btn-secondary" type="submit">Filter</button>
        </form>
    </div>
</div>

<div class="row">
    <div class="col-lg-8">
        <div class="block">
            <div class="title"><strong>Stock Levels</strong></div>
            <div class="table-responsive">
                <table class="table table-dark table-striped">
                    <thead><tr><th>Product</th><th>Qty</th><th>Status</th></tr></thead>
                    <tbody>
                    @foreach($watches as $w)
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

    <div class="col-lg-4">
        <div class="block">
            <div class="title"><strong>Incoming Stock</strong></div>
            <form method="POST" action="{{ route('admin.inventory.incoming') }}">
                @csrf
                <div class="form-group">
                    <label>Product</label>
                    <select class="form-control" name="watch_id" required>
                        @foreach($watches as $w)
                            <option value="{{ $w->id }}">{{ $w->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label>Quantity</label>
                    <input class="form-control" type="number" name="quantity" min="1" value="1" required>
                </div>
                <div class="form-group">
                    <label>Note (optional)</label>
                    <input class="form-control" type="text" name="note" maxlength="255">
                </div>
                <button class="btn btn-primary" type="submit">Record Incoming</button>
            </form>
        </div>
    </div>
</div>
@endsection
