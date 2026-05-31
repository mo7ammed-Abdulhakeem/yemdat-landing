@props([
    'for' => null,
    'required' => false,
])

<label @if ($for) for="{{ $for }}" @endif {{ $attributes->merge(['class' => 'block text-sm font-medium text-ink mb-1']) }}>
    {{ $slot }}
    @if ($required)
        <span class="text-danger" aria-hidden="true">*</span>
    @endif
</label>
