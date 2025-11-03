<div {{ $attributes->merge([
    'class' => 'group relative p-6 rounded-2xl bg-gradient-to-br from-[#1a1a1a] to-[#111111] 
                border border-gray-800/50 transition-all duration-300 overflow-hidden animate-fade-in-up min-h-[30rem]'
]) }}>
    {{-- Hover Glow --}}
    <div class="absolute inset-0 bg-gradient-to-br from-indigo-500/5 to-purple-500/5 
                opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>

    <div class="relative z-10">
        {{ $slot }}
    </div>
</div>
