@extends('layouts.master')

@section('content')
  <x-logo class="logo-container" />

  <form class="credential-form" method="POST" action="{{ route('register') }}">
    @csrf
    <h1 class="credential-title">Register to Crown & Dial</h1>

    {{-- Name --}}
    <div class="credential-row">
      <label for="name">Name</label>
      <input id="name" type="text" name="name" value="{{ old('name') }}" required autofocus autocomplete="name">

      @error('name')
        <div>{{ $message }}</div>
      @enderror
    </div>

    {{-- Email --}}
    <div class="credential-row">
      <label for="email">Email</label>
      <input id="email" type="email" name="email" value="{{ old('email') }}" required autocomplete="username">

      @error('email')
        <div>{{ $message }}</div>
      @enderror
    </div>

    <div class="credential-span">
      {{-- Password --}}
      <div class="credential-row">
        <label for="password">Password</label>
        <input id="password" type="password" name="password" required autocomplete="new-password">

        @error('password')
          <div>{{ $message }}</div>
        @enderror
      </div>

      {{-- Confirm Password --}}
      <div class="credential-row">
        <label for="password_confirmation">Confirm Password</label>
        <input id="password_confirmation" type="password" name="password_confirmation" required
          autocomplete="new-password">

        @error('password_confirmation')
          <div>{{ $message }}</div>
        @enderror
      </div>
    </div>


    {{-- Already registered + Submit --}}
    <div class="credential-bottom">
      <button class="accent-button" type="submit">
        Register
      </button>

      <a href="{{ route('login') }}">Already registered?</a>
    </div>

  </form>
@endsection
