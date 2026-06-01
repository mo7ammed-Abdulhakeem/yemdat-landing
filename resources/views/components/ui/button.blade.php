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
@elseif ($type === 'submit')
    {{-- Submit buttons get a built-in loading state: once the form actually submits we show a
         spinner and disable the button, preventing double-submits and giving instant feedback.
         We hook the form's `submit` event (not the click) so disabling never cancels the
         in-flight submission. --}}
    <button
        type="submit"
        x-data="{ loading: false }"
        x-init="$el.form && $el.form.addEventListener('submit', () => loading = true)"
        x-bind:disabled="loading"
        {{ $attributes->merge(['class' => $classes]) }}
    >
        <svg x-show="loading" x-cloak class="animate-spin h-4 w-4" viewBox="0 0 24 24" fill="none" aria-hidden="true">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
        </svg>
        {{ $slot }}
    </button>
@else
    <button type="{{ $type }}" {{ $attributes->merge(['class' => $classes]) }}>{{ $slot }}</button>
@endif
