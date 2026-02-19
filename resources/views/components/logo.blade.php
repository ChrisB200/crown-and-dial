@props(['class' => ''])

<a href="{{ route('home') }}" {{ $attributes->merge(['class' => $class]) }}>
  <img class="logo light-logo" src="{{ asset('logo-light.png') }}" alt="Logo Light" />
  <img class="logo dark-logo" src="{{ asset('logo-dark.png') }}" alt="Logo Dark" />
</a>
