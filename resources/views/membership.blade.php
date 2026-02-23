<x-app-layout>
    <div class="py-20 bg-white relative">
        
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col gap-y-32">
            <!-- Header -->
            <div class="text-center">
                <h1 class="text-3xl md:text-5xl font-bold text-yemdat-brown mb-6">
                    {{ __('membership.title') }}
                </h1>
                <p class="text-gray-600 text-xl mb-10 max-w-3xl mx-auto">
                    {{ __('membership.subtitle') }}
                </p>
                <a href="{{ route('register') }}" class="inline-block bg-yemdat-brown text-white px-10 py-4 rounded-md font-medium text-lg hover:bg-yemdat-dark transition shadow-lg hover:shadow-xl transform hover:-translate-y-1">
                    {{ __('membership.register_now') }}
                </a>
            </div>

            <!-- Membership Types -->
            <div>
                <h2 class="text-3xl font-bold text-yemdat-brown text-center mb-16">{{ __('membership.types_title') }}</h2>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-10">
                    @forelse ($membershipTiers as $tier)
                        <div class="bg-white p-10 rounded-xl border border-gray-200 shadow-sm hover:shadow-md transition flex flex-col items-start h-full relative">
                            <div class="w-16 h-16 bg-yemdat-beige rounded-2xl flex items-center justify-center mb-8 text-yemdat-gold">
                                <!-- Icons based on slug -->
                                @if($tier->slug == 'intern')
                                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                                @elseif($tier->slug == 'expert')
                                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"></path></svg>
                                @elseif($tier->slug == 'corporate')
                                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                                @else
                                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"></path></svg>
                                @endif
                            </div>
                            <h3 class="font-bold text-2xl text-yemdat-brown mb-4">{{ $tier->name }}</h3>
                            <p class="text-gray-600 text-base leading-relaxed mb-4">
                                {{ $tier->description }}
                            </p>
                            
                            <!-- Features List -->
                            @if($tier->features && count($tier->features) > 0)
                                <ul class="mb-8 space-y-2">
                                    @foreach($tier->features as $feature)
                                        @if(!empty($feature))
                                        <li class="flex items-start text-gray-600 text-sm">
                                            <svg class="w-4 h-4 text-yemdat-gold mr-2 mt-1 shrink-0 rtl:ml-2 rtl:mr-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                            {{ $feature }}
                                        </li>
                                        @endif
                                    @endforeach
                                </ul>
                            @endif

                            <a href="{{ route('register') }}" class="mt-auto block text-center w-full py-3 rounded-lg border-2 border-yemdat-brown text-yemdat-brown font-bold hover:bg-yemdat-brown hover:text-white transition">
                                {{ __('membership.register_now') }}
                            </a>
                        </div>
                    @empty
                        <!-- Fallback content if no tiers found (e.g. before migration) -->
                        <div class="col-span-3 text-center py-10">
                            <p class="text-xl text-gray-500">Membership plans are loading...</p>
                        </div>
                    @endforelse
                </div>
            </div>

            <!-- Benefits -->
            <div>
                <h2 class="text-3xl font-bold text-yemdat-brown text-center mb-16">{{ __('membership.benefits_title') }}</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <!-- Benefit 1 -->
                    <div class="bg-white p-8 rounded-xl border border-gray-100 shadow-sm flex items-center gap-6 ltr:border-l-4 rtl:border-r-4 border-yemdat-gold hover:shadow-md transition">
                        <div class="w-12 h-12 bg-yemdat-beige rounded-lg flex items-center justify-center text-yemdat-gold shrink-0">
                             <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                        </div>
                        <span class="text-gray-700 font-medium text-lg">{{ __('membership.benefit_1') }}</span>
                    </div>

                    <!-- Benefit 2 -->
                    <div class="bg-white p-8 rounded-xl border border-gray-100 shadow-sm flex items-center gap-6 ltr:border-l-4 rtl:border-r-4 border-yemdat-gold hover:shadow-md transition">
                        <div class="w-12 h-12 bg-yemdat-beige rounded-lg flex items-center justify-center text-yemdat-gold shrink-0">
                             <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                        </div>
                        <span class="text-gray-700 font-medium text-lg">{{ __('membership.benefit_2') }}</span>
                    </div>

                     <!-- Benefit 3 -->
                    <div class="bg-white p-8 rounded-xl border border-gray-100 shadow-sm flex items-center gap-6 ltr:border-l-4 rtl:border-r-4 border-yemdat-gold hover:shadow-md transition">
                        <div class="w-12 h-12 bg-yemdat-beige rounded-lg flex items-center justify-center text-yemdat-gold shrink-0">
                             <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"></path></svg>
                        </div>
                        <span class="text-gray-700 font-medium text-lg">{{ __('membership.benefit_3') }}</span>
                    </div>

                     <!-- Benefit 4 -->
                    <div class="bg-white p-8 rounded-xl border border-gray-100 shadow-sm flex items-center gap-6 ltr:border-l-4 rtl:border-r-4 border-yemdat-gold hover:shadow-md transition">
                        <div class="w-12 h-12 bg-yemdat-beige rounded-lg flex items-center justify-center text-yemdat-gold shrink-0">
                             <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                        </div>
                        <span class="text-gray-700 font-medium text-lg">{{ __('membership.benefit_4') }}</span>
                    </div>
                </div>
            </div>

            <!-- Footer Note -->
            <div class="max-w-3xl mx-auto w-full space-y-6">
                <div class="border border-yemdat-gold rounded-xl p-6 flex items-center justify-center gap-4 bg-yemdat-beige/20 shadow-sm">
                     <div class="rounded-full border-2 border-yemdat-gold p-1 shrink-0">
                        <svg class="w-5 h-5 text-yemdat-gold" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                     </div>
                     <span class="text-yemdat-brown font-semibold text-lg">{{ __('membership.free_note_1') }}</span>
                </div>
                 <div class="border border-yemdat-gold rounded-xl p-6 flex items-center justify-center gap-4 bg-yemdat-beige/20 shadow-sm">
                     <div class="rounded-full border-2 border-yemdat-gold p-1 shrink-0">
                        <svg class="w-5 h-5 text-yemdat-gold" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                     </div>
                     <span class="text-yemdat-brown font-semibold text-lg">{{ __('membership.free_note_2') }}</span>
                </div>
            </div>
        </div>

        </div>
    </div>
</x-app-layout>
