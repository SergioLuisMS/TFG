@extends('layouts.base')

@section('content')
<div class="space-y-6" x-data="cronometroManager()" x-init="inicializar()">

    {{-- Botón de volver --}}
    <a href="{{ route('dashboard') }}" class="text-sm text-azul hover:underline flex items-center mb-4">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" viewBox="0 0 20 20" fill="currentColor">
            <path fill-rule="evenodd" d="M12.293 16.293a1 1 0 010 1.414l-6-6a1 1 0 010-1.414l6-6a1 1 0 111.414 1.414L8.414 10l5.293 5.293a1 1 0 010 1.414z" clip-rule="evenodd" />
        </svg>
        Volver al dashboard
    </a>

    <form method="GET" action="{{ route('tareas.index') }}" class="mb-4">
        <label for="ordenar_por" class="mr-2 font-semibold">Ordenar por:</label>
        <select name="ordenar_por" id="ordenar_por" onchange="this.form.submit()" class="border rounded px-2 py-1">
            <option value="">Ver todas</option>
            <option value="fecha_inicio" {{ request('ordenar_por') == 'fecha_inicio' ? 'selected' : '' }}>Fecha de Inicio</option>
            <option value="empleado_id" {{ request('ordenar_por') == 'empleado_id' ? 'selected' : '' }}>Empleado</option>
            <option value="estado" {{ request('ordenar_por') == 'estado' ? 'selected' : '' }}>Estado</option>
        </select>
    </form>

    <h2 class="text-2xl font-bold mb-4">Tareas por orden</h2>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
        @foreach($ordenes as $orden)
        <div class="bg-white shadow-md rounded-lg p-4 border border-gray-300 hover:shadow-lg transition">
            <div class="flex justify-between items-center mb-2">
                <div>
                    <span class="text-sm text-gray-500 font-semibold">OR:</span>
                    <span class="font-bold text-blue-700">{{ str_pad($orden->numero_orden, 10, '0', STR_PAD_LEFT) }}</span>
                    <span class="ml-4 font-bold text-green-700">{{ $orden->matricula ?? 'Sin matrícula' }}</span>
                </div>
                <a href="{{ route('tareas.create', ['orden' => $orden->id]) }}"
                    class="bg-verde hover:bg-azul text-negro text-sm font-semibold px-3 py-1 rounded shadow">
                    ➕ Añadir tarea
                </a>
            </div>

            {{-- Lista de tareas --}}
            @forelse($orden->tareas as $tarea)
            @php
            $borderColor = match($tarea->estado) {
            'En curso' => 'border-green-500',
            'Finalizada' => 'border-red-500',
            default => 'border-gray-300'
            };
            $mostrarCronometroEnVivo = $tarea->estado === 'En curso' && $tarea->cronometro_inicio !== null;
            @endphp

            @if ($mostrarCronometroEnVivo)
            <div class="border-2 {{ $borderColor }} rounded-lg p-3 mb-2 bg-gray-50"
                data-id="{{ $tarea->id }}"
                data-inicio="{{ $tarea->cronometro_inicio }}">
                @else
                <div class="border-2 {{ $borderColor }} rounded-lg p-3 mb-2 bg-gray-50">
                    <p class="text-sm font-mono text-gray-700 mt-2">
                        Tiempo actual:
                        @php
                        $h = str_pad(floor($tarea->tiempo_real / 3600), 2, '0', STR_PAD_LEFT);
                        $m = str_pad(floor(($tarea->tiempo_real % 3600) / 60), 2, '0', STR_PAD_LEFT);
                        $s = str_pad($tarea->tiempo_real % 60, 2, '0', STR_PAD_LEFT);
                        @endphp
                        {{ "{$h}:{$m}:{$s}" }}
                    </p>
                    @endif

                    <div class="flex justify-between items-center">
                        <div>
                            <div class="font-semibold text-blue-800">
                                {{ $tarea->empleado->nombre }} {{ $tarea->empleado->primer_apellido }}
                            </div>
                            <div class="text-sm text-gray-600">
                                {{ $orden->vehiculo ?? 'Vehículo no especificado' }}
                            </div>
                        </div>
                        <div class="text-sm">
                            @if($tarea->estado === 'Asignada')
                            <form method="POST" action="{{ route('tareas.marcarEnCurso', $tarea) }}">
                                @csrf
                                <button type="submit" class="text-white bg-green-600 px-3 py-1 rounded hover:bg-green-700">Iniciar</button>
                            </form>
                            @elseif($tarea->estado === 'En curso')
                            <form action="{{ route('tareas.finalizar', $tarea) }}" method="POST">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="bg-red-600 hover:bg-red-700 text-white font-semibold px-4 py-1 rounded">
                                    Finalizar
                                </button>
                            </form>
                            @endif
                        </div>
                    </div>

                    <div class="text-sm mt-2 text-gray-700 border border-dashed border-gray-400 p-2 rounded">
                        {{ $tarea->descripcion ?? 'Sin descripción' }}
                    </div>

                    @if($tarea->estado === 'Finalizada' && $tarea->tiempo_real)
                    @php
                    $h = str_pad(floor($tarea->tiempo_real / 3600), 2, '0', STR_PAD_LEFT);
                    $m = str_pad(floor(($tarea->tiempo_real % 3600) / 60), 2, '0', STR_PAD_LEFT);
                    $s = str_pad($tarea->tiempo_real % 60, 2, '0', STR_PAD_LEFT);
                    $tiempoFormateado = "{$h}:{$m}:{$s}";
                    @endphp
                    <div class="relative group text-sm text-red-700 font-mono mt-2">
                        <span class="cursor-pointer group-hover:underline" onclick="activarEdicion('{{ $tarea->id }}')">
                            ⏱ Tiempo total: {{ $tiempoFormateado }}
                        </span>
                        <form action="{{ route('tareas.actualizarTiempo', $tarea->id) }}" method="POST" class="hidden mt-1" id="form-tiempo-{{ $tarea->id }}">
                            @csrf
                            <input type="text" name="tiempo_real" placeholder="hh:mm:ss" required>
                            <button type="submit" class="ml-2 bg-green-500 text-white px-2 rounded">Guardar</button>
                        </form>
                    </div>
                    @endif
                </div>
                @empty
                <p class="text-sm text-gray-500">No hay tareas asignadas a esta orden.</p>
                @endforelse
            </div>
            @endforeach
        </div>


        <script>
            function activarEdicion(id) {
                const form = document.getElementById('form-tiempo-' + id);
                form.classList.toggle('hidden');
            }

            function cronometroManager() {
                return {
                    tareaAbierta: null,
                    cronometros: {},
                    inicializar() {
                        document.querySelectorAll('[data-inicio]').forEach(el => {
                            const id = el.getAttribute('data-id');
                            const inicio = new Date(el.getAttribute('data-inicio'));

                            if (id && inicio) {
                                this.iniciarCronometro(id, inicio);
                            }
                        });
                    },
                    iniciarCronometro(id, inicio) {
                        setInterval(() => {
                            const ahora = new Date();
                            const diff = Math.floor((ahora - inicio) / 1000);

                            const h = String(Math.floor(diff / 3600)).padStart(2, '0');
                            const m = String(Math.floor((diff % 3600) / 60)).padStart(2, '0');
                            const s = String(diff % 60).padStart(2, '0');

                            this.cronometros[id] = `${h}:${m}:${s}`;
                        }, 1000);
                    }
                }
            }
        </script>
        @endsection
