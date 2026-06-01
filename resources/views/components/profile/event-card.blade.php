@props([
    'event',
    'certificate' => null,
])

@php
    $ar = app()->getLocale() === 'ar';
    $isPast = ($event->end_date ?? $event->start_date) < now();

    // Status badge: ongoing (green), ended (red), or upcoming (neutral).
    if ($event->status === 'Ongoing') {
        $statusColor = 'bg-green-100/90 text-green-700';
        $statusText = $ar ? 'يحدث الآن' : 'Happening Now';
    } elseif ($isPast) {
        $statusColor = 'bg-red-100/90 text-red-700';
        $statusText = $ar ? 'انتهى' : 'Ended';
    } else {
        $statusColor = 'bg-yemdat-gold text-yemdat-brown';
        $statusText = $event->remaining_time;
    }
@endphp

<div class="bg-white rounded-xl border border-gray-200 overflow-hidden hover:border-yemdat-gold transition shadow-sm hover:shadow-md flex flex-col {{ $isPast ? 'opacity-95' : '' }}">
    {{-- Image header --}}
    <div class="w-full h-40 bg-gray-100 relative">
        @if ($event->image)
            <img src="{{ asset('storage/'.$event->image) }}" alt="{{ $event->title }}" class="w-full h-full object-cover">
        @else
            <div class="w-full h-full flex items-center justify-center">
                <svg class="w-10 h-10 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
            </div>
        @endif

        <div class="absolute top-3 right-3 rtl:right-auto rtl:left-3">
            <span class="inline-flex items-center px-2 py-1 rounded-md text-xs font-bold shadow-sm backdrop-blur-sm {{ $statusColor }}">
                {{ $statusText }}
            </span>
        </div>
    </div>

    {{-- Content --}}
    <div class="p-5 flex flex-col flex-grow">
        <h4 class="font-bold text-gray-900 text-lg mb-2 line-clamp-2 leading-snug">
            <a href="{{ route('events.show', $event->slug) }}" class="hover:text-yemdat-gold transition">{{ $event->title }}</a>
        </h4>

        <div class="space-y-2 mt-auto">
            {{-- Date --}}
            <div class="flex items-center text-sm text-gray-500 gap-2">
                <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                <span dir="ltr">{{ $event->start_date->format('M d, Y') }}</span>
            </div>

            {{-- Location --}}
            <div class="flex items-center text-sm text-gray-500 gap-2">
                <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                <span class="truncate">{{ $event->location ?? ($ar ? 'عبر الإنترنت' : 'Online') }}</span>
            </div>
        </div>

        @if ($event->join_url && ! $isPast)
            <div class="mt-5 pt-4 border-t border-gray-100 flex justify-between items-center">
                <a href="{{ $event->join_url }}" target="_blank" class="text-sm font-bold text-yemdat-brown hover:text-yemdat-gold flex items-center gap-1">
                    {{ $ar ? 'رابط الحضور' : 'Event Join Link' }}
                    <svg class="w-4 h-4 rtl:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                </a>
            </div>
        @endif

        @if ($certificate)
            <div class="mt-4 pt-4 border-t border-gray-100">
                <a href="{{ route('certificates.download', $certificate) }}" target="_blank"
                   class="inline-flex w-full items-center justify-center gap-2 px-4 py-2 bg-yemdat-brown text-white rounded-lg text-sm font-bold hover:bg-yemdat-gold transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                    {{ $ar ? 'تحميل الشهادة' : 'Download Certificate' }}
                </a>
            </div>
        @endif
    </div>
</div>
