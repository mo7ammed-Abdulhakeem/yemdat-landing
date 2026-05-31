@props([
    'variant' => 'primary',
    'size' => 'md',
    'href' => null,
    'type' => 'button',
])

@php
    $base = 'inline-flex items-center justify-center gap-2 font-semibold rounded-btn transition-colors duration-150 focus:outline-none focus-visible:ring-2 focus-visible:ring-accent focus-visible:ring-offset-2 disabled:opacity-60 disabled:pointer-events-none';

    $variants = [
        'primary' => 'bg-primary text-white hover:bg-primary-hover',
        'accent'  => 'bg-accent text-ink hover:bg-accent-hover',
        'outline' => 'border border-primary text-primary hover:bg-primary hover:text-white',
        'ghost'   => 'text-primary hover:bg-yemdat-sand',
        'danger'  => 'bg-danger text-white hover:opacity-90',
    ];

    $sizes = [
        'sm' => 'text-sm px-3 py-1.5',
        'md' => 'text-sm px-5 py-2.5',
        'lg' => 'text-base px-6 py-3',
    ];

    $classes = $base.' '.($variants[$variant] ?? $variants['primary']).' '.($sizes[$size] ?? $sizes['md']);
@endphp

@if ($href)
    <a href="{{ $href }}" {{ $attributes->merge(['class' => $classes]) }}>{{ $slot }}</a>
@else
    <button type="{{ $type }}" {{ $attributes->merge(['class' => $classes]) }}>{{ $slot }}</button>
@endif
