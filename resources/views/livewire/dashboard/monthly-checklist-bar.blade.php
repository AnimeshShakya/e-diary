<x-dashboard.card wire:init="load" class="hover:border-indigo-500/50">
    <div class="flex items-center justify-between mb-6">
        <h3 class="text-lg font-semibold text-gray-200">Monthly Checklist (Last 30 Days)</h3>
        <span class="text-sm text-gray-400">Progress Overview</span>
    </div>

    @php
        $labels = $chart['labels'] ?? [];
        $data = $chart['datasets'][0]['data'] ?? [];
        $max = max(30, (int) max($data ?: [0]));
    @endphp

    @if(count($labels) > 0)
            <div class="relative w-full h-64">
                <canvas id="monthlyChecklistChart"></canvas>
            </div>

            <script>
                (function(){
                    const init = function () {
                        const el = document.getElementById('monthlyChecklistChart');
                        if (!el) return;
                        const ctx = el.getContext('2d');
                        new Chart(ctx, {
                        type: 'bar',
                        data: {
                            labels: @json($labels),
                            datasets: [{
                                label: 'Success Count (out of 30)',
                                data: @json($data),
                                borderRadius: 6,
                                backgroundColor: function(context) {
                                    const index = context.dataIndex;
                                    const gradient = ctx.createLinearGradient(0, 0, 0, 300);
                                    gradient.addColorStop(0, 'rgba(99, 102, 241, 0.9)');
                                    gradient.addColorStop(1, 'rgba(168, 85, 247, 0.5)');
                                    return gradient;
                                },
                                hoverBackgroundColor: 'rgba(139, 92, 246, 0.9)',
                                borderWidth: 0,
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            scales: {
                                x: {
                                    grid: { display: false },
                                    ticks: { color: '#a1a1aa', font: { size: 12, weight: '500' } }
                                },
                                y: {
                                    grid: { color: 'rgba(255,255,255,0.05)' },
                                    ticks: { color: '#71717a', stepSize: 5, font: { size: 11 } },
                                    suggestedMax: 30
                                }
                            },
                            plugins: {
                                legend: {
                                    display: false
                                },
                                tooltip: {
                                    backgroundColor: '#1f2937',
                                    titleColor: '#e5e7eb',
                                    bodyColor: '#cbd5e1',
                                    borderWidth: 1,
                                    borderColor: 'rgba(255,255,255,0.1)',
                                    cornerRadius: 8,
                                    padding: 10
                                }
                            },
                            animation: {
                                duration: 800,
                                easing: 'easeOutQuart'
                            }
                        }
                        });
                    };

                    if (window.Chart) {
                        init();
                    } else {
                        const s = document.createElement('script');
                        s.src = 'https://cdn.jsdelivr.net/npm/chart.js';
                        s.onload = init;
                        document.head.appendChild(s);
                    }
                })();
            </script>
        @else
            <div class="text-center py-12">
                <div class="w-16 h-16 mx-auto mb-4 rounded-full bg-gray-800/50 flex items-center justify-center">
                    <svg class="w-8 h-8 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                    </svg>
                </div>
            <p class="text-gray-500 text-sm">No checklist data for the last 30 days</p>
        </div>
    @endif
</x-dashboard.card>
