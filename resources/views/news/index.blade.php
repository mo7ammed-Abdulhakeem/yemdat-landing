<x-app-layout>
    <div class="py-20 bg-gray-50 min-h-screen" dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <!-- Header -->
            <div class="text-center max-w-3xl mx-auto mb-16">
                 <div class="w-16 h-16 bg-white shadow-sm border border-gray-100 rounded-2xl flex items-center justify-center text-yemdat-gold mx-auto mb-6 transform -rotate-6">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"></path></svg>
                </div>
                <h1 class="text-4xl md:text-5xl font-extrabold text-yemdat-brown mb-6 tracking-tight">
                    {{ __('news.title') }}
                </h1>
                <p class="text-xl text-gray-500">
                    {{ __('news.subtitle') }}
                </p>
            </div>

            <!-- News Grid -->
            @if($posts->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                    @foreach($posts as $post)
                        <article class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden flex flex-col h-full hover:shadow-lg transition-all duration-300 group hover:-translate-y-1">
                            <!-- Image container with fixed aspect ratio -->
                            <a href="{{ route('news.show', $post->slug) }}" class="relative block h-56 bg-gray-50 overflow-hidden shrink-0 border-b border-gray-100" style="height: 14rem; border-bottom: 1px solid #e5e7eb;">
                                <div class="absolute inset-0 z-10 pointer-events-none" style="box-shadow: inset 0 0 0 1px rgba(0,0,0,0.05);"></div>
                                @if($post->image)
                                    <img src="{{ asset('storage/' . $post->image) }}" alt="{{ $post->title }}" class="w-full h-full object-cover group-hover:scale-105 transition duration-700 ease-in-out">
                                @else
                                    <div class="w-full h-full flex items-center justify-center text-gray-300 bg-yemdat-beige/30">
                                         <svg class="h-16 w-16" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                    </div>
                                @endif

                                <!-- Type Badge -->
                                <div class="absolute top-4 right-4 rtl:right-auto rtl:left-4">
                                    @php
                                        $badgeClass = 'bg-yemdat-gold text-white';
                                        $badgeStyle = '';
                                        if($post->type === 'announcement') {
                                            $badgeClass = 'text-white';
                                            $badgeStyle = 'background-color: #2563eb; border: none;';
                                        } elseif($post->type === 'update') {
                                            $badgeClass = 'text-white';
                                            $badgeStyle = 'background-color: #16a34a; border: none;';
                                        }
                                    @endphp
                                    <span class="px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider shadow-md backdrop-blur-sm opacity-90 {{ $badgeClass }}" style="{{ $badgeStyle }}">
                                        {{ app()->getLocale() == 'ar' ? __("global.{$post->type}") : ucfirst($post->type) }}
                                    </span>
                                </div>
                            </a>

                            <!-- Content -->
                            <div class="p-6 flex-grow flex flex-col">
                                <!-- Meta (Date & Author) -->
                                <div class="flex items-center text-sm text-gray-500 mb-4 gap-4">
                                    <time class="flex items-center">
                                        <svg class="w-4 h-4 mr-1.5 rtl:ml-1.5 text-yemdat-gold" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                        {{ $post->created_at->format('M d, Y') }}
                                    </time>
                                </div>

                                <h3 class="text-xl font-bold text-gray-900 mb-3 flex-grow line-clamp-2 leading-tight group-hover:text-yemdat-brown transition-colors">
                                    <a href="{{ route('news.show', $post->slug) }}">
                                        {{ $post->title }}
                                    </a>
                                </h3>

                                <!-- Excerpt -->
                                <p class="text-gray-600 text-sm mb-6" style="min-height: 4.5rem; display: -webkit-box; -webkit-line-clamp: 3; -webkit-box-orient: vertical; overflow: hidden;">
                                     {{ Str::limit(strip_tags($post->content), 120) }}
                                </p>

                                <!-- Tags & Read More -->
                               <div class="mt-auto pt-4 border-t border-gray-100 flex items-center justify-between">
                                    <div class="flex gap-2 font-medium">
                                        <a href="{{ route('news.show', $post->slug) }}" class="text-yemdat-brown hover:text-yemdat-gold text-sm font-bold flex items-center gap-1 transition">
                                            {{ app()->getLocale() == 'ar' ? 'اقرأ المزيد' : 'Read More' }}
                                            <svg class="w-4 h-4 rtl:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                                        </a>
                                    </div>
                                    @php
                                        $tags = is_string($post->tags) ? json_decode($post->tags, true) : $post->tags;
                                    @endphp
                                    @if(is_array($tags) && count($tags) > 0)
                                        <div class="flex flex-wrap gap-1 justify-end max-w-[50%]">
                                            @foreach(array_slice($tags, 0, 2) as $tag)
                                                <span class="text-[10px] font-bold text-gray-500 bg-gray-100 px-2 py-1 rounded truncate max-w-full">#{{ trim($tag) }}</span>
                                            @endforeach
                                        </div>
                                    @endif
                               </div>
                            </div>
                        </article>
                    @endforeach
                </div>
                
                <!-- Pagination -->
                <div class="mt-12">
                     {{ $posts->links() }}
                </div>
            @else
                <div class="text-center bg-white rounded-2xl p-12 border border-gray-100 shadow-sm">
                    <div class="w-20 h-20 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-4 text-gray-300">
                         <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"></path></svg>
                    </div>
                    <h3 class="text-lg font-bold text-gray-900 mb-2">{{ app()->getLocale() == 'ar' ? 'لا توجد أخبار حالياً' : 'No news found' }}</h3>
                    <p class="text-gray-500">{{ app()->getLocale() == 'ar' ? 'يرجى التحقق لاحقاً للحصول على أحدث التحديثات.' : 'Check back later for the latest updates and announcements.' }}</p>
                </div>
            @endif

        </div>
    </div>
</x-app-layout>
