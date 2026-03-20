@extends('layouts.user')


@push('head')
  @vite('resources/css/settings.css')
@endpush

@section('sidebar')
  <button class="account-sidebar-toggle" id="account-sidebar-toggle" type="button" aria-controls="account-sidebar" aria-expanded="false">
    Account Menu
  </button>
  <aside class="sidebar" id="account-sidebar">
    <button class="account-sidebar-close" id="account-sidebar-close" type="button" aria-label="Close account menu">Close</button>
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
        <a class="sidebar-anchor" href="{{ route('account.wishlist.index') }}">Wishlist</a>
      </li>
    </ul>
    <ul class="sidebar-list">
      <h3 class="sidebar-title">Messages</h3>
      <li class="sidebar-anchors">
        <a class="sidebar-anchor" href="{{ route('account.messages.index') }}">Past Messages</a>
      </li>
    </ul>
  </aside>
  <div class="account-sidebar-backdrop" id="account-sidebar-backdrop"></div>
  <script>
    (() => {
      const toggleButton = document.getElementById('account-sidebar-toggle');
      const closeButton = document.getElementById('account-sidebar-close');
      const sidebar = document.getElementById('account-sidebar');
      const backdrop = document.getElementById('account-sidebar-backdrop');

      if (!toggleButton || !closeButton || !sidebar || !backdrop) return;

      const closeMenu = () => {
        sidebar.classList.remove('is-open');
        backdrop.classList.remove('is-open');
        toggleButton.setAttribute('aria-expanded', 'false');
      };

      const openMenu = () => {
        sidebar.classList.add('is-open');
        backdrop.classList.add('is-open');
        toggleButton.setAttribute('aria-expanded', 'true');
      };

      toggleButton.addEventListener('click', () => {
        if (sidebar.classList.contains('is-open')) {
          closeMenu();
        } else {
          openMenu();
        }
      });

      closeButton.addEventListener('click', closeMenu);
      backdrop.addEventListener('click', closeMenu);

      sidebar.querySelectorAll('.sidebar-anchor').forEach((anchor) => {
        anchor.addEventListener('click', closeMenu);
      });
    })();
  </script>
@stop
