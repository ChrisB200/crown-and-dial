@extends('layouts.settings')

@push('head')
  @vite('resources/css/account/profile/security.css')
@endpush

@section('page')
  <form class="edit-form" method="post" action="{{ route('password.update') }}">
    @csrf
    @method('put')
    <div>
      <h2 class="credential-title">Change Password</h2>
      <br />
      <hr />
    </div>
    <div class="credential-rows">
      <div class="credential-row">
        <label for="current_password">Current Password</label>
        <input name="current_password" type="password" />
      </div>
      <div class="credential-row">
        <label for="password">Password</label>
        <input name="password" type="password" />
      </div>
      <div class="credential-row">
        <label for="password_confirmation">Confirm Password</label>
        <input name="password_confirmation" type="password" />
      </div>
      <button class="accent-button">Submit</button>
    </div>
  </form>
@stop
