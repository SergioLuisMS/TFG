@extends('layouts.base')

@section('content')

{{-- PANTALLA DE CARGA (Splash Screen) --}}
<div id="splash" class="fixed inset-0 flex items-center justify-center bg-white z-50 transition-opacity duration-1000 opacity-100">
    <img src="{{ asset('storage/fotos/Recurso 23.png') }}" alt="Logo" class="w-1/2 max-w-md animate-fade-in">
</div>

{{-- CONTENIDO DEL DASHBOARD --}}
<div id="dashboard-content" class="opacity-0 transition-opacity duration-1000">

    {{-- Encabezado --}}
    <h1 class="text-3xl font-bold mb-6">Bienvenido al Panel de Gestión</h1>

    {{-- Gráfico de órdenes por mes --}}
    <div class="bg-white mt-10 p-6 rounded shadow-md">
        <h3 class="text-xl font-bold mb-4">Órdenes por mes ({{ now()->year }})</h3>
        <canvas id="ordenesPorMesChart"></canvas>
    </div>

</div>

{{-- Chart.js CDN --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

{{-- Lógica para cargar gráfico --}}
<script>
    document.addEventListener('DOMContentLoaded', async () => {
        const res = await fetch('/ordenes/datos/mensuales');
        const data = await res.json();

        const ctx = document.getElementById('ordenesPorMesChart').getContext('2d');

        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: data.meses,
                datasets: [{
                    label: 'Órdenes',
                    data: data.datos,
                    backgroundColor: '#317080'
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true,
                        precision: 0
                    }
                }
            }
        });
    });
</script>

{{-- ANIMACIÓN DE ENTRADA DEL DASHBOARD --}}
<script>
    // Espera a que el DOM esté completamente cargado para manipular los elementos
    // Captura el splash screen y el contenido real del dashboard para controlar su visibilidad con animaciones

    document.addEventListener('DOMContentLoaded', () => {
        const splash = document.getElementById('splash');
        const dashboard = document.getElementById('dashboard-content');

        // Inicio del efecto de entrada del splash
        setTimeout(() => {
            splash.classList.add('opacity-100');
        }, 100);

        // Desvanecer el splash después de 1.5s
        setTimeout(() => {
            splash.classList.remove('opacity-100');
            splash.classList.add('opacity-0');

            // Ocultar splash y mostrar el dashboard después del fade-out
            setTimeout(() => {
                splash.classList.add('hidden');
                dashboard.classList.remove('opacity-0');
                dashboard.classList.add('opacity-100');
            }, 1000);
        }, 1500);
    });
</script>

{{-- Animación personalizada --}}
<style>
    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: scale(0.95);
        }

        to {
            opacity: 1;
            transform: scale(1);
        }
    }

    .animate-fade-in {
        animation: fadeIn 1s ease-out forwards;
    }
</style>

@endsection
