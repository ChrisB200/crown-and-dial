@extends('layouts.admin')

@section('title', 'Admin Dashboard')
@section('page_title', 'Dashboard')

@section('content')
<div class="row">
    <div class="col-md-3 col-sm-6">
        <div class="statistic-block block">
            <div class="progress-details d-flex align-items-end justify-content-between">
                <div class="title">
                    <div class="icon"><i class="icon-grid"></i></div><strong>Total Products</strong>
                </div>
                <div class="number dashtext-1">{{ $totalProducts }}</div>
            </div>
        </div>
    </div>
    <div class="col-md-3 col-sm-6">
        <div class="statistic-block block">
            <div class="progress-details d-flex align-items-end justify-content-between">
                <div class="title">
                    <div class="icon"><i class="icon-user-1"></i></div><strong>New Customers</strong>
                </div>
                <div class="number dashtext-2">{{ $newCustomers->count() }}</div>
            </div>
        </div>
    </div>
    <div class="col-md-3 col-sm-6">
        <div class="statistic-block block">
            <div class="progress-details d-flex align-items-end justify-content-between">
                <div class="title">
                    <div class="icon"><i class="icon-warning"></i></div><strong>Low Stock</strong>
                </div>
                <div class="number dashtext-3">{{ $lowStockCount }}</div>
            </div>
        </div>
    </div>
    <div class="col-md-3 col-sm-6">
        <div class="statistic-block block">
            <div class="progress-details d-flex align-items-end justify-content-between">
                <div class="title">
                    <div class="icon"><i class="icon-close"></i></div><strong>Out of Stock</strong>
                </div>
                <div class="number dashtext-4">{{ $outOfStockCount }}</div>
            </div>
        </div>
    </div>
</div>

<div class="row mt-4">
    <div class="col-lg-6">
        <div class="block">
            <div class="title"><strong>Latest Customers</strong></div>
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead><tr><th>Name</th><th>Email</th><th>Joined</th></tr></thead>
                    <tbody>
                    @foreach($newCustomers as $c)
                        <tr>
                            <td>{{ $c->name }}</td>
                            <td>{{ $c->email }}</td>
                            <td>{{ $c->created_at?->format('Y-m-d') }}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="col-lg-6">
        <div class="block">
            <div class="title"><strong>Recent Orders</strong></div>
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead><tr><th>#</th><th>Customer</th><th>Status</th><th>Total</th></tr></thead>
                    <tbody>
                    @foreach($recentOrders as $o)
                        <tr>
                            <td><a href="{{ route('admin.orders.show', $o) }}">{{ $o->id }}</a></td>
                            <td>{{ $o->user?->name }}</td>
                            <td><span class="badge badge-secondary">{{ $o->status }}</span></td>
                            <td>£{{ number_format((float)$o->total, 2) }}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="row mt-4">
    <div class="col-lg-12">
        <div class="block">
            <div class="title d-flex justify-content-between align-items-center flex-wrap gap-2">
                <strong>Recent customer messages</strong>
                <a class="btn btn-sm accent-button" href="{{ route('admin.messages.index') }}">View all messages</a>
            </div>
            <div class="table-responsive">
                <table class="table table-striped mb-0">
                    <thead>
                    <tr>
                        <th>From</th>
                        <th>Subject</th>
                        <th>Date</th>
                        <th></th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse($recentMessages as $msg)
                        <tr class="{{ $msg->read_by_admin ? '' : 'table-info' }}">
                            <td>{{ $msg->senderLabel() }}</td>
                            <td>{{ \Illuminate\Support\Str::limit($msg->subject, 60) }}</td>
                            <td>{{ $msg->created_at?->format('Y-m-d H:i') }}</td>
                            <td>
                                <a class="btn btn-sm accent-button" href="{{ route('admin.messages.show', $msg) }}">Open / reply</a>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="4" class="text-muted">No messages yet.</td></tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
