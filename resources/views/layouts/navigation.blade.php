@php
    $aboutActive = request()->routeIs('about') || request()->routeIs('vision');
    $member = auth()->guard('member')->user();
    $memberFirstName = $member ? \Illuminate\Support\Str::before(trim($member->full_name), ' ') : '';
    $isAdmin = auth()->guard('web')->check()
        && (auth()->guard('web')->user()->isSuperAdmin() || auth()->guard('web')->user()->role === 'admin');
@endphp

<nav x-data="{ open: false, scrolled: false }"
     @scroll.window="scrolled = window.scrollY > 8"
     class="sticky top-0 z-50 bg-surface-raised border-b border-border transition-shadow duration-200"
     :class="scrolled ? 'shadow-md' : 'shadow-sm'">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center h-20">
            <!-- Logo -->
            <div class="shrink-0 flex items-center">
                <a href="{{ route('home') }}" class="flex items-center gap-3">
                    <svg width="40" height="40" viewBox="0 0 50 50" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                        <rect x="10" y="5" width="30" height="8" rx="2" fill="#F2CB57"/>
                        <rect x="5" y="16" width="35" height="8" rx="2" fill="#C88D16"/>
                        <rect x="0" y="27" width="40" height="8" rx="2" fill="#593E2D"/>
                    </svg>
                    <span class="font-bold text-2xl text-primary tracking-wide">YEMDAT</span>
                </a>
            </div>

            <!-- Desktop Navigation Links -->
            <div class="hidden xl:flex items-center gap-6">
                <x-nav-link :href="route('home')" :active="request()->routeIs('home')">{{ __('nav.home') }}</x-nav-link>

                <!-- About dropdown (About Us + Vision & Mission) -->
                <div class="relative" x-data="{ aboutOpen: false }" @keydown.escape.window="aboutOpen = false">
                    <button type="button"
                            @click="aboutOpen = ! aboutOpen"
                            :aria-expanded="aboutOpen.toString()"
                            aria-haspopup="true"
                            class="inline-flex items-center gap-1 whitespace-nowrap px-1 pt-1 border-b-2 text-sm leading-5 focus:outline-none transition duration-150 ease-in-out {{ $aboutActive ? 'border-accent text-primary font-semibold' : 'border-transparent text-ink-soft font-medium hover:text-primary hover:border-border' }}">
                        {{ __('nav.about') }}
                        <svg class="w-4 h-4 transition-transform duration-150" :class="aboutOpen ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>
                    <div x-show="aboutOpen" x-cloak
                         @click.away="aboutOpen = false"
                         x-transition:enter="transition ease-out duration-150"
                         x-transition:enter-start="opacity-0 -translate-y-1"
                         x-transition:enter-end="opacity-100 translate-y-0"
                         class="absolute start-0 mt-3 w-52 py-1 bg-surface-raised border border-border rounded-card shadow-pop z-50">
                        <a href="{{ route('about') }}" class="block px-4 py-2 text-sm text-ink-soft hover:text-primary hover:bg-surface-sunken transition">{{ __('nav.about_us') }}</a>
                        <a href="{{ route('vision') }}" class="block px-4 py-2 text-sm text-ink-soft hover:text-primary hover:bg-surface-sunken transition">{{ __('nav.vision_mission') }}</a>
                    </div>
                </div>

                <x-nav-link :href="route('membership')" :active="request()->routeIs('membership')">{{ __('nav.membership') }}</x-nav-link>
                <x-nav-link :href="route('events.index')" :active="request()->routeIs('events.*')">{{ __('nav.training_events') }}</x-nav-link>
                <x-nav-link :href="route('news')" :active="request()->routeIs('news')">{{ __('nav.news') }}</x-nav-link>
                <x-nav-link :href="route('contact')" :active="request()->routeIs('contact')">{{ __('nav.contact') }}</x-nav-link>

                <!-- Be a Trainer CTA -->
                <a href="{{ route('trainer.create') }}"
                   class="inline-flex items-center whitespace-nowrap shrink-0 px-4 py-1.5 rounded-btn border text-sm font-semibold transition duration-150 {{ request()->routeIs('trainer.*') ? 'bg-primary text-white border-primary' : 'border-primary text-primary hover:bg-primary hover:text-white' }}">
                    {{ __('nav.be_a_trainer') }}
                </a>
            </div>

            <!-- Desktop right cluster: auth + language -->
            <div class="hidden xl:flex items-center gap-3">
                @if($isAdmin)
                    <a href="{{ url('/admin') }}" class="inline-flex items-center whitespace-nowrap shrink-0 px-4 py-2 bg-primary border border-transparent rounded-btn font-semibold text-xs text-white uppercase tracking-widest hover:bg-primary-hover focus:outline-none transition duration-150">
                        {{ __('nav.dashboard') }}
                    </a>
                @elseif($member)
                    <!-- Member dropdown -->
                    <div class="relative" x-data="{ userOpen: false }" @keydown.escape.window="userOpen = false">
                        <button type="button"
                                @click="userOpen = ! userOpen"
                                :aria-expanded="userOpen.toString()"
                                aria-haspopup="true"
                                class="inline-flex items-center gap-2 shrink-0 px-2 py-1.5 rounded-btn text-sm font-semibold text-ink-soft hover:text-primary hover:bg-surface-sunken focus:outline-none transition duration-150">
                            <span class="flex items-center justify-center w-8 h-8 rounded-full bg-surface-sunken text-primary">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                            </span>
                            <span class="max-w-[8rem] truncate">{{ $memberFirstName }}</span>
                            <svg class="w-4 h-4 transition-transform duration-150" :class="userOpen ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
                        </button>
                        <div x-show="userOpen" x-cloak
                             @click.away="userOpen = false"
                             x-transition:enter="transition ease-out duration-150"
                             x-transition:enter-start="opacity-0 -translate-y-1"
                             x-transition:enter-end="opacity-100 translate-y-0"
                             class="absolute end-0 mt-3 w-56 py-1 bg-surface-raised border border-border rounded-card shadow-pop z-50">
                            <div class="px-4 py-2 border-b border-border">
                                <p class="text-sm font-semibold text-ink truncate">{{ $member->full_name }}</p>
                                <p class="text-xs text-ink-soft truncate">{{ $member->email }}</p>
                            </div>
                            <a href="{{ route('profile.show') }}" class="block px-4 py-2 text-sm text-ink-soft hover:text-primary hover:bg-surface-sunken transition">{{ __('nav.my_profile') }}</a>
                            <form method="POST" action="{{ route('public.logout') }}">
                                @csrf
                                <button type="submit" class="w-full text-start px-4 py-2 text-sm text-danger hover:bg-danger-soft transition">{{ __('nav.log_out') }}</button>
                            </form>
                        </div>
                    </div>
                @else
                    <a href="{{ route('public.login') }}" class="inline-flex items-center whitespace-nowrap shrink-0 px-4 py-2 bg-surface-raised border border-border rounded-btn font-semibold text-xs text-ink uppercase tracking-widest hover:bg-surface-sunken focus:outline-none transition duration-150">
                        {{ __('nav.sign_in') }}
                    </a>
                    <a href="{{ route('register') }}" class="inline-flex items-center whitespace-nowrap shrink-0 px-4 py-2 bg-accent border border-transparent rounded-btn font-semibold text-xs text-primary uppercase tracking-widest hover:bg-accent-hover focus:outline-none transition duration-150 shadow-sm">
                        {{ __('home.btn_join') }}
                    </a>
                @endif

                <!-- Language switcher -->
                @if (app()->getLocale() == 'en')
                    <a href="{{ route('lang.switch', 'ar') }}" class="inline-flex items-center gap-1 shrink-0 p-2 rounded-btn text-sm font-bold text-ink-soft hover:text-primary hover:bg-surface-sunken transition duration-150" title="العربية">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M12 21a9.004 9.004 0 008.716-6.747M12 21a9.004 9.004 0 01-8.716-6.747M12 21c2.485 0 4.5-4.03 4.5-9S13.627 3 12 3m0 18c-2.485 0-4.5-4.03-4.5-9S9.627 3 12 3m0 0a8.997 8.997 0 017.843 4.582M12 3a8.997 8.997 0 00-7.843 4.582m15.686 0A11.953 11.953 0 0112 10.5c-2.998 0-5.74-1.1-7.843-2.918m15.686 0A8.959 8.959 0 0121 12c0 .778-.099 1.533-.284 2.253m0 0A17.919 17.919 0 0112 16.5c-3.162 0-6.133-.815-8.716-2.247m0 0A9.015 9.015 0 013 12c0-1.605.42-3.113 1.157-4.418" /></svg>
                        AR
                    </a>
                @else
                    <a href="{{ route('lang.switch', 'en') }}" class="inline-flex items-center gap-1 shrink-0 p-2 rounded-btn text-sm font-bold text-ink-soft hover:text-primary hover:bg-surface-sunken transition duration-150" title="English">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M12 21a9.004 9.004 0 008.716-6.747M12 21a9.004 9.004 0 01-8.716-6.747M12 21c2.485 0 4.5-4.03 4.5-9S13.627 3 12 3m0 18c-2.485 0-4.5-4.03-4.5-9S9.627 3 12 3m0 0a8.997 8.997 0 017.843 4.582M12 3a8.997 8.997 0 00-7.843 4.582m15.686 0A11.953 11.953 0 0112 10.5c-2.998 0-5.74-1.1-7.843-2.918m15.686 0A8.959 8.959 0 0121 12c0 .778-.099 1.533-.284 2.253m0 0A17.919 17.919 0 0112 16.5c-3.162 0-6.133-.815-8.716-2.247m0 0A9.015 9.015 0 013 12c0-1.605.42-3.113 1.157-4.418" /></svg>
                        EN
                    </a>
                @endif
            </div>

            <!-- Mobile right cluster: language + hamburger -->
            <div class="flex items-center gap-1 xl:hidden">
                @if (app()->getLocale() == 'en')
                    <a href="{{ route('lang.switch', 'ar') }}" class="inline-flex items-center gap-1 shrink-0 p-2 rounded-btn text-sm font-bold text-ink-soft hover:text-primary hover:bg-surface-sunken transition duration-150" title="العربية">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M12 21a9.004 9.004 0 008.716-6.747M12 21a9.004 9.004 0 01-8.716-6.747M12 21c2.485 0 4.5-4.03 4.5-9S13.627 3 12 3m0 18c-2.485 0-4.5-4.03-4.5-9S9.627 3 12 3m0 0a8.997 8.997 0 017.843 4.582M12 3a8.997 8.997 0 00-7.843 4.582m15.686 0A11.953 11.953 0 0112 10.5c-2.998 0-5.74-1.1-7.843-2.918m15.686 0A8.959 8.959 0 0121 12c0 .778-.099 1.533-.284 2.253m0 0A17.919 17.919 0 0112 16.5c-3.162 0-6.133-.815-8.716-2.247m0 0A9.015 9.015 0 013 12c0-1.605.42-3.113 1.157-4.418" /></svg>
                        AR
                    </a>
                @else
                    <a href="{{ route('lang.switch', 'en') }}" class="inline-flex items-center gap-1 shrink-0 p-2 rounded-btn text-sm font-bold text-ink-soft hover:text-primary hover:bg-surface-sunken transition duration-150" title="English">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M12 21a9.004 9.004 0 008.716-6.747M12 21a9.004 9.004 0 01-8.716-6.747M12 21c2.485 0 4.5-4.03 4.5-9S13.627 3 12 3m0 18c-2.485 0-4.5-4.03-4.5-9S9.627 3 12 3m0 0a8.997 8.997 0 017.843 4.582M12 3a8.997 8.997 0 00-7.843 4.582m15.686 0A11.953 11.953 0 0112 10.5c-2.998 0-5.74-1.1-7.843-2.918m15.686 0A8.959 8.959 0 0121 12c0 .778-.099 1.533-.284 2.253m0 0A17.919 17.919 0 0112 16.5c-3.162 0-6.133-.815-8.716-2.247m0 0A9.015 9.015 0 013 12c0-1.605.42-3.113 1.157-4.418" /></svg>
                        EN
                    </a>
                @endif

                <button @click="open = ! open" type="button"
                        :aria-expanded="open.toString()"
                        aria-controls="mobile-menu"
                        aria-label="{{ __('nav.menu') }}"
                        class="inline-flex items-center justify-center p-2 rounded-btn text-ink-soft hover:text-primary hover:bg-surface-sunken focus:outline-none transition duration-150">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24" aria-hidden="true">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Mobile menu -->
    <div id="mobile-menu" x-show="open" x-cloak
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 -translate-y-2"
         x-transition:enter-end="opacity-100 translate-y-0"
         class="xl:hidden border-t border-border">
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link :href="route('home')" :active="request()->routeIs('home')">{{ __('nav.home') }}</x-responsive-nav-link>
            <x-responsive-nav-link :href="route('about')" :active="request()->routeIs('about')">{{ __('nav.about_us') }}</x-responsive-nav-link>
            <x-responsive-nav-link :href="route('vision')" :active="request()->routeIs('vision')">{{ __('nav.vision_mission') }}</x-responsive-nav-link>
            <x-responsive-nav-link :href="route('membership')" :active="request()->routeIs('membership')">{{ __('nav.membership') }}</x-responsive-nav-link>
            <x-responsive-nav-link :href="route('events.index')" :active="request()->routeIs('events.*')">{{ __('nav.training_events') }}</x-responsive-nav-link>
            <x-responsive-nav-link :href="route('news')" :active="request()->routeIs('news')">{{ __('nav.news') }}</x-responsive-nav-link>
            <x-responsive-nav-link :href="route('contact')" :active="request()->routeIs('contact')">{{ __('nav.contact') }}</x-responsive-nav-link>
            <x-responsive-nav-link :href="route('trainer.create')" :active="request()->routeIs('trainer.*')">{{ __('nav.be_a_trainer') }}</x-responsive-nav-link>
        </div>

        <!-- Settings / account -->
        <div class="pt-4 pb-3 border-t border-border">
            @if($isAdmin)
                <div class="px-4">
                    <div class="font-medium text-base text-ink">{{ auth()->guard('web')->user()->name }}</div>
                    <div class="font-medium text-sm text-ink-soft">{{ auth()->guard('web')->user()->email }}</div>
                </div>
                <div class="mt-3 space-y-1">
                    <x-responsive-nav-link :href="url('/admin')">{{ __('nav.dashboard') }}</x-responsive-nav-link>
                    <form method="POST" action="{{ route('filament.admin.auth.logout') }}">
                        @csrf
                        <button type="submit" class="w-full text-start block ps-3 pe-4 py-2 border-s-4 border-transparent text-danger font-medium hover:bg-danger-soft focus:outline-none transition duration-150">
                            {{ __('nav.log_out') }}
                        </button>
                    </form>
                </div>
            @elseif($member)
                <div class="px-4">
                    <div class="font-medium text-base text-ink">{{ $member->full_name }}</div>
                    <div class="font-medium text-sm text-ink-soft">{{ $member->email }}</div>
                </div>
                <div class="mt-3 space-y-1">
                    <x-responsive-nav-link :href="route('profile.show')" :active="request()->routeIs('profile.*')">{{ __('nav.my_profile') }}</x-responsive-nav-link>
                    <form method="POST" action="{{ route('public.logout') }}">
                        @csrf
                        <button type="submit" class="w-full text-start block ps-3 pe-4 py-2 border-s-4 border-transparent text-danger font-medium hover:bg-danger-soft focus:outline-none transition duration-150">
                            {{ __('nav.log_out') }}
                        </button>
                    </form>
                </div>
            @else
                <div class="space-y-1">
                    <x-responsive-nav-link :href="route('public.login')">{{ __('nav.sign_in') }}</x-responsive-nav-link>
                    <a href="{{ route('register') }}" class="block ps-3 pe-4 py-2 border-s-4 border-accent text-primary bg-accent/10 font-bold focus:outline-none transition duration-150">
                        {{ __('home.btn_join') }}
                    </a>
                </div>
            @endif
        </div>
    </div>
</nav>
