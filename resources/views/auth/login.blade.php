@extends('layouts.master')

@section('content')
  @if (session('status'))
    <div>
      {{ session('status') }}
    </div>
  @endif

  <x-logo class="logo-container" />

  <form class="credential-form" method="POST" action="{{ route('login') }}">
    @csrf


    <h1 class="credential-title">Login to Crown & Dial</h1>

    {{-- Email --}}
    <div class="credential-row">
      <label for="email">Email</label>
      <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus>
      @error('email')
        <div>{{ $message }}</div>
      @enderror
    </div>

    {{-- Password --}}
    <div class="credential-row">
      <label for="password">Password</label>
      <input id="password" type="password" name="password" required>
      @error('password')
        <div>{{ $message }}</div>
      @enderror
    </div>

    {{-- Remember Me --}}
    <div>
      <label>
        <input type="checkbox" name="remember">
        Remember me
      </label>
    </div>

    {{-- Forgot + Login --}}
    <div class="credential-bottom">
      <button class="accent-button" type="submit">
        Log in
      </button>

      @if (Route::has('password.request'))
        <a href="{{ route('password.request') }}">
          Forgot your password?
        </a>
      @endif
      <p>Don't have an account? <a class="bold" href="/register">Create One</a></p>
    </div>
  </form>
  <script>
    document.addEventListener("DOMContentLoaded", () => {
      const html = document.documentElement;

      if (localStorage.getItem("theme") === "dark") {
        html.classList.add("dark");
      } else {
        html.classList.remove("dark");
      }
    });
  </script>
@endsection
