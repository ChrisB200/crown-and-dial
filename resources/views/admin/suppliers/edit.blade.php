@extends('layouts.admin')

@section('title', 'Edit Supplier')
@section('page_title', 'Edit Supplier')

@section('content')
<div class="d-flex justify-content-between mb-3">
    <h4 class="mb-0">Edit Supplier</h4>
    <a class="btn btn-secondary" href="{{ route('admin.suppliers.index') }}">Back to Suppliers</a>
</div>

@if ($errors->any())
    <div class="alert alert-danger">
        <ul class="mb-0">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="block">
    <form action="{{ route('admin.suppliers.update', $supplier) }}" method="POST">
        @csrf
        @method('put')

        <div class="form-group">
            <label>Name</label>
            <input class="form-control" type="text" name="name" value="{{ old('name', $supplier->name) }}" required>
        </div>
        <div class="form-group">
            <label>Contact</label>
            <input class="form-control" type="text" name="contact" value="{{ old('contact', $supplier->contact) }}" required>
        </div>

        <button class="btn btn-primary" type="submit">Save Changes</button>
    </form>
</div>
@endsection
