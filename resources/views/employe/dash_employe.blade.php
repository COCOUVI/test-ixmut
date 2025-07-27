@extends('layouts.master')

@section('title', 'Tableau de bord Employé')

@section('page-title', 'Accueil')

@section('content')
<div class="max-w-5xl mx-auto px-4 py-6">
    <h2 class="text-2xl font-bold text-dark mb-6 text-center">Classement journalier des employés</h2>

    <div class="w-full overflow-x-auto">
        <div class="min-w-[300px] h-[500px] md:h-[600px] bg-white rounded-xl shadow-lg p-4">
            <canvas id="performanceChart" class="w-full h-full"></canvas>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    const scores = @json(collect($scores)->sortByDesc('score')->values());
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
            indexAxis: 'x',
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                title: {
                    display: true,
                    text: 'Score de performance du jour'
                },
                legend: {
                    display: false
                }
            },
            scales: {
                x: { ticks: { color: '#164f63', font: { weight: 'bold' } } },
                y: { beginAtZero: true, ticks: { color: '#164f63', font: { weight: 'bold' } } }
            }
        }
    });
</script>

@endpush
@endsection
