@extends('layouts.admin')

@section('title', 'Product returns')
@section('page_title', 'Product returns')

@section('content')
@if(session('status'))
    <div class="alert alert-success">{{ session('status') }}</div>
@endif

<div class="row mb-3">
    <div class="col-md-6">
        <form class="form-inline" method="GET">
            <label class="mr-2">Status</label>
            <select name="status" class="form-control mr-2" onchange="this.form.submit()">
                <option value="pending" {{ $status === 'pending' ? 'selected' : '' }}>Pending</option>
                <option value="approved" {{ $status === 'approved' ? 'selected' : '' }}>Approved</option>
                <option value="rejected" {{ $status === 'rejected' ? 'selected' : '' }}>Rejected</option>
                <option value="" {{ $status === '' ? 'selected' : '' }}>All</option>
            </select>
        </form>
    </div>
</div>

<div class="block">
    <div class="table-responsive">
        <table class="table table-striped">
            <thead>
            <tr>
                <th>ID</th>
                <th>Customer</th>
                <th>Order</th>
                <th>Product</th>
                <th>Size</th>
                <th>Qty</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
            </thead>
            <tbody>
            @forelse($returns as $r)
                <tr>
                    <td>{{ $r->id }}</td>
                    <td>{{ $r->user?->name }}<br><small>{{ $r->user?->email }}</small></td>
                    <td>#{{ $r->order_id }}</td>
                    <td>{{ $r->watchOrder?->watch?->name ?? '—' }}</td>
                    <td>{{ $r->watchOrder?->size }}mm</td>
                    <td>{{ $r->quantity }}</td>
                    <td><span class="badge badge-secondary">{{ $r->status }}</span></td>
                    <td style="min-width: 220px;">
                        @if($r->status === \App\Models\ProductReturn::STATUS_PENDING)
                            <form method="POST" action="{{ route('admin.returns.approve', $r) }}" class="mb-2">
                                @csrf
                                @method('PATCH')
                                <input type="text" name="admin_notes" class="form-control form-control-sm mb-1" placeholder="Notes (optional)">
                                <button type="submit" class="btn btn-sm btn-success">Approve &amp; restock</button>
                            </form>
                            <form method="POST" action="{{ route('admin.returns.reject', $r) }}">
                                @csrf
                                @method('PATCH')
                                <input type="text" name="admin_notes" class="form-control form-control-sm mb-1" placeholder="Reason (required)" required>
                                <button type="submit" class="btn btn-sm btn-outline-danger">Reject</button>
                            </form>
                        @else
                            <small>{{ $r->processed_at?->format('Y-m-d H:i') }}</small>
                            @if($r->admin_notes)
                                <p class="mb-0"><em>{{ $r->admin_notes }}</em></p>
                            @endif
                        @endif
                    </td>
                </tr>
            @empty
                <tr><td colspan="8" class="text-muted">No return requests.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
    {{ $returns->links() }}
</div>
@endsection
