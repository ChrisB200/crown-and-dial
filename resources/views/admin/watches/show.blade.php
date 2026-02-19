@extends('layouts.admin')

@section('content')
  <p>
    {{ $watch->name }}
  </p>
  <p>
    {{ $watch->image_path }}
  </p>
  <img src="{{ asset('storage/' . $watch->image_path) }}" />
@stop
