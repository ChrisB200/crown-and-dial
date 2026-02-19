@props(['name', 'class' => ''])

<span {{ $attributes->merge(['class' => $class]) }}>
    {!! file_get_contents(resource_path("icons/$name.svg")) !!}
</span>
