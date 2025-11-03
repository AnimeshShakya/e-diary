<x-dashboard.card class="hover:border-emerald-500/50">
    <div class="flex items-center justify-between mb-6">
        <h3 class="text-lg font-semibold text-gray-200">Time Usage Trend (Last 60 Days)</h3>
        <span class="text-sm text-gray-400">Hours per Day</span>
    </div>

    @php
        $labels = $chart['labels'] ?? [];
        $datasets = $chart['datasets'] ?? [];
    @endphp

    @if(count($labels) > 0)
        <div class="relative w-full h-96">
            <canvas id="timeUsageTrendChart"></canvas>
        </div>

        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const el = document.getElementById('timeUsageTrendChart');
                if (!el) return;
                if (el.dataset.rendered) return;
                el.dataset.rendered = '1';

                const ctx = el.getContext('2d');
                new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: @json($labels),
                        datasets: @json($datasets).map((dataset) => {
                            const colors = {
                                'Sleep (avg)': { border: '#A78BFA', bg: 'rgba(167, 139, 250, 0.1)' },
                                'Sleep (MA7)': { border: '#F59E0B', bg: 'rgba(245, 158, 11, 0.1)' },
                                'Management (avg)': { border: '#34D399', bg: 'rgba(52, 211, 153, 0.1)' }
                            };
                            
                            const color = colors[dataset.label] || { border: '#6366F1', bg: 'rgba(99, 102, 241, 0.1)' };

                            return {
                                label: dataset.label,
                                data: dataset.data,
                                borderColor: color.border,
                                backgroundColor: color.bg,
                                borderWidth: 2,
                                borderDash: dataset.borderDash || [],
                                fill: true,
                                tension: 0.4,
                                pointRadius: 0,
                                pointHoverRadius: 5,
                                pointHoverBackgroundColor: color.border,
                                pointHoverBorderColor: '#fff',
                                pointHoverBorderWidth: 2
                            };
                        })
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
                                    padding: 15,
                                    boxWidth: 8,
                                    boxHeight: 8
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
                                        return context.dataset.label + ': ' + 
                                               (context.parsed.y !== null ? context.parsed.y.toFixed(1) + ' hrs' : 'N/A');
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
                                    callback: function(value) { return value + 'h'; }
                                },
                                suggestedMax: 24
                            }
                        },
                        animation: { duration: 800, easing: 'easeOutQuart' }
                    }
                });
            });
        </script>
    @else
        <div class="text-center py-12">
            <div class="w-16 h-16 mx-auto mb-4 rounded-full bg-gray-800/50 flex items-center justify-center">
                <svg class="w-8 h-8 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                          d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <p class="text-gray-500 text-sm">No time usage data for the last 60 days</p>
        </div>
    @endif
</x-dashboard.card>
