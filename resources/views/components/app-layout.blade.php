<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700" rel="stylesheet" />
        <link href="https://fonts.bunny.net/css?family=tajawal:400,500,700" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        
        <!-- Favicon -->
        <link rel="icon" type="image/svg+xml" href="data:image/svg+xml,%3Csvg width='40' height='40' viewBox='0 0 50 50' fill='none' xmlns='http://www.w3.org/2000/svg'%3E%3Crect x='10' y='5' width='30' height='8' rx='2' fill='%23F2CB57'/%3E%3Crect x='5' y='16' width='35' height='8' rx='2' fill='%23C88D16'/%3E%3Crect x='0' y='27' width='40' height='8' rx='2' fill='%23593E2D'/%3E%3C/svg%3E">
        <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('logo.png') }}" onerror="this.onerror=null; this.href='/favicon.ico';">
    </head>
    <body class="{{ app()->getLocale() === 'ar' ? 'font-arabic' : 'font-sans' }} antialiased bg-yemdat-beige text-yemdat-brown">
        <x-ui.toast />
        <div class="min-h-screen">
            <!-- Notification Bar -->
            @if(isset($settings['notification_bar_enabled']) && $settings['notification_bar_enabled'])
                <div class="bg-yemdat-brown text-yemdat-gold text-center py-2 px-4 text-sm font-medium">
                    {{ $settings['notification_bar_text'] ?? '' }}
                </div>
            @endif

            @include('layouts.navigation')

            <!-- Page Content -->
            <main>
                {{ $slot }}
            </main>

            @include('layouts.footer')
        </div>
    </body>
</html>
