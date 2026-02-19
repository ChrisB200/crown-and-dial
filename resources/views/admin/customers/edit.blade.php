@extends('layouts.admin')

@section('title', 'Edit Customer')
@section('page_title', 'Edit Customer')

@section('content')
<div class="row">
    <div class="col-lg-6">
        <div class="block">
            <div class="title"><strong>Edit Customer</strong></div>
            <form method="POST" action="{{ route('admin.customers.update', $customer) }}">
                @csrf
                @method('put')
                <div class="form-group">
                    <label>Name</label>
                    <input class="form-control" name="name" value="{{ old('name', $customer->name) }}" required>
                </div>
                <div class="form-group">
                    <label>Email</label>
                    <input class="form-control" name="email" value="{{ old('email', $customer->email) }}" required>
                </div>
                <hr>
                <p class="text-muted mb-2">Optional: set a new password for this user.</p>
                <div class="form-group">
                    <label>New Password</label>
                    <input class="form-control" name="password" type="password" autocomplete="new-password">
                </div>
                <div class="form-group">
                    <label>Confirm New Password</label>
                    <input class="form-control" name="password_confirmation" type="password" autocomplete="new-password">
                </div>
                <button class="btn btn-primary" type="submit">Save</button>
                <a class="btn btn-secondary" href="{{ route('admin.customers.index') }}">Cancel</a>
            </form>
        </div>
    </div>
</div>
@endsection
