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

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        
        <!-- Favicon -->
        <link rel="icon" href="{{ asset('favicon.ico') }}">
    </head>
    <body class="font-sans antialiased bg-yemdat-beige text-yemdat-brown">
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
