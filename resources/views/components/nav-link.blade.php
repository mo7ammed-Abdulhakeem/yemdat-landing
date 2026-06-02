@props(['active' => false])

@php
    $base = 'inline-flex items-center whitespace-nowrap px-1 pt-1 border-b-2 text-sm leading-5 focus:outline-none transition duration-150 ease-in-out';
    $state = $active
        ? 'border-accent text-primary font-semibold'
        : 'border-transparent text-ink-soft font-medium hover:text-primary hover:border-border';
@endphp

<a {{ $attributes->merge(['class' => $base.' '.$state]) }}>
    {{ $slot }}
</a>
