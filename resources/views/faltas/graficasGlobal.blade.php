@extends('layouts.base')

@section('content')

{{-- Librerías Chart.js y Gauge --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chartjs-gauge@0.3.0/dist/chartjs-gauge.min.js"></script>

{{-- Script externo con funciones de gráficas --}}
<script src="{{ asset('js/graficasEmpleado.js') }}"></script>

<div class="bg-white p-6 rounded shadow-md">

    {{-- Botón de volver --}}
    <a href="{{ route('dashboard') }}" class="text-sm text-azul hover:underline flex items-center mb-4">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" viewBox="0 0 20 20" fill="currentColor">
            <path fill-rule="evenodd" d="M12.293 16.293a1 1 0 010 1.414l-6-6a1 1 0 010-1.414l6-6a1 1 0 111.414 1.414L8.414 10l5.293 5.293a1 1 0 010 1.414z" clip-rule="evenodd" />
        </svg>
        Volver al dashboard
    </a>

    <h2 class="text-2xl font-bold mb-6">Gráficas de asistencia</h2>

    {{-- Selector de empleados --}}
    <label for="empleado" class="block text-lg font-semibold text-negro mb-2">
        Selecciona el empleado que quieres revisar:
    </label>
    <select id="empleado" class="w-full md:w-1/2 border border-gray-300 px-3 py-2 rounded mb-6 text-negro">
        <option value="">-- Selecciona un empleado --</option>
        @foreach ($empleados as $empleado)
            <option value="{{ $empleado->id }}">{{ $empleado->nombre }} {{ $empleado->primer_apellido }}</option>
        @endforeach
    </select>

    {{-- Foto y nombre --}}
    <div id="empleado-info" class="flex flex-col items-center mb-6 hidden">
        <div class="w-32 h-32 rounded-full overflow-hidden shadow">
            <img id="foto-empleado" src="" alt="Foto del empleado" class="w-full h-full object-cover">
        </div>
        <h2 id="nombre-empleado" class="text-2xl font-bold mt-3"></h2>
    </div>

    {{-- Información detallada --}}
    <div id="info" class="mb-4 text-negro hidden">
        <p><strong>Asistencias:</strong> <span id="asistencias"></span></p>
        <p><strong>Faltas:</strong> <span id="faltas"></span></p>
        <p><strong>Porcentaje de asistencia:</strong> <span id="porcentaje"></span>%</p>
        <p><strong>Minutos totales de retraso:</strong> <span id="minutosRetraso"></span> minutos</p>
        <p><strong>Días con retraso:</strong> <span id="diasRetraso"></span> días</p>
        <p id="comparacion" class="mt-2 text-sm font-semibold"></p>
        <p id="mensaje-mensual" class="text-sm mt-2 font-semibold"></p>
    </div>

    {{-- Gráfica principal --}}
    <div class="w-full max-w-sm mx-auto">
        <canvas id="asistenciaChart"></canvas>
    </div>

    {{-- Contenedor de gráficas secundarias --}}
    <div id="graficas-seccion" class="hidden">
        @php
            $graficas = [
                ['id' => 'comparativaPorcentajeChart', 'titulo' => 'Comparativa con la media'],
                ['id' => 'comparativaFaltasChart', 'titulo' => 'Comparativa de faltas'],
                ['id' => 'graficoLineasFaltas', 'titulo' => 'Faltas mensuales (año actual)'],
                ['id' => 'grafico-tareas-mes', 'titulo' => 'Tareas realizadas por empleado (mes actual)'],
                ['id' => 'grafico-ordenes-mes', 'titulo' => 'Órdenes distintas trabajadas por empleado (mes actual)']
            ];
        @endphp

        @foreach ($graficas as $grafica)
            <div class="w-full max-w-2xl mx-auto mt-10">
                <h3 class="text-lg font-bold mb-2 text-negro">{{ $grafica['titulo'] }}</h3>
                <canvas id="{{ $grafica['id'] }}" height="100"></canvas>
            </div>
        @endforeach
    </div>
</div>

<script>
    document.getElementById('empleado').addEventListener('change', function () {
        const id = this.value;
        if (id) window.graficasEmpleado.cargarDatosEmpleado(id);
    });

    window.graficasEmpleado.cargarGraficaGlobal("/faltas/graficas/tareas-mensuales", "grafico-tareas-mes", "rgba(49,112,128,1)");
    window.graficasEmpleado.cargarGraficaGlobal("/faltas/graficas/ordenes-mensuales", "grafico-ordenes-mes", "rgba(135,40,41,1)");
</script>
@endsection
