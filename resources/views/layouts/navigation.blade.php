<nav x-data="{ open: false }" class="bg-yemdat-beige">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-20"> <!-- Increased height for better spacing -->
            <div class="flex items-center">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('home') }}" class="flex items-center gap-3">
                        <!-- SVG Logo -->
                        <svg width="40" height="40" viewBox="0 0 50 50" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <rect x="10" y="5" width="30" height="8" rx="2" fill="#F2CB57"/>
                            <rect x="5" y="16" width="35" height="8" rx="2" fill="#C88D16"/>
                            <rect x="0" y="27" width="40" height="8" rx="2" fill="#593E2D"/>
                        </svg>
                        <span class="font-bold text-2xl text-yemdat-brown tracking-wide">YEMDAT</span>
                    </a>
                </div>
            </div>

            <!-- Navigation Links (Desktop) -->
            <div class="hidden space-x-8 sm:-my-px sm:ml-10 sm:flex items-center">
                <a href="{{ route('home') }}" class="inline-flex items-center px-1 pt-1 border-b-2 {{ request()->routeIs('home') ? 'border-yemdat-brown' : 'border-transparent hover:border-gray-300' }} text-sm font-medium leading-5 text-yemdat-brown focus:outline-none focus:border-yemdat-orange transition duration-150 ease-in-out">
                    {{ __('nav.home') }}
                </a>
                <a href="{{ route('about') }}" class="inline-flex items-center px-1 pt-1 border-b-2 {{ request()->routeIs('about') ? 'border-yemdat-brown' : 'border-transparent hover:border-gray-300' }} text-sm font-medium leading-5 text-gray-500 hover:text-yemdat-brown focus:outline-none focus:text-yemdat-brown focus:border-gray-300 transition duration-150 ease-in-out">
                    {{ __('nav.about') }}
                </a>
                <a href="{{ route('vision') }}" class="inline-flex items-center px-1 pt-1 border-b-2 {{ request()->routeIs('vision') ? 'border-yemdat-brown' : 'border-transparent hover:border-gray-300' }} text-sm font-medium leading-5 text-gray-500 hover:text-yemdat-brown focus:outline-none focus:text-yemdat-brown focus:border-gray-300 transition duration-150 ease-in-out">
                    {{ __('nav.vision_mission') }}
                </a>
                 <a href="{{ route('membership') }}" class="inline-flex items-center px-1 pt-1 border-b-2 {{ request()->routeIs('membership') ? 'border-yemdat-brown' : 'border-transparent hover:border-gray-300' }} text-sm font-medium leading-5 text-gray-500 hover:text-yemdat-brown focus:outline-none focus:text-yemdat-brown focus:border-gray-300 transition duration-150 ease-in-out">
                    {{ __('nav.membership') }}
                </a>
                  <a href="{{ route('events.index') }}" class="inline-flex items-center px-1 pt-1 border-b-2 {{ request()->routeIs('events.*') ? 'border-yemdat-brown' : 'border-transparent hover:border-gray-300' }} text-sm font-medium leading-5 text-gray-500 hover:text-yemdat-brown focus:outline-none focus:text-yemdat-brown focus:border-gray-300 transition duration-150 ease-in-out">
                    {{ __('nav.training_events') }}
                </a>
                 <a href="{{ route('news') }}" class="inline-flex items-center px-1 pt-1 border-b-2 {{ request()->routeIs('news') ? 'border-yemdat-brown' : 'border-transparent hover:border-gray-300' }} text-sm font-medium leading-5 text-gray-500 hover:text-yemdat-brown focus:outline-none focus:text-yemdat-brown focus:border-gray-300 transition duration-150 ease-in-out">
                    {{ __('nav.news') }}
                </a>
                <a href="{{ route('contact') }}" class="inline-flex items-center px-1 pt-1 border-b-2 {{ request()->routeIs('contact') ? 'border-yemdat-brown' : 'border-transparent hover:border-gray-300' }} text-sm font-medium leading-5 text-gray-500 hover:text-yemdat-brown focus:outline-none focus:text-yemdat-brown focus:border-gray-300 transition duration-150 ease-in-out">
                    {{ __('nav.contact') }}
                </a>
            </div>

            <!-- Language Switcher & Desktop Buttons -->
            <div class="hidden sm:flex sm:items-center sm:ml-6 gap-4">
                @if(auth()->guard('web')->check() && (auth()->guard('web')->user()->isSuperAdmin() || auth()->guard('web')->user()->role === 'admin'))
                    <a href="{{ route('admin.dashboard') }}" class="inline-flex items-center px-4 py-2 bg-yemdat-brown border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-yemdat-brown/90 focus:outline-none transition ease-in-out duration-150">
                        {{ app()->getLocale() == 'ar' ? 'لوحة التحكم' : 'Dashboard' }}
                    </a>
                @elseif(auth()->guard('member')->check())
                    <a href="{{ route('profile.show') }}" class="inline-flex items-center px-4 py-2 bg-yemdat-brown border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-yemdat-brown/90 focus:outline-none transition ease-in-out duration-150">
                        {{ app()->getLocale() == 'ar' ? 'ملفي الشخصي' : 'My Profile' }}
                    </a>
                @else
                    <a href="{{ route('public.login') }}" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-50 focus:outline-none transition ease-in-out duration-150">
                        {{ app()->getLocale() == 'ar' ? 'تسجيل الدخول' : 'Sign In' }}
                    </a>
                    <a href="{{ route('register') }}" class="inline-flex items-center px-4 py-2 bg-yemdat-gold border border-transparent rounded-md font-semibold text-xs text-yemdat-brown uppercase tracking-widest hover:bg-yemdat-gold/90 focus:outline-none transition ease-in-out duration-150 shadow-sm">
                        {{ __('home.btn_join') }}
                    </a>
                @endif

                @if (app()->getLocale() == 'en')
                    <a href="{{ route('lang.switch', 'ar') }}" class="flex items-center justify-center p-2 text-sm font-bold text-gray-500 hover:text-yemdat-brown hover:bg-gray-100 rounded-lg transition duration-150 ease-in-out" title="العربية">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5 mr-1">
                          <path stroke-linecap="round" stroke-linejoin="round" d="M12 21a9.004 9.004 0 008.716-6.747M12 21a9.004 9.004 0 01-8.716-6.747M12 21c2.485 0 4.5-4.03 4.5-9S13.627 3 12 3m0 18c-2.485 0-4.5-4.03-4.5-9S9.627 3 12 3m0 0a8.997 8.997 0 017.843 4.582M12 3a8.997 8.997 0 00-7.843 4.582m15.686 0A11.953 11.953 0 0112 10.5c-2.998 0-5.74-1.1-7.843-2.918m15.686 0A8.959 8.959 0 0121 12c0 .778-.099 1.533-.284 2.253m0 0A17.919 17.919 0 0112 16.5c-3.162 0-6.133-.815-8.716-2.247m0 0A9.015 9.015 0 013 12c0-1.605.42-3.113 1.157-4.418" />
                        </svg>
                        AR
                    </a>
                @else
                    <a href="{{ route('lang.switch', 'en') }}" class="flex items-center justify-center p-2 text-sm font-bold text-gray-500 hover:text-yemdat-brown hover:bg-gray-100 rounded-lg transition duration-150 ease-in-out" title="English">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5 mr-1">
                             <path stroke-linecap="round" stroke-linejoin="round" d="M12 21a9.004 9.004 0 008.716-6.747M12 21a9.004 9.004 0 01-8.716-6.747M12 21c2.485 0 4.5-4.03 4.5-9S13.627 3 12 3m0 18c-2.485 0-4.5-4.03-4.5-9S9.627 3 12 3m0 0a8.997 8.997 0 017.843 4.582M12 3a8.997 8.997 0 00-7.843 4.582m15.686 0A11.953 11.953 0 0112 10.5c-2.998 0-5.74-1.1-7.843-2.918m15.686 0A8.959 8.959 0 0121 12c0 .778-.099 1.533-.284 2.253m0 0A17.919 17.919 0 0112 16.5c-3.162 0-6.133-.815-8.716-2.247m0 0A9.015 9.015 0 013 12c0-1.605.42-3.113 1.157-4.418" />
                        </svg>
                        EN
                    </a>
                @endif
            </div>

            <!-- Mobile Language Switcher & Hamburger -->
            <div class="flex items-center sm:hidden gap-1">
                @if (app()->getLocale() == 'en')
                    <a href="{{ route('lang.switch', 'ar') }}" class="flex items-center p-2 text-gray-500 hover:text-yemdat-brown hover:bg-gray-100 rounded-md transition duration-150" title="العربية">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4 mr-1">
                          <path stroke-linecap="round" stroke-linejoin="round" d="M12 21a9.004 9.004 0 008.716-6.747M12 21a9.004 9.004 0 01-8.716-6.747M12 21c2.485 0 4.5-4.03 4.5-9S13.627 3 12 3m0 18c-2.485 0-4.5-4.03-4.5-9S9.627 3 12 3m0 0a8.997 8.997 0 017.843 4.582M12 3a8.997 8.997 0 00-7.843 4.582m15.686 0A11.953 11.953 0 0112 10.5c-2.998 0-5.74-1.1-7.843-2.918m15.686 0A8.959 8.959 0 0121 12c0 .778-.099 1.533-.284 2.253m0 0A17.919 17.919 0 0112 16.5c-3.162 0-6.133-.815-8.716-2.247m0 0A9.015 9.015 0 013 12c0-1.605.42-3.113 1.157-4.418" />
                        </svg>
                        <span class="font-bold text-sm">AR</span>
                    </a>
                @else
                    <a href="{{ route('lang.switch', 'en') }}" class="flex items-center p-2 text-gray-500 hover:text-yemdat-brown hover:bg-gray-100 rounded-md transition duration-150" title="English">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4 ml-1">
                             <path stroke-linecap="round" stroke-linejoin="round" d="M12 21a9.004 9.004 0 008.716-6.747M12 21a9.004 9.004 0 01-8.716-6.747M12 21c2.485 0 4.5-4.03 4.5-9S13.627 3 12 3m0 18c-2.485 0-4.5-4.03-4.5-9S9.627 3 12 3m0 0a8.997 8.997 0 017.843 4.582M12 3a8.997 8.997 0 00-7.843 4.582m15.686 0A11.953 11.953 0 0112 10.5c-2.998 0-5.74-1.1-7.843-2.918m15.686 0A8.959 8.959 0 0121 12c0 .778-.099 1.533-.284 2.253m0 0A17.919 17.919 0 0112 16.5c-3.162 0-6.133-.815-8.716-2.247m0 0A9.015 9.015 0 013 12c0-1.605.42-3.113 1.157-4.418" />
                        </svg>
                        <span class="font-bold text-sm">EN</span>
                    </a>
                @endif

                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-500 hover:text-yemdat-brown hover:bg-gray-100 focus:outline-none transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden">
        <div class="pt-2 pb-3 space-y-1">
             <a href="{{ route('home') }}" class="block pl-3 pr-4 py-2 border-l-4 {{ request()->routeIs('home') ? 'border-yemdat-brown text-yemdat-brown bg-indigo-50' : 'border-transparent text-gray-600 hover:text-gray-800 hover:bg-gray-50 hover:border-gray-300' }} text-base font-medium focus:outline-none transition duration-150 ease-in-out">
                {{ __('nav.home') }}
            </a>
             <a href="{{ route('about') }}" class="block pl-3 pr-4 py-2 border-l-4 {{ request()->routeIs('about') ? 'border-yemdat-brown text-yemdat-brown bg-indigo-50' : 'border-transparent text-gray-600 hover:text-gray-800 hover:bg-gray-50 hover:border-gray-300' }} text-base font-medium focus:outline-none transition duration-150 ease-in-out">
                {{ __('nav.about') }}
            </a>
            <a href="{{ route('vision') }}" class="block pl-3 pr-4 py-2 border-l-4 {{ request()->routeIs('vision') ? 'border-yemdat-brown text-yemdat-brown bg-indigo-50' : 'border-transparent text-gray-600 hover:text-gray-800 hover:bg-gray-50 hover:border-gray-300' }} text-base font-medium focus:outline-none transition duration-150 ease-in-out">
                {{ __('nav.vision_mission') }}
            </a>
             <a href="{{ route('membership') }}" class="block pl-3 pr-4 py-2 border-l-4 {{ request()->routeIs('membership') ? 'border-yemdat-brown text-yemdat-brown bg-indigo-50' : 'border-transparent text-gray-600 hover:text-gray-800 hover:bg-gray-50 hover:border-gray-300' }} text-base font-medium focus:outline-none transition duration-150 ease-in-out">
                {{ __('nav.membership') }}
            </a>
             <a href="{{ route('events.index') }}" class="block pl-3 pr-4 py-2 border-l-4 {{ request()->routeIs('events.*') ? 'border-yemdat-brown text-yemdat-brown bg-indigo-50' : 'border-transparent text-gray-600 hover:text-gray-800 hover:bg-gray-50 hover:border-gray-300' }} text-base font-medium focus:outline-none transition duration-150 ease-in-out">
                {{ __('nav.training_events') }}
            </a>
             <a href="{{ route('news') }}" class="block pl-3 pr-4 py-2 border-l-4 {{ request()->routeIs('news') ? 'border-yemdat-brown text-yemdat-brown bg-indigo-50' : 'border-transparent text-gray-600 hover:text-gray-800 hover:bg-gray-50 hover:border-gray-300' }} text-base font-medium focus:outline-none transition duration-150 ease-in-out">
                {{ __('nav.news') }}
            </a>
            <a href="{{ route('contact') }}" class="block pl-3 pr-4 py-2 border-l-4 {{ request()->routeIs('contact') ? 'border-yemdat-brown text-yemdat-brown bg-indigo-50' : 'border-transparent text-gray-600 hover:text-gray-800 hover:bg-gray-50 hover:border-gray-300' }} text-base font-medium focus:outline-none transition duration-150 ease-in-out">
        <!-- Mobile Responsive Settings Options -->
        <div class="pt-4 pb-1 border-t border-gray-200">
            @if(auth()->guard('web')->check() && (auth()->guard('web')->user()->isSuperAdmin() || auth()->guard('web')->user()->role === 'admin'))
                <div class="px-4">
                    <div class="font-medium text-base text-gray-800">{{ auth()->guard('web')->user()->name }}</div>
                    <div class="font-medium text-sm text-gray-500">{{ auth()->guard('web')->user()->email }}</div>
                </div>

                <div class="mt-3 space-y-1">
                    <a href="{{ route('admin.dashboard') }}" class="block pl-3 pr-4 py-2 border-l-4 border-transparent text-gray-600 hover:text-gray-800 hover:bg-gray-50 hover:border-gray-300 text-base font-medium focus:outline-none transition duration-150 ease-in-out">
                        {{ app()->getLocale() == 'ar' ? 'لوحة التحكم' : 'Dashboard' }}
                    </a>

                    <!-- Authentication -->
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="w-full text-start block pl-3 pr-4 py-2 border-l-4 border-transparent text-red-600 hover:text-red-800 hover:bg-red-50 hover:border-red-300 text-base font-medium focus:outline-none transition duration-150 ease-in-out">
                            {{ app()->getLocale() == 'ar' ? 'تسجيل الخروج' : 'Log Out' }}
                        </button>
                    </form>
                </div>
            @elseif(auth()->guard('member')->check())
                <div class="px-4">
                    <div class="font-medium text-base text-gray-800">{{ auth()->guard('member')->user()->full_name }}</div>
                    <div class="font-medium text-sm text-gray-500">{{ auth()->guard('member')->user()->email }}</div>
                </div>
                
                <div class="mt-3 space-y-1">
                    <a href="{{ route('profile.show') }}" class="block pl-3 pr-4 py-2 border-l-4 border-transparent text-gray-600 hover:text-gray-800 hover:bg-gray-50 hover:border-gray-300 text-base font-medium focus:outline-none transition duration-150 ease-in-out">
                        {{ app()->getLocale() == 'ar' ? 'ملفي الشخصي' : 'My Profile' }}
                    </a>

                    <!-- Authentication -->
                    <form method="POST" action="{{ route('public.logout') }}">
                        @csrf
                        <button type="submit" class="w-full text-start block pl-3 pr-4 py-2 border-l-4 border-transparent text-red-600 hover:text-red-800 hover:bg-red-50 hover:border-red-300 text-base font-medium focus:outline-none transition duration-150 ease-in-out">
                            {{ app()->getLocale() == 'ar' ? 'تسجيل الخروج' : 'Log Out' }}
                        </button>
                    </form>
                </div>
            @else
                <a href="{{ route('public.login') }}" class="block pl-3 pr-4 py-2 border-l-4 border-transparent text-gray-600 hover:text-gray-800 hover:bg-gray-50 hover:border-gray-300 text-base font-medium focus:outline-none transition duration-150 ease-in-out">
                    {{ app()->getLocale() == 'ar' ? 'تسجيل الدخول' : 'Sign In' }}
                </a>
                <a href="{{ route('register') }}" class="block pl-3 pr-4 py-2 border-l-4 border-yemdat-gold text-yemdat-brown bg-yemdat-gold/10 font-bold focus:outline-none transition duration-150 ease-in-out">
                    {{ __('home.btn_join') }}
                </a>
            @endif
        </div>
    </div>
</nav>
