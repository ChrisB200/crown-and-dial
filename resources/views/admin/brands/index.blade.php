@extends('layouts.admin')

@section('title', 'Brands')
@section('page_title', 'Brands')

@section('content')
<div class="d-flex justify-content-between mb-3">
    <h4 class="mb-0">Brands</h4>
    <a class="btn btn-primary" href="{{ route('admin.brands.create') }}">Add Brand</a>
</div>
<div class="block">
    <div class="table-responsive">
        <table class="table table-dark table-striped">
            <thead><tr><th>Name</th><th class="text-right">Actions</th></tr></thead>
            <tbody>
            @foreach($brands as $brand)
                <tr>
                    <td>{{ $brand->name }}</td>
                    <td class="text-right">
                        <a class="btn btn-sm btn-outline-secondary" href="{{ route('admin.brands.edit', $brand) }}">Edit</a>
                        <form method="POST" action="{{ route('admin.brands.destroy', $brand) }}" style="display:inline">
                            @csrf
                            @method('delete')
                            <button class="btn btn-sm btn-outline-danger" onclick="return confirm('Delete brand?')">Delete</button>
                        </form>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
    <div>{{ $brands->links() }}</div>
</div>
@endsection
