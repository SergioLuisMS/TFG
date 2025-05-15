@extends('layouts.tareasEmpleado', ['noSplash' => true])

@section('content')

<div class="flex flex-col gap-4 mb-8">

    {{-- Botón para volver al Dashboard --}}
    <a href="{{ route('empleado.dashboard') }}"
        class="inline-block px-4 py-2 bg-azul text-white rounded hover:bg-granate transition shadow w-fit">
        ← Volver al Panel Principal
    </a>

    <div class="container mx-auto px-4 py-6">
        <h1 class="text-2xl font-semibold mb-4">Tareas Asignadas</h1>

        @if ($tareas->isEmpty())
            <div class="text-center py-10 text-gray-600">
                <p class="text-lg">No tienes tareas asignadas actualmente.</p>
            </div>
        @else
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                @foreach ($tareas as $tarea)
                    @php
                        $borderColor = match($tarea->estado) {
                            'Asignada' => 'border-yellow-500',
                            'En curso' => 'border-green-500',
                            'Finalizada' => 'border-red-500',
                            default => 'border-gray-300'
                        };

                        $inicio = $tarea->cronometro_inicio ? \Carbon\Carbon::parse($tarea->cronometro_inicio)->timestamp : null;
                        $acumulado = $tarea->tiempo_real ?? 0;
                    @endphp

                    <div class="border-l-4 {{ $borderColor }} bg-white shadow-sm rounded-lg p-4 hover:shadow-md transition"
                        x-data="cronometro({{ $inicio }}, {{ $acumulado }}, {{ $tarea->id }})"
                        x-init="init()">

                        <div class="text-xl font-bold mb-2">{{ $tarea->orden->matricula ?? 'Sin Matrícula' }}</div>

                        <div class="text-sm text-gray-600 mb-1">
                            <strong>Fecha de inicio:</strong> {{ $tarea->created_at->format('d/m/Y H:i') }}
                        </div>

                        <div class="text-gray-700 text-sm mb-2">
                            <strong>Descripción:</strong> {{ $tarea->descripcion }}
                        </div>

                        @if ($tarea->estado === 'En curso')
                            {{-- Cronómetro en vivo y botón Finalizar --}}
                            <div class="flex flex-col gap-2">
                                <div class="text-lg font-mono mb-2" x-text="formattedTime"></div>
                                <button @click="finalizarTarea"
                                    class="text-sm border border-red-600 px-3 py-1 rounded hover:bg-red-600 hover:text-white transition">
                                    Finalizar Tarea
                                </button>
                            </div>
                        @elseif ($tarea->estado === 'Finalizada')
                            {{-- Mostrar tiempo finalizado --}}
                            <div class="text-sm font-mono text-green-700 mt-2">
                                Tiempo total:
                                @php
                                    $h = str_pad(floor($tarea->tiempo_real / 3600), 2, '0', STR_PAD_LEFT);
                                    $m = str_pad(floor(($tarea->tiempo_real % 3600) / 60), 2, '0', STR_PAD_LEFT);
                                    $s = str_pad($tarea->tiempo_real % 60, 2, '0', STR_PAD_LEFT);
                                @endphp
                                {{ "{$h}:{$m}:{$s}" }}
                            </div>
                        @endif

                    </div>
                @endforeach
            </div>
        @endif
    </div>
</div>

{{-- Script Alpine.js --}}
<script>
    function cronometro(inicioTimestamp, acumulado, tareaId) {
        let acumuladoMs = acumulado * 1000;
        return {
            startTime: inicioTimestamp ? inicioTimestamp * 1000 : null,
            running: !!inicioTimestamp,
            elapsed: acumuladoMs + (inicioTimestamp ? Date.now() - inicioTimestamp * 1000 : 0),
            formattedTime: '',
            interval: null,

            init() {
                this.updateDisplay();
                if (this.running) {
                    this.start();
                }
            },

            start() {
                if (this.interval) {
                    clearInterval(this.interval);
                }
                this.running = true;
                this.startTime = Date.now() - this.elapsed;
                this.interval = setInterval(() => {
                    this.elapsed = Date.now() - this.startTime;
                    this.updateDisplay();
                }, 1000);
            },

            stop() {
                this.running = false;
                if (this.interval) {
                    clearInterval(this.interval);
                    this.interval = null;
                }
            },

            finalizarTarea() {
                const tiempoSegundos = Math.floor(this.elapsed / 1000);
                fetch(`/tareas/${tareaId}/finalizar`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({ tiempo_real: tiempoSegundos })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        location.reload();
                    } else {
                        console.error('Error al finalizar la tarea');
                    }
                })
                .catch(error => console.error('Error en la solicitud:', error));
            },

            updateDisplay() {
                let totalSeconds = Math.floor(this.elapsed / 1000);
                let hours = String(Math.floor(totalSeconds / 3600)).padStart(2, '0');
                let minutes = String(Math.floor((totalSeconds % 3600) / 60)).padStart(2, '0');
                let seconds = String(totalSeconds % 60).padStart(2, '0');
                this.formattedTime = `${hours}:${minutes}:${seconds}`;
            }
        };
    }
</script>

@endsection
