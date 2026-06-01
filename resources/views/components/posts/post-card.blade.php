@props([
    'post',
])

@php
    $ar = app()->getLocale() === 'ar';

    // Corner tag: prefer the first custom tag, else the post type — localized via global.* keys.
    $displayTag = ucfirst($post->type);
    $tags = is_string($post->tags) ? json_decode($post->tags, true) : $post->tags;
    if (is_array($tags) && count($tags) > 0) {
        $displayTag = ucfirst($tags[0]);
    }
    $tagLabel = $ar
        ? (\Illuminate\Support\Facades\Lang::has('global.'.strtolower($displayTag))
            ? __('global.'.strtolower($displayTag))
            : (\Illuminate\Support\Facades\Lang::has('global.'.$displayTag) ? __('global.'.$displayTag) : $displayTag))
        : $displayTag;
@endphp

<article class="bg-white rounded-[1.5rem] shadow-sm border border-gray-200 overflow-hidden flex flex-col h-full hover:shadow-2xl transition-all duration-500 group">
    <a href="{{ route('news.show', $post->slug) }}" class="relative block h-64 bg-yemdat-brown overflow-hidden shrink-0">
        @if ($post->image)
            <img src="{{ asset('storage/'.$post->image) }}" alt="{{ $post->title }}" loading="lazy" decoding="async" class="w-full h-full object-cover group-hover:scale-105 group-hover:opacity-90 transition duration-700 ease-in-out">
        @else
            <div class="w-full h-full flex items-center justify-center bg-yemdat-brown">
                <svg class="h-20 w-20 text-yemdat-gold/50" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4m0 5c0 2.21-3.582 4-8 4s-8-1.79-8-4" /></svg>
            </div>
        @endif

        <div class="absolute top-4 right-4 rtl:left-4 rtl:right-auto">
            <span class="px-4 py-1.5 bg-white text-gray-800 rounded-md text-xs font-bold shadow-md">{{ $tagLabel }}</span>
        </div>
    </a>

    <div class="p-8 flex-grow flex flex-col">
        <h3 class="text-2xl font-bold text-gray-900 mb-4 flex-grow line-clamp-2 leading-tight group-hover:text-yemdat-brown transition-colors">
            <a href="{{ route('news.show', $post->slug) }}">{{ $post->title }}</a>
        </h3>

        <p class="text-gray-500 text-base mb-8 line-clamp-3 leading-relaxed">{{ Str::limit(strip_tags($post->content), 150) }}</p>

        <div class="mt-auto flex items-center justify-between text-sm">
            <div class="flex items-center text-gray-400 font-medium">
                <svg class="w-4 h-4 mr-2 rtl:mr-0 rtl:ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                {{ $post->created_at->format('M d, Y') }}
            </div>

            <a href="{{ route('news.show', $post->slug) }}" class="text-gray-900 hover:text-yemdat-brown font-bold flex items-center transition group-hover:underline decoration-2 underline-offset-4">
                {{ $ar ? 'اقرأ المزيد' : 'Read More' }}
                <svg class="w-4 h-4 ml-1.5 rtl:ml-0 rtl:mr-1.5 rtl:rotate-180 transform group-hover:-translate-y-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
            </a>
        </div>
    </div>
</article>
