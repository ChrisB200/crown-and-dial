@extends('layouts.admin')

@section('title', 'Create Customer')
@section('page_title', 'Create Customer')

@section('content')
<div class="row">
    <div class="col-lg-6">
        <div class="block">
            <div class="title"><strong>New Customer</strong></div>
            <form method="POST" action="{{ route('admin.customers.store') }}">
                @csrf
                <div class="form-group">
                    <label>Name</label>
                    <input class="form-control" name="name" value="{{ old('name') }}" required>
                </div>
                <div class="form-group">
                    <label>Email</label>
                    <input class="form-control" name="email" type="email" value="{{ old('email') }}" required>
                </div>
                <div class="form-group">
                    <label>Password</label>
                    <input class="form-control" name="password" type="password" required>
                </div>
                <div class="form-group">
                    <label>Confirm Password</label>
                    <input class="form-control" name="password_confirmation" type="password" required>
                </div>
                <button class="btn btn-primary" type="submit">Create</button>
                <a class="btn btn-secondary" href="{{ route('admin.customers.index') }}">Cancel</a>
            </form>
        </div>
    </div>
</div>
@endsection
