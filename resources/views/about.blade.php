<x-app-layout>
    <div class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col gap-y-32">
            <!-- Header -->
            <div class="text-center max-w-4xl mx-auto" data-reveal>
                <h1 class="text-3xl md:text-5xl font-bold text-yemdat-brown mb-8">
                    {{ __('about.title') }}
                </h1>
                <div class="space-y-6 text-xl text-gray-700 leading-relaxed">
                    <p>{{ __('about.description_1') }}</p>
                    <p>{{ __('about.description_2') }}</p>
                </div>
            </div>

            <!-- Why YEMDAT -->
            <div>
                <h2 class="text-3xl font-bold text-yemdat-brown text-center mb-16" data-reveal>{{ __('about.why_title') }}</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8" data-reveal-group>
                    <!-- Item 1 -->
                    <x-ui.card padding="p-8" class="flex items-center gap-6 hover:shadow-md transition">
                        <div class="w-14 h-14 bg-yemdat-beige rounded-xl flex items-center justify-center text-yemdat-gold shrink-0">
                            <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path></svg>
                        </div>
                        <span class="text-primary font-medium text-lg">{{ __('about.why_1') }}</span>
                    </x-ui.card>

                    <!-- Item 2 -->
                    <x-ui.card padding="p-8" class="flex items-center gap-6 hover:shadow-md transition">
                        <div class="w-14 h-14 bg-yemdat-beige rounded-xl flex items-center justify-center text-yemdat-gold shrink-0">
                            <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        </div>
                        <span class="text-primary font-medium text-lg">{{ __('about.why_2') }}</span>
                    </x-ui.card>

                    <!-- Item 3 -->
                    <x-ui.card padding="p-8" class="flex items-center gap-6 hover:shadow-md transition">
                        <div class="w-14 h-14 bg-yemdat-beige rounded-xl flex items-center justify-center text-yemdat-gold shrink-0">
                            <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9"></path></svg>
                        </div>
                        <span class="text-primary font-medium text-lg">{{ __('about.why_3') }}</span>
                    </x-ui.card>

                    <!-- Item 4 -->
                    <x-ui.card padding="p-8" class="flex items-center gap-6 hover:shadow-md transition">
                        <div class="w-14 h-14 bg-yemdat-beige rounded-xl flex items-center justify-center text-yemdat-gold shrink-0">
                            <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9"></path></svg>
                        </div>
                        <span class="text-primary font-medium text-lg">{{ __('about.why_4') }}</span>
                    </x-ui.card>
                </div>
            </div>

             <!-- Footer Tagline -->
            <div class="max-w-4xl mx-auto w-full" data-reveal>
                 <div class="bg-yemdat-beige/30 border border-yemdat-gold/30 rounded-2xl p-10 text-center">
                    <p class="text-gray-700 text-lg mb-6 leading-relaxed">
                        {{ __('about.footer_text') }}
                    </p>
                    <p class="text-yemdat-brown font-bold text-xl italic">
                         {{ __('about.footer_tagline') }}
                    </p>
                 </div>
            </div>
        </div>
    </div>
</x-app-layout>
