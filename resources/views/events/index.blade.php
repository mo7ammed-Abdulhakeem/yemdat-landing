<x-app-layout>
    @php
        $ar = app()->getLocale() === 'ar';
        $hasFilters = ! empty($activeFilters);
        $total = $happeningNow->count() + $upcomingEvents->count() + $pastEvents->count();
    @endphp

    <div class="py-12 bg-white" dir="{{ $ar ? 'rtl' : 'ltr' }}">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-10" data-reveal>
                <h2 class="text-3xl font-extrabold text-yemdat-brown sm:text-4xl">
                    {{ __('nav.training_events') }}
                </h2>
                <p class="mt-4 text-lg text-gray-500">
                    {{ $ar ? 'انضم إلى ورش العمل والندوات وجلسات التدريب القادمة.' : 'Join our upcoming workshops, seminars, and training sessions.' }}
                </p>
            </div>

            {{-- Filter bar --}}
            <form method="GET" action="{{ route('events.index') }}" class="bg-surface rounded-card border border-border p-4 mb-12">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-3 items-end">
                    <div class="lg:col-span-2">
                        <x-ui.input name="q" :value="$q" :label="$ar ? 'بحث' : 'Search'"
                            :placeholder="$ar ? 'ابحث عن فعالية…' : 'Search events…'" />
                    </div>

                    <x-ui.select name="format" :label="$ar ? 'النوع' : 'Type'">
                        <option value="">{{ $ar ? 'الكل' : 'All' }}</option>
                        <option value="event" @selected($format === 'event')>{{ $ar ? 'فعالية' : 'Event' }}</option>
                        <option value="workshop" @selected($format === 'workshop')>{{ $ar ? 'ورشة عمل' : 'Workshop' }}</option>
                        <option value="course" @selected($format === 'course')>{{ $ar ? 'دورة' : 'Course' }}</option>
                    </x-ui.select>

                    <x-ui.select name="level" :label="$ar ? 'المستوى' : 'Level'">
                        <option value="">{{ $ar ? 'الكل' : 'All' }}</option>
                        <option value="beginner" @selected($level === 'beginner')>{{ $ar ? 'مبتدئ' : 'Beginner' }}</option>
                        <option value="intermediate" @selected($level === 'intermediate')>{{ $ar ? 'متوسط' : 'Intermediate' }}</option>
                        <option value="advanced" @selected($level === 'advanced')>{{ $ar ? 'متقدم' : 'Advanced' }}</option>
                    </x-ui.select>

                    <x-ui.select name="specialty" :label="$ar ? 'المجال' : 'Topic'">
                        <option value="">{{ $ar ? 'الكل' : 'All' }}</option>
                        @foreach ($specialties as $sp)
                            <option value="{{ $sp->slug }}" @selected($specialty === $sp->slug)>{{ $sp->name }}</option>
                        @endforeach
                    </x-ui.select>
                </div>

                <div class="flex items-center gap-3 mt-4">
                    <x-ui.button type="submit">{{ $ar ? 'تطبيق' : 'Apply' }}</x-ui.button>
                    @if ($hasFilters)
                        <a href="{{ route('events.index') }}" class="text-sm font-semibold text-ink-soft hover:text-primary">
                            {{ $ar ? 'مسح الفلاتر' : 'Clear filters' }}
                        </a>
                    @endif
                </div>
            </form>

            {{-- No results across all sections --}}
            @if ($hasFilters && $total === 0)
                <div class="text-center py-16">
                    <p class="text-gray-500 italic bg-gray-50 rounded-lg py-8 px-6 max-w-md mx-auto">
                        {{ $ar ? 'لا توجد فعاليات تطابق الفلاتر المحددة.' : 'No events match the selected filters.' }}
                    </p>
                </div>
            @endif

            <!-- Happening Now Section -->
            @if($happeningNow->count() > 0)
                <div class="mb-16">
                    <div class="flex items-center mb-6">
                        <span class="relative flex h-4 w-4 mr-3 rtl:mr-0 rtl:ml-3">
                          <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-red-400 opacity-75"></span>
                          <span class="relative inline-flex rounded-full h-4 w-4 bg-red-500"></span>
                        </span>
                        <h3 class="text-2xl font-bold text-red-600">
                            {{ $ar ? 'يحدث الآن' : 'Happening Now' }}
                        </h3>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8" data-reveal-group>
                        @foreach($happeningNow as $event)
                            <x-events.event-card :event="$event" variant="live" />
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- Upcoming Events -->
            @if($upcomingEvents->count() > 0 || ! $hasFilters)
                <div class="mb-16">
                    <h3 class="text-2xl font-bold text-gray-900 mb-6 border-l-4 rtl:border-l-0 rtl:border-r-4 border-yemdat-gold pl-4 rtl:pl-0 rtl:pr-4" data-reveal>
                        {{ $ar ? 'الفعاليات القادمة' : 'Upcoming Events' }}
                    </h3>

                    @if($upcomingEvents->count() > 0)
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8" data-reveal-group>
                            @foreach($upcomingEvents as $event)
                                <x-events.event-card :event="$event" variant="upcoming" />
                            @endforeach
                        </div>
                    @else
                        <p class="text-gray-500 italic text-center py-8 bg-gray-50 rounded-lg">
                            {{ $ar ? 'لا توجد فعاليات قادمة مجدولة في الوقت الحالي.' : 'No upcoming events scheduled at the moment.' }}
                        </p>
                    @endif
                </div>
            @endif

            <!-- Past Events -->
            @if($pastEvents->count() > 0)
                <div>
                    <h3 class="text-2xl font-bold text-gray-400 mb-6 border-l-4 rtl:border-l-0 rtl:border-r-4 border-gray-300 pl-4 rtl:pl-0 rtl:pr-4" data-reveal>
                        {{ $ar ? 'الفعاليات السابقة' : 'Past Events' }}
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8" data-reveal-group>
                        @foreach($pastEvents as $event)
                            <x-events.event-card :event="$event" variant="past" />
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
