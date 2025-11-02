@props(['name', 'route', 'icon'])

@php
$isActive = request()->routeIs($route);
@endphp

<a href="{{ route($route) }}"
   class="flex items-center gap-3 px-3 py-2 rounded-md text-sm font-medium transition
          {{ $isActive ? 'bg-gray-800 text-white' : 'text-gray-400 hover:bg-gray-800 hover:text-white' }}">
   <x-dynamic-component :component="'lucide-'.$icon" class="w-4 h-4" />
   {{ $name }}
</a>
