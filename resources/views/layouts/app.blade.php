<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ $title ?? 'Dashboard' }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        @livewireStyles
    </head>
    <body class="font-sans antialiased bg-[#0c0c0c] text-gray-200 flex min-h-screen">
        {{-- Sidebar --}}
        <aside class="w-64 bg-[#1a1a1a] border-r border-gray-800 p-4 flex flex-col">
            <div class="mb-6">
                <h1 class="text-xl font-bold">Daily Diary</h1>
                <p class="text-gray-400 text-sm mt-1">{{ Auth::user()->name ?? 'Guest' }}</p>
            </div>

            <nav class="flex flex-col space-y-2">
                <a href="{{ route('dashboard') }}" class="p-2 rounded hover:bg-gray-800 transition">🏠 Dashboard</a>
                <a href="{{ route('diaries.index') }}" class="p-2 rounded hover:bg-gray-800 transition">📖 My Diaries</a>
                <a href="{{ route('profile.edit') }}" class="p-2 rounded hover:bg-gray-800 transition">⚙️ Profile</a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="p-2 rounded hover:bg-red-700 transition text-left w-full">🚪 Logout</button>
                </form>
            </nav>
        </aside>

        {{-- Main Content --}}
        <main class="flex-1 p-8 space-y-6 overflow-y-auto">
            @isset($header)
                <header class="flex justify-between items-center mb-6">
                    <h2 class="text-2xl font-semibold">{{ $header }}</h2>
                </header>
            @endisset

            <div class="bg-[#1a1a1a]/60 backdrop-blur-lg p-6 rounded-2xl shadow-md border border-gray-800">
                {{ $slot }}
            </div>
        </main>

        @livewireScripts
    </body>
</html>
