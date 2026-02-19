<x-app-layout>
    <div class="py-12 bg-white" dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <h2 class="text-3xl font-extrabold text-yemdat-brown sm:text-4xl">
                    {{ __('nav.training_events') }}
                </h2>
                <p class="mt-4 text-lg text-gray-500">
                    {{ app()->getLocale() == 'ar' ? 'انضم إلى ورش العمل والندوات وجلسات التدريب القادمة.' : 'Join our upcoming workshops, seminars, and training sessions.' }}
                </p>
            </div>

            <!-- Happening Now Section -->
            @if($happeningNow->count() > 0)
                <div class="mb-16">
                    <div class="flex items-center mb-6">
                        <span class="relative flex h-4 w-4 mr-3 rtl:mr-0 rtl:ml-3">
                          <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-red-400 opacity-75"></span>
                          <span class="relative inline-flex rounded-full h-4 w-4 bg-red-500"></span>
                        </span>
                        <h3 class="text-2xl font-bold text-red-600">
                            {{ app()->getLocale() == 'ar' ? 'يحدث الآن' : 'Happening Now' }}
                        </h3>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                        @foreach($happeningNow as $event)
                            <div class="bg-white rounded-xl shadow-lg border-2 border-red-100 hover:border-red-300 transition-all duration-300 overflow-hidden flex flex-col h-full group">
                                <!-- Image & Countup -->
                                <div class="relative h-48 bg-gray-200 overflow-hidden">
                                    @if($event->image)
                                        <img src="{{ asset('storage/' . $event->image) }}" alt="{{ $event->title }}" class="w-full h-full object-cover group-hover:scale-105 transition duration-500">
                                    @else
                                        <div class="w-full h-full flex items-center justify-center bg-red-50 text-red-200">
                                            <svg class="h-12 w-12" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z" />
                                            </svg>
                                        </div>
                                    @endif
                                    
                                    <div class="absolute top-4 right-4 rtl:right-auto rtl:left-4 bg-red-600 text-white px-3 py-1 rounded-full text-xs font-bold shadow-sm animate-pulse flex items-center gap-1">
                                        <span class="w-2 h-2 bg-white rounded-full"></span>
                                        LIVE
                                    </div>
                                </div>

                                <!-- Content -->
                                <div class="p-6 flex-grow flex flex-col">
                                    <h4 class="text-xl font-bold text-gray-900 mb-3 flex-grow line-clamp-2">
                                        <a href="{{ route('events.show', $event->slug) }}" class="hover:text-red-600 transition">
                                            {{ $event->title }}
                                        </a>
                                    </h4>
                                    
                                     <!-- Date & Time Information -->
                                    <div class="space-y-2 mb-4 text-sm">
                                        <!-- Start -->
                                        <div class="flex items-center text-gray-700">
                                            <svg class="w-4 h-4 mr-2 rtl:ml-2 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                            <span class="font-bold">{{ app()->getLocale() == 'ar' ? 'البدء:' : 'Start:' }}</span>
                                            <span class="mx-1">{{ $event->start_date->format('M d, Y | h:i A') }}</span>
                                        </div>
                                        
                                        <!-- End (if exists) -->
                                        @if($event->end_date)
                                        <div class="flex items-center text-gray-500">
                                            <svg class="w-4 h-4 mr-2 rtl:ml-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                            <span class="font-bold">{{ app()->getLocale() == 'ar' ? 'النهاية:' : 'End:' }}</span>
                                            <span class="mx-1">{{ $event->end_date->format('M d, Y | h:i A') }}</span>
                                        </div>
                                        @endif

                                        <!-- Location -->
                                        @if($event->location)
                                        <div class="flex items-center text-gray-500">
                                            <svg class="w-4 h-4 mr-2 rtl:ml-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                                            <span class="truncate max-w-[200px]">{{ $event->location }}</span>
                                        </div>
                                        @endif
                                    </div>

                                    <a href="{{ route('events.show', $event->slug) }}" class="mt-auto block w-full text-center py-2.5 bg-red-600 text-white rounded-lg hover:bg-red-700 transition font-bold text-sm shadow-md" style="background-color: #dc2626 !important; color: white !important;">
                                        {{ app()->getLocale() == 'ar' ? 'انضم للفعالية الآن' : 'Join Event Now' }}
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- Upcoming Events -->
            <div class="mb-16">
                <h3 class="text-2xl font-bold text-gray-900 mb-6 border-l-4 rtl:border-l-0 rtl:border-r-4 border-yemdat-gold pl-4 rtl:pl-0 rtl:pr-4">
                    {{ app()->getLocale() == 'ar' ? 'الفعاليات القادمة' : 'Upcoming Events' }}
                </h3>
                
                @if($upcomingEvents->count() > 0)
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                        @foreach($upcomingEvents as $event)
                            <div class="bg-white rounded-xl shadow-md hover:shadow-xl transition-shadow duration-300 overflow-hidden border border-gray-100 flex flex-col h-full group">
                                <!-- Image & Countdown -->
                                <div class="relative h-48 bg-gray-200 overflow-hidden">
                                     @if($event->image)
                                        <img src="{{ asset('storage/' . $event->image) }}" alt="{{ $event->title }}" class="w-full h-full object-cover group-hover:scale-105 transition duration-500">
                                    @else
                                        <div class="w-full h-full flex items-center justify-center bg-yemdat-beige text-yemdat-brown">
                                            <svg class="h-12 w-12" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                            </svg>
                                        </div>
                                    @endif
                                    
                                    <!-- Countdown Badge -->
                                    <div class="absolute top-4 right-4 rtl:right-auto rtl:left-4 bg-yemdat-gold text-yemdat-brown px-3 py-1 rounded-full text-xs font-bold shadow-md">
                                        {{ $event->remaining_time }}
                                    </div>
                                </div>

                                <!-- Content -->
                                <div class="p-6 flex-grow flex flex-col">
                                    <h4 class="text-xl font-bold text-gray-900 mb-4 flex-grow line-clamp-2">
                                        <a href="{{ route('events.show', $event->slug) }}" class="hover:text-yemdat-gold transition">
                                            {{ $event->title }}
                                        </a>
                                    </h4>

                                     <!-- Date & Time Information -->
                                    <div class="space-y-2 mb-4 text-sm">
                                        <!-- Start -->
                                        <div class="flex items-start justify-between w-full">
                                            <div class="flex items-center text-gray-700">
                                                <svg class="w-4 h-4 mr-2 rtl:ml-2 text-yemdat-gold" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                                <span class="font-bold">{{ app()->getLocale() == 'ar' ? 'البدء:' : 'Start:' }}</span>
                                                <span class="mx-1">{{ $event->start_date->format('M d, Y | h:i A') }}</span>
                                            </div>
                                            <div class="text-xs font-bold text-yemdat-brown bg-yemdat-gold/20 px-2 py-0.5 rounded whitespace-nowrap">
                                                {{ $event->remaining_time }}
                                            </div>
                                        </div>
                                        
                                        <!-- End (if exists) -->
                                        @if($event->end_date)
                                        <div class="flex items-center text-gray-500">
                                            <svg class="w-4 h-4 mr-2 rtl:ml-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                            <span class="font-bold">{{ app()->getLocale() == 'ar' ? 'النهاية:' : 'End:' }}</span>
                                            <span class="mx-1">{{ $event->end_date->format('M d, Y | h:i A') }}</span>
                                        </div>
                                        @endif

                                        <!-- Location -->
                                        @if($event->location)
                                        <div class="flex items-center text-gray-500">
                                            <svg class="w-4 h-4 mr-2 rtl:ml-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                                            <span class="truncate max-w-[200px]">{{ $event->location }}</span>
                                        </div>
                                        @endif
                                    </div>
                                    
                                    <!-- Lecturer Mini -->
                                    <div class="flex items-center mt-2 mb-6 pt-4 border-t border-gray-100">
                                        @if($event->lecturer_image)
                                            <img src="{{ asset('storage/' . $event->lecturer_image) }}" alt="" class="h-8 w-8 rounded-full object-cover border border-gray-200 shrink-0">
                                        @else
                                            <div class="h-8 w-8 rounded-full bg-gray-100 flex items-center justify-center text-gray-400 shrink-0">
                                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                                </svg>
                                            </div>
                                        @endif
                                        <span class="ml-2 rtl:ml-0 rtl:mr-2 text-sm text-gray-700 font-bold">{{ $event->lecturer_name }}</span>
                                    </div>
                                    
                                    <a href="{{ route('events.show', $event->slug) }}" class="mt-auto block w-full text-center py-2.5 bg-yemdat-brown text-white rounded-lg hover:bg-yemdat-gold hover:text-yemdat-brown transition font-bold text-sm shadow-md">
                                        {{ app()->getLocale() == 'ar' ? 'عرض التفاصيل' : 'View Details' }}
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-gray-500 italic text-center py-8 bg-gray-50 rounded-lg">
                        {{ app()->getLocale() == 'ar' ? 'لا توجد فعاليات قادمة مجدولة في الوقت الحالي.' : 'No upcoming events scheduled at the moment.' }}
                    </p>
                @endif
            </div>

            <!-- Past Events -->
            @if($pastEvents->count() > 0)
                <div>
                    <h3 class="text-2xl font-bold text-gray-400 mb-6 border-l-4 rtl:border-l-0 rtl:border-r-4 border-gray-300 pl-4 rtl:pl-0 rtl:pr-4">
                        {{ app()->getLocale() == 'ar' ? 'الفعاليات السابقة' : 'Past Events' }}
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                         @foreach($pastEvents as $event)
                            <div class="bg-gray-50 rounded-xl overflow-hidden border border-gray-200 flex flex-col h-full grayscale hover:grayscale-0 transition duration-500 group">
                                <!-- Image & Badge -->
                                <div class="relative h-48 bg-gray-200 overflow-hidden">
                                     @if($event->image)
                                        <img src="{{ asset('storage/' . $event->image) }}" alt="{{ $event->title }}" class="w-full h-full object-cover group-hover:scale-105 transition duration-500">
                                    @else
                                        <div class="w-full h-full flex items-center justify-center bg-gray-200 text-gray-400">
                                            <svg class="h-12 w-12" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                            </svg>
                                        </div>
                                    @endif
                                    
                                    <div class="absolute top-4 right-4 rtl:right-auto rtl:left-4 bg-gray-600 text-white px-3 py-1 rounded-full text-xs font-bold shadow-sm">
                                        {{ app()->getLocale() == 'ar' ? 'انتهى' : 'Ended' }}
                                    </div>
                                </div>

                                <!-- Content -->
                                <div class="p-6 flex-grow flex flex-col">
                                    <h4 class="text-xl font-bold text-gray-700 mb-4 flex-grow line-clamp-2">
                                        <a href="{{ route('events.show', $event->slug) }}" class="hover:text-gray-900 transition">
                                            {{ $event->title }}
                                        </a>
                                    </h4>

                                     <!-- Date & Time Information -->
                                    <div class="space-y-2 mb-4 text-sm text-gray-500">
                                        <!-- Start -->
                                        <div class="flex items-center">
                                            <svg class="w-4 h-4 mr-2 rtl:ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                            <span class="font-bold">{{ app()->getLocale() == 'ar' ? 'البدء:' : 'Start:' }}</span>
                                            <span class="mx-1">{{ $event->start_date->format('M d, Y | h:i A') }}</span>
                                        </div>

                                        <!-- End (if exists) -->
                                        @if($event->end_date)
                                        <div class="flex items-center">
                                            <svg class="w-4 h-4 mr-2 rtl:ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                            <span class="font-bold">{{ app()->getLocale() == 'ar' ? 'النهاية:' : 'End:' }}</span>
                                            <span class="mx-1">{{ $event->end_date->format('M d, Y | h:i A') }}</span>
                                        </div>
                                        @endif
                                    </div>
                                    
                                    <!-- Lecturer Mini -->
                                    <div class="flex items-center mt-2 mb-6 pt-4 border-t border-gray-200">
                                        @if($event->lecturer_image)
                                            <img src="{{ asset('storage/' . $event->lecturer_image) }}" alt="" class="h-8 w-8 rounded-full object-cover border border-gray-200 shrink-0 opacity-75 group-hover:opacity-100 transition">
                                        @else
                                            <div class="h-8 w-8 rounded-full bg-gray-100 flex items-center justify-center text-gray-400 shrink-0">
                                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                                </svg>
                                            </div>
                                        @endif
                                        <span class="ml-2 rtl:ml-0 rtl:mr-2 text-sm text-gray-600 font-bold group-hover:text-gray-800 transition">{{ $event->lecturer_name }}</span>
                                    </div>

                                    <a href="{{ route('events.show', $event->slug) }}" class="mt-auto block w-full text-center py-2.5 border border-gray-300 text-gray-500 rounded-lg hover:bg-gray-600 hover:text-white transition font-bold text-sm">
                                        {{ app()->getLocale() == 'ar' ? 'عرض الملخص' : 'View Recap' }}
                                    </a>
                                </div>
                            </div>
                         @endforeach
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
