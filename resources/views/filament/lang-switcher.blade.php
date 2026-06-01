@php
    $target = app()->getLocale() === 'ar' ? 'en' : 'ar';
    $label = app()->getLocale() === 'ar' ? 'English' : 'العربية';
@endphp

<x-filament::button
    tag="a"
    :href="url('/lang/' . $target)"
    icon="heroicon-m-language"
    color="gray"
    size="sm"
    class="me-2"
    title="{{ $label }}"
>
    {{ $label }}
</x-filament::button>
