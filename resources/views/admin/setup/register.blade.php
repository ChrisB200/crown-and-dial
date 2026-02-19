@extends('layouts.master')

@section('content')
  <x-logo class="logo-container" />

  <form class="credential-form" method="POST" action="{{ route('admin.setup.store') }}">
    @csrf

    <h1 class="credential-title">Create Admin Account</h1>
    <p class="credential-title" style="margin-top:-8px; margin-bottom:18px;">First-time setup. This page disappears once an admin exists.</p>

    @if ($errors->any())
      <div style="margin-bottom:14px;">
        <ul style="margin:0; padding-left:18px;">
          @foreach ($errors->all() as $e)
            <li>{{ $e }}</li>
          @endforeach
        </ul>
      </div>
    @endif

    <div class="credential-row">
      <label for="name">Name</label>
      <input id="name" type="text" name="name" value="{{ old('name') }}" required autofocus>
      @error('name')
        <div>{{ $message }}</div>
      @enderror
    </div>

    <div class="credential-row">
      <label for="email">Email</label>
      <input id="email" type="email" name="email" value="{{ old('email') }}" required>
      @error('email')
        <div>{{ $message }}</div>
      @enderror
    </div>

    <div class="credential-row">
      <label for="password">Password</label>
      <input id="password" type="password" name="password" required>
      @error('password')
        <div>{{ $message }}</div>
      @enderror
    </div>

    <div class="credential-row">
      <label for="password_confirmation">Confirm Password</label>
      <input id="password_confirmation" type="password" name="password_confirmation" required>
      @error('password_confirmation')
        <div>{{ $message }}</div>
      @enderror
    </div>

    <div class="credential-bottom">
      <button class="accent-button" type="submit">Create Admin</button>
    </div>
  </form>

@endsection

