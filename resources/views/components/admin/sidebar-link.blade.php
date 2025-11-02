@php
    $isActive = request()->url() === url($href) || request()->is(trim(parse_url($href, PHP_URL_PATH), '/') . '*');
    
    // Icon SVG paths mapping
    $icons = [
        'home' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>',
        'users' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>',
        'book-open' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>',
        'shield-alert' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.618 5.984A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016zM12 9v2m0 4h.01"></path>',
    ];
    $iconPath = $icons[$icon] ?? '';
@endphp

<a 
    href="{{ $href }}" 
    class="group relative flex items-center gap-3 px-3 py-2.5 rounded-xl font-medium text-sm transition-all duration-200 overflow-hidden
        {{ $isActive 
            ? 'text-indigo-400 bg-gradient-to-r from-indigo-500/10 to-purple-500/10 shadow-lg shadow-indigo-500/10' 
            : 'text-gray-400 hover:text-gray-200 hover:bg-gray-800/50' 
        }}"
>
    {{-- Active indicator border --}}
    @if($isActive)
        <span class="absolute left-0 top-1/2 -translate-y-1/2 w-1 h-8 bg-gradient-to-b from-indigo-500 to-purple-500 rounded-r-full shadow-lg shadow-indigo-500/50"></span>
    @endif

    {{-- Icon --}}
    @if($iconPath)
        <svg class="w-5 h-5 flex-shrink-0 transition-transform duration-200 group-hover:scale-110 {{ $isActive ? 'text-indigo-400' : '' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            {!! $iconPath !!}
        </svg>
    @endif
    
    {{-- Link Text --}}
    <span class="flex-1">{{ $slot }}</span>

    {{-- Hover glow effect --}}
    <span class="absolute inset-0 rounded-xl bg-gradient-to-r from-indigo-500/0 via-purple-500/0 to-indigo-500/0 opacity-0 group-hover:opacity-100 transition-opacity duration-300 pointer-events-none"></span>
</a>
