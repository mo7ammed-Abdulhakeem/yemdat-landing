@props([
    'title',
    'subtitle' => null,
])

<div {{ $attributes->merge(['class' => 'mb-8']) }}>
    <h1 class="text-3xl font-bold text-primary">{{ $title }}</h1>
    @if ($subtitle)
        <p class="mt-2 text-ink-soft">{{ $subtitle }}</p>
    @endif
    {{ $slot }}
</div>
