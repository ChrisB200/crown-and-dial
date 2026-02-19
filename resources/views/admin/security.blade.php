@extends('layouts.admin')

@section('title', 'Security')
@section('page_title', 'Security')

@section('content')
<div class="row">
    <div class="col-lg-6">
        <div class="block">
            <div class="title"><strong>Change Password</strong></div>
            <form method="POST" action="{{ route('password.update') }}">
                @csrf
                @method('put')
                <div class="form-group">
                    <label>Current Password</label>
                    <input class="form-control" name="current_password" type="password" required>
                </div>
                <div class="form-group">
                    <label>New Password</label>
                    <input class="form-control" name="password" type="password" required>
                </div>
                <div class="form-group">
                    <label>Confirm New Password</label>
                    <input class="form-control" name="password_confirmation" type="password" required>
                </div>
                <button class="btn btn-primary" type="submit">Update Password</button>
            </form>
        </div>
    </div>
</div>
@endsection
