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
                <x-ui.button variant="accent" :href="route('profile.edit')">
                    {{ app()->getLocale() == 'ar' ? 'تعديل الملف' : 'Edit Profile' }}
                </x-ui.button>
            </div>

            @if(session('success'))
                <!-- Success Popup Modal -->
                <div 
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition.opacity.duration.300ms
                    class="fixed inset-0 z-50 flex items-center justify-center bg-black/40 backdrop-blur-sm p-4"
                    style="display: none;"
                >
                    <div 
                        x-show="show"
                        x-transition:enter="transition ease-out duration-300 transform"
                        x-transition:enter-start="opacity-0 scale-90 translate-y-4"
                        x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                        x-transition:leave="transition ease-in duration-200 transform"
                        x-transition:leave-start="opacity-100 scale-100 translate-y-0"
                        x-transition:leave-end="opacity-0 scale-95 translate-y-4"
                        @click.away="show = false"
                        class="bg-white rounded-3xl shadow-2xl max-w-sm w-full p-8 text-center relative overflow-hidden"
                    >
                        <div class="absolute top-0 inset-x-0 h-2 bg-gradient-to-r from-green-400 to-green-500"></div>
                        
                        <!-- Close Button -->
                        <button @click="show = false" class="absolute top-4 right-4 rtl:left-4 rtl:right-auto text-gray-400 hover:text-gray-600 transition bg-gray-50 rounded-full p-1 border border-gray-100 shadow-sm hover:shadow">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                        </button>
                        
                        <!-- Icon -->
                        <div class="w-20 h-20 bg-green-50 rounded-full flex items-center justify-center mx-auto mb-5 border-4 border-white shadow-sm relative">
                            <div class="absolute inset-0 bg-green-100 rounded-full animate-ping opacity-25"></div>
                            <svg class="w-10 h-10 text-green-500 relative z-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                        </div>
                        
                        <!-- Text Content -->
                        <h3 class="text-2xl font-extrabold text-gray-900 mb-2 font-sans tracking-tight">
                            {{ app()->getLocale() == 'ar' ? 'تم بنجاح!' : 'Success!' }}
                        </h3>
                        <p class="text-gray-600 mb-8 leading-relaxed font-medium">
                            {{ session('success') }}
                        </p>
                        
                        <!-- Action -->
                        <button @click="show = false" class="w-full py-3 bg-green-50 text-green-700 font-bold rounded-xl border border-green-100 hover:bg-green-500 hover:text-white hover:border-green-500 hover:shadow-lg transition-all transform active:scale-95">
                            {{ app()->getLocale() == 'ar' ? 'حسناً، إغلاق' : 'Okay, Close' }}
                        </button>
                    </div>
                </div>
            @endif

            @php
                $ar = app()->getLocale() === 'ar';
                $totalEvents = $upcomingEvents->count() + $pastEvents->count();
                $certCount = $certificatesByEvent->count();
                $fieldLabels = [
                    'full_name'       => $ar ? 'الاسم الكامل' : 'Full name',
                    'email'           => $ar ? 'البريد الإلكتروني' : 'Email',
                    'phone_number'    => $ar ? 'رقم الهاتف' : 'Phone number',
                    'country'         => $ar ? 'البلد' : 'Country',
                    'gender'          => $ar ? 'الجنس' : 'Gender',
                    'specialty'       => $ar ? 'التخصص' : 'Specialty',
                    'education_level' => $ar ? 'المستوى التعليمي' : 'Education level',
                    'bio'             => $ar ? 'النبذة التعريفية' : 'Bio',
                    'linkedin_url'    => $ar ? 'حساب لينكدإن' : 'LinkedIn profile',
                ];
            @endphp

            <!-- Dashboard: at-a-glance stats -->
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-6">
                <!-- Registered events -->
                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5 flex items-center gap-4">
                    <div class="w-12 h-12 rounded-xl bg-yemdat-gold/15 text-yemdat-brown flex items-center justify-center shrink-0">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                    </div>
                    <div>
                        <div class="text-2xl font-extrabold text-gray-900 leading-none">{{ $totalEvents }}</div>
                        <div class="text-sm text-gray-500 mt-1">{{ $ar ? 'فعالية مسجّلة' : 'Registered events' }}</div>
                        @if($upcomingEvents->count())
                            <div class="text-xs font-bold text-green-600 mt-0.5">
                                {{ $upcomingEvents->count() }} {{ $ar ? 'قادمة' : 'upcoming' }}
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Certificates -->
                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5 flex items-center gap-4">
                    <div class="w-12 h-12 rounded-xl bg-yemdat-gold/15 text-yemdat-brown flex items-center justify-center shrink-0">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                    </div>
                    <div>
                        <div class="text-2xl font-extrabold text-gray-900 leading-none">{{ $certCount }}</div>
                        <div class="text-sm text-gray-500 mt-1">{{ $ar ? 'شهادة إتمام' : 'Certificates earned' }}</div>
                    </div>
                </div>

                <!-- Profile completeness -->
                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
                    <div class="flex items-center justify-between mb-2">
                        <div class="text-sm text-gray-500">{{ $ar ? 'اكتمال الملف' : 'Profile completeness' }}</div>
                        <div class="text-2xl font-extrabold text-gray-900 leading-none">{{ $completion['percent'] }}%</div>
                    </div>
                    <div class="w-full h-2 rounded-full bg-gray-100 overflow-hidden">
                        <div class="h-full rounded-full bg-gradient-to-r from-yemdat-gold to-yemdat-orange transition-all" style="width: {{ $completion['percent'] }}%"></div>
                    </div>
                    <div class="text-xs text-gray-400 mt-2">
                        {{ $completion['completed'] }} / {{ $completion['total'] }} {{ $ar ? 'حقل مكتمل' : 'fields complete' }}
                    </div>
                </div>
            </div>

            <!-- Complete-your-profile nudge -->
            @if($completion['percent'] < 100)
                <div class="bg-gradient-to-br from-yemdat-beige to-white rounded-2xl border border-yemdat-gold/30 shadow-sm p-6 mb-6">
                    <div class="flex flex-col sm:flex-row sm:items-center gap-4 justify-between">
                        <div>
                            <h3 class="font-bold text-gray-900 mb-1">{{ $ar ? 'أكمل ملفك الشخصي' : 'Complete your profile' }}</h3>
                            <p class="text-sm text-gray-600 mb-3">{{ $ar ? 'أضف المعلومات التالية ليكتمل ملفك ويظهر بشكل أفضل:' : 'Add the following to round out your profile:' }}</p>
                            <div class="flex flex-wrap gap-2">
                                @foreach($completion['missing'] as $key)
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full bg-white border border-yemdat-gold/40 text-xs font-semibold text-yemdat-brown">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                                        {{ $fieldLabels[$key] ?? $key }}
                                    </span>
                                @endforeach
                            </div>
                        </div>
                        <x-ui.button variant="accent" :href="route('profile.edit')" class="shrink-0">
                            {{ $ar ? 'إكمال الملف' : 'Complete profile' }}
                        </x-ui.button>
                    </div>
                </div>
            @endif

            <!-- Next upcoming event highlight -->
            @if($nextEvent)
                <div class="relative overflow-hidden rounded-2xl bg-gradient-to-r from-yemdat-brown to-yemdat-dark text-white shadow-sm mb-8 p-6">
                    <div class="flex flex-col md:flex-row md:items-center gap-5 justify-between">
                        <div class="flex items-start gap-4">
                            <div class="w-12 h-12 rounded-xl bg-white/10 flex items-center justify-center shrink-0">
                                <svg class="w-6 h-6 text-yemdat-gold" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                            </div>
                            <div>
                                <div class="text-xs uppercase tracking-wider text-yemdat-gold font-bold mb-1">{{ $ar ? 'فعاليتك القادمة' : 'Your next event' }}</div>
                                <h3 class="text-lg font-bold leading-snug">
                                    <a href="{{ route('events.show', $nextEvent->slug) }}" class="hover:text-yemdat-gold transition">{{ $nextEvent->title }}</a>
                                </h3>
                                <div class="flex flex-wrap items-center gap-x-4 gap-y-1 mt-2 text-sm text-white/80">
                                    <span class="inline-flex items-center gap-1.5">
                                        <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                        <span dir="ltr">{{ $nextEvent->start_date->format('M d, Y · h:i A') }}</span>
                                    </span>
                                    <span class="inline-flex items-center gap-1.5">
                                        <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                                        {{ $nextEvent->location ?? ($ar ? 'عبر الإنترنت' : 'Online') }}
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="flex items-center gap-3 shrink-0">
                            <span class="inline-flex items-center px-3 py-1 rounded-full bg-yemdat-gold text-yemdat-brown text-sm font-bold whitespace-nowrap">{{ $nextEvent->remaining_time }}</span>
                            @if($nextEvent->join_url)
                                <a href="{{ $nextEvent->join_url }}" target="_blank" class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-white text-yemdat-brown text-sm font-bold hover:bg-yemdat-gold transition whitespace-nowrap">
                                    {{ $ar ? 'رابط الحضور' : 'Join link' }}
                                    <svg class="w-4 h-4 rtl:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                                </a>
                            @endif
                        </div>
                    </div>
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
                        <x-ui.badge class="mb-4 uppercase tracking-wider">
                            {{ $member->membershipTier ? (app()->getLocale() == 'ar' ? $member->membershipTier->name_ar : $member->membershipTier->name_en) : 'Member' }}
                        </x-ui.badge>
                        
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
                
                @if($upcomingEvents->isEmpty() && $pastEvents->isEmpty())
                    <div class="p-8 text-center text-gray-500">
                        {{ $ar ? 'لم تقم بالتسجيل في أي فعالية بعد.' : 'You have not registered for any events yet.' }}
                        <div class="mt-4">
                            <x-ui.button variant="accent" :href="route('events.index')">
                                {{ $ar ? 'استعرض الفعاليات' : 'Browse Events' }}
                            </x-ui.button>
                        </div>
                    </div>
                @else
                    @if($upcomingEvents->isNotEmpty())
                        <div class="p-6 bg-gray-50/50">
                            <h4 class="text-sm font-bold uppercase tracking-wider text-gray-500 mb-4 flex items-center gap-2">
                                <span class="w-2 h-2 rounded-full bg-green-500"></span>
                                {{ $ar ? 'القادمة' : 'Upcoming' }}
                                <span class="text-gray-400 font-semibold">({{ $upcomingEvents->count() }})</span>
                            </h4>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                @foreach($upcomingEvents as $event)
                                    <x-profile.event-card :event="$event" :certificate="$certificatesByEvent->get($event->id)" />
                                @endforeach
                            </div>
                        </div>
                    @endif

                    @if($pastEvents->isNotEmpty())
                        <div class="p-6 bg-gray-50/50 @if($upcomingEvents->isNotEmpty()) border-t border-gray-100 @endif">
                            <h4 class="text-sm font-bold uppercase tracking-wider text-gray-500 mb-4 flex items-center gap-2">
                                <span class="w-2 h-2 rounded-full bg-gray-400"></span>
                                {{ $ar ? 'السابقة' : 'Past' }}
                                <span class="text-gray-400 font-semibold">({{ $pastEvents->count() }})</span>
                            </h4>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                @foreach($pastEvents as $event)
                                    <x-profile.event-card :event="$event" :certificate="$certificatesByEvent->get($event->id)" />
                                @endforeach
                            </div>
                        </div>
                    @endif
                @endif
            </div>

            <!-- Sent Messages -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden mt-8">
                <div class="p-8 border-b border-gray-100 flex items-center gap-3">
                    <svg class="w-6 h-6 text-yemdat-brown" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                    <div>
                        <h3 class="text-lg font-bold text-yemdat-brown mb-1">
                            {{ app()->getLocale() == 'ar' ? 'الرسائل المرسلة' : 'My Sent Messages' }}
                        </h3>
                        <p class="text-gray-500 text-sm">
                            {{ app()->getLocale() == 'ar' ? 'سجل رسائلك إلى إدارة الموقع عبر اتصل بنا.' : 'A record of your messages sent via Contact Us.' }}
                        </p>
                    </div>
                </div>
                
                @if($member->contacts && $member->contacts->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-start text-xs font-bold text-gray-500 uppercase tracking-wider">{{ app()->getLocale() == 'ar' ? 'الموضوع' : 'Subject' }}</th>
                                    <th class="px-6 py-3 text-start text-xs font-bold text-gray-500 uppercase tracking-wider">{{ app()->getLocale() == 'ar' ? 'تاريخ الإرسال' : 'Date Sent' }}</th>
                                    <th class="px-6 py-3 text-start text-xs font-bold text-gray-500 uppercase tracking-wider">{{ app()->getLocale() == 'ar' ? 'نص الرسالة' : 'Message' }}</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($member->contacts()->orderBy('created_at', 'desc')->get() as $message)
                                <tr class="hover:bg-gray-50 transition">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-gray-900">
                                        {{ Str::limit($message->subject, 40) }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        <div dir="ltr" class="text-start inline-block">
                                            {{ $message->created_at->format('M d, Y h:i A') }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-500">
                                        {{ Str::limit($message->message, 80) }}
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="p-8 text-center text-gray-500">
                        {{ app()->getLocale() == 'ar' ? 'لم تقم بإرسال أي رسائل تواصل بعد.' : 'You have not sent any contact messages yet.' }}
                        <div class="mt-4">
                            <x-ui.button variant="outline" :href="route('contact')">
                                {{ app()->getLocale() == 'ar' ? 'تواصل معنا' : 'Contact Us' }}
                            </x-ui.button>
                        </div>
                    </div>
                @endif
            </div>

            <!-- Email Preferences -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 mt-8">
                <h3 class="text-lg font-bold text-yemdat-brown mb-1">
                    {{ app()->getLocale() == 'ar' ? 'تفضيلات البريد الإلكتروني' : 'Email Preferences' }}
                </h3>
                @if($member->unsubscribed_at)
                    <p class="text-sm text-gray-500 mb-4">
                        {{ app()->getLocale() == 'ar' ? 'أنت غير مشترك حالياً في رسائل البث الإخبارية.' : 'You are currently unsubscribed from broadcast emails.' }}
                    </p>
                    <form action="{{ route('member.email-preferences') }}" method="POST">
                        @csrf
                        <input type="hidden" name="action" value="resubscribe">
                        <x-ui.button type="submit">
                            {{ app()->getLocale() == 'ar' ? 'إعادة الاشتراك' : 'Re-subscribe' }}
                        </x-ui.button>
                    </form>
                @else
                    <p class="text-sm text-gray-500">
                        {{ app()->getLocale() == 'ar' ? 'أنت مشترك في رسائل البث الإخبارية.' : 'You are subscribed to broadcast emails.' }}
                    </p>
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
