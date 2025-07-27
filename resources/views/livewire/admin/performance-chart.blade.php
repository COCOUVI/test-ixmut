<div class="h-[500px]">
    <canvas id="performanceChart"></canvas>

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script>
            const scores = @json($scores);
            const labels = scores.map(e => e.name);
            const data = scores.map(e => e.score);

            new Chart(document.getElementById('performanceChart'), {
                type: 'bar',
                data: {
                    labels,
                    datasets: [{
                        label: 'Score',
                        data,
                        backgroundColor: 'rgba(54, 162, 235, 0.7)'
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false },
                        title: { display: true, text: 'Score de performance du jour' }
                    }
                }
            });
        </script>
    @endpush
</div>
