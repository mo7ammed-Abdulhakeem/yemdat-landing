<x-app-layout>
    <div class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col gap-y-16">
            
            <!-- Header -->
            <div class="text-center max-w-3xl mx-auto">
                 <div class="w-16 h-16 bg-white rounded-xl flex items-center justify-center text-yemdat-gold mx-auto mb-6">
                     <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"></path></svg>
                </div>
                <h1 class="text-3xl md:text-4xl font-bold text-yemdat-brown mb-6">
                    {{ __('contact.title') }}
                </h1>
                <p class="text-xl text-gray-600">
                    {{ __('contact.subtitle_1') }}
                    <br>
                    {{ __('contact.subtitle_2') }}
                </p>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-start">
                
                <!-- Contact Form -->
                <div class="lg:col-span-2 bg-white p-8 md:p-10 rounded-2xl border border-gray-200 shadow-sm" x-data="{
                    showSuccess: {{ session('success') ? 'true' : 'false' }}
                }">
                    <h2 class="text-2xl font-bold text-yemdat-brown mb-2">{{ __('contact.form_title') }}</h2>
                    <p class="text-gray-500 mb-8">{{ __('contact.form_desc') }}</p>

                    <!-- Success Message (New Floating Modal) -->
                    @if(session('success'))
                        <x-alert-modal type="success" :message="session('success')" />
                    @endif

                    <form action="{{ route('contact.store') }}" method="POST" class="flex flex-col gap-6">
                        @csrf

                        <!-- Validation Errors (New Floating Modal) -->
                        @if ($errors->any())
                            <x-alert-modal type="error" :message="$errors->first()" />
                        @endif

                        <x-ui.input
                            name="name"
                            :label="__('contact.label_name')"
                            :value="auth()->guard('member')->user()->full_name ?? ''"
                            :required="true"
                            :placeholder="__('contact.placeholder_name')"
                        />

                        <x-ui.input
                            name="email"
                            type="email"
                            :label="__('contact.label_email')"
                            :value="auth()->guard('member')->user()->email ?? ''"
                            :required="true"
                            :placeholder="__('contact.placeholder_email')"
                        />

                        <x-ui.input
                            name="phone_number"
                            :label="__('contact.label_phone').' ('.(app()->getLocale() == 'ar' ? 'إختياري' : 'Optional').')'"
                            :value="auth()->guard('member')->user()->phone_number ?? ''"
                            dir="ltr"
                            :placeholder="app()->getLocale() == 'ar' ? 'مثال: +966500000000' : 'e.g. +1234567890'"
                        />

                        <x-ui.input
                            name="subject"
                            :label="__('contact.label_subject')"
                            :required="true"
                            :placeholder="__('contact.placeholder_subject')"
                        />

                        <x-ui.textarea
                            name="message"
                            :label="__('contact.label_message')"
                            :required="true"
                            :rows="4"
                            :placeholder="__('contact.placeholder_message')"
                        />

                        <x-ui.button type="submit" class="w-full">
                            <svg class="w-5 h-5 rtl:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path></svg>
                            {{ __('contact.btn_send') }}
                        </x-ui.button>
                    </form>
                </div>

                <!-- Sidebar -->
                <div class="flex flex-col gap-8">
                    <!-- Social Links -->
                    <div class="bg-white p-8 rounded-2xl border border-yemdat-gold/30 shadow-sm">
                        <h3 class="text-xl font-bold text-yemdat-brown mb-2">{{ __('contact.follow_title') }}</h3>
                        <p class="text-gray-500 mb-6">{{ __('contact.follow_desc') }}</p>
                        
                        <div class="flex flex-col gap-3">
                            <!-- Facebook -->
                            @if(isset($settings['site_facebook']) && $settings['site_facebook'])
                            <a href="{{ $settings['site_facebook'] }}" target="_blank" class="flex items-center gap-3 w-full p-3 rounded-lg border border-gray-200 hover:bg-gray-50 transition text-gray-700 font-medium">
                                <span class="w-8 h-8 flex items-center justify-center">
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
                                </span>
                                {{ __('contact.social_facebook') }}
                            </a>
                            @endif
                            
                            <!-- Twitter -->
                            @if(isset($settings['site_twitter']) && $settings['site_twitter'])
                             <a href="{{ $settings['site_twitter'] }}" target="_blank" class="flex items-center gap-3 w-full p-3 rounded-lg border border-gray-200 hover:bg-gray-50 transition text-gray-700 font-medium">
                                <span class="w-8 h-8 flex items-center justify-center">
                                   <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M23.953 4.57a10 10 0 01-2.825.775 4.958 4.958 0 002.163-2.723c-.951.555-2.005.959-3.127 1.184a4.92 4.92 0 00-8.384 4.482C7.69 8.095 4.067 6.13 1.64 3.162a4.822 4.822 0 00-.666 2.475c0 1.71.87 3.213 2.188 4.096a4.904 4.904 0 01-2.228-.616v.06a4.923 4.923 0 003.946 4.827 4.996 4.996 0 01-2.212.085 4.936 4.936 0 004.604 3.417 9.867 9.867 0 01-6.102 2.105c-.39 0-.779-.023-1.17-.067a13.995 13.995 0 007.557 2.209c9.053 0 13.998-7.496 13.998-13.985 0-.21 0-.42-.015-.63A9.935 9.935 0 0024 4.59z"/></svg>
                                </span>
                                {{ __('contact.social_twitter') }}
                            </a>
                            @endif

                            <!-- LinkedIn -->
                            @if(isset($settings['site_linkedin']) && $settings['site_linkedin'])
                             <a href="{{ $settings['site_linkedin'] }}" target="_blank" class="flex items-center gap-3 w-full p-3 rounded-lg border border-gray-200 hover:bg-gray-50 transition text-gray-700 font-medium">
                                <span class="w-8 h-8 flex items-center justify-center">
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/></svg>
                                </span>
                                {{ __('contact.social_linkedin') }}
                            </a>
                            @endif
                            
                            <!-- Instagram -->
                            @if(isset($settings['site_instagram']) && $settings['site_instagram'])
                             <a href="{{ $settings['site_instagram'] }}" target="_blank" class="flex items-center gap-3 w-full p-3 rounded-lg border border-gray-200 hover:bg-gray-50 transition text-gray-700 font-medium">
                                <span class="w-8 h-8 flex items-center justify-center">
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.069-4.85.069-3.204 0-3.584-.012-4.849-.069-3.225-.149-4.771-1.664-4.919-4.919-.058-1.265-.069-1.644-.069-4.849 0-3.204.012-3.584.069-4.849.149-3.228 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/></svg>
                                </span>
                                {{ __('contact.social_instagram') ?? 'Instagram' }}
                            </a>
                            @endif

                            <!-- WhatsApp -->
                            @if(isset($settings['site_whatsapp']) && $settings['site_whatsapp'])
                             <a href="{{ $settings['site_whatsapp'] }}" target="_blank" class="flex items-center gap-3 w-full p-3 rounded-lg border border-gray-200 hover:bg-gray-50 transition text-gray-700 font-medium">
                                <span class="w-8 h-8 flex items-center justify-center">
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981zm11.387-5.464c-.074-.124-.272-.198-.57-.347-.297-.149-1.758-.868-2.031-.967-.272-.099-.47-.149-.669.149-.198.297-.768.967-.941 1.165-.173.198-.347.223-.644.074-.297-.149-1.255-.462-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.521.151-.172.2-.296.3-.495.099-.198.05-.372-.025-.521-.075-.148-.669-1.611-.916-2.206-.242-.579-.487-.501-.669-.51l-.57-.01c-.198 0-.52.074-.792.372s-1.04 1.016-1.04 2.479 1.065 2.876 1.213 3.074c.149.198 2.095 3.2 5.076 4.487.709.306 1.263.489 1.694.626.712.226 1.36.194 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.695.248-1.29.173-1.414z"/></svg>
                                </span>
                                {{ __('contact.social_whatsapp') ?? 'WhatsApp' }}
                            </a>
                            @endif

                             <!-- Email -->
                             @if(isset($settings['site_email']) && $settings['site_email'])
                             <a href="mailto:{{ $settings['site_email'] }}" class="flex items-center gap-3 w-full p-3 rounded-lg border border-gray-200 hover:bg-gray-50 transition text-gray-700 font-medium">
                                <span class="w-8 h-8 flex items-center justify-center">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                                </span>
                                {{ $settings['site_email'] }}
                            </a>
                            @endif
                        </div>
                    </div>

                    <!-- Note -->
                    <div class="bg-yemdat-offwhite p-6 rounded-2xl border border-yemdat-gold/20 text-center">
                        <p class="text-yemdat-brown font-medium italic">
                            {{ __('contact.note_text') }}
                        </p>
                    </div>

                </div>
            </div>

        </div>
    </div>
</x-app-layout>
