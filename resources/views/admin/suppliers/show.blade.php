@extends('layouts.admin')

@section('title', 'Supplier')
@section('page_title', 'Supplier')

@section('content')
<div class="d-flex justify-content-between mb-3">
    <h4 class="mb-0">Supplier Details</h4>
    <a class="btn btn-secondary" href="{{ route('admin.suppliers.index') }}">Back to Suppliers</a>
</div>

<div class="block">
    <dl class="row mb-0">
        <dt class="col-sm-3">Name</dt>
        <dd class="col-sm-9">{{ $supplier->name }}</dd>

        <dt class="col-sm-3">Contact</dt>
        <dd class="col-sm-9">{{ $supplier->contact }}</dd>
    </dl>

    <div class="mt-3">
        <a class="btn btn-info" href="{{ route('admin.suppliers.edit', $supplier) }}">Edit</a>
    </div>
</div>
@endsection
