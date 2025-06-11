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

    <form method="GET" action="{{ route('tareas.index') }}" class="mb-6">
        <label for="estado" class="block text-sm font-semibold text-gray-700 mb-1">Filtrar por estado:</label>
        <select name="estado" id="estado" onchange="this.form.submit()"
            class="block w-full max-w-xs px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-azul focus:border-azul text-sm bg-white">
            <option value="">Ver todas</option>
            <option value="Asignada" {{ request('estado') == 'Asignada' ? 'selected' : '' }}>Asignadas</option>
            <option value="En curso" {{ request('estado') == 'En curso' ? 'selected' : '' }}>En curso</option>
            <option value="Finalizada" {{ request('estado') == 'Finalizada' ? 'selected' : '' }}>Finalizadas</option>
        </select>
    </form>



    <h2 class="text-2xl font-bold mb-4">Tareas por orden</h2>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
        @foreach($ordenes as $orden)
        <div class="bg-white rounded-lg p-4 border border-gray-300 transition duration-200 ease-in-out transform hover:scale-[1.01] hover:shadow-xl">
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
            <div class="border-2 {{ $borderColor }} rounded-lg p-3 mb-2 bg-gray-50 transition duration-200 ease-in-out transform hover:scale-[1.01] hover:shadow-md"
                data-id="{{ $tarea->id }}"
                data-inicio="{{ $tarea->cronometro_inicio }}">
                <p class="text-sm font-mono text-gray-700 mt-2">
                    Tiempo actual: <span x-text="cronometros[{{ $tarea->id }}] ?? '00:00:00'">00:00:00</span>
                </p>
                @else
                <div class="border-2 {{ $borderColor }} rounded-lg p-3 mb-2 bg-gray-50 transition duration-200 ease-in-out transform hover:scale-[1.01] hover:shadow-md"
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

                    @if($tarea->comentarios->count())
                    <div class="mt-3 text-sm text-gray-800 border-t pt-2">
                        <h4 class="font-semibold mb-1 text-gray-700">🗨 Comentarios:</h4>
                        <ul class="space-y-2">
                            @foreach($tarea->comentarios as $comentario)
                            <li class="border rounded bg-gray-50 p-2">
                                <div class="flex justify-between items-center">
                                    <span class="font-medium text-blue-700">
                                        {{ $comentario->empleado->nombre }} {{ $comentario->empleado->primer_apellido }}
                                    </span>
                                    <span class="text-xs text-gray-500">
                                        {{ $comentario->created_at->format('d/m/Y H:i') }}
                                    </span>
                                </div>

                                <div class="text-gray-700 mt-1">
                                    {{ $comentario->contenido }}
                                </div>

                                {{-- Valoración del administrador --}}
                                <form action="{{ route('comentarios.valorar', $comentario) }}" method="POST" class="mt-2 inline-flex items-center gap-2">
                                    @csrf
                                    @method('PATCH')

                                    <label for="valoracion_{{ $comentario->id }}" class="text-xs text-gray-600">Valorar:</label>
                                    <select name="valoracion" id="valoracion_{{ $comentario->id }}"
                                        onchange="this.form.submit()"
                                        class="text-sm border border-gray-300 rounded px-2 py-1 focus:outline-none focus:ring">
                                        @for ($i = 1; $i <= 5; $i++)
                                            <option value="{{ $i }}" {{ $comentario->valoracion == $i ? 'selected' : '' }}>
                                            {{ $i }}
                                            </option>
                                            @endfor
                                    </select>
                                </form>
                            </li>
                            @endforeach
                        </ul>
                    </div>
                    @endif


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
