@props([
    'path',
])

@php
    $ar = app()->getLocale() === 'ar';
    $levelLabels = [
        'beginner' => $ar ? 'مبتدئ' : 'Beginner',
        'intermediate' => $ar ? 'متوسط' : 'Intermediate',
        'advanced' => $ar ? 'متقدم' : 'Advanced',
    ];
@endphp

<div class="bg-surface-raised rounded-card shadow-card border border-border overflow-hidden flex flex-col h-full group transition-all duration-300 hover:-translate-y-1 hover:shadow-pop">
    {{-- Cover --}}
    <a href="{{ route('paths.show', $path->slug) }}" class="block relative h-44 w-full shrink-0 overflow-hidden bg-surface-sunken">
        @if ($path->image)
            <img src="{{ asset('storage/'.$path->image) }}" alt="{{ $path->title }}" loading="lazy" decoding="async" class="w-full h-full object-cover group-hover:scale-105 transition duration-500">
        @else
            <div class="w-full h-full flex items-center justify-center bg-gradient-to-br from-primary to-yemdat-dark text-accent">
                <svg class="h-14 w-14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 6.75V15m6-6v8.25m.503 3.498 4.875-2.437c.381-.19.622-.58.622-1.006V4.82c0-.836-.88-1.38-1.628-1.006l-3.869 1.934c-.317.159-.69.159-1.006 0L9.503 3.252a1.125 1.125 0 0 0-1.006 0L3.622 5.689C3.24 5.88 3 6.27 3 6.695V19.18c0 .836.88 1.38 1.628 1.006l3.869-1.934c.317-.159.69-.159 1.006 0l4.994 2.497c.317.158.69.158 1.006 0Z" /></svg>
            </div>
        @endif

        @if ($path->level)
            <div class="absolute top-3 {{ $ar ? 'left-3' : 'right-3' }}">
                <x-ui.badge variant="accent">{{ $levelLabels[$path->level] ?? $path->level }}</x-ui.badge>
            </div>
        @endif
    </a>

    {{-- Body --}}
    <div class="p-6 flex-grow flex flex-col">
        <h3 class="text-lg font-bold text-ink mb-2 line-clamp-2">
            <a href="{{ route('paths.show', $path->slug) }}" class="hover:text-primary transition">{{ $path->title }}</a>
        </h3>

        @if ($path->summary)
            <p class="text-sm text-ink-soft leading-relaxed line-clamp-3 mb-4">{{ $path->summary }}</p>
        @endif

        <div class="mt-auto pt-4 border-t border-border flex items-center justify-between text-xs text-ink-soft">
            <span class="inline-flex items-center gap-1 font-semibold">
                <svg class="w-4 h-4 text-accent-hover" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h7"/></svg>
                {{ $path->steps_count ?? $path->steps()->count() }} {{ $ar ? 'خطوة' : 'steps' }}
            </span>
            @if ($path->estimated_weeks)
                <span class="inline-flex items-center gap-1">
                    <svg class="w-4 h-4 text-accent-hover" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    {{ $path->estimated_weeks }} {{ $ar ? 'أسبوع' : 'weeks' }}
                </span>
            @endif
        </div>
    </div>
</div>
