<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'Dashboard' }}</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
    
    <style>
        @keyframes fade-in-up {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .animate-fade-in-up {
            animation: fade-in-up 0.6s ease-out forwards;
            opacity: 0;
        }
    </style>
</head>
<body class="bg-[#0c0c0c] text-gray-100 font-sans antialiased" x-data="{ sidebarOpen: true }">
    @php
        $user = auth()->user();
    @endphp

    <div class="min-h-screen flex">
        {{-- Floating Sidebar --}}
        <aside 
            x-show="sidebarOpen"
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="-translate-x-full"
            x-transition:enter-end="translate-x-0"
            x-transition:leave="transition ease-in duration-300"
            x-transition:leave-start="translate-x-0"
            x-transition:leave-end="-translate-x-full"
            class="fixed lg:sticky top-0 h-screen w-64 bg-gradient-to-b from-[#111111] to-[#0a0a0a] backdrop-blur-xl border-r border-gray-800/50 z-50 flex flex-col shadow-2xl"
        >
            {{-- Logo Section --}}
            <div class="p-6 border-b border-gray-800/50">
                <a href="{{ route('dashboard') }}" class="flex items-center gap-3 group">
                    <div class="w-10 h-10 rounded-2xl bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center shadow-lg group-hover:shadow-indigo-500/50 transition-all duration-300">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                        </svg>
                    </div>
                    <div>
                        <div class="font-semibold text-lg">Daily Diary</div>
                        <div class="text-xs text-gray-500">Dashboard</div>
                    </div>
                </a>
            </div>

            {{-- Navigation --}}
            <nav class="flex-1 px-4 py-6 space-y-2 overflow-y-auto">
                <x-admin.sidebar-link href="{{ route('dashboard') }}" icon="home">
                    Dashboard
                </x-admin.sidebar-link>
                <x-admin.sidebar-link href="{{ route('diaries.index') }}" icon="book-open">
                    My Diaries
                </x-admin.sidebar-link>
                @if(\Illuminate\Support\Facades\Route::has('scores'))
                    <x-admin.sidebar-link href="{{ route('scores') }}" icon="score">
                        Scores
                    </x-admin.sidebar-link>
                @endif
                @if(\Illuminate\Support\Facades\Route::has('affirmations.index'))
                    <x-admin.sidebar-link href="{{ route('affirmations.index') }}" icon="notadminaffirmations">
                        Affirmations
                    </x-admin.sidebar-link>
                @endif
                <x-admin.sidebar-link href="{{ route('profile.edit') }}" icon="profile">
                    Profile
                </x-admin.sidebar-link>
            </nav>

            {{-- User Profile & Logout --}}
            <div class="p-4 border-t border-gray-800/50">
                <div class="flex items-center gap-3 mb-3 p-3 rounded-xl bg-gray-900/30">
                    <div class="w-10 h-10 rounded-full bg-gradient-to-br from-indigo-400 to-purple-500 flex items-center justify-center text-sm font-semibold">
                        {{ substr($user->name ?? 'U', 0, 1) }}
                    </div>
                    <div class="flex-1 min-w-0">
                        <div class="text-sm font-medium truncate">{{ $user->name ?? 'User' }}</div>
                        <div class="text-xs text-gray-500 truncate">{{ $user->email ?? '' }}</div>
                    </div>
                </div>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="w-full flex items-center gap-2 px-4 py-2.5 text-sm font-medium text-red-400 hover:text-red-300 hover:bg-red-500/10 rounded-xl transition-all duration-200">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                        </svg>
                        Logout
                    </button>
                </form>
            </div>
        </aside>

        {{-- Main Content Area --}}
        <div class="flex-1 flex flex-col min-h-screen">
            {{-- Top Navbar --}}
            <header class="sticky top-0 z-40 bg-[#0c0c0c]/80 backdrop-blur-xl border-b border-gray-800/50">
                <div class="flex items-center justify-between px-6 py-4">
                    {{-- Breadcrumb & Sidebar Toggle --}}
                    <div class="flex items-center gap-4">
                        <button 
                            @click="sidebarOpen = !sidebarOpen"
                            class="p-2 rounded-xl hover:bg-gray-800/50 transition-colors lg:hidden"
                        >
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                            </svg>
                        </button>
                        @isset($header)
                            <h1 class="text-xl font-semibold bg-gradient-to-r from-indigo-400 to-purple-400 bg-clip-text text-transparent">
                                {{ $header }}
                            </h1>
                        @endisset
                    </div>

                    {{-- Right Actions --}}
                    <div class="flex items-center gap-3">
                        @livewire('notification-bell')
                    </div>
                </div>
            </header>

            {{-- Main Content --}}
            <main class="flex-1 p-6 lg:p-8 overflow-auto">
                {{ $slot }}
            </main>

            {{-- Footer --}}
            <footer class="px-6 py-4 border-t border-gray-800/50 text-center text-xs text-gray-500">
                © {{ date('Y') }} Daily Diary. All rights reserved.
            </footer>
        </div>
    </div>

    @livewireScripts
</body>
</html>
