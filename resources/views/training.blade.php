<x-app-layout>
    <div class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col gap-y-32">
            
            <!-- Header -->
            <div class="text-center max-w-3xl mx-auto">
                <h1 class="text-3xl md:text-4xl font-bold text-yemdat-brown mb-6">
                    {{ __('training.title') }}
                </h1>
                <p class="text-xl text-gray-600">
                    {{ __('training.subtitle') }}
                </p>
            </div>

            <!-- Categories Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
                 <!-- Item 1 -->
                <div class="bg-white p-8 rounded-xl border border-gray-200 shadow-sm flex flex-col items-center text-center gap-6 hover:shadow-md transition">
                     <div class="w-16 h-16 bg-yemdat-beige rounded-xl flex items-center justify-center text-yemdat-gold shrink-0">
                         <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4m0 5c0 2.21-3.582 4-8 4s-8-1.79-8-4"></path></svg>
                    </div>
                    <span class="text-yemdat-brown font-medium text-lg">{{ __('training.cat_1') }}</span>
                </div>

                <!-- Item 2 -->
                <div class="bg-white p-8 rounded-xl border border-gray-200 shadow-sm flex flex-col items-center text-center gap-6 hover:shadow-md transition">
                     <div class="w-16 h-16 bg-yemdat-beige rounded-xl flex items-center justify-center text-yemdat-gold shrink-0">
                         <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                    </div>
                    <span class="text-yemdat-brown font-medium text-lg">{{ __('training.cat_2') }}</span>
                </div>

                <!-- Item 3 -->
                <div class="bg-white p-8 rounded-xl border border-gray-200 shadow-sm flex flex-col items-center text-center gap-6 hover:shadow-md transition">
                     <div class="w-16 h-16 bg-yemdat-beige rounded-xl flex items-center justify-center text-yemdat-gold shrink-0">
                         <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"></path></svg>
                    </div>
                    <span class="text-yemdat-brown font-medium text-lg">{{ __('training.cat_3') }}</span>
                </div>

                <!-- Item 4 -->
                 <div class="bg-white p-8 rounded-xl border border-gray-200 shadow-sm flex flex-col items-center text-center gap-6 hover:shadow-md transition">
                     <div class="w-16 h-16 bg-yemdat-beige rounded-xl flex items-center justify-center text-yemdat-gold shrink-0">
                         <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                    </div>
                    <span class="text-yemdat-brown font-medium text-lg">{{ __('training.cat_4') }}</span>
                </div>
            </div>

            <!-- Upcoming Events Section -->
            <div>
                <h2 class="text-2xl font-bold text-yemdat-brown text-center mb-16">{{ __('training.upcoming_title') }}</h2>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                     <!-- Event 1 -->
                    <div class="bg-white p-8 rounded-xl border border-gray-200 shadow-sm flex flex-col gap-6 hover:shadow-md transition">
                        <div class="w-12 h-12 bg-yemdat-beige rounded-lg flex items-center justify-center text-yemdat-gold shrink-0">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4m0 5c0 2.21-3.582 4-8 4s-8-1.79-8-4"></path></svg>
                        </div>
                        <div>
                            <h3 class="text-lg font-bold text-yemdat-brown mb-2">{{ __('training.event_1_title') }}</h3>
                            <div class="flex items-center gap-2 text-gray-500 text-sm mb-4">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                {{ __('training.event_1_date') }}
                            </div>
                            <p class="text-gray-600 text-sm leading-relaxed">{{ __('training.event_1_desc') }}</p>
                        </div>
                    </div>

                    <!-- Event 2 -->
                    <div class="bg-white p-8 rounded-xl border border-gray-200 shadow-sm flex flex-col gap-6 hover:shadow-md transition">
                       <div class="w-12 h-12 bg-yemdat-beige rounded-lg flex items-center justify-center text-yemdat-gold shrink-0">
                           <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                       </div>
                       <div>
                           <h3 class="text-lg font-bold text-yemdat-brown mb-2">{{ __('training.event_2_title') }}</h3>
                           <div class="flex items-center gap-2 text-gray-500 text-sm mb-4">
                               <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                               {{ __('training.event_2_date') }}
                           </div>
                           <p class="text-gray-600 text-sm leading-relaxed">{{ __('training.event_2_desc') }}</p>
                       </div>
                   </div>

                   <!-- Event 3 -->
                   <div class="bg-white p-8 rounded-xl border border-gray-200 shadow-sm flex flex-col gap-6 hover:shadow-md transition">
                       <div class="w-12 h-12 bg-yemdat-beige rounded-lg flex items-center justify-center text-yemdat-gold shrink-0">
                           <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"></path></svg>
                       </div>
                       <div>
                           <h3 class="text-lg font-bold text-yemdat-brown mb-2">{{ __('training.event_3_title') }}</h3>
                           <div class="flex items-center gap-2 text-gray-500 text-sm mb-4">
                               <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                               {{ __('training.event_3_date') }}
                           </div>
                           <p class="text-gray-600 text-sm leading-relaxed">{{ __('training.event_3_desc') }}</p>
                       </div>
                   </div>
                </div>
            </div>

             <!-- Notification Box -->
             <div class="max-w-4xl mx-auto w-full">
                 <div class="bg-yemdat-offwhite border border-gray-100 rounded-2xl p-10 flex flex-col items-center text-center gap-6">
                    <div class="text-yemdat-gold">
                        <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                    <div>
                        <h3 class="text-xl font-bold text-yemdat-brown mb-2">{{ __('training.notice_title') }}</h3>
                        <p class="text-gray-600">
                             {{ __('training.notice_text') }}
                        </p>
                    </div>
                 </div>
            </div>

        </div>
    </div>
</x-app-layout>
