<x-dashboard.card class="hover:border-purple-500/50">
    <div class="flex items-center justify-between mb-6">
        <h3 class="text-lg font-semibold text-gray-200">Yearly Checklist Comparison</h3>
        <span class="text-sm text-gray-400">Completion Rate (%)</span>
    </div>

    @php
        $months = $chart['labels'] ?? ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'];
        $datasets = $chart['datasets'] ?? [];
    @endphp

    @if(count($datasets) > 0)
            {{-- Chart --}}
            <div class="relative w-full h-80">
                <canvas id="yearlyChecklistChart"></canvas>
            </div>

            <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
            <script>
                document.addEventListener('DOMContentLoaded', function () {
                    const ctx = document.getElementById('yearlyChecklistChart').getContext('2d');
                    const chart = new Chart(ctx, {
                        type: 'bar',
                        data: {
                            labels: @json($months),
                            datasets: @json($datasets)
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
                });
            </script>
        @else
            <div class="text-center py-12">
                <div class="w-16 h-16 mx-auto mb-4 rounded-full bg-gray-800/50 flex items-center justify-center">
                    <svg class="w-8 h-8 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                              d="M16 8v8m-4-5v5m-4-2v2m-2 4h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                </div>
            <p class="text-gray-500 text-sm">No yearly checklist data available</p>
        </div>
    @endif
</x-dashboard.card>
