@extends('layouts.admin')

@section('title', 'Suppliers')
@section('page_title', 'Suppliers')

@section('content')
<div class="d-flex justify-content-between mb-3">
    <h4 class="mb-0">Suppliers</h4>
    <a class="btn btn-primary" href="{{ route('admin.suppliers.create') }}">Add Supplier</a>
</div>

@if (session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

<div class="block">
    <div class="table-responsive">
        <table class="table table-dark table-striped mb-0">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Contact</th>
                    <th class="text-right">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($suppliers as $supplier)
                    <tr>
                        <td>{{ $supplier->name }}</td>
                        <td>{{ $supplier->contact }}</td>
                        <td class="text-right">
                            <a class="btn btn-sm btn-outline-secondary" href="{{ route('admin.suppliers.show', $supplier) }}">View</a>
                            <a class="btn btn-sm btn-outline-info" href="{{ route('admin.suppliers.edit', $supplier) }}">Edit</a>
                            <form method="POST" action="{{ route('admin.suppliers.destroy', $supplier) }}" style="display:inline">
                                @csrf
                                @method('delete')
                                <button class="btn btn-sm btn-outline-danger" onclick="return confirm('Delete supplier?')">Delete</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3" class="text-center text-muted">No suppliers found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-3">
        {{ $suppliers->links() }}
    </div>
</div>
@endsection
