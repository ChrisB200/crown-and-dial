@extends('layouts.master')

@section('html-head')
  @stack('head')
@stop

@section('content')
  <header>
    <nav>
      <x-logo class="left" />
      <div class="middle">
        <form class="search-form" action="{{ route('watches.index') }}" method="GET" class="search-form">
          <input class="search" type="search" name="q" placeholder="What are you looking for?" />
          <button type="submit" class="search-button">
            <x-icon name="search" class="icon" />
          </button>
        </form>
      </div>
      <div class="right">
        <a href="{{ route('basket.index') }}">
          <x-icon name="shopping-cart" class="icon" />
        </a>
        <a href="{{ route('account.profile.edit') }}">
          <x-icon name="user" class="icon" />
        </a>
        <div class="accessibility">
          <button class="menu-burger-button" id="accessibility-desktop">
            <x-icon name="menu" class="burger icon" />
          </button>
          <div class="accessibility-options hidden" id="accessibility-options">
            <x-theme-toggle />
            <button class="font-size-btn" data-size="14">A-</button>
            <button class="font-size-btn" data-size="16">R</button>
            <button class="font-size-btn" data-size="18">A+</button>
          </div>
        </div>
      </div>
      <x-icon name="menu" class="menu icon" id="menu-icon" />
      <div id="mobile-menu" class="mobile-menu">
        <div class="mobile-top">
          <x-logo class="mobile-logo-container" />
          <x-icon name="menu" class="menu icon mobile" id="close-menu-icon" />
        </div>

        <form class="search-form" action="{{ route('watches.index') }}" method="GET" class="search-form">
          <input class="search" type="search" name="q" placeholder="What are you looking for?" />
        </form>
        <ul class="mobile-anchors">
          <li>
            <a href="{{ route('home') }}">HOME</a>
          </li>
          <li>
            <a href="{{ route('watches.index') }}">WATCHES</a>
          </li>
          <li>
            <a href="{{ route('about') }}">ABOUT</a>
          </li>
          <li>
            <a href="{{ route('contact.create') }}">CONTACT US</a>
          </li>
          <li>
            <a href="{{ route('basket.index') }}">BASKET</a>
          </li>
          <li>
            <a href="{{ route('account.profile.edit') }}">PROFILE</a>
          </li>
        </ul>
        <div class="mobile-accessibility">
          <x-theme-toggle />
          <div class="font-sizes">
            <button class="font-size-btn" data-size="14">A-</button>
            <button class="font-size-btn" data-size="16">R</button>
            <button class="font-size-btn" data-size="18">A+</button>
          </div>
        </div>
      </div>
    </nav>
    <ul class="underlinks">
      <li>
        <a href="{{ route('watches.index') }}">WATCHES</a>
      </li>
      <li>
        <a href="{{ route('about') }}">ABOUT</a>
      </li>
      <li>
        <a href="{{ route('contact.create') }}">CONTACT US</a>
      </li>
    </ul>
  </header>
  <div class="page">
    @yield('sidebar')
    <main>
      @yield('page')
    </main>
  </div>
  <footer>
    <div>
      <x-logo class="left" />
      <p>Copyright 2025 © for Crown and Dial. All Rights Reserved</p>
      <ul>
        <li><a href="{{ route('home') }}">Home</a></li>
        <li><a href="{{ route('about') }}">About Us</a></li>
        <li><a href="{{ route('contact.index') }}">Contact Us</a></li>
      </ul>
    </div>
  </footer>
  <script>
    const mobileMenu = document.getElementById("mobile-menu");
    const menuIcon = document.getElementById("menu-icon");
    const closeMenuIcon = document.getElementById("close-menu-icon");
    const accessibility = document.getElementById("accessibility-desktop");
    const accessibilityOptions = document.getElementById("accessibility-options");

    menuIcon.addEventListener("click", () => {
      mobileMenu.classList.add("active");
      document.querySelector("nav").classList.add("menu-open");
    })

    accessibility.addEventListener("click", () => {
      accessibilityOptions.classList.toggle("hidden");
    });

    closeMenuIcon.addEventListener("click", () => {
      mobileMenu.classList.remove("active");
      document.querySelector("nav").classList.remove("menu-open");
    })
  </script>
@stop
