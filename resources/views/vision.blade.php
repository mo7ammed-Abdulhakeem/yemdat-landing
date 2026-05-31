<x-app-layout>
    <div class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col gap-y-16">

            <!-- Vision & Mission Section -->
            <div class="flex flex-col gap-y-12">
                <!-- Vision Card -->
                <x-ui.card padding="p-10" class="flex flex-col md:flex-row items-center gap-8">
                    <div class="w-16 h-16 bg-yemdat-brown rounded-2xl flex items-center justify-center text-white shrink-0">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                    </div>
                    <div class="flex-1 text-center md:text-start rtl:md:text-right">
                        <h2 class="text-2xl font-bold text-primary mb-4">{{ __('vision.vision_title') }}</h2>
                        <p class="text-gray-700 text-lg leading-relaxed">{{ __('vision.vision_text') }}</p>
                    </div>
                </x-ui.card>

                <!-- Mission Card -->
                <x-ui.card padding="p-10" class="flex flex-col md:flex-row items-center gap-8">
                    <div class="w-16 h-16 bg-yemdat-gold rounded-full flex items-center justify-center text-white shrink-0">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 2a10 10 0 100 20 10 10 0 000-20zm0 18a8 8 0 110-16 8 8 0 010 16zm0-14a6 6 0 100 12 6 6 0 000-12z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8a4 4 0 100 8 4 4 0 000-8z"></path></svg>
                    </div>
                    <div class="flex-1 text-center md:text-start rtl:md:text-right">
                        <h2 class="text-2xl font-bold text-primary mb-4">{{ __('vision.mission_title') }}</h2>
                        <p class="text-gray-700 text-lg leading-relaxed">{{ __('vision.mission_text') }}</p>
                    </div>
                </x-ui.card>
            </div>

            <!-- Objectives Section -->
            <div>
                <h2 class="text-2xl font-bold text-primary text-center mb-10">{{ __('vision.objectives_title') }}</h2>
                <div class="flex flex-col gap-y-6">
                    @foreach(['obj_1', 'obj_2', 'obj_3', 'obj_4', 'obj_5'] as $obj)
                        <x-ui.card padding="p-6" class="flex items-center gap-6 ltr:border-l-4 rtl:border-r-4 border-yemdat-gold hover:shadow-md transition">
                            <div class="w-8 h-8 rounded-full border-2 border-yemdat-gold flex items-center justify-center text-yemdat-gold shrink-0">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                            </div>
                            <span class="text-gray-700 font-medium text-lg">{{ __('vision.' . $obj) }}</span>
                        </x-ui.card>
                    @endforeach
                </div>
            </div>

            <!-- Footer Tagline -->
            <div class="mt-8">
                <div class="bg-yemdat-beige border border-yemdat-gold/30 rounded-2xl p-10 text-center">
                    <p class="text-primary font-bold text-xl italic">
                        {{ __('vision.footer_tagline') }}
                    </p>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
