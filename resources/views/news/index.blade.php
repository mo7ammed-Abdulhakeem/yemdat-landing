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

            <!-- Filter & Search Bar -->
            <div class="mb-12">
                <form action="{{ route('news') }}" method="GET" class="flex flex-col lg:flex-row justify-between items-center gap-6">
                    <!-- Left: Category Pills -->
                    <div class="flex flex-wrap items-center gap-3 justify-center lg:justify-start">
                        @foreach($presetCategories as $category)
                            <button type="submit" name="category" value="{{ $category }}" 
                                class="px-5 py-2.5 rounded-full text-sm font-semibold transition-all duration-300 border 
                                {{ $activeCategory === $category ? 'bg-yemdat-brown text-white border-yemdat-brown shadow-md transform scale-105' : 'bg-white text-gray-600 border-gray-200 hover:border-yemdat-gold hover:text-yemdat-brown' }}">
                                {{ app()->getLocale() == 'ar' ? __("global.{$category}") : $category }}
                            </button>
                        @endforeach
                        <!-- Preserve search if clicking a category -->
                        @if($searchQuery)
                            <input type="hidden" name="search" value="{{ $searchQuery }}">
                        @endif
                    </div>

                    <!-- Right: Search Input -->
                    <div class="relative w-full lg:w-80">
                         <div class="absolute inset-y-0 start-0 flex items-center ps-4 pointer-events-none">
                            <svg class="w-5 h-5 text-gray-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z"/>
                            </svg>
                        </div>
                        <input type="text" name="search" value="{{ $searchQuery }}" 
                            class="block w-full p-4 ps-12 text-sm text-gray-900 border border-gray-200 rounded-full bg-white focus:ring-yemdat-gold focus:border-yemdat-gold shadow-sm transition-all" 
                            placeholder="{{ app()->getLocale() == 'ar' ? 'بحث في الأخبار...' : 'Search news...' }}"
                        >
                        <!-- Preserve category if typing a search -->
                        @if($activeCategory !== 'All')
                            <input type="hidden" name="category" value="{{ $activeCategory }}">
                        @endif
                    </div>
                </form>
            </div>

            <!-- Hero Post (Only shows on initial load) -->
            @if($showHero && $heroPost)
                <div class="mb-16">
                    <a href="{{ route('news.show', $heroPost->slug) }}" class="group block relative bg-white rounded-[2rem] shadow-sm border border-gray-200 overflow-hidden hover:shadow-xl transition-all duration-500">
                        <div class="grid grid-cols-1 lg:grid-cols-2 min-h-[500px]">
                            <!-- Left: Image -->
                            <div class="relative h-64 lg:h-full lg:min-h-[500px] overflow-hidden">
                                @if($heroPost->image)
                                    <img src="{{ asset('storage/' . $heroPost->image) }}" alt="{{ $heroPost->title }}" class="absolute inset-0 w-full h-full object-cover group-hover:scale-105 transition duration-700 ease-in-out">
                                @else
                                    <div class="absolute inset-0 bg-yemdat-beige/30 flex items-center justify-center">
                                        <svg class="w-24 h-24 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                                    </div>
                                @endif
                                
                                <!-- Floating Type Badge -->
                                <div class="absolute top-6 left-6 rtl:left-auto rtl:right-6">
                                     <span class="px-4 py-1.5 rounded-md text-xs font-bold uppercase tracking-wider bg-yemdat-gold text-white shadow-md">
                                        {{ app()->getLocale() == 'ar' ? __("global.{$heroPost->type}") : ucfirst($heroPost->type) }}
                                     </span>
                                </div>
                            </div>
                            
                            <!-- Right: Content -->
                            <div class="p-10 lg:p-14 flex flex-col justify-center">
                                <div class="flex items-center text-gray-500 mb-6 font-medium">
                                    <svg class="w-5 h-5 mr-2 rtl:ml-2 text-yemdat-gold" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                    {{ $heroPost->created_at->format('M d, Y') }}
                                    <span class="mx-3">•</span>
                                    <span>{{ ceil(str_word_count(strip_tags($heroPost->content)) / 200) }} min read</span>
                                </div>
                                
                                <h2 class="text-3xl lg:text-5xl font-extrabold text-yemdat-brown mb-6 leading-tight group-hover:text-yemdat-gold transition-colors">
                                    {{ $heroPost->title }}
                                </h2>
                                
                                <p class="text-gray-600 text-lg mb-8 leading-relaxed line-clamp-4">
                                     {{ Str::limit(strip_tags($heroPost->content), 250) }}
                                </p>
                                
                                <div class="mt-auto flex items-center font-bold text-yemdat-brown group-hover:text-yemdat-gold transition-colors">
                                    {{ app()->getLocale() == 'ar' ? 'اقرأ المقال' : 'Read Article' }}
                                    <svg class="w-5 h-5 ml-2 rtl:mr-2 rtl:rotate-180 transform group-hover:translate-x-2 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
            @endif

            <!-- News Grid -->
            @if($posts->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-10">
                    @foreach($posts as $post)
                        <article class="bg-white rounded-[1.5rem] shadow-sm border border-gray-200 overflow-hidden flex flex-col h-full hover:shadow-2xl transition-all duration-500 group">
                            <!-- Image container -->
                            <a href="{{ route('news.show', $post->slug) }}" class="relative block h-64 bg-yemdat-brown overflow-hidden shrink-0">
                                @if($post->image)
                                    <img src="{{ asset('storage/' . $post->image) }}" alt="{{ $post->title }}" class="w-full h-full object-cover group-hover:scale-105 group-hover:opacity-90 transition duration-700 ease-in-out">
                                @else
                                    <div class="w-full h-full flex items-center justify-center bg-yemdat-brown">
                                        <!-- Fallback Graphic from Mockup (Database Icon in Brown box) -->
                                         <svg class="h-20 w-20 text-yemdat-gold/50" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4m0 5c0 2.21-3.582 4-8 4s-8-1.79-8-4" />
                                        </svg>
                                    </div>
                                @endif

                                <!-- Floating Corner Tag -->
                                <div class="absolute top-4 right-4 rtl:left-4 rtl:right-auto">
                                    @php
                                        // Priority: System Type, then first JSON tag
                                        $displayTag = ucfirst($post->type);
                                        $tags = is_string($post->tags) ? json_decode($post->tags, true) : $post->tags;
                                        if (is_array($tags) && count($tags) > 0) {
                                            $displayTag = ucfirst($tags[0]);
                                        }
                                    @endphp
                                    <span class="px-4 py-1.5 bg-white text-gray-800 rounded-md text-xs font-bold shadow-md">
                                        {{ app()->getLocale() == 'ar' ? __("global.{$displayTag}") : $displayTag }}
                                    </span>
                                </div>
                            </a>

                            <!-- Content -->
                            <div class="p-8 flex-grow flex flex-col">
                                <h3 class="text-2xl font-bold text-gray-900 mb-4 flex-grow line-clamp-2 leading-tight group-hover:text-yemdat-brown transition-colors">
                                    <a href="{{ route('news.show', $post->slug) }}">
                                        {{ $post->title }}
                                    </a>
                                </h3>

                                <p class="text-gray-500 text-base mb-8 line-clamp-3 leading-relaxed">
                                     {{ Str::limit(strip_tags($post->content), 150) }}
                                </p>

                                <div class="mt-auto flex items-center justify-between text-sm">
                                    <div class="flex items-center text-gray-400 font-medium">
                                        <svg class="w-4 h-4 mr-2 rtl:ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                        {{ $post->created_at->format('M d, Y') }}
                                    </div>
                                    
                                    <a href="{{ route('news.show', $post->slug) }}" class="text-gray-900 hover:text-yemdat-brown font-bold flex items-center transition group-hover:underline decoration-2 underline-offset-4">
                                        {{ app()->getLocale() == 'ar' ? 'اقرأ المزيد' : 'Read More' }}
                                        <svg class="w-4 h-4 ml-1.5 rtl:mr-1.5 rtl:rotate-180 transform group-hover:-translate-y-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                                    </a>
                               </div>
                            </div>
                        </article>
                    @endforeach
                </div>
                
                <!-- Simple Load More Button (Mockup style pagination) -->
                @if($posts->hasPages())
                    <div class="mt-16 flex justify-center">
                        <div class="bg-white px-8 py-3 rounded-md border border-gray-200 shadow-sm font-semibold text-gray-700 hover:bg-gray-50 transition cursor-pointer">
                            {{ $posts->appends(request()->query())->links() }}
                        </div>
                    </div>
                @endif
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
