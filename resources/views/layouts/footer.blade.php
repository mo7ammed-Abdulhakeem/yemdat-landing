<footer class="bg-yemdat-brown text-white pt-12 pb-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <!-- Brand & Description -->
            <div>
                <div class="flex items-center gap-3 mb-4">
                     <!-- SVG Logo Reversed -->
                     <svg width="30" height="30" viewBox="0 0 50 50" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <rect x="10" y="5" width="30" height="8" rx="2" fill="#F2CB57"/>
                        <rect x="5" y="16" width="35" height="8" rx="2" fill="#C88D16"/>
                        <rect x="0" y="27" width="40" height="8" rx="2" fill="#FAF8F3"/>
                    </svg>
                    <span class="font-bold text-xl tracking-wide">YEMDAT</span>
                </div>
                <p class="text-gray-300 text-sm leading-relaxed mb-6">
                    A voluntary community dedicated to developing data and AI professionals' capabilities.
                </p>
                <a href="{{ auth()->guard('member')->check() ? route('profile.show') : route('register') }}" class="inline-block bg-yemdat-gold text-yemdat-brown font-bold px-6 py-2 rounded-lg hover:bg-white transition shadow-md">
                    {{ auth()->guard('member')->check() ? (app()->getLocale() == 'ar' ? 'ملفي الشخصي' : 'My Profile') : __('home.btn_join') }}
                </a>
            </div>

            <!-- Quick Links -->
            <div>
                <h3 class="text-lg font-semibold mb-4 text-yemdat-gold">Quick Links</h3>
                <ul class="space-y-2 text-sm text-gray-300">
                    <li><a href="{{ route('home') }}" class="hover:text-white transition">{{ __('nav.home') }}</a></li>
                    <li><a href="{{ route('about') }}" class="hover:text-white transition">{{ __('nav.about') }}</a></li>
                    <li><a href="{{ route('vision') }}" class="hover:text-white transition">{{ __('nav.vision_mission') }}</a></li>
                    <li><a href="{{ route('membership') }}" class="hover:text-white transition">{{ __('nav.membership') }}</a></li>
                    <li><a href="{{ route('training') }}" class="hover:text-white transition">{{ __('nav.training_events') }}</a></li>
                    <li><a href="{{ route('news') }}" class="hover:text-white transition">{{ __('nav.news') }}</a></li>
                    <li><a href="{{ route('contact') }}" class="hover:text-white transition">{{ __('nav.contact') }}</a></li>
                </ul>
            </div>

            <!-- Follow Us -->
            <div>
                <h3 class="text-lg font-semibold mb-4 text-yemdat-gold">Follow Us</h3>
                <div class="flex space-x-4">
                    <!-- Social Icons -->
                    @if(isset($settings['site_facebook']) && $settings['site_facebook'])
                    <a href="{{ $settings['site_facebook'] }}" target="_blank" class="bg-white/10 p-2 rounded hover:bg-white/20 transition">
                        <span class="sr-only">Facebook</span>
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path fill-rule="evenodd" d="M22 12c0-5.523-4.477-10-10-10S2 6.477 2 12c0 4.991 3.657 9.128 8.438 9.878v-6.987h-2.54V12h2.54V9.797c0-2.506 1.492-3.89 3.777-3.89 1.094 0 2.238.195 2.238.195v2.46h-1.26c-1.243 0-1.63.771-1.63 1.562V12h2.773l-.443 2.89h-2.33v6.988C18.343 21.128 22 16.991 22 12z" clip-rule="evenodd" /></svg>
                    </a>
                    @endif

                    @if(isset($settings['site_twitter']) && $settings['site_twitter'])
                    <a href="{{ $settings['site_twitter'] }}" target="_blank" class="bg-white/10 p-2 rounded hover:bg-white/20 transition">
                        <span class="sr-only">Twitter</span>
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path d="M8.29 20.251c7.547 0 11.675-6.253 11.675-11.675 0-.178 0-.355-.012-.53A8.348 8.348 0 0022 5.92a8.19 8.19 0 01-2.357.646 4.118 4.118 0 001.804-2.27 8.224 8.224 0 01-2.605.996 4.107 4.107 0 00-6.993 3.743 11.65 11.65 0 01-8.457-4.287 4.106 4.106 0 001.27 5.477A4.072 4.072 0 012.8 9.713v.052a4.105 4.105 0 003.292 4.022 4.095 4.095 0 01-1.853.07 4.108 4.108 0 003.834 2.85A8.233 8.233 0 012 18.407a11.616 11.616 0 006.29 1.84" /></svg>
                    </a>
                    @endif

                    @if(isset($settings['site_linkedin']) && $settings['site_linkedin'])
                    <a href="{{ $settings['site_linkedin'] }}" target="_blank" class="bg-white/10 p-2 rounded hover:bg-white/20 transition">
                        <span class="sr-only">LinkedIn</span>
                         <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path fill-rule="evenodd" d="M19 0h-14c-2.761 0-5 2.239-5 5v14c0 2.761 2.239 5 5 5h14c2.762 0 5-2.239 5-5v-14c0-2.761-2.238-5-5-5zm-11 19h-3v-11h3v11zm-1.5-12.268c-.966 0-1.75-.79-1.75-1.764s.784-1.764 1.75-1.764 1.75.79 1.75 1.764-.783 1.764-1.75 1.764zm13.5 12.268h-3v-5.604c0-3.368-4-3.113-4 0v5.604h-3v-11h3v1.765c1.396-2.586 7-2.777 7 2.476v6.759z" clip-rule="evenodd" /></svg>
                    </a>
                    @endif

                    <!-- WhatsApp -->
                    @if(isset($settings['site_whatsapp']) && $settings['site_whatsapp'])
                    <a href="{{ $settings['site_whatsapp'] }}" target="_blank" class="bg-white/10 p-2 rounded hover:bg-white/20 transition">
                        <span class="sr-only">WhatsApp</span>
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981zm11.387-5.464c-.074-.124-.272-.198-.57-.347-.297-.149-1.758-.868-2.031-.967-.272-.099-.47-.149-.669.149-.198.297-.768.967-.941 1.165-.173.198-.347.223-.644.074-.297-.149-1.255-.462-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.521.151-.172.2-.296.3-.495.099-.198.05-.372-.025-.521-.075-.148-.669-1.611-.916-2.206-.242-.579-.487-.501-.669-.51l-.57-.01c-.198 0-.52.074-.792.372s-1.04 1.016-1.04 2.479 1.065 2.876 1.213 3.074c.149.198 2.095 3.2 5.076 4.487.709.306 1.263.489 1.694.626.712.226 1.36.194 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.695.248-1.29.173-1.414z"/></svg>
                    </a>
                    @endif

                    @if(isset($settings['site_instagram']) && $settings['site_instagram'])
                    <a href="{{ $settings['site_instagram'] }}" target="_blank" class="bg-white/10 p-2 rounded hover:bg-white/20 transition">
                        <span class="sr-only">Instagram</span>
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.069-4.85.069-3.204 0-3.584-.012-4.849-.069-3.225-.149-4.771-1.664-4.919-4.919-.058-1.265-.069-1.644-.069-4.849 0-3.204.012-3.584.069-4.849.149-3.228 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/></svg>
                    </a>
                    @endif
                </div>
            </div>
        </div>

        <div class="mt-12 border-t border-yemdat-gold/20 pt-8 text-center text-xs text-gray-400">
            <p>YEMDAT – Yemeni Data Hub</p>
            <p>All rights reserved © 2026</p>
        </div>
    </div>
</footer>
