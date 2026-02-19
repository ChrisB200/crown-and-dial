@extends('layouts.admin')

@section('title', 'Watches')
@section('page_title', 'Watches')

@section('content')
<div class="d-flex justify-content-between mb-3">
    <h4 class="mb-0">Watches</h4>
    <a class="btn btn-primary" href="{{ route('admin.watches.create') }}">Add Watch</a>
</div>

@if (session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

<div class="block">
    <div class="table-responsive">
        <table class="table table-dark table-striped mb-0">
            <thead>
                <tr>
                    <th>Product</th>
                    <th>Brand</th>
                    <th>Category</th>
                    <th>Supplier</th>
                    <th class="text-right">Price</th>
                    <th class="text-right">Stock</th>
                    <th class="text-right">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($watches as $watch)
                    <tr>
                        <td class="font-weight-bold">{{ $watch->name }}</td>
                        <td>{{ $watch->brand?->name ?? '-' }}</td>
                        <td>{{ $watch->category?->name ?? '-' }}</td>
                        <td>{{ $watch->supplier?->name ?? '-' }}</td>
                        <td class="text-right">£{{ number_format((float)$watch->price, 2) }}</td>
                        <td class="text-right">{{ $watch->inventory?->quantity ?? 0 }}</td>
                        <td class="text-right">
                            <a class="btn btn-sm btn-outline-secondary" href="{{ route('admin.watches.show', $watch) }}">View</a>
                            <a class="btn btn-sm btn-outline-info" href="{{ route('admin.watches.edit', $watch) }}">Edit</a>
                            <form method="POST" action="{{ route('admin.watches.destroy', $watch) }}" style="display:inline">
                                @csrf
                                @method('delete')
                                <button class="btn btn-sm btn-outline-danger" onclick="return confirm('Delete this watch?')">Delete</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center text-muted">No watches found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-3">
        {{ $watches->links() }}
    </div>
</div>
@endsection
