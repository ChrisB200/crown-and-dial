@extends('layouts.admin')

@section('title', 'Message: '.$message->subject)
@section('page_title', 'Message')

@section('content')
@if(session('status'))
    <div class="alert alert-success">{{ session('status') }}</div>
@endif

<div class="block mb-4">
    <p><strong>From:</strong> {{ $message->senderLabel() }}</p>
    <p><strong>Subject:</strong> {{ $message->subject }}</p>
    <p><strong>Received:</strong> {{ $message->created_at?->format('Y-m-d H:i') }}</p>
    <hr>
    <p class="font-weight-bold mb-2">Customer message</p>
    <div class="message-body" style="white-space: pre-wrap;">{{ $message->content }}</div>

    @if($message->admin_reply)
        <hr>
        <p class="font-weight-bold mb-2">Your previous reply</p>
        <p class="text-muted small mb-1">Sent {{ $message->replied_at?->format('Y-m-d H:i') }}</p>
        <div class="admin-reply-preview" style="white-space: pre-wrap;">{{ $message->admin_reply }}</div>
    @endif
</div>

<div class="block">
    <div class="title"><strong>{{ $message->admin_reply ? 'Update reply' : 'Reply to customer' }}</strong></div>
    <p class="small text-muted mb-3">
        Logged-in customers will see your reply under <strong>Account → Past Messages</strong>. Guest enquiries are not linked to an account.
    </p>
    <form method="POST" action="{{ route('admin.messages.reply', $message) }}">
        @csrf
        @method('PATCH')
        <div class="form-group">
            <label for="admin_reply">Reply</label>
            <textarea id="admin_reply" name="admin_reply" class="form-control" rows="8" required>{{ old('admin_reply', $message->admin_reply) }}</textarea>
            @error('admin_reply')
                <span class="text-danger small">{{ $errors->first('admin_reply') }}</span>
            @enderror
        </div>
        <button type="submit" class="btn btn-sm accent-button">Send reply to customer</button>
    </form>
    <hr class="mt-4">
    <a class="btn btn-sm btn-secondary" href="{{ route('admin.messages.index') }}">Back to inbox</a>
</div>
@endsection
