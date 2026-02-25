<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }} - Admin</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <style>
        /* Force Desktop Sidebar behavior */
        @media (min-width: 1024px) {
            .desktop-sidebar {
                display: flex !important;
                position: static !important;
                height: 100vh;
                overflow-y: auto;
                flex-shrink: 0;
            }
            .mobile-sidebar {
                display: none !important;
            }
        }
        /* Force Mobile Sidebar behavior */
        @media (max-width: 1023px) {
            .desktop-sidebar {
                display: none !important;
            }
            .mobile-sidebar {
                display: flex !important;
                position: fixed !important;
                top: 0;
                bottom: 0;
                z-index: 50;
            }
        }
    </style>
</head>
<body class="font-sans antialiased bg-gray-50 text-gray-900" x-data="{ sidebarOpen: false }">
    <div class="flex h-screen overflow-hidden">
        
        <!-- Sidebar Backdrop (Mobile) -->
        <div x-show="sidebarOpen" @click="sidebarOpen = false" x-transition:enter="transition-opacity ease-linear duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="transition-opacity ease-linear duration-300" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 bg-gray-900/80 z-20 lg:hidden"></div>

        <!-- Mobile Sidebar (Fixed/Off-canvas) -->
        <aside 
            :style="sidebarOpen ? 'transform: translateX(0)' : (document.dir === 'rtl' ? 'transform: translateX(100%)' : 'transform: translateX(-100%)')"
            class="mobile-sidebar w-64 bg-yemdat-brown text-white transition-transform duration-300 shadow-xl flex flex-col lg:hidden"
        >
            @include('components.admin.sidebar-content')
        </aside>

        <!-- Desktop Sidebar (Static/In-flow) -->
        <aside class="desktop-sidebar hidden lg:flex flex-col w-64 bg-yemdat-brown text-white shrink-0 h-screen overflow-y-auto">
            @include('components.admin.sidebar-content')
        </aside>

        <!-- Main Content -->
        <div class="flex-1 flex flex-col overflow-hidden">
            
            <!-- Top Header -->
            <header class="bg-white shadow-sm h-16 flex items-center justify-between px-6 lg:px-8 z-10">
                <div class="flex items-center">
                    <!-- Burger Menu (Mobile) -->
                    <button @click="sidebarOpen = true" class="text-gray-500 hover:text-gray-700 focus:outline-none lg:hidden mr-4 rtl:ml-4 rtl:mr-0">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
                    </button>
                    
                    @if (isset($header))
                        <div class="font-semibold text-xl text-yemdat-brown leading-tight">
                            {{ $header }}
                        </div>
                    @endif
                </div>

                <div class="flex items-center gap-4">
                     <!-- User Name or additional topnav items -->
                     <span class="text-sm font-medium text-gray-500">Admin Panel</span>
                </div>
            </header>

            <!-- Scrollable Content -->
            <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-50 p-6">
                @if(session('success'))
                    <div class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative">
                        <span class="block sm:inline">{{ session('success') }}</span>
                    </div>
                @endif
                
                {{ $slot }}
            </main>
        </div>
    </div>
</body>
</html>
