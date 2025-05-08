@extends('layouts.base')

@section('content')

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chartjs-gauge@0.3.0/dist/chartjs-gauge.min.js"></script>

<div class="bg-white p-6 rounded shadow-md">
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

    {{-- Foto y nombre del empleado --}}
    <div id="empleado-info" class="flex flex-col items-center mb-6 hidden">
        <div class="w-32 h-32 rounded-full overflow-hidden shadow">
            <img id="foto-empleado" src="" alt="Foto del empleado" class="w-full h-full object-cover">
        </div>
        <h2 id="nombre-empleado" class="text-2xl font-bold mt-3"></h2>
    </div>

    {{-- Detalles numéricos --}}
    <div id="info" class="mb-4 text-negro hidden">
        <p><strong>Asistencias:</strong> <span id="asistencias"></span></p>
        <p><strong>Faltas:</strong> <span id="faltas"></span></p>
        <p><strong>Porcentaje de asistencia:</strong> <span id="porcentaje"></span>%</p>
        <p id="comparacion" class="mt-2 text-sm font-semibold"></p>
        <p id="mensaje-mensual" class="text-sm mt-2 font-semibold"></p>
    </div>

    {{-- Gráfico tipo doughnut: asistencia vs faltas --}}
    <div class="w-full max-w-sm mx-auto">
        <canvas id="asistenciaChart"></canvas>
    </div>

    {{-- Agrupar secciones de gráficas para ocultarlas --}}
    <div id="graficas-seccion" class="hidden">

        {{-- Gráfico barras porcentaje empleado vs media --}}
        <div class="w-full max-w-md mx-auto mt-10">
            <h3 class="text-lg font-bold mb-2 text-negro">Comparativa con la media</h3>
            <canvas id="comparativaPorcentajeChart"></canvas>
        </div>

        {{-- Gráfico barras faltas mes actual vs anterior --}}
        <div class="w-full max-w-md mx-auto mt-10">
            <h3 class="text-lg font-bold mb-2 text-negro">Comparativa de faltas</h3>
            <canvas id="comparativaFaltasChart"></canvas>
        </div>

        {{-- Gráfico líneas: faltas mensuales durante el año --}}
        <div class="w-full max-w-xl mx-auto mt-10">
            <h3 class="text-lg font-bold mb-2 text-negro">Faltas mensuales (año actual)</h3>
            <canvas id="graficoLineasFaltas"></canvas>
        </div>

        {{-- Gráfico barras: tareas mensuales por empleado --}}
        <div class="w-full max-w-2xl mx-auto mt-10">
            <h3 class="text-lg font-bold mb-2 text-negro">Tareas realizadas por empleado (mes actual)</h3>
            <canvas id="grafico-tareas-mes" height="100"></canvas>
        </div>

        {{-- Gráfico barras: órdenes mensuales por empleado --}}
        <div class="w-full max-w-2xl mx-auto mt-10">
            <h3 class="text-lg font-bold mb-2 text-negro">Órdenes distintas trabajadas por empleado (mes actual)</h3>
            <canvas id="grafico-ordenes-mes" height="100"></canvas>
        </div>

    </div>

</div>

