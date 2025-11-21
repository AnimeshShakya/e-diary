<x-dashboard.card class="hover:border-emerald-500/50">
    <div class="flex items-center justify-between mb-6">
        <h3 class="text-lg font-semibold text-[#e2e8f0]">Time Usage Trend (Last 60 Days)</h3>
        <span class="text-sm text-[#94a3b8]">Hours per Day</span>
    </div>

    @php
        $labels   = $chart['labels'] ?? [];
        $datasets = $chart['datasets'] ?? [];
    @endphp

    @if(count($labels) > 0)
        <div class="relative w-full h-96">
            <canvas id="timeUsageTrendChart"></canvas>
        </div>
    @else
        <div class="text-center py-12">
            <div class="w-16 h-16 mx-auto mb-4 rounded-full bg-[#1a1a1a]/50 flex items-center justify-center">
                <svg class="w-8 h-8 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                          d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <p class="text-gray-500 text-sm">No time usage data for the last 60 days</p>
        </div>
    @endif

    <script>
        (function(){
            const init = function () {
                const el = document.getElementById('timeUsageTrendChart');
                if (!el) return;
                if (el.dataset.rendered) return;
                el.dataset.rendered = '1';

                const labels = @json($labels);
                const datasets = @json($datasets);

                if (!labels.length) return;

                const ctx = el.getContext('2d');

                new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: labels,
                        datasets: datasets.map(ds => ({
                            label: ds.label,
                            data: ds.data,
                            borderColor: ds.borderColor,
                            backgroundColor: ds.borderColor + '20',
                            borderWidth: 2,
                            fill: true,
                            tension: 0.4,
                            pointRadius: 0,
                            pointHoverRadius: 5,
                            pointHoverBackgroundColor: ds.borderColor,
                            pointHoverBorderColor: '#fff',
                            pointHoverBorderWidth: 2
                        }))
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        interaction: { mode: 'index', intersect: false },
                        plugins: {
                            legend: {
                                display: true,
                                position: 'top',
                                labels: {
                                    color: '#e5e7eb',
                                    font: { size: 12, weight: '500' },
                                    usePointStyle: true,
                                    padding: 15
                                }
                            },
                            tooltip: {
                                backgroundColor: '#1f2937',
                                titleColor: '#f9fafb',
                                bodyColor: '#d1d5db',
                                borderWidth: 1,
                                borderColor: 'rgba(255,255,255,0.1)',
                                cornerRadius: 8,
                                padding: 12,
                                callbacks: {
                                    label: function(context) {
                                        return `${context.dataset.label}: ${context.parsed.y?.toFixed(1)} hrs`;
                                    }
                                }
                            }
                        },
                        scales: {
                            x: {
                                grid: { display: false },
                                ticks: {
                                    color: '#9ca3af',
                                    font: { size: 10 },
                                    maxRotation: 45,
                                    minRotation: 45,
                                    autoSkip: true,
                                    maxTicksLimit: 15
                                }
                            },
                            y: {
                                beginAtZero: true,
                                grid: { color: 'rgba(255,255,255,0.05)', drawBorder: false },
                                ticks: {
                                    color: '#71717a',
                                    font: { size: 11 },
                                    callback: (value) => value + 'h'
                                },
                                suggestedMax: 15
                            }
                        },
                        animation: { duration: 800, easing: 'easeOutQuart' }
                    }
                });
            };

            if (window.Chart) init();
            else {
                const s = document.createElement('script');
                s.src = 'https://cdn.jsdelivr.net/npm/chart.js';
                s.onload = init;
                document.head.appendChild(s);
            }

            Livewire.on('chart-data-loaded-time-usage', () => {
                if (window.Chart) init();
            });
        })();
    </script>
</x-dashboard.card>
