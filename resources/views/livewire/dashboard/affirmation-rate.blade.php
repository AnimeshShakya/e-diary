<div class="rounded-2xl border border-[#2a2a2a]/60 bg-[#111111]/30 p-6">
    <h3 class="text-lg font-semibold mb-4">Affirmation Completion (30 Days)</h3>
    <canvas id="affirmationRateChart"></canvas>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('livewire:init', () => {
    const el = document.getElementById('affirmationRateChart');
    if (!el) return; if (el.dataset.rendered) return; el.dataset.rendered = '1';
    const ctx = el.getContext('2d');
    const chartData = @json($chart);
    new Chart(ctx, { type: 'bar', data: chartData, options: { responsive: true, scales: { y: { beginAtZero: true, max: 100 } } } });
});
</script>
@endpush
