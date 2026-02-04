<x-dashboard.card class="hover:border-purple-500/50">
    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between mb-4 sm:mb-6 gap-2">
        <h3 class="text-base sm:text-lg font-semibold text-[#e2e8f0]">Yearly Checklist Comparison</h3>
        <span class="text-xs sm:text-sm text-[#94a3b8]">Completion Rate (%)</span>
    </div>

    @php
        $months = $chart['labels'] ?? ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'];
        $datasets = $chart['datasets'] ?? [];
    @endphp

    @if(count($datasets) > 0)
        {{-- Chart --}}
        <div class="relative w-full h-64 sm:h-72 lg:h-80">
            <canvas id="yearlyChecklistChart"></canvas>
        </div>
    @else
        <div class="text-center py-8 sm:py-12">
            <div class="w-12 h-12 sm:w-16 sm:h-16 mx-auto mb-3 sm:mb-4 rounded-full bg-[#1a1a1a]/50 flex items-center justify-center">
                    <svg class="w-6 h-6 sm:w-8 sm:h-8 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                              d="M16 8v8m-4-5v5m-4-2v2m-2 4h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                </div>
            <p class="text-gray-500 text-xs sm:text-sm">No yearly checklist data available</p>
        </div>
    @endif

    <script>
        (function(){
            const init = function () {
                const el = document.getElementById('yearlyChecklistChart');
                if (!el) return;
                if (el.dataset.rendered) return;
                el.dataset.rendered = '1';

                const months = @json($months);
                const datasets = @json($datasets);
                
                if (!datasets || datasets.length === 0) return;

                const ctx = el.getContext('2d');
                new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: months,
                        datasets: datasets
                            },
                            options: {
                                responsive: true,
                                maintainAspectRatio: false,
                                interaction: {
                                    mode: 'index',
                                    intersect: false,
                                },
                                scales: {
                                    x: {
                                        grid: { display: false },
                                        ticks: {
                                            color: '#a1a1aa',
                                            font: { size: 12, weight: '500' },
                                            padding: 8
                                        }
                                    },
                                    y: {
                                        beginAtZero: true,
                                        grid: { color: 'rgba(255,255,255,0.05)' },
                                        ticks: {
                                            color: '#71717a',
                                            stepSize: 20,
                                            font: { size: 11 },
                                            callback: function(value) {
                                                return value + '%';
                                            }
                                        },
                                        suggestedMax: 100
                                    }
                                },
                                plugins: {
                                    legend: {
                                        labels: {
                                            color: '#e5e7eb',
                                            font: { size: 13, weight: '500' },
                                            usePointStyle: true,
                                            padding: 20
                                        }
                                    },
                                    tooltip: {
                                        backgroundColor: '#1f2937',
                                        titleColor: '#f9fafb',
                                        bodyColor: '#d1d5db',
                                        borderWidth: 1,
                                        borderColor: 'rgba(255,255,255,0.1)',
                                        cornerRadius: 8,
                                        padding: 10,
                                        callbacks: {
                                            label: function(context) {
                                                return context.dataset.label + ': ' + context.parsed.y + '%';
                                            }
                                        }
                                    }
                                },
                                animation: {
                                    duration: 900,
                                    easing: 'easeOutQuart'
                                }
                            }
                        });
                    };

                    // Initialize chart immediately or after Chart.js loads
                    if (window.Chart) {
                        init();
                    } else {
                        const s = document.createElement('script');
                        s.src = 'https://cdn.jsdelivr.net/npm/chart.js';
                        s.onload = init;
                        document.head.appendChild(s);
                    }
                    
                    // Also listen for dynamic updates
                    Livewire.on('chart-data-loaded-yearly', () => {
                        if (window.Chart) {
                            init();
                        }
                    });
                })();
            </script>
</x-dashboard.card>
