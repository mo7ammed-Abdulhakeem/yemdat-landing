@props(['active' => false])

@php
    $base = 'block ps-3 pe-4 py-2 border-s-4 text-base focus:outline-none transition duration-150 ease-in-out';
    $state = $active
        ? 'border-accent text-primary font-semibold bg-surface-sunken'
        : 'border-transparent text-ink-soft font-medium hover:text-primary hover:bg-surface-sunken hover:border-border';
@endphp

<a {{ $attributes->merge(['class' => $base.' '.$state]) }}>
    {{ $slot }}
</a>
