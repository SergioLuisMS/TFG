@extends('layouts.base')

@section('content')
<div class="bg-white p-6 rounded shadow-md">
    <h2 class="text-2xl font-bold mb-6">Asistencia de {{ $empleado->nombre }} {{ $empleado->primer_apellido }}</h2>

    <div class="mb-4 text-negro">
        <p><strong>Días laborables este mes:</strong> {{ $totalDias }}</p>
        <p><strong>Días asistidos:</strong> {{ $totalAsistencias }}</p>
        <p><strong>Días de falta:</strong> {{ $totalFaltas }}</p>
        <p><strong>Porcentaje de asistencia:</strong> {{ $porcentajeAsistencia }}%</p>
        <p><strong>Media general de asistencia:</strong> {{ $mediaGeneral }}%</p>

        @if ($diferencia > 0)
            <p class="text-green-600 font-semibold">Está <strong>{{ $diferencia }}%</strong> por encima de la media.</p>
        @elseif ($diferencia < 0)
            <p class="text-red-600 font-semibold">Está <strong>{{ abs($diferencia) }}%</strong> por debajo de la media.</p>
        @else
            <p class="font-semibold">Tiene exactamente la misma asistencia que la media.</p>
        @endif
    </div>

    <div class="w-full max-w-sm mx-auto">
        <canvas id="asistenciaChart"></canvas>
    </div>

    <div class="mt-6">
        <a href="{{ route('asistencias.index') }}"
           class="bg-azul hover:bg-granate text-white px-4 py-2 rounded-lg font-semibold shadow transition">
            Volver
        </a>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const ctx = document.getElementById('asistenciaChart').getContext('2d');

        const data = {
            labels: ['Asistencias', 'Faltas'],
            datasets: [{
                data: [{{ $totalAsistencias }}, {{ $totalFaltas }}],
                backgroundColor: ['#7ebdb3', '#d23e5d'],
                borderColor: ['#1d1d1b', '#1d1d1b'],
                borderWidth: 1
            }]
        };

        const options = {
            responsive: true,
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: {
                        color: '#1d1d1b',
                        font: {
                            size: 14
                        }
                    }
                }
            }
        };

        new Chart(ctx, {
            type: 'doughnut',
            data: data,
            options: options
        });
    });
</script>
@endsection
