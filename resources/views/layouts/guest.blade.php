<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans text-dark">
        <div class="min-vh-100 d-flex flex-column justify-content-center align-items-center pt-5 pt-sm-0 bg-light" style="background: linear-gradient(to bottom right, #f0f9ff, #e0e7ff);">
            <div class="text-center">
                <a href="/">
                    <x-application-logo class="w-20 h-20 mx-auto" />
                </a>
                <h1 class="mt-3 h2 fw-bold text-dark">QREN Attendance System</h1>
                <p class="mt-2 text-muted">Login to access your dashboard</p>
            </div>

            <div class="w-100 max-w-sm mt-4 p-4 bg-white shadow-lg overflow-hidden rounded-3 border border-light">
                {{ $slot }}
            </div>
        </div>
    </body>
</html>
