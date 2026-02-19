@extends('layouts.settings')

@push('head')
  @vite('resources/css/account/profile/delete.css')
@endpush

@section('page')
  <form method="post" action="{{ route('account.profile.destroy') }}">
    @csrf
    @method('delete')
    <h1>Delete Account</h1>
    <p>Are you sure you want to delete your account?</p>
    <button class="accent-button">Delete</button>
  </form>
@stop
