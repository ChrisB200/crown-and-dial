@extends('layouts.settings')

@push('head')
  @vite('resources/css/account/messages/index.css')
@endpush

@section('page')
  <h2 class="credential-title">
    Past Messages
  </h2>
  @if (count($messages) === 0)
    <div class="account-empty-state">
      <p class="eyebrow">No messages yet</p>
      <h3>Your message history is empty.</h3>
      <p>Need help with an order or a product? Contact us and your messages will appear here.</p>
      <a href="{{ route('contact.create') }}" class="accent-link">Contact Support</a>
    </div>
  @else
    <div class="messages">
      @foreach ($messages as $message)
        <div class="message">
          <div class="message-header">
            <h3 class="message-subject">{{ $message->subject }}</h3>
            <p class="message-date">
              {{ $message->created_at->format('d-m-Y H:i') }}
            </p>
          </div>
          <p class="message-content">{{ $message->content }}</p>
        </div>
      @endforeach
    </div>
  @endif
@stop
