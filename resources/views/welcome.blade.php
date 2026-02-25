<x-app-layout>
<!-- Hero Section CSS -->
    <style>
        .hero-container {
            background-color: #f7f4f0;
            border-bottom: 1px solid #e8e3dc;
            padding-top: 4rem;
            padding-bottom: 5rem;
        }
        .hero-label {
            color: #c99320;
            font-size: 0.85rem;
            font-weight: 700;
            letter-spacing: 0.1em;
            text-transform: uppercase;
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
        }
        .hero-title {
            color: #4b392a;
            font-size: 3rem;
            font-weight: 900;
            line-height: 1.1;
            letter-spacing: -0.02em;
            font-family: 'Arial Black', Impact, 'Segoe UI Black', Roboto, Helvetica, Arial, sans-serif;
            margin-bottom: 1rem;
        }
        .hero-desc {
            color: #4b5e71;
            font-size: 1.1rem;
            font-weight: 400;
            line-height: 1.6;
            max-width: 52rem;
            margin: 0 auto 2rem auto;
            padding: 0 1rem;
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
        }
        .hero-btn {
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 6px;
            font-weight: 700;
            font-size: 1.05rem;
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
            transition: all 0.2s ease-in-out;
            width: 100%;
            height: 54px;
        }
        .hero-btn-primary {
            background-color: #4b392a;
            color: #ffffff;
        }
        .hero-btn-primary:hover {
            background-color: #37291e;
        }
        .hero-btn-secondary {
            background-color: #ffffff;
            color: #4b5e71;
            border: 1.5px solid #d4a742;
            box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
        }
        .hero-btn-secondary:hover {
            background-color: #fafafa;
        }

        /* Small screens (sm) */
        @media (min-width: 640px) {
            .hero-btn {
                width: 260px;
                height: 58px;
                font-size: 1.15rem;
            }
        }

        /* Medium screens (md) */
        @media (min-width: 768px) {
            .hero-title {
                font-size: 5rem;
                margin-bottom: 1.5rem;
            }
            .hero-desc {
                font-size: 1.35rem;
                margin-bottom: 2.5rem;
            }
            .hero-container {
                padding-top: 6rem;
                padding-bottom: 9rem;
            }
            .hero-label {
                font-size: 0.95rem;
            }
        }
    </style>

    <!-- Hero Section -->
    <div class="text-center px-4 sm:px-6 lg:px-8 hero-container">
        <div class="max-w-5xl mx-auto">
            <div class="mb-5 flex justify-center">
                 <div class="hero-label">YEMDAT</div>
            </div>

            <h1 class="hero-title">
                {{ __('home.hero_title') }}
            </h1>

            <p class="hero-desc">
                {{ __('home.hero_desc') }}
            </p>

            <!-- Action Buttons -->
            <div class="flex flex-col sm:flex-row justify-center items-center gap-4">
                <a href="{{ auth()->guard('member')->check() ? route('profile.show') : route('register') }}" class="hero-btn hero-btn-primary">
                    {{ auth()->guard('member')->check() ? (app()->getLocale() == 'ar' ? 'ملفي الشخصي' : 'My Profile') : __('home.btn_join') }}
                    @if(!auth()->guard('member')->check())
                    <svg style="width: 1.25rem; height: 1.25rem; margin-left: 0.5rem;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                    @endif
                </a>
                 <a href="{{ route('events.index') }}" class="hero-btn hero-btn-secondary">
                    {{ __('home.btn_explore') }}
                </a>
            </div>
        </div>
    </div>

    <!-- Features Section -->
    <div class="py-16 bg-white/50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <!-- Data Management -->
                <div class="bg-white p-8 rounded-xl shadow-sm border border-gray-100 hover:border-yemdat-gold/50 transition duration-300 text-center flex flex-col items-center">
                    <div class="w-16 h-16 bg-yemdat-beige rounded-2xl flex items-center justify-center mb-6 text-yemdat-gold">
                       <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4m0 5c0 2.21-3.582 4-8 4s-8-1.79-8-4"></path></svg>
                    </div>
                    <h3 class="text-xl font-bold text-yemdat-brown mb-3">{{ __('home.feat_1_title') }}</h3>
                    <p class="text-gray-600">{{ __('home.feat_1_desc') }}</p>
                </div>

                <!-- Professional Network -->
                <div class="bg-white p-8 rounded-xl shadow-sm border border-gray-100 hover:border-yemdat-gold/50 transition duration-300 text-center flex flex-col items-center">
                     <div class="w-16 h-16 bg-yemdat-beige rounded-2xl flex items-center justify-center mb-6 text-yemdat-gold">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                    </div>
                    <h3 class="text-xl font-bold text-yemdat-brown mb-3">{{ __('home.feat_2_title') }}</h3>
                    <p class="text-gray-600">{{ __('home.feat_2_desc') }}</p>
                </div>

                <!-- Training -->
                 <div class="bg-white p-8 rounded-xl shadow-sm border border-gray-100 hover:border-yemdat-gold/50 transition duration-300 text-center flex flex-col items-center">
                     <div class="w-16 h-16 bg-yemdat-beige rounded-2xl flex items-center justify-center mb-6 text-yemdat-gold">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M12 14l9-5-9-5-9 5 9 5z"></path><path d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5zm0 0l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14zm-4 6v-7.5l4-2.222"></path></svg>
                    </div>
                    <h3 class="text-xl font-bold text-yemdat-brown mb-3">{{ __('home.feat_3_title') }}</h3>
                    <p class="text-gray-600">{{ __('home.feat_3_desc') }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Upcoming Events Section -->
    @if(isset($upcomingEvents) && $upcomingEvents->count() > 0)
    <div class="py-16 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h2 class="text-center text-2xl font-bold text-yemdat-brown mb-12">
                {{ app()->getLocale() == 'ar' ? 'الفعاليات القادمة' : 'Upcoming Events' }}
            </h2>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-12">
                @foreach($upcomingEvents as $event)
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden flex flex-col h-full hover:shadow-lg transition-all duration-300 group hover:-translate-y-1">
                        <div class="relative bg-gray-50 overflow-hidden shrink-0" style="height: 14rem; border-bottom: 1px solid #e5e7eb;">
                            <div class="absolute inset-0 z-10 pointer-events-none" style="box-shadow: inset 0 0 0 1px rgba(0,0,0,0.05);"></div>
                            @if($event->image)
                                <img src="{{ asset('storage/' . $event->image) }}" alt="{{ $event->title }}" class="w-full h-full object-cover group-hover:scale-105 transition duration-500">
                            @else
                                <div class="w-full h-full flex items-center justify-center text-yemdat-gold/30">
                                    <svg class="h-16 w-16" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                </div>
                            @endif
                            <div class="absolute top-4 right-4 bg-white/90 backdrop-blur-sm px-3 py-1 rounded-full text-xs font-bold text-yemdat-brown shadow-sm">
                                {{ $event->remaining_time }}
                            </div>
                        </div>
                        <div class="p-6">
                            <div class="text-xs font-bold text-yemdat-gold uppercase tracking-wider mb-2">
                                {{ $event->start_date->format('M d, Y') }}
                            </div>
                            <h3 class="font-bold text-gray-900 text-lg mb-3 line-clamp-2">
                                <a href="{{ route('events.show', $event->slug) }}" class="hover:text-yemdat-brown transition">
                                    {{ $event->title }}
                                </a>
                            </h3>
                            <div class="flex items-center text-sm text-gray-500 mb-4">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                                <span class="line-clamp-1">{{ $event->location ?? 'Online' }}</span>
                            </div>
                            <a href="{{ route('events.show', $event->slug) }}" class="text-sm font-bold text-yemdat-brown hover:text-yemdat-gold transition flex items-center">
                                {{ app()->getLocale() == 'ar' ? 'التفاصيل' : 'View Details' }}
                                <svg class="w-4 h-4 ml-1 rtl:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg>
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
            
            <div class="text-center">
                <a href="{{ route('events.index') }}" class="inline-block bg-yemdat-brown text-white px-8 py-3 rounded-lg font-bold transition shadow-md hover:bg-yemdat-brown/90">
                    {{ app()->getLocale() == 'ar' ? 'عرض كل الفعاليات' : 'View All Events' }}
                </a>
            </div>
        </div>
    </div>
    @endif

    <!-- Latest News -->
    <div class="py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h2 class="text-center text-2xl font-bold text-yemdat-brown mb-12">{{ __('home.news_title') }}</h2>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 max-w-4xl mx-auto">
                @foreach($latestNews as $post)
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
                            <!-- Meta (Date) -->
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

            <div class="text-center mt-12">
                 <a href="{{ route('news') }}" class="inline-block bg-white text-gray-500 border border-gray-200 px-8 py-3 rounded-full text-sm font-bold hover:border-yemdat-gold hover:text-yemdat-brown transition shadow-sm">
                    {{ __('home.btn_more_news') }}
                </a>
            </div>
        </div>
    </div>
</x-app-layout>
