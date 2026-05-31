@props([
    'variant' => 'info',
    'title' => null,
])

@php
    $variants = [
        'success' => 'bg-success-soft text-success border-success/30',
        'danger'  => 'bg-danger-soft text-danger border-danger/30',
        'warning' => 'bg-warning-soft text-warning border-warning/30',
        'info'    => 'bg-info-soft text-info border-info/30',
    ];

    $classes = 'rounded-xl border px-4 py-3 text-sm '.($variants[$variant] ?? $variants['info']);
@endphp

<div role="alert" {{ $attributes->merge(['class' => $classes]) }}>
    @if ($title)
        <p class="font-semibold mb-0.5">{{ $title }}</p>
    @endif
    {{ $slot }}
</div>
