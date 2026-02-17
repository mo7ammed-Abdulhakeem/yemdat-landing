<x-app-layout>
    <div class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col gap-y-32">
            
            <!-- Header -->
            <div class="text-center max-w-3xl mx-auto mb-16">
                 <div class="w-16 h-16 bg-yemdat-beige rounded-xl flex items-center justify-center text-yemdat-gold mx-auto mb-6">
                     <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"></path></svg>
                </div>
                <h1 class="text-3xl md:text-4xl font-bold text-yemdat-brown mb-6">
                    {{ __('news.title') }}
                </h1>
                <p class="text-xl text-gray-600">
                    {{ __('news.subtitle') }}
                </p>
            </div>

            <!-- News List -->
            <div class="max-w-4xl mx-auto w-full flex flex-col gap-8">
                <!-- Item 1 -->
                <div class="bg-white p-8 rounded-xl border border-gray-200 shadow-sm hover:shadow-md transition">
                    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 gap-4">
                         <h2 class="text-xl md:text-2xl font-bold text-yemdat-brown">{{ __('news.item_1_title') }}</h2>
                         <span class="bg-yemdat-gold text-white px-4 py-1 rounded-full text-sm font-medium shrink-0">{{ __('news.item_1_badge') }}</span>
                    </div>
                    <div class="flex items-center gap-2 text-gray-500 text-sm mb-4">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                        {{ __('news.item_1_date') }}
                    </div>
                    <p class="text-gray-600 leading-relaxed">{{ __('news.item_1_desc') }}</p>
                </div>

                <!-- Item 2 -->
                <div class="bg-white p-8 rounded-xl border border-gray-200 shadow-sm hover:shadow-md transition">
                    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 gap-4">
                         <h2 class="text-xl md:text-2xl font-bold text-yemdat-brown">{{ __('news.item_2_title') }}</h2>
                         <span class="bg-yemdat-gold text-white px-4 py-1 rounded-full text-sm font-medium shrink-0">{{ __('news.item_2_badge') }}</span>
                    </div>
                    <div class="flex items-center gap-2 text-gray-500 text-sm mb-4">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                        {{ __('news.item_2_date') }}
                    </div>
                    <p class="text-gray-600 leading-relaxed">{{ __('news.item_2_desc') }}</p>
                </div>

                <!-- Item 3 -->
                <div class="bg-white p-8 rounded-xl border border-gray-200 shadow-sm hover:shadow-md transition">
                    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 gap-4">
                         <h2 class="text-xl md:text-2xl font-bold text-yemdat-brown">{{ __('news.item_3_title') }}</h2>
                         <span class="bg-yemdat-gold text-white px-4 py-1 rounded-full text-sm font-medium shrink-0">{{ __('news.item_3_badge') }}</span>
                    </div>
                    <div class="flex items-center gap-2 text-gray-500 text-sm mb-4">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                        {{ __('news.item_3_date') }}
                    </div>
                    <p class="text-gray-600 leading-relaxed">{{ __('news.item_3_desc') }}</p>
                </div>
            </div>

            <!-- Stay Updated -->
             <div class="max-w-4xl mx-auto w-full">
                 <div class="bg-yemdat-offwhite border border-gray-100 rounded-2xl p-16 flex flex-col items-center text-center gap-6">
                    <div class="text-yemdat-gold rotate-12 transform">
                        <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"></path></svg>
                    </div>
                    <div>
                        <h3 class="text-xl font-bold text-yemdat-brown mb-2">{{ __('news.footer_title') }}</h3>
                        <p class="text-gray-600 text-lg">
                             {{ __('news.footer_text') }}
                        </p>
                    </div>
                 </div>
            </div>
        </div>
    </div>
</x-app-layout>
