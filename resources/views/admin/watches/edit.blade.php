@extends('layouts.admin')

@section('title', 'Edit Watch')
@section('page_title', 'Edit Watch')

@section('content')
<div class="d-flex justify-content-between mb-3">
    <h4 class="mb-0">Edit Watch</h4>
    <a class="btn btn-secondary" href="{{ route('admin.watches.index') }}">Back to Watches</a>
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
    <form action="{{ route('admin.watches.update', $watch) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('put')

        <div class="form-row">
            <div class="form-group col-md-4">
                <label>Brand</label>
                <select class="form-control" name="brand_id" required>
                    @foreach ($brands as $brand)
                        <option value="{{ $brand->id }}" {{ (int)old('brand_id', $watch->brand_id) === (int)$brand->id ? 'selected' : '' }}>
                            {{ $brand->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="form-group col-md-4">
                <label>Category</label>
                <select class="form-control" name="category_id" required>
                    @foreach ($categories as $category)
                        <option value="{{ $category->id }}" {{ (int)old('category_id', $watch->category_id) === (int)$category->id ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="form-group col-md-4">
                <label>Supplier</label>
                <select class="form-control" name="supplier_id" required>
                    @foreach ($suppliers as $supplier)
                        <option value="{{ $supplier->id }}" {{ (int)old('supplier_id', $watch->supplier_id) === (int)$supplier->id ? 'selected' : '' }}>
                            {{ $supplier->name }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="form-row">
            <div class="form-group col-md-6">
                <label>Name</label>
                <input class="form-control" type="text" name="name" value="{{ old('name', $watch->name) }}" required>
            </div>
            <div class="form-group col-md-3">
                <label>Price</label>
                <input class="form-control" type="number" name="price" step="0.01" value="{{ old('price', $watch->price) }}" required>
            </div>
            <div class="form-group col-md-3">
                <label>Size</label>
                <input class="form-control" type="number" name="size" step="1" value="{{ old('size', $watch->size) }}" required>
            </div>
        </div>

        <div class="form-group">
            <label>Description</label>
            <textarea class="form-control" name="description" rows="4" required>{{ old('description', $watch->description) }}</textarea>
        </div>

        <div class="form-group">
            <label>Replace Image (optional)</label>
            <input class="form-control-file" type="file" name="image">
            @if($watch->image_path)
                <small class="form-text text-muted">Current: {{ $watch->image_path }}</small>
            @endif
        </div>

        <button class="btn btn-primary" type="submit">Save Changes</button>
    </form>
</div>
@endsection