{{-- Librería Chart.js --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    let chart = null;
    let porcentajeChart = null;
    let faltasChart = null;
    let lineasChart = null;

    document.getElementById('empleado').addEventListener('change', async function() {
        const id = this.value;
        if (!id) return;

        // 1. PETICIÓN DE DATOS PRINCIPALES
        const res = await fetch(`/faltas/graficas/datos/${id}`);
        const data = await res.json();

        document.getElementById('graficas-seccion').classList.remove('hidden');

        // Mostrar nombre y foto
        document.getElementById('nombre-empleado').textContent = data.nombre;
        const foto = document.getElementById('foto-empleado');
        foto.src = data.foto ? `/storage/${data.foto}` : "";
        foto.classList.toggle('hidden', !data.foto);
        document.getElementById('empleado-info').classList.remove('hidden');

        // Mostrar info numérica
        document.getElementById('info').classList.remove('hidden');
        document.getElementById('asistencias').textContent = data.asistencias;
        document.getElementById('faltas').textContent = data.faltas;
        document.getElementById('porcentaje').textContent = data.porcentaje;

        // Comparativa con media general
        const comparacionText = document.getElementById('comparacion');
        if (data.diferenciaPorcentaje > 0) {
            comparacionText.textContent = `Este empleado tiene un ${data.diferenciaPorcentaje}% más asistencia que la media (${data.media}%).`;
            comparacionText.className = "text-green-600 font-semibold mt-2";
        } else if (data.diferenciaPorcentaje < 0) {
            comparacionText.textContent = `Este empleado tiene un ${Math.abs(data.diferenciaPorcentaje)}% menos asistencia que la media (${data.media}%).`;
            comparacionText.className = "text-red-600 font-semibold mt-2";
        } else {
            comparacionText.textContent = `Este empleado tiene exactamente la media de asistencia (${data.media}%).`;
            comparacionText.className = "text-yellow-600 font-semibold mt-2";
        }

        // Comparativa faltas con mes anterior
        const mensajeMensual = document.getElementById('mensaje-mensual');
        if (data.diferenciaFaltas > 0) {
            mensajeMensual.textContent = `Este mes tiene ${data.diferenciaFaltas} falta(s) más que el anterior 😕`;
            mensajeMensual.className = 'text-red-600 text-sm font-semibold mt-2';
        } else if (data.diferenciaFaltas < 0) {
            mensajeMensual.textContent = `Este mes tiene ${Math.abs(data.diferenciaFaltas)} falta(s) menos que el anterior 🎉`;
            mensajeMensual.className = 'text-green-600 text-sm font-semibold mt-2';
        } else {
            mensajeMensual.textContent = `Tiene el mismo número de faltas que el mes anterior.`;
            mensajeMensual.className = 'text-yellow-600 text-sm font-semibold mt-2';
        }

        // 2. GRÁFICO TIPO DOUGHNUT
        const ctx = document.getElementById('asistenciaChart').getContext('2d');
        if (chart) {
            chart.data.datasets[0].data = [data.asistencias, data.faltas];
            chart.update();
        } else {
            chart = new Chart(ctx, {
                type: 'doughnut',
                data: {
                    labels: ['Asistencias', 'Faltas'],
                    datasets: [{
                        data: [data.asistencias, data.faltas],
                        backgroundColor: ['#7ebdb3', '#d23e5d'],
                        borderColor: ['#1d1d1b', '#1d1d1b'],
                        borderWidth: 1
                    }]
                },
                options: {
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
                }
            });
        }

        // 3. GRÁFICO BARRAS: porcentaje vs media
        const ctxPorcentaje = document.getElementById('comparativaPorcentajeChart').getContext('2d');
        if (porcentajeChart) {
            porcentajeChart.data.datasets[0].data = [data.porcentaje, data.media];
            porcentajeChart.update();
        } else {
            porcentajeChart = new Chart(ctxPorcentaje, {
                type: 'bar',
                data: {
                    labels: ['Empleado', 'Media'],
                    datasets: [{
                        label: '% Asistencia',
                        data: [data.porcentaje, data.media],
                        backgroundColor: ['#317080', '#7ebdb3']
                    }]
                },
                options: {
                    scales: {
                        y: {
                            beginAtZero: true,
                            max: 100
                        }
                    }
                }
            });
        }

        // 4. GRÁFICO BARRAS: faltas actual vs anterior
        const ctxFaltas = document.getElementById('comparativaFaltasChart').getContext('2d');
        if (faltasChart) {
            faltasChart.data.datasets[0].data = [data.faltas, data.faltasMesAnterior];
            faltasChart.update();
        } else {
            faltasChart = new Chart(ctxFaltas, {
                type: 'bar',
                data: {
                    labels: ['Este mes', 'Mes anterior'],
                    datasets: [{
                        label: 'Faltas',
                        data: [data.faltas, data.faltasMesAnterior],
                        backgroundColor: ['#d23e5d', '#872829']
                    }]
                },
                options: {
                    scales: {
                        y: {
                            beginAtZero: true,
                            precision: 0
                        }
                    }
                }
            });
        }

        // 5. PETICIÓN Y GRÁFICO LÍNEAS: faltas mensuales
        const resLineas = await fetch(`/faltas/graficas/faltas-mensuales/${id}`);
        const datosLineas = await resLineas.json();

        const ctxLineas = document.getElementById('graficoLineasFaltas').getContext('2d');
        if (lineasChart) {
            lineasChart.data.labels = datosLineas.meses;
            lineasChart.data.datasets[0].data = datosLineas.faltas;
            lineasChart.update();
        } else {
            lineasChart = new Chart(ctxLineas, {
                type: 'line',
                data: {
                    labels: datosLineas.meses,
                    datasets: [{
                        label: 'Faltas por mes',
                        data: datosLineas.faltas,
                        fill: false,
                        borderColor: '#d23e5d',
                        tension: 0.2,
                        pointBackgroundColor: '#872829',
                        pointBorderColor: '#872829'
                    }]
                },
                options: {
                    responsive: true,
                    scales: {
                        y: {
                            beginAtZero: true,
                            precision: 0
                        }
                    },
                    plugins: {
                        legend: {
                            labels: {
                                color: '#1d1d1b'
                            }
                        }
                    }
                }
            });
        }
    });
    // 6. GRÁFICO: tareas por empleado en el mes
    fetch("/faltas/graficas/tareas-mensuales")
        .then(res => res.json())
        .then(({
            labels,
            data
        }) => {
            new Chart(document.getElementById("grafico-tareas-mes"), {
                type: 'bar',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Tareas este mes',
                        data: data,
                        backgroundColor: 'rgba(49,112,128,0.6)',
                        borderColor: 'rgba(49,112,128,1)',
                        borderWidth: 1
                    }]
                },
                options: {
                    indexAxis: 'y',
                    responsive: true,
                    plugins: {
                        legend: {
                            display: false
                        },
                        title: {
                            display: true,
                            text: 'Tareas realizadas por empleado (mes actual)'
                        }
                    },
                    scales: {
                        x: {
                            beginAtZero: true,
                            precision: 0
                        }
                    }
                }
            });
        });

    // 7. GRÁFICO: órdenes distintas en las que ha trabajado cada empleado este mes
    fetch("/faltas/graficas/ordenes-mensuales")
        .then(res => res.json())
        .then(({
            labels,
            data
        }) => {
            new Chart(document.getElementById("grafico-ordenes-mes"), {
                type: 'bar',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Órdenes distintas',
                        data: data,
                        backgroundColor: 'rgba(135,40,41,0.6)',
                        borderColor: 'rgba(135,40,41,1)',
                        borderWidth: 1
                    }]
                },
                options: {
                    indexAxis: 'y',
                    responsive: true,
                    plugins: {
                        legend: {
                            display: false
                        },
                        title: {
                            display: true,
                            text: 'Órdenes distintas trabajadas por empleado (mes actual)'
                        }
                    },
                    scales: {
                        x: {
                            beginAtZero: true,
                            precision: 0
                        }
                    }
                }
            });
        });
</script>
@endsection
