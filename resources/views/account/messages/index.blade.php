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
      @foreach ($messages as $msg)
        <div class="message">
          <div class="message-header">
            <h3 class="message-subject">{{ $msg->subject }}</h3>
            <p class="message-date">
              {{ $msg->created_at->format('d-m-Y H:i') }}
            </p>
          </div>
          <p class="message-content">{{ $msg->content }}</p>

          @if($msg->admin_reply)
            <div class="message-reply">
              <p class="message-reply-label">Reply from Crown &amp; Dial</p>
              <p class="message-reply-date">{{ $msg->replied_at?->format('d-m-Y H:i') }}</p>
              <p class="message-reply-body">{{ $msg->admin_reply }}</p>
            </div>
          @endif
        </div>
      @endforeach
    </div>
  @endif
@stop
