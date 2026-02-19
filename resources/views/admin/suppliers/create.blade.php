@extends('layouts.admin')

@section('title', 'Add Supplier')
@section('page_title', 'Add Supplier')

@section('content')
<div class="d-flex justify-content-between mb-3">
    <h4 class="mb-0">Add Supplier</h4>
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
    <form action="{{ route('admin.suppliers.store') }}" method="POST">
        @csrf
        <div class="form-group">
            <label>Name</label>
            <input class="form-control" type="text" name="name" value="{{ old('name') }}" required autofocus>
        </div>
        <div class="form-group">
            <label>Contact</label>
            <input class="form-control" type="text" name="contact" value="{{ old('contact') }}" required>
            <small class="form-text text-muted">Phone number or email (whatever you use to contact the supplier).</small>
        </div>
        <button class="btn btn-primary" type="submit">Create Supplier</button>
    </form>
</div>
@endsection
