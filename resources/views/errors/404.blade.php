<x-app-layout>
    <div class="relative flex min-h-screen items-center justify-center bg-[#faf9f8] py-24">
        <div class="absolute inset-0 overflow-hidden pointer-events-none flex items-center justify-center">
            <span class="text-[#f0ece6] text-[18rem] sm:text-[24rem] font-black leading-none select-none" style="font-family: 'Arial Black', Impact, sans-serif;">
                404
            </span>
        </div>

        <div class="relative z-10 max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center w-full">
            <!-- Icon -->
            <div class="mx-auto w-32 h-32 bg-white rounded-full shadow-xl shadow-gray-200/50 flex items-center justify-center mb-10 border-[8px] border-white/50 relative">
                <svg class="w-14 h-14 text-yemdat-gold" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z"></path>
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 11.25v2.25m0 3h.01"></path>
                </svg>
            </div>

            <!-- Typography -->
            <h1 class="text-4xl sm:text-5xl md:text-6xl font-black text-yemdat-brown mb-6" style="font-family: 'Arial Black', Impact, sans-serif;">
                {{ app()->getLocale() == 'ar' ? 'الصفحة غير موجودة' : 'Data Not Found' }}
            </h1>

            <p class="text-lg sm:text-xl text-gray-500 max-w-2xl mx-auto mb-8 leading-relaxed">
                {{ app()->getLocale() == 'ar' ? 'عذراً! يبدو أن الصفحة التي تبحث عنها قد تم نقلها أو حذفها أو أنها ببساطة غير موجودة في قاعدة بياناتنا.' : 'Oops! It looks like the page you are looking for has been moved, deleted, or simply doesn\'t exist in our database.' }}
            </p>

            <!-- Error Code text above buttons -->
            <div class="mb-6 text-sm font-bold text-gray-400 uppercase tracking-widest">
                {{ app()->getLocale() == 'ar' ? 'رمز الخطأ: 404' : 'Error Code: 404' }}
            </div>

            <!-- Main Buttons -->
            <div class="flex flex-col sm:flex-row justify-center gap-4 mb-16 px-4">
                <a href="{{ route('home') }}" class="bg-yemdat-brown text-white hover:bg-[#382d25] px-8 py-4 rounded-md font-bold transition shadow-sm flex items-center justify-center text-lg w-full sm:w-auto hover:-translate-y-1">
                    <svg class="w-5 h-5 mr-3 rtl:mr-0 rtl:ml-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                    {{ app()->getLocale() == 'ar' ? 'العودة للرئيسية' : 'Back to Homepage' }}
                </a>
                
                <a href="{{ url()->previous() }}" class="bg-white text-yemdat-brown border border-gray-200 hover:border-gray-300 px-8 py-4 rounded-md font-bold transition shadow-sm flex items-center justify-center text-lg w-full sm:w-auto hover:-translate-y-1">
                    <svg class="w-5 h-5 mr-3 rtl:mr-0 rtl:ml-3 rtl:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                    {{ app()->getLocale() == 'ar' ? 'الرجوع للخلف' : 'Go Back Previous' }}
                </a>
            </div>

            <!-- Divider -->
            <div class="relative mb-12 max-w-3xl mx-auto">
                <div class="absolute inset-0 flex items-center pointer-events-none">
                    <div class="w-full border-t border-gray-200"></div>
                </div>
                <div class="relative flex justify-center">
                    <span class="bg-[#faf9f8] px-6 text-sm font-bold text-gray-400 uppercase tracking-widest">
                        {{ app()->getLocale() == 'ar' ? 'أو جرب استكشاف هذه الأقسام' : 'OR TRY EXPLORING THESE AREAS' }}
                    </span>
                </div>
            </div>

            <!-- Cards -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 max-w-4xl mx-auto px-4">
                
                <!-- Upcoming Events -->
                <a href="{{ route('events.index') }}" class="bg-white p-8 rounded-xl shadow-sm border border-gray-100 hover:border-yemdat-gold/50 hover:-translate-y-1 transition duration-300 flex flex-col items-center group">
                    <div class="w-14 h-14 bg-yemdat-beige/30 rounded-full flex items-center justify-center mb-4 text-yemdat-gold group-hover:bg-yemdat-gold group-hover:text-white transition-colors duration-300">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4m0 5c0 2.21-3.582 4-8 4s-8-1.79-8-4"></path></svg>
                    </div>
                    <h3 class="text-md font-bold text-gray-800">{{ app()->getLocale() == 'ar' ? 'الفعاليات القادمة' : 'Upcoming Events' }}</h3>
                </a>

                <!-- Explore Courses -->
                <a href="{{ route('events.index') }}" class="bg-white p-8 rounded-xl shadow-sm border border-gray-100 hover:border-yemdat-gold/50 hover:-translate-y-1 transition duration-300 flex flex-col items-center group">
                    <div class="w-14 h-14 bg-yemdat-beige/30 rounded-full flex items-center justify-center mb-4 text-yemdat-gold group-hover:bg-yemdat-gold group-hover:text-white transition-colors duration-300">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                    </div>
                    <h3 class="text-md font-bold text-gray-800">{{ app()->getLocale() == 'ar' ? 'استكشاف الدورات' : 'Explore Courses' }}</h3>
                </a>

                <!-- Contact Us -->
                <a href="{{ route('contact') }}" class="bg-white p-8 rounded-xl shadow-sm border border-gray-100 hover:border-yemdat-gold/50 hover:-translate-y-1 transition duration-300 flex flex-col items-center group">
                    <div class="w-14 h-14 bg-yemdat-beige/30 rounded-full flex items-center justify-center mb-4 text-yemdat-gold group-hover:bg-yemdat-gold group-hover:text-white transition-colors duration-300">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                    </div>
                    <h3 class="text-md font-bold text-gray-800">{{ app()->getLocale() == 'ar' ? 'تواصل معنا' : 'Contact Us' }}</h3>
                </a>

            </div>
        </div>
    </div>
</x-app-layout>
