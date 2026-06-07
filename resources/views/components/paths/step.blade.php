@props([
    'step',
    'number' => null,
])

@php
    $ar = app()->getLocale() === 'ar';
    $isInternal = $step->isInternal() && $step->event;

    $typeMeta = [
        'event'   => ['label' => $ar ? 'فعالية يمدات' : 'Yemdat Event', 'variant' => 'accent'],
        'video'   => ['label' => $ar ? 'فيديو' : 'Video',               'variant' => 'info'],
        'article' => ['label' => $ar ? 'مقال' : 'Article',             'variant' => 'neutral'],
        'course'  => ['label' => $ar ? 'دورة' : 'Course',              'variant' => 'success'],
        'doc'     => ['label' => $ar ? 'مصدر' : 'Resource',            'variant' => 'neutral'],
        'other'   => ['label' => $ar ? 'مصدر' : 'Resource',            'variant' => 'neutral'],
    ];
    $meta = $typeMeta[$step->resource_type] ?? $typeMeta['other'];

    // Internal steps link to the event page; external steps open their URL in a new tab.
    $href = $isInternal ? route('events.show', $step->event->slug) : ($step->url ?: null);
    $external = ! $isInternal && $href;
@endphp

<div class="relative flex gap-4 rtl:flex-row-reverse">
    {{-- Number bubble + connector --}}
    <div class="flex flex-col items-center shrink-0">
        <div class="w-10 h-10 rounded-full bg-primary text-white flex items-center justify-center font-bold shadow-sm">
            {{ $number ?? '•' }}
        </div>
        <div class="flex-1 w-px bg-border mt-1"></div>
    </div>

    {{-- Card --}}
    <div class="flex-1 pb-8">
        <div class="bg-surface-raised rounded-card shadow-card border border-border p-5 transition hover:shadow-pop">
            <div class="flex flex-wrap items-center gap-2 mb-2">
                <x-ui.badge :variant="$meta['variant']">{{ $meta['label'] }}</x-ui.badge>
                @if ($step->is_optional)
                    <x-ui.badge variant="neutral">{{ $ar ? 'اختياري' : 'Optional' }}</x-ui.badge>
                @endif
            </div>

            <h4 class="text-base font-bold text-ink mb-1">
                @if ($href)
                    <a href="{{ $href }}" @if ($external) target="_blank" rel="noopener noreferrer" @endif class="hover:text-primary transition inline-flex items-center gap-1">
                        {{ $step->title }}
                        @if ($external)
                            <svg class="w-3.5 h-3.5 text-ink-soft" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 6H5.25A2.25 2.25 0 0 0 3 8.25v10.5A2.25 2.25 0 0 0 5.25 21h10.5A2.25 2.25 0 0 0 18 18.75V10.5m-10.5 6L21 3m0 0h-5.25M21 3v5.25"/></svg>
                        @endif
                    </a>
                @else
                    {{ $step->title }}
                @endif
            </h4>

            @if ($isInternal && $step->event->start_date)
                <p class="text-xs text-ink-soft mb-2" dir="ltr">{{ $step->event->start_date->format('M d, Y | h:i A') }}</p>
            @endif

            @if ($step->notes)
                <p class="text-sm text-ink-soft leading-relaxed">{{ $step->notes }}</p>
            @endif
        </div>
    </div>
</div>
