@extends('layouts.user')

@push('head')
  @vite('resources/css/contact/create.css')
@endpush

@section('page')
  <section class="form-section">
    <form action="{{ route('contact.store') }}" method="POST">
      @csrf
      <h1 class="section-title">CONTACT US</h1>
      <div class="form-row">
        <label for="subject">Subject <span class="asterisk">*</span></label>
        <input id="subject" type="text" name="subject" required />
      </div>
      <div class="form-row">
        <label for="content">Message <span class="asterisk">*</span></label>
        <textarea rows="8" id="content" type="content" name="content" required></textarea>
      </div>
      <button class="accent-button">Submit</button>
    </form>
  </section>
@stop
