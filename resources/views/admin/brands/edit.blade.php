@extends('layouts.admin')

@section('title', 'Edit Brand')
@section('page_title', 'Edit Brand')

@section('content')
<div class="row">
    <div class="col-lg-6">
        <div class="block">
            <div class="title"><strong>Edit Brand</strong></div>
            <form method="POST" action="{{ route('admin.brands.update', $brand) }}">
                @csrf
                @method('put')
                <div class="form-group">
                    <label>Name</label>
                    <input class="form-control" name="name" value="{{ old('name', $brand->name) }}" required>
                </div>
                <button class="btn btn-primary" type="submit">Save</button>
                <a class="btn btn-secondary" href="{{ route('admin.brands.index') }}">Cancel</a>
            </form>
        </div>
    </div>
</div>
@endsection
