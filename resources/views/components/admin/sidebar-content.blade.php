            <!-- Logo -->
            <div class="flex items-center justify-center h-20 bg-yemdat-dark shadow-md shrink-0">
                <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-2">
                    <svg width="32" height="32" viewBox="0 0 50 50" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <rect x="10" y="5" width="30" height="8" rx="2" fill="#F2CB57"/>
                        <rect x="5" y="16" width="35" height="8" rx="2" fill="#C88D16"/>
                        <rect x="0" y="27" width="40" height="8" rx="2" fill="#FFFFFF"/>
                    </svg>
                    <span class="text-xl font-bold tracking-wider text-yemdat-gold">YEMDAT (v2)</span>
                </a>
            </div>

            <!-- Nav Links -->
            <nav class="flex-1 px-4 py-6 space-y-2 overflow-y-auto">
                <a href="{{ route('admin.dashboard') }}" class="flex items-center px-4 py-3 rounded-lg transition-colors {{ request()->routeIs('admin.dashboard') ? 'bg-yemdat-gold text-yemdat-brown font-bold' : 'text-gray-300 hover:bg-white/10 hover:text-white' }}">
                    <svg class="w-5 h-5 mr-3 rtl:ml-3 rtl:mr-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path></svg>
                    Dashboard
                </a>

                @if(auth()->user()->hasPermission('analytics'))
                <div class="pt-4 pb-2 text-xs font-semibold text-gray-400 uppercase tracking-wider px-4">Analytics</div>
                <a href="{{ route('admin.analytics.members') }}" class="flex items-center px-4 py-3 rounded-lg transition-colors {{ request()->routeIs('admin.analytics.members') ? 'bg-yemdat-gold text-yemdat-brown font-bold' : 'text-gray-300 hover:bg-white/10 hover:text-white' }}">
                    <svg class="w-5 h-5 mr-3 rtl:ml-3 rtl:mr-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                    Member Stats
                </a>
                <a href="{{ route('admin.analytics.events') }}" class="flex items-center px-4 py-3 rounded-lg transition-colors {{ request()->routeIs('admin.analytics.events') ? 'bg-yemdat-gold text-yemdat-brown font-bold' : 'text-gray-300 hover:bg-white/10 hover:text-white' }}">
                    <svg class="w-5 h-5 mr-3 rtl:ml-3 rtl:mr-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.488 9H15V3.512A9.025 9.025 0 0120.488 9z"></path></svg>
                    Event Reports
                </a>
                @endif

                @if(auth()->user()->hasPermission('events') || auth()->user()->hasPermission('posts'))
                <div class="pt-4 pb-2 text-xs font-semibold text-gray-400 uppercase tracking-wider px-4">Content</div>

                    @if(auth()->user()->hasPermission('events'))
                    <a href="{{ route('admin.events.index') }}" class="flex items-center px-4 py-3 rounded-lg transition-colors {{ request()->routeIs('admin.events.*') ? 'bg-yemdat-gold text-yemdat-brown font-bold' : 'text-gray-300 hover:bg-white/10 hover:text-white' }}">
                        <svg class="w-5 h-5 mr-3 rtl:ml-3 rtl:mr-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                        Events
                    </a>
                    @endif

                    @if(auth()->user()->hasPermission('posts'))
                    <a href="{{ route('admin.posts.index') }}" class="flex items-center px-4 py-3 rounded-lg transition-colors {{ request()->routeIs('admin.posts.*') ? 'bg-yemdat-gold text-yemdat-brown font-bold' : 'text-gray-300 hover:bg-white/10 hover:text-white' }}">
                        <svg class="w-5 h-5 mr-3 rtl:ml-3 rtl:mr-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9.5a2.5 2.5 0 00-2.5-2.5H15M9 11l3 3m0 0l3-3m-3 3V8"></path></svg>
                        News / Posts
                    </a>
                    @endif

                    @if(auth()->user()->hasPermission('settings'))
                    <a href="{{ route('admin.membership-tiers.index') }}" class="flex items-center px-4 py-3 rounded-lg transition-colors {{ request()->routeIs('admin.membership-tiers.*') ? 'bg-yemdat-gold text-yemdat-brown font-bold' : 'text-gray-300 hover:bg-white/10 hover:text-white' }}">
                        <svg class="w-5 h-5 mr-3 rtl:ml-3 rtl:mr-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                        Membership Plans
                    </a>
                    <a href="{{ route('admin.emails.index') }}" class="flex items-center px-4 py-3 rounded-lg transition-colors {{ request()->routeIs('admin.emails.*') ? 'bg-yemdat-gold text-yemdat-brown font-bold' : 'text-gray-300 hover:bg-white/10 hover:text-white' }}">
                        <svg class="w-5 h-5 mr-3 rtl:ml-3 rtl:mr-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                        Email Templates
                    </a>
                    @endif
                @endif

                @if(auth()->user()->hasPermission('members'))
                <div class="pt-4 pb-2 text-xs font-semibold text-gray-400 uppercase tracking-wider px-4">People</div>

                <a href="{{ route('admin.members.index') }}" class="flex items-center px-4 py-3 rounded-lg transition-colors {{ request()->routeIs('admin.members.*') ? 'bg-yemdat-gold text-yemdat-brown font-bold' : 'text-gray-300 hover:bg-white/10 hover:text-white' }}">
                    <svg class="w-5 h-5 mr-3 rtl:ml-3 rtl:mr-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                    Members
                </a>
                @endif

                @if(auth()->user()->hasPermission('messages'))
                <a href="{{ route('admin.messages.index') }}" class="flex items-center px-4 py-3 rounded-lg transition-colors {{ request()->routeIs('admin.messages.*') ? 'bg-yemdat-gold text-yemdat-brown font-bold' : 'text-gray-300 hover:bg-white/10 hover:text-white' }}">
                    <svg class="w-5 h-5 mr-3 rtl:ml-3 rtl:mr-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                    Messages
                </a>
                @endif

                @if(auth()->user()->hasPermission('trainers'))
                <a href="{{ route('admin.trainers.index') }}" class="flex items-center px-4 py-3 rounded-lg transition-colors {{ request()->routeIs('admin.trainers.*') ? 'bg-yemdat-gold text-yemdat-brown font-bold' : 'text-gray-300 hover:bg-white/10 hover:text-white' }}">
                    <svg class="w-5 h-5 mr-3 rtl:ml-3 rtl:mr-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                    Trainer Requests
                </a>
                @endif

                @if(auth()->user()->isSuperAdmin())
                <div class="pt-4 pb-2 text-xs font-semibold text-gray-400 uppercase tracking-wider px-4">Administration</div>
                
                <a href="{{ route('admin.users.index') }}" class="flex items-center px-4 py-3 rounded-lg transition-colors {{ request()->routeIs('admin.users.*') ? 'bg-yemdat-gold text-yemdat-brown font-bold' : 'text-gray-300 hover:bg-white/10 hover:text-white' }}">
                    <svg class="w-5 h-5 mr-3 rtl:ml-3 rtl:mr-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                    Manage Users
                </a>
                @endif

                @if(auth()->user()->hasPermission('settings'))
                <div class="pt-4 pb-2 text-xs font-semibold text-gray-400 uppercase tracking-wider px-4">System</div>

                <a href="{{ route('admin.settings') }}" class="flex items-center px-4 py-3 rounded-lg transition-colors {{ request()->routeIs('admin.settings') ? 'bg-yemdat-gold text-yemdat-brown font-bold' : 'text-gray-300 hover:bg-white/10 hover:text-white' }}">
                    <svg class="w-5 h-5 mr-3 rtl:ml-3 rtl:mr-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                    Settings
                </a>
                @endif
            </nav>
            
            <div class="p-4 border-t border-yemdat-dark shrink-0">
                 <!-- Profile Link -->
                 <a href="{{ route('admin.profile.edit') }}" class="flex items-center justify-center w-full px-4 py-2 mb-2 bg-yemdat-gold/10 text-yemdat-gold hover:bg-yemdat-gold hover:text-yemdat-brown rounded-lg transition mb-2">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                    {{ auth()->user()->name }}
                </a>

                 <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="w-full flex items-center justify-center px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition">
                         <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                        Logout
                    </button>
                </form>
            </div>
