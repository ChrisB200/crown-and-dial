@extends('layouts.admin')

@section('title', 'Customers')
@section('page_title', 'Customers')

@section('content')
<div class="row mb-3">
    <div class="col-md-8">
        <form class="form-inline" method="GET">
            <input class="form-control mr-2" type="text" name="q" value="{{ $q }}" placeholder="Search name or email">
            <button class="btn btn-secondary" type="submit">Search</button>
        </form>
    </div>
    <div class="col-md-4 text-right">
        <a class="btn btn-primary" href="{{ route('admin.customers.create') }}"><i class="fa fa-plus"></i> Add Customer</a>
    </div>
</div>

<div class="block">
    <div class="table-responsive">
        <table class="table table-striped">
            <thead><tr><th>Name</th><th>Email</th><th>Joined</th><th></th></tr></thead>
            <tbody>
            @foreach($customers as $c)
                <tr>
                    <td>{{ $c->name }}</td>
                    <td>{{ $c->email }}</td>
                    <td>{{ $c->created_at?->format('Y-m-d') }}</td>
                    <td class="text-right">
                        <a class="btn btn-sm btn-outline-primary" href="{{ route('admin.customers.edit', $c) }}">Edit</a>
                        <form method="POST" action="{{ route('admin.customers.destroy', $c) }}" style="display:inline-block" onsubmit="return confirm('Delete this customer?');">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-sm btn-outline-danger" type="submit">Delete</button>
                        </form>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
    <div>
        {{ $customers->links() }}
    </div>
</div>
@endsection
