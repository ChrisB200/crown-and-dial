@extends('layouts.settings')

@push('head')
  @vite('resources/css/account/profile/delete.css')
@endpush

@section('page')
  <form class="delete-form" method="post" action="{{ route('account.profile.destroy') }}">
    @csrf
    @method('delete')
    <div class="form-header">
      <h2 class="credential-title">Delete Account</h2>
      <hr />
    </div>
    <div class="credential-rows">
      <p class="delete-copy">Are you sure you want to delete your account?</p>
      <button class="accent-button">Delete</button>
    </div>
  </form>
@stop
