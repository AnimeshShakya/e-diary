<div class="space-y-8">
    {{-- Monthly Performance Score --}}
    <div>
        @livewire('score-calculator', ['mode' => 'monthly'])
    </div>

    {{-- Lifestyle Dashboard Widgets --}}
    <div class="grid grid-cols-1 gap-6">
        {{-- Render each widget full width --}}
        @livewire('dashboard.monthly-checklist-bar')
        @livewire('dashboard.yearly-checklist-comparison')
        <div>
            @livewire('dashboard.time-usage-trend-component')
        </div>
    </div>
</div>
