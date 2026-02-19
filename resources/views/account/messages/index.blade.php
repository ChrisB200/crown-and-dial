@extends('layouts.settings')

@push('head')
  @vite('resources/css/account/messages/index.css')
@endpush

@section('page')
  <h1>
    Past Messages
  </h1>
  @if (count($messages) === 0)
    <p class="no-messages">There are no messages</p>
  @endif
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
@stop
