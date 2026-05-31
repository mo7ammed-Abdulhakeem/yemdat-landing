@props([
    'padding' => 'p-6',
])

<div {{ $attributes->merge(['class' => "bg-surface-raised rounded-card shadow-card border border-border {$padding}"]) }}>
    {{ $slot }}
</div>
