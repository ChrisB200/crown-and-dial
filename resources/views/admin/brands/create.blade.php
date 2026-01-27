@extends('layouts.admin')

@section('title', 'Create Brand')
@section('page_title', 'Create Brand')

@section('content')
<div class="row">
    <div class="col-lg-6">
        <div class="block">
            <div class="title"><strong>New Brand</strong></div>
            <form method="POST" action="{{ route('admin.brands.store') }}">
                @csrf
                <div class="form-group">
                    <label>Name</label>
                    <input class="form-control" name="name" required>
                </div>
                <button class="btn btn-primary" type="submit">Create</button>
            </form>
        </div>
    </div>
</div>
@endsection
