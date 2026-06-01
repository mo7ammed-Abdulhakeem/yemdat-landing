@props([
    'event',
    'variant' => 'upcoming', // live | upcoming | past
])

@php
    $ar = app()->getLocale() === 'ar';
    $isLive = $variant === 'live';
    $isUpcoming = $variant === 'upcoming';
    $isPast = $variant === 'past';

    $wrapper = match ($variant) {
        'live' => 'bg-white border-2 border-red-100 hover:border-red-300 shadow-lg',
        'past' => 'bg-gray-50 border border-gray-200 grayscale hover:grayscale-0',
        default => 'bg-white border border-gray-200 shadow-sm hover:shadow-lg',
    };
    $titleColor = $isPast ? 'text-gray-700' : 'text-gray-900';
    $titleHover = match ($variant) {
        'live' => 'hover:text-red-600',
        'past' => 'hover:text-gray-900',
        default => 'hover:text-yemdat-gold',
    };
    $fallback = match ($variant) {
        'live' => 'bg-red-50 text-red-200',
        'past' => 'bg-gray-200 text-gray-400',
        default => 'bg-yemdat-beige text-yemdat-brown',
    };
    $startIcon = $isLive ? 'text-red-600' : ($isPast ? 'text-gray-400' : 'text-yemdat-gold');
    $cta = match ($variant) {
        'live' => ['class' => 'bg-red-600 text-white hover:bg-red-700 shadow-md', 'text' => $ar ? 'انضم للفعالية الآن' : 'Join Event Now'],
        'past' => ['class' => 'border border-gray-300 text-gray-500 hover:bg-gray-600 hover:text-white', 'text' => $ar ? 'عرض الملخص' : 'View Recap'],
        default => ['class' => 'bg-yemdat-brown text-white hover:bg-yemdat-gold hover:text-yemdat-brown shadow-md', 'text' => $ar ? 'عرض التفاصيل' : 'View Details'],
    };
@endphp

<div class="rounded-2xl overflow-hidden flex flex-col h-full group transition-all duration-300 hover:-translate-y-1 {{ $wrapper }}">
    {{-- Image / status corner --}}
    <div class="relative bg-gray-50 overflow-hidden shrink-0 border-b border-gray-100" style="height: 14rem;">
        <div class="absolute inset-0 z-10 pointer-events-none" style="box-shadow: inset 0 0 0 1px rgba(0,0,0,0.05);"></div>
        @if ($event->image)
            <img src="{{ asset('storage/'.$event->image) }}" alt="{{ $event->title }}" loading="lazy" decoding="async" class="w-full h-full object-cover group-hover:scale-105 transition duration-500">
        @else
            <div class="w-full h-full flex items-center justify-center {{ $fallback }}">
                <svg class="h-12 w-12" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z" /></svg>
            </div>
        @endif

        @if ($isLive)
            <div class="absolute top-4 right-4 rtl:right-auto rtl:left-4 bg-red-600 text-white px-3 py-1 rounded-full text-xs font-bold shadow-sm animate-pulse flex items-center gap-1">
                <span class="w-2 h-2 bg-white rounded-full"></span> LIVE
            </div>
        @elseif ($isUpcoming)
            <div class="absolute top-4 right-4 rtl:right-auto rtl:left-4 bg-yemdat-gold text-yemdat-brown px-3 py-1 rounded-full text-xs font-bold shadow-md">
                {{ $event->remaining_time }}
            </div>
        @else
            <div class="absolute top-4 right-4 rtl:right-auto rtl:left-4 bg-gray-600 text-white px-3 py-1 rounded-full text-xs font-bold shadow-sm">
                {{ $ar ? 'انتهى' : 'Ended' }}
            </div>
        @endif
    </div>

    {{-- Content --}}
    <div class="p-6 flex-grow flex flex-col">
        <h4 class="text-xl font-bold {{ $titleColor }} mb-4 flex-grow line-clamp-2">
            <a href="{{ route('events.show', $event->slug) }}" class="{{ $titleHover }} transition">{{ $event->title }}</a>
        </h4>

        <div class="space-y-2 mb-4 text-sm {{ $isPast ? 'text-gray-500' : '' }}">
            {{-- Start --}}
            <div class="flex items-center justify-between w-full">
                <div class="flex items-center {{ $isPast ? '' : 'text-gray-700' }}">
                    <svg class="w-4 h-4 mr-2 rtl:mr-0 rtl:ml-2 {{ $startIcon }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                    <span class="font-bold">{{ $ar ? 'البدء:' : 'Start:' }}</span>
                    <span class="mx-1">{{ $event->start_date->format('M d, Y | h:i A') }}</span>
                </div>
                @if ($isUpcoming)
                    <div class="text-xs font-bold text-yemdat-brown bg-yemdat-gold/20 px-2 py-0.5 rounded whitespace-nowrap">{{ $event->remaining_time }}</div>
                @endif
            </div>

            {{-- End --}}
            @if ($event->end_date)
                <div class="flex items-center text-gray-500">
                    <svg class="w-4 h-4 mr-2 rtl:mr-0 rtl:ml-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    <span class="font-bold">{{ $ar ? 'النهاية:' : 'End:' }}</span>
                    <span class="mx-1">{{ $event->end_date->format('M d, Y | h:i A') }}</span>
                </div>
            @endif

            {{-- Location (not shown on past events) --}}
            @if ($event->location && ! $isPast)
                <div class="flex items-center text-gray-500">
                    <svg class="w-4 h-4 mr-2 rtl:mr-0 rtl:ml-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                    <span class="truncate max-w-[200px]">{{ $event->location }}</span>
                </div>
            @endif
        </div>

        {{-- Lecturer (not shown on live events) --}}
        @unless ($isLive)
            <div class="flex items-center mt-2 mb-6 pt-4 border-t {{ $isPast ? 'border-gray-200' : 'border-gray-100' }}">
                @if ($event->lecturer_image)
                    <img src="{{ asset('storage/'.$event->lecturer_image) }}" alt="" class="h-8 w-8 rounded-full object-cover border border-gray-200 shrink-0">
                @else
                    <div class="h-8 w-8 rounded-full bg-gray-100 flex items-center justify-center text-gray-400 shrink-0">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" /></svg>
                    </div>
                @endif
                <span class="ml-2 rtl:ml-0 rtl:mr-2 text-sm font-bold {{ $isPast ? 'text-gray-600 group-hover:text-gray-800 transition' : 'text-gray-700' }}">{{ $event->lecturer_name }}</span>
            </div>
        @endunless

        <a href="{{ route('events.show', $event->slug) }}" class="mt-auto block w-full text-center py-2.5 rounded-lg transition font-bold text-sm {{ $cta['class'] }}">
            {{ $cta['text'] }}
        </a>
    </div>
</div>
