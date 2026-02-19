@extends('layouts.user')


@push('head')
  @vite('resources/css/settings.css')
@endpush

@section('sidebar')
  <aside class="sidebar">
    <ul class="sidebar-list">
      <h3 class="sidebar-title">Account Settings</h3>
      <li class="sidebar-anchors">
        <a class="sidebar-anchor" href="{{ route('account.profile.edit') }}">Profile</a>
        <a class="sidebar-anchor" href="{{ route('account.profile.security') }}">Security</a>
        <a class="sidebar-anchor" href="{{ route('account.profile.delete') }}">Delete</a>
      </li>
    </ul>
    <ul class="sidebar-list">
      <h3 class="sidebar-title">Orders</h3>
      <li class="sidebar-anchors">
        <a class="sidebar-anchor" href="{{ route('account.orders.index') }}">Past Orders</a>
      </li>
    </ul>
    <ul class="sidebar-list">
      <h3 class="sidebar-title">Messages</h3>
      <li class="sidebar-anchors">
        <a class="sidebar-anchor" href="{{ route('account.messages.index') }}">Past Messages</a>
      </li>
    </ul>
  </aside>
@stop
