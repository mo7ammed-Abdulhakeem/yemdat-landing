<x-app-layout>
    <div x-data="{ 
        showModal: {{ $errors->any() ? 'true' : 'false' }}, 
        showSuccessModal: {{ session('success') ? 'true' : 'false' }},
        plan: '{{ old('membership_type') }}',
        openModal(selectedPlan) {
            this.plan = selectedPlan;
            this.showModal = true;
        }
    }" class="py-20 bg-white relative">
        
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col gap-y-32">
            <!-- Header -->
            <div class="text-center">
                <h1 class="text-3xl md:text-5xl font-bold text-yemdat-brown mb-6">
                    {{ __('membership.title') }}
                </h1>
                <p class="text-gray-600 text-xl mb-10 max-w-3xl mx-auto">
                    {{ __('membership.subtitle') }}
                </p>
                <button @click="openModal('intern')" class="inline-block bg-yemdat-brown text-white px-10 py-4 rounded-md font-medium text-lg hover:bg-yemdat-dark transition shadow-lg hover:shadow-xl transform hover:-translate-y-1">
                    {{ __('membership.register_now') }}
                </button>
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

                            <button @click="openModal('{{ $tier->slug }}')" class="mt-auto w-full py-3 rounded-lg border-2 border-yemdat-brown text-yemdat-brown font-bold hover:bg-yemdat-brown hover:text-white transition">
                                {{ __('membership.register_now') }}
                            </button>
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

        <!-- Success Modal -->
        <div 
            x-show="showSuccessModal" 
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 scale-90"
            x-transition:enter-end="opacity-100 scale-100"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100 scale-100"
            x-transition:leave-end="opacity-0 scale-90"
            class="fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4"
            style="display: none;"
        >
            <div class="bg-green-500 rounded-xl shadow-2xl w-full max-w-md p-8 text-center text-white relative">
                <button @click="showSuccessModal = false" class="absolute top-4 right-4 text-white/80 hover:text-white transition">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
                
                <div class="w-16 h-16 bg-white/20 rounded-full flex items-center justify-center mx-auto mb-6">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                </div>

                <h3 class="text-2xl font-bold mb-2">{{ __('membership.success_title') ?? 'Success!' }}</h3>
                <p class="text-white/90 text-lg mb-8">{{ session('success') }}</p>
                
                <button @click="showSuccessModal = false" class="bg-white text-green-600 px-8 py-3 rounded-lg font-bold hover:bg-green-50 transition shadow-sm w-full">
                    {{ __('membership.btn_close') ?? 'Close' }}
                </button>
            </div>
        </div>

        <!-- Registration Modal -->
        <div 
            x-show="showModal" 
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            class="fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4"
            style="display: none;"
        >
            <div 
                @click.away="showModal = false"
                class="bg-white rounded-xl shadow-2xl w-full max-w-2xl overflow-hidden"
            >
                <!-- Modal Header -->
                <div class="flex justify-between items-center p-6 border-b border-gray-100">
                    <div>
                        <h3 class="text-2xl font-bold text-yemdat-brown">{{ __('membership.form_title') }}</h3>
                        <p class="text-gray-500 text-sm mt-1">{{ __('membership.form_subtitle') }}</p>
                    </div>
                    <button @click="showModal = false" class="text-gray-400 hover:text-gray-600 transition">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>

                <!-- Modal Body -->
                <form action="{{ route('membership.store') }}" method="POST">
                    @csrf
                    <div class="p-8 space-y-6 max-h-[70vh] overflow-y-auto" x-data="{ specialty: '{{ old('specialty') }}' }">
                        
                        <!-- Display Validation Errors -->
                        @if ($errors->any())
                            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <!-- Full Name -->
                        <div>
                            <label class="block text-sm font-medium text-yemdat-brown mb-2">{{ __('membership.label_fullname') }}</label>
                            <input type="text" name="full_name" value="{{ old('full_name') }}" required class="w-full rounded-lg border-gray-300 bg-gray-50 focus:border-yemdat-gold focus:ring-yemdat-gold focus:bg-white transition" placeholder="{{ __('membership.placeholder_fullname') }}">
                        </div>

                        <!-- Email -->
                        <div>
                                <label class="block text-sm font-medium text-yemdat-brown mb-2">{{ __('membership.label_email') }}</label>
                                <input type="email" name="email" value="{{ old('email') }}" required class="w-full rounded-lg border-gray-300 bg-gray-50 focus:border-yemdat-gold focus:ring-yemdat-gold focus:bg-white transition rtl:text-right" placeholder="{{ __('membership.placeholder_email') }}">
                        </div>

                        <!-- Phone -->
                        <div>
                            <label for="phone_number" class="block text-sm font-medium text-gray-700 mb-1">
                                {{ __('membership.label_phone') }} <span class="text-red-500">*</span>
                            </label>
                            <div class="flex gap-2" x-data="{ isMobile: window.innerWidth < 640 }" @resize.window="isMobile = window.innerWidth < 640">
                                <!-- Desktop: Code + Country -->
                                <select name="phone_code" x-show="!isMobile" :disabled="isMobile" class="w-1/3 rounded-lg border-gray-300 shadow-sm focus:border-yemdat-gold focus:ring-yemdat-gold" required>
                                    <option value="" disabled selected>Code</option>
                                    @foreach($phoneCodes as $code => $countryName)
                                        <option value="{{ $code }}" {{ old('phone_code') == $code ? 'selected' : '' }}>{{ $code }} ({{ $countryName }})</option>
                                    @endforeach
                                </select>

                                <!-- Mobile: Code Only -->
                                <select name="phone_code" x-show="isMobile" :disabled="!isMobile" class="w-24 rounded-lg border-gray-300 shadow-sm focus:border-yemdat-gold focus:ring-yemdat-gold" style="display: none;" required>
                                    <option value="" disabled selected>Code</option>
                                    @foreach($phoneCodes as $code => $countryName)
                                        <option value="{{ $code }}" {{ old('phone_code') == $code ? 'selected' : '' }}>{{ $code }}</option>
                                    @endforeach
                                </select>

                                <input type="tel" name="phone_number" id="phone_number" required class="flex-1 rounded-lg border-gray-300 shadow-sm focus:border-yemdat-gold focus:ring-yemdat-gold" placeholder="{{ __('membership.placeholder_phone') }}" value="{{ old('phone_number') }}">
                            </div>
                        </div>

                         <!-- Education Level -->
                         <div>
                            <label for="education_level" class="block text-sm font-medium text-gray-700 mb-1">
                                {{ __('membership.education_level') }}
                            </label>
                            <select name="education_level" id="education_level" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-yemdat-gold focus:ring-yemdat-gold">
                                <option value="" disabled selected>{{ __('membership.education_level') }}</option>
                                <option value="High School" {{ old('education_level') == 'High School' ? 'selected' : '' }}>{{ __('membership.edu_high_school') }}</option>
                                <option value="Bachelor's Degree" {{ old('education_level') == "Bachelor's Degree" ? 'selected' : '' }}>{{ __('membership.edu_bachelor') }}</option>
                                <option value="Master's Degree" {{ old('education_level') == "Master's Degree" ? 'selected' : '' }}>{{ __('membership.edu_master') }}</option>
                                <option value="PhD" {{ old('education_level') == 'PhD' ? 'selected' : '' }}>{{ __('membership.edu_phd') }}</option>
                                <option value="Other" {{ old('education_level') == 'Other' ? 'selected' : '' }}>{{ __('membership.edu_other') }}</option>
                            </select>
                        </div>

                        <!-- Country -->
                            <div>
                            <label class="block text-sm font-medium text-yemdat-brown mb-2">{{ __('membership.label_country') }}</label>
                            <select name="country" required class="w-full rounded-lg border-gray-300 bg-gray-50 focus:border-yemdat-gold focus:ring-yemdat-gold focus:bg-white transition">
                                <option value="">{{ __('membership.select_country') }}</option>
                                @foreach($countries as $country)
                                    <option value="{{ $country }}" {{ old('country') == $country ? 'selected' : '' }}>{{ $country }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Speciality -->
                        <div>
                                <label class="block text-sm font-medium text-yemdat-brown mb-2">{{ __('membership.label_speciality') }}</label>
                                <select name="specialty" x-model="specialty" required class="w-full rounded-lg border-gray-300 bg-gray-50 focus:border-yemdat-gold focus:ring-yemdat-gold focus:bg-white transition">
                                <option value="">{{ __('membership.select_speciality') }}</option>
                                <option value="data_mgmt">{{ __('membership.spec_data_mgmt') }}</option>
                                <option value="governance">{{ __('membership.spec_governance') }}</option>
                                <option value="ai">{{ __('membership.spec_ai') }}</option>
                                    <option value="analytics">{{ __('membership.spec_analytics') }}</option>
                                <option value="other">{{ __('membership.spec_other') }}</option>
                            </select>
                        </div>

                        <!-- Other Speciality (Hidden by default) -->
                        <div x-show="specialty === 'other'" x-transition>
                            <label class="block text-sm font-medium text-yemdat-brown mb-2">{{ __('membership.label_speciality_other') ?? 'Please specify' }}</label>
                            <input type="text" name="specialty_other" value="{{ old('specialty_other') }}" class="w-full rounded-lg border-gray-300 bg-gray-50 focus:border-yemdat-gold focus:ring-yemdat-gold focus:bg-white transition" placeholder="Your Speciality">
                        </div>

                        <!-- Membership Type -->
                            <div>
                            <label class="block text-sm font-medium text-yemdat-brown mb-2">{{ __('membership.label_membership_type') }}</label>
                                <select name="membership_type" x-model="plan" required class="w-full rounded-lg border-gray-300 bg-gray-50 focus:border-yemdat-gold focus:ring-yemdat-gold focus:bg-white transition">
                                <option value="">{{ __('membership.select_membership_type') }}</option>
                                @forelse ($membershipTiers as $tier)
                                    <option value="{{ $tier->slug }}">{{ $tier->name }}</option>
                                @empty
                                    <option value="intern" x-text="'Intern'"></option>
                                @endforelse
                            </select>
                        </div>

                    </div>

                    <!-- Footer -->
                    <div class="p-6 border-t border-gray-100 flex justify-end gap-3 bg-gray-50">
                        <button type="button" @click="showModal = false" class="px-6 py-2 rounded-lg border border-gray-300 text-gray-700 bg-white hover:bg-gray-50 transition font-medium">
                            {{ __('membership.btn_cancel') }}
                        </button>
                        <button type="submit" class="px-8 py-2 rounded-lg bg-yemdat-brown text-white hover:bg-yemdat-dark transition font-medium shadow-sm">
                            {{ __('membership.btn_submit') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
