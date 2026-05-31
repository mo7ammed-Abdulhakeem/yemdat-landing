@props([
    'variant' => 'neutral',
])

@php
    $variants = [
        'neutral' => 'bg-yemdat-sand text-ink',
        'accent'  => 'bg-accent text-ink',
        'success' => 'bg-success-soft text-success',
        'danger'  => 'bg-danger-soft text-danger',
        'warning' => 'bg-warning-soft text-warning',
        'info'    => 'bg-info-soft text-info',
    ];

    $classes = 'inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-semibold '.($variants[$variant] ?? $variants['neutral']);
@endphp

<span {{ $attributes->merge(['class' => $classes]) }}>{{ $slot }}</span>
