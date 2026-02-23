<x-app-layout>
    <div class="bg-gray-50 min-h-screen py-12" dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <div class="mb-8 flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-extrabold text-gray-900 leading-tight">
                        {{ app()->getLocale() == 'ar' ? 'ملفي الشخصي' : 'My Profile' }}
                    </h1>
                    <p class="text-gray-500 mt-1">
                        {{ app()->getLocale() == 'ar' ? 'إدارة بيانات عضويتك في مجتمع يمدات.' : 'Manage your Yemdat community membership details.' }}
                    </p>
                </div>
                <a href="{{ route('profile.edit') }}" class="px-6 py-2 bg-yemdat-gold text-yemdat-brown font-bold rounded-xl hover:bg-yemdat-orange transition shadow-sm">
                    {{ app()->getLocale() == 'ar' ? 'تعديل الملف' : 'Edit Profile' }}
                </a>
            </div>

            @if(session('success'))
                <div class="mb-6 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-xl relative flex items-center gap-3">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    <span class="block sm:inline font-medium">{{ session('success') }}</span>
                </div>
            @endif

            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <!-- Profile Header Card -->
                <div class="p-8 border-b border-gray-100 flex flex-col md:flex-row items-center md:items-start gap-6 relative">
                    <div class="absolute top-0 inset-x-0 h-1.5 bg-gradient-to-r from-yemdat-brown via-yemdat-gold to-yemdat-brown"></div>
                    
                    <div class="relative w-32 h-32 rounded-full p-1 bg-white ring-1 ring-gray-100 shrink-0">
                        <div class="w-full h-full rounded-full bg-gray-50 flex items-center justify-center text-4xl font-bold text-yemdat-gold uppercase">
                            {{ mb_substr($member->full_name, 0, 1) }}
                        </div>
                    </div>

                    <div class="text-center md:text-start flex-grow">
                        <h2 class="text-2xl font-bold text-gray-900 mb-2">{{ $member->full_name }}</h2>
                        <span class="inline-block px-3 py-1 bg-yemdat-beige text-yemdat-brown text-sm font-bold rounded-full mb-4 uppercase tracking-wider">
                            {{ $member->membershipTier ? (app()->getLocale() == 'ar' ? $member->membershipTier->name_ar : $member->membershipTier->name_en) : 'Member' }}
                        </span>
                        
                        <div class="text-gray-600 mb-6 max-w-2xl">
                            @if($member->bio)
                                <p class="whitespace-pre-line">{{ $member->bio }}</p>
                            @else
                                <p class="italic text-gray-400">{{ app()->getLocale() == 'ar' ? 'لم يتم إضافة نبذة تعريفية بعد.' : 'No bio added yet.' }}</p>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Personal Info Details -->
                <div class="p-8">
                    <h3 class="text-lg font-bold text-yemdat-brown mb-6 border-b border-gray-100 pb-2">
                        {{ app()->getLocale() == 'ar' ? 'المعلومات الشخصية' : 'Personal Information' }}
                    </h3>
                    
                    <dl class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-6">
                        <div>
                            <dt class="text-sm font-bold text-gray-500 uppercase tracking-wider mb-1">{{ app()->getLocale() == 'ar' ? 'البريد الإلكتروني' : 'Email Address' }}</dt>
                            <dd class="text-base text-gray-900 font-medium">{{ $member->email }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-bold text-gray-500 uppercase tracking-wider mb-1">{{ app()->getLocale() == 'ar' ? 'رقم الهاتف' : 'Phone Number' }}</dt>
                            <dd class="text-base text-gray-900 font-medium" dir="ltr">{{ $member->phone_code }} {{ $member->phone_number }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-bold text-gray-500 uppercase tracking-wider mb-1">{{ app()->getLocale() == 'ar' ? 'البلد' : 'Country' }}</dt>
                            <dd class="text-base text-gray-900 font-medium">{{ $member->country }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-bold text-gray-500 uppercase tracking-wider mb-1">{{ app()->getLocale() == 'ar' ? 'الجنس' : 'Gender' }}</dt>
                            <dd class="text-base text-gray-900 font-medium">{{ $member->gender }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-bold text-gray-500 uppercase tracking-wider mb-1">{{ app()->getLocale() == 'ar' ? 'المستوى التعليمي' : 'Education Level' }}</dt>
                            <dd class="text-base text-gray-900 font-medium">{{ $member->education_level }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-bold text-gray-500 uppercase tracking-wider mb-1">{{ app()->getLocale() == 'ar' ? 'التخصص' : 'Specialty' }}</dt>
                            <dd class="text-base text-gray-900 font-medium">
                                {{ __('membership.spec_' . strtolower(str_replace(' ', '_', $member->specialty))) }}
                                @if($member->specialty === 'Other' && $member->specialty_other)
                                    ({{ $member->specialty_other }})
                                @endif
                            </dd>
                        </div>
                    </dl>
                </div>
            </div>

            <!-- Registered Events -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden mt-8">
                <div class="p-8 border-b border-gray-100 flex items-center gap-3">
                    <svg class="w-6 h-6 text-yemdat-brown" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                    <div>
                        <h3 class="text-lg font-bold text-yemdat-brown mb-1">
                            {{ app()->getLocale() == 'ar' ? 'الفعاليات المسجلة' : 'My Registered Events' }}
                        </h3>
                        <p class="text-gray-500 text-sm">
                            {{ app()->getLocale() == 'ar' ? 'سجل الفعاليات التي سجلت حضورك بها' : 'A record of events you have registered to attend.' }}
                        </p>
                    </div>
                </div>
                
                @if($member->events->count() > 0)
                    <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-6 bg-gray-50/50">
                        @foreach($member->events()->orderBy('start_date', 'desc')->get() as $event)
                            <div class="bg-white rounded-xl border border-gray-200 overflow-hidden hover:border-yemdat-gold transition shadow-sm hover:shadow-md flex flex-col">
                                <!-- Image Header -->
                                <div class="w-full h-40 bg-gray-100 relative">
                                    @if($event->image)
                                        <img src="{{ asset('storage/' . $event->image) }}" class="w-full h-full object-cover">
                                    @else
                                        <div class="w-full h-full flex items-center justify-center">
                                            <svg class="w-10 h-10 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                        </div>
                                    @endif
                                    
                                    <!-- Status Badge -->
                                    <div class="absolute top-3 right-3 rtl:right-auto rtl:left-3">
                                        @php
                                            $statusColor = 'bg-white/90 text-yemdat-brown'; // Default/Upcoming
                                            $statusText = $event->status;
                                            if($event->status === 'Happening Now') {
                                                $statusColor = 'bg-green-100/90 text-green-700';
                                            } elseif($event->end_date < now()) {
                                                $statusColor = 'bg-red-100/90 text-red-700';
                                                $statusText = app()->getLocale() == 'ar' ? 'انتهى' : 'Ended';
                                            }
                                        @endphp
                                        <span class="inline-flex items-center px-2 py-1 rounded-md text-xs font-bold shadow-sm backdrop-blur-sm {{ $statusColor }}">
                                            {{ $statusText }}
                                        </span>
                                    </div>
                                </div>
                                
                                <!-- Content -->
                                <div class="p-5 flex flex-col flex-grow">
                                    <h4 class="font-bold text-gray-900 text-lg mb-2 line-clamp-2 leading-snug">
                                        <a href="{{ route('events.show', $event->slug) }}" class="hover:text-yemdat-gold transition">{{ $event->title }}</a>
                                    </h4>
                                    
                                    <div class="space-y-2 mt-auto">
                                        <!-- Date -->
                                        <div class="flex items-center text-sm text-gray-500 gap-2">
                                            <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                            <span dir="ltr">{{ $event->start_date->format('M d, Y') }}</span>
                                        </div>
                                        
                                        <!-- Location -->
                                        <div class="flex items-center text-sm text-gray-500 gap-2">
                                            <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                                            <span class="truncate">{{ $event->location ?? 'Online' }}</span>
                                        </div>
                                    </div>
                                    
                                    @if($event->join_url)
                                        <div class="mt-5 pt-4 border-t border-gray-100 flex justify-between items-center">
                                            <a href="{{ $event->join_url }}" target="_blank" class="text-sm font-bold text-yemdat-brown hover:text-yemdat-gold flex items-center gap-1">
                                                {{ app()->getLocale() == 'ar' ? 'رابط الحضور' : 'Event Join Link' }}
                                                <svg class="w-4 h-4 rtl:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                                            </a>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="p-8 text-center text-gray-500">
                        {{ app()->getLocale() == 'ar' ? 'لم تقم بالتسجيل في أي فعالية بعد.' : 'You have not registered for any events yet.' }}
                        <div class="mt-4">
                            <a href="{{ route('events.index') }}" class="inline-block px-6 py-2 bg-yemdat-gold text-yemdat-brown font-bold rounded-xl hover:bg-yemdat-orange transition shadow-sm">
                                {{ app()->getLocale() == 'ar' ? 'استعرض الفعاليات' : 'Browse Events' }}
                            </a>
                        </div>
                    </div>
                @endif
            </div>

            <!-- Logout Button -->
            <div class="mt-8 flex justify-end">
                <form action="{{ route('public.logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="text-red-600 font-bold hover:text-red-700 hover:underline">
                        {{ app()->getLocale() == 'ar' ? 'تسجيل الخروج' : 'Log Out' }} &rarr;
                    </button>
                </form>
            </div>

        </div>
    </div>
</x-app-layout>
