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
    <body class="font-sans antialiased">
        <div class="min-h-screen bg-gray-100 dark:bg-gray-900">
            @include('layouts.navigation')

            <!-- Sidebar -->
            <div class="flex flex-col w-64 bg-white dark:bg-gray-800 h-full">
                <!-- Sidebar Content -->
                <div class="p-6">
                    <h2 class="text-lg font-semibold text-gray-800 dark:text-white">Menu</h2>
                    <ul class="mt-6">
                        <li class="mb-4"><a href="{{ route('dashboard') }}" class="text-gray-600 dark:text-gray-200 hover:text-gray-800 dark:hover:text-white">Dashboard</a></li>
                        <li class="mb-4"><a href="{{ route('diary') }}" class="text-gray-600 dark:text-gray-200 hover:text-gray-800 dark:hover:text-white">Diary</a></li>
                    </ul>
                </div>
            </div>

            <!-- Page Heading -->
            @if (isset($header))
                <header class="bg-white dark:bg-gray-800 shadow">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endif

            <!-- Page Content -->
            <main>
                {{ $slot }}
            </main>
        </div>

        <!-- JavaScript to toggle dark mode -->
        <script>
            function toggleDarkMode() {
                const body = document.querySelector('html');
                body.classList.toggle('dark-mode');
            }
        </script>
    </body>
</html>
