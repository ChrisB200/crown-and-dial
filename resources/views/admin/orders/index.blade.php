@extends('layouts.admin')

@section('title', 'Orders')
@section('page_title', 'Orders')

@section('content')
<div class="row mb-3">
    <div class="col-md-6">
        <form class="form-inline" method="GET">
            <label class="mr-2">Status</label>
            <select name="status" class="form-control mr-2">
                <option value="">All</option>
                @foreach(['pending','paid','shipped','delivered','cancelled'] as $st)
                    <option value="{{ $st }}" @selected($status===$st)>{{ ucfirst($st) }}</option>
                @endforeach
            </select>
            <button class="btn btn-secondary">Filter</button>
        </form>
    </div>
</div>

<div class="block">
    <div class="table-responsive">
        <table class="table table-striped">
            <thead><tr><th>#</th><th>Customer</th><th>Status</th><th>Total</th><th>Created</th></tr></thead>
            <tbody>
            @foreach($orders as $o)
                <tr>
                    <td><a href="{{ route('admin.orders.show', $o) }}">{{ $o->id }}</a></td>
                    <td>{{ $o->user?->name }}</td>
                    <td><span class="badge badge-secondary">{{ $o->status }}</span></td>
                    <td>£{{ number_format((float)$o->total,2) }}</td>
                    <td>{{ $o->created_at?->format('Y-m-d H:i') }}</td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
    <div class="mt-3">{{ $orders->links() }}</div>
</div>
@endsection
