@extends('layouts.user')

@push('head')
  @vite('resources/css/contact/create.css')
@endpush

@section('page')
  <section class="form-section">
    <form action="{{ route('contact.store') }}" method="POST">
      @csrf
      <h1 class="section-title">CONTACT US</h1>

      @if ($errors->any())
        <div class="alert alert-error">
          <ul>
            @foreach ($errors->all() as $err)
              <li>{{ $err }}</li>
            @endforeach
          </ul>
        </div>
      @endif

      @guest
        <div class="form-row">
          <label for="guest_name">Your name <span class="asterisk">*</span></label>
          <input id="guest_name" type="text" name="guest_name" value="{{ old('guest_name') }}" required />
        </div>
        <div class="form-row">
          <label for="guest_email">Email <span class="asterisk">*</span></label>
          <input id="guest_email" type="email" name="guest_email" value="{{ old('guest_email') }}" required />
        </div>
      @endguest

      <div class="form-row">
        <label for="subject">Subject <span class="asterisk">*</span></label>
        <input id="subject" type="text" name="subject" value="{{ old('subject') }}" required />
      </div>
      <div class="form-row">
        <label for="content">Message <span class="asterisk">*</span></label>
        <textarea rows="8" id="content" name="content" required>{{ old('content') }}</textarea>
      </div>
      <button class="accent-button" type="submit">Submit</button>
    </form>
  </section>
@stop
