@props([
    'name',
    'type' => 'text',
    'label' => null,
    'value' => null,
    'required' => false,
    'id' => null,
])

@php
    $id = $id ?? $name;
    $hasError = $errors->has($name);
    $field = 'block w-full rounded-btn bg-surface-raised px-3 py-2 text-ink shadow-sm focus:outline-none focus:ring-2 '
        .($hasError
            ? 'border border-danger focus:border-danger focus:ring-danger/40'
            : 'border border-border focus:border-primary focus:ring-accent/50');
@endphp

<div class="space-y-1">
    @if ($label)
        <x-ui.label :for="$id" :required="$required">{{ $label }}</x-ui.label>
    @endif

    <input
        type="{{ $type }}"
        name="{{ $name }}"
        id="{{ $id }}"
        value="{{ old($name, $value) }}"
        @required($required)
        {{ $attributes->merge(['class' => $field]) }}
    />

    @error($name)
        <p class="text-xs text-danger">{{ $message }}</p>
    @enderror
</div>
