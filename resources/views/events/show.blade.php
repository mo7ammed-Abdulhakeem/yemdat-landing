<x-app-layout>
    <div class="bg-gray-50 min-h-screen py-12" dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <!-- Page Header -->
            <div class="mb-8">
                <nav class="flex mb-4" aria-label="Breadcrumb">
                    <ol class="inline-flex items-center space-x-1 md:space-x-3 rtl:space-x-reverse">
                        <li class="inline-flex items-center">
                            <a href="{{ route('home') }}" class="text-sm font-medium text-gray-500 hover:text-yemdat-brown">
                                {{ app()->getLocale() == 'ar' ? 'الرئيسية' : 'Home' }}
                            </a>
                        </li>
                        <li>
                            <div class="flex items-center">
                                <svg class="w-3 h-3 text-gray-400 mx-1 rtl:rotate-180" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 9 4-4-4-4"/>
                                </svg>
                                <a href="{{ route('events.index') }}" class="text-sm font-medium text-gray-500 hover:text-yemdat-brown ml-1 md:ml-2">
                                    {{ app()->getLocale() == 'ar' ? 'الفعاليات' : 'Events' }}
                                </a>
                            </div>
                        </li>
                    </ol>
                </nav>
                <h1 class="text-3xl md:text-4xl font-extrabold text-gray-900 leading-tight">
                    {{ $event->title }}
                </h1>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                
                <!-- Left Column: Cover & Details -->
                <div class="lg:col-span-2 space-y-8">
                    
                    <!-- Cover Image Block -->
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden p-2">
                        @if($event->image)
                            <img class="w-full h-auto rounded-xl object-cover aspect-video" src="{{ asset('storage/' . $event->image) }}" alt="{{ $event->title }}">
                        @else
                            <div class="w-full h-64 bg-gray-100 rounded-xl flex items-center justify-center text-gray-400">
                                <svg class="w-16 h-16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                            </div>
                        @endif
                    </div>

                    <!-- Event Details Block -->
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8">
                        <h2 class="text-xl font-bold text-gray-900 mb-6 flex items-center gap-2 border-b border-gray-100 pb-4">
                            <svg class="w-5 h-5 text-yemdat-brown" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7"></path></svg>
                            {{ app()->getLocale() == 'ar' ? 'تفاصيل الحدث' : 'Event Details' }}
                        </h2>
                        <div class="prose max-w-none text-gray-600 leading-relaxed">
                            {!! $event->description !!}
                        </div>
                    </div>
                </div>

                <!-- Right Column: Info & Speaker -->
                <div class="lg:col-span-1 space-y-6">
                    
                    <!-- Event Info Block (Time, Date, Location, Status) -->
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                        <div class="flex items-center justify-between mb-8">
                            <h3 class="font-bold text-gray-900 text-lg">{{ app()->getLocale() == 'ar' ? 'معلومات الحدث' : 'Event Info' }}</h3>
                            
                            @php
                                $statusColor = 'bg-yemdat-brown/10 text-yemdat-brown border-yemdat-brown/20'; // Default/Upcoming
                                $statusText = $event->status;
                                
                                if($event->status === 'Happening Now') {
                                    $statusColor = 'bg-green-100 text-green-700 border-green-200';
                                } elseif($event->end_date < now()) {
                                    $statusColor = 'bg-red-100 text-red-700 border-red-200';
                                    $statusText = app()->getLocale() == 'ar' ? 'انتهى' : 'Ended';
                                }
                            @endphp
                            
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold border {{ $statusColor }}">
                                {{ $statusText }}
                            </span>
                        </div>

                        <div class="space-y-6">
                            <!-- Date -->
                            <div class="flex items-start gap-4">
                                <div class="w-12 h-12 rounded-xl border border-gray-100 flex items-center justify-center text-yemdat-gold text-xl shadow-sm">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                </div>
                                <div>
                                    <div class="text-xs text-gray-500 uppercase font-bold tracking-wider mb-1">{{ app()->getLocale() == 'ar' ? 'التاريخ والوقت' : 'Date & Time' }}</div>
                                    <div class="text-base font-bold text-gray-900 leading-tight">{{ $event->start_date->format('F d, Y') }}</div>
                                    <div class="text-sm text-yemdat-blue font-medium">{{ $event->start_date->format('h:i A') }}</div>
                                    @if($event->status == 'Upcoming')
                                        <div class="text-xs text-yemdat-brown font-bold mt-1 bg-yemdat-gold/20 inline-block px-2 py-0.5 rounded">
                                            {{ $event->remaining_time }}
                                        </div>
                                    @endif
                                </div>
                            </div>

                            <!-- Location -->
                            <div class="flex items-start gap-4">
                                <div class="w-12 h-12 rounded-xl border border-gray-100 flex items-center justify-center text-yemdat-gold text-xl shadow-sm shrink-0">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                                </div>
                                <div class="overflow-hidden">
                                    <div class="text-xs text-gray-500 uppercase font-bold tracking-wider mb-1">{{ app()->getLocale() == 'ar' ? 'الموقع' : 'Location' }}</div>
                                    @if(filter_var($event->location, FILTER_VALIDATE_URL))
                                        <a href="{{ $event->location }}" target="_blank" class="text-sm font-bold text-yemdat-brown hover:underline truncate block" title="{{ $event->location }}">
                                            {{ $event->location }}
                                        </a>
                                    @else
                                        <div class="text-sm font-bold text-gray-900">{{ $event->location ?? 'Online' }}</div>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- Registration Button Logic -->
                        @php
                            $isUpcoming = false;
                            if($event->end_date) {
                                if($event->end_date >= now()) {
                                    $isUpcoming = true;
                                }
                            } else {
                                if($event->start_date->addHours(3) >= now()) {
                                    $isUpcoming = true;
                                }
                            }
                            
                            $member = auth()->guard('member')->user();
                            $isRegistered = $member ? $event->members->contains($member->id) : false;
                        @endphp

                        @if($isUpcoming)
                            <div class="mt-8 pt-6 border-t border-gray-100">
                                @if(!$member)
                                    <div class="space-y-3">
                                        <p class="text-sm text-center text-gray-500">{{ app()->getLocale() == 'ar' ? 'يجب تسجيل الدخول للتسجيل في الفعالية' : 'Please sign in to register for this event.' }}</p>
                                        <a href="{{ route('public.login', ['redirect' => url()->current()]) }}" class="block w-full py-3 text-center bg-gray-100 text-yemdat-brown font-bold rounded-xl hover:bg-gray-200 transition">
                                            {{ app()->getLocale() == 'ar' ? 'تسجيل الدخول / إنشاء حساب' : 'Sign In / Join Community' }}
                                        </a>
                                    </div>
                                @elseif($isRegistered)
                                    <div class="p-4 bg-green-50 border border-green-200 rounded-xl text-center">
                                        <p class="text-green-700 font-bold mb-3 flex items-center justify-center gap-2">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                            {{ app()->getLocale() == 'ar' ? 'تم تسجيلك بنجاح في الفعالية' : 'You are successfully registered' }}
                                        </p>
                                        @if($event->join_url)
                                            <a href="{{ $event->join_url }}" target="_blank" class="block w-full py-3 text-center bg-yemdat-gold text-yemdat-brown font-bold rounded-xl hover:bg-yemdat-orange transition shadow-sm">
                                                {{ app()->getLocale() == 'ar' ? 'رابط حضور الفعالية' : 'Event Join Link' }}
                                            </a>
                                        @endif
                                    </div>
                                @else
                                    <form action="{{ route('events.register', $event->slug) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="w-full block py-3 text-center bg-yemdat-gold text-yemdat-brown font-bold rounded-xl hover:bg-yemdat-orange transition shadow-sm hover:shadow-md">
                                            {{ app()->getLocale() == 'ar' ? 'تأكيد التسجيل في الفعالية' : 'Confirm Event Registration' }}
                                        </button>
                                    </form>
                                @endif
                            </div>
                        @endif
                    </div>

                    <!-- Speaker Block -->
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8 text-center relative overflow-hidden group">
                        <!-- Card Header Decoration -->
                         <div class="absolute top-0 inset-x-0 h-1.5 bg-gradient-to-r from-yemdat-brown via-yemdat-gold to-yemdat-brown"></div>
                         
                        <div class="relative inline-block mb-6 mt-2">
                             <!-- Glowing Effect -->
                            <div class="absolute -inset-1 bg-gradient-to-br from-yemdat-gold to-yemdat-brown opacity-30 blur rounded-full group-hover:opacity-50 transition duration-500"></div>
                            
                            <!-- Profile Image -->
                            <div class="relative w-32 h-32 rounded-full p-1 bg-white ring-1 ring-gray-100">
                                @if($event->lecturer_image)
                                    <img src="{{ asset('storage/' . $event->lecturer_image) }}" alt="{{ $event->lecturer_name }}" class="w-full h-full rounded-full object-cover">
                                @else
                                    <div class="w-full h-full rounded-full bg-gray-50 flex items-center justify-center text-gray-300">
                                         <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <h3 class="text-lg font-bold text-gray-900 mb-1">{{ $event->lecturer_name }}</h3>
                        @if($event->lecturer_title)
                            <p class="text-sm text-yemdat-brown font-medium mb-6 bg-yemdat-beige/30 inline-block px-3 py-1 rounded-full">{{ $event->lecturer_title }}</p>
                        @endif

                        @if($event->lecturer_linkedin)
                             <a href="{{ $event->lecturer_linkedin }}" target="_blank" class="flex items-center justify-center gap-2 text-gray-500 hover:text-[#0077b5] transition-colors mb-2 text-sm font-medium">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M19 0h-14c-2.761 0-5 2.239-5 5v14c0 2.761 2.239 5 5 5h14c2.762 0 5-2.239 5-5v-14c0-2.761-2.238-5-5-5zm-11 19h-3v-11h3v11zm-1.5-12.268c-.966 0-1.75-.79-1.75-1.764s.784-1.764 1.75-1.764 1.75.79 1.75 1.764-.783 1.764-1.75 1.764zm13.5 12.268h-3v-5.604c0-3.368-4-3.113-4 0v5.604h-3v-11h3v1.765c1.396-2.586 7-2.777 7 2.476v6.759z"/></svg>
                                LinkedIn
                            </a>
                        @endif
                    </div>
                </div>

            </div>
            
            @if(isset($similarEvents) && $similarEvents->count() > 0)
                <div class="mt-16 pt-8 border-t border-gray-200 w-full col-span-1 lg:col-span-3">
                    <h2 class="text-2xl font-bold text-gray-900 mb-8 border-b border-gray-100 pb-4">
                        {{ app()->getLocale() == 'ar' ? 'فعاليات أخرى قد تهمك' : 'More Upcoming Events' }}
                    </h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                        @foreach($similarEvents as $simEvent)
                            <div class="bg-white rounded-3xl border border-gray-100 shadow-sm hover:shadow-xl transition-all duration-300 flex flex-col group overflow-hidden">
                                <div class="relative h-56 w-full shrink-0 overflow-hidden bg-gray-100">
                                    @if($simEvent->image)
                                        <img src="{{ asset('storage/' . $simEvent->image) }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                                    @else
                                        <div class="w-full h-full flex flex-col items-center justify-center text-gray-300">
                                            <svg class="h-12 w-12" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                            </svg>
                                        </div>
                                    @endif
                                </div>

                                <div class="p-6 flex-grow flex flex-col">
                                    <h4 class="text-xl font-bold text-gray-900 mb-4 flex-grow line-clamp-2">
                                        <a href="{{ route('events.show', $simEvent->slug) }}" class="hover:text-yemdat-gold transition">
                                            {{ $simEvent->title }}
                                        </a>
                                    </h4>

                                    <div class="space-y-2 mb-4 text-sm">
                                        <div class="flex items-center text-gray-700">
                                            <svg class="w-4 h-4 mr-2 rtl:ml-2 text-yemdat-gold shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                            <span dir="ltr">{{ $simEvent->start_date->format('M d, Y | h:i A') }}</span>
                                        </div>
                                        @if($simEvent->location)
                                        <div class="flex items-center text-gray-500">
                                            <svg class="w-4 h-4 mr-2 rtl:ml-2 text-gray-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                                            <span class="truncate">{{ $simEvent->location }}</span>
                                        </div>
                                        @endif
                                    </div>
                                    
                                    <a href="{{ route('events.show', $simEvent->slug) }}" class="mt-auto pt-4 border-t border-gray-100 flex items-center text-yemdat-brown font-bold hover:text-yemdat-gold transition">
                                        {{ app()->getLocale() == 'ar' ? 'التفاصيل والتسجيل' : 'Details & Register' }}
                                        <svg class="w-5 h-5 ml-2 rtl:mr-2 rtl:ml-0 rtl:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg>
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
