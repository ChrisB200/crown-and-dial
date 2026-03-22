@extends('layouts.admin')

@section('title', 'Customer messages')
@section('page_title', 'Customer messages')

@section('content')
<div class="row mb-3">
    <div class="col-md-8">
        <form class="form-inline" method="GET">
            <input class="form-control mr-2" type="text" name="q" value="{{ $q }}" placeholder="Search subject, email, name…"/>
            <button class="btn btn-sm accent-button" type="submit">Search</button>
        </form>
    </div>
</div>

<div class="block">
    <div class="table-responsive">
        <table class="table table-striped">
            <thead>
            <tr>
                <th>From</th>
                <th>Subject</th>
                <th>Date</th>
                <th>Status</th>
                <th></th>
            </tr>
            </thead>
            <tbody>
            @forelse($messages as $message)
                <tr class="{{ $message->read_by_admin ? '' : 'table-info' }}">
                    <td>{{ $message->senderLabel() }}</td>
                    <td>{{ $message->subject }}</td>
                    <td>{{ $message->created_at?->format('Y-m-d H:i') }}</td>
                    <td>
                        @if($message->admin_reply)
                            <span class="badge badge-success">Replied</span>
                        @elseif($message->read_by_admin)
                            <span class="badge badge-secondary">Read</span>
                        @else
                            <span class="badge badge-warning">New</span>
                        @endif
                    </td>
                    <td>
                        <a class="btn btn-sm accent-button" href="{{ route('admin.messages.show', $message) }}">Open</a>
                    </td>
                </tr>
            @empty
                <tr><td colspan="5" class="text-muted">No messages yet.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
    {{ $messages->links() }}
</div>
@endsection
