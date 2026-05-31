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
                            <x-events.event-card :event="$event" variant="live" />
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
                            <x-events.event-card :event="$event" variant="upcoming" />
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
                            <x-events.event-card :event="$event" variant="past" />
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
