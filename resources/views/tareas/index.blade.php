@extends('layouts.base')

@section('content')
<div class="space-y-6">

    {{-- Botón de volver --}}
    <a href="{{ route('dashboard') }}" class="text-sm text-azul hover:underline flex items-center mb-4">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" viewBox="0 0 20 20" fill="currentColor">
            <path fill-rule="evenodd" d="M12.293 16.293a1 1 0 010 1.414l-6-6a1 1 0 010-1.414l6-6a1 1 0 111.414 1.414L8.414 10l5.293 5.293a1 1 0 010 1.414z" clip-rule="evenodd" />
        </svg>
        Volver al dashboard
    </a>

    <h2 class="text-2xl font-bold mb-4">Tareas por orden</h2>

    @foreach($ordenes as $orden)
    <div class="bg-white shadow-md rounded-lg p-4">
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
        <div class="border rounded-lg p-3 mb-2 bg-gray-50">
            <div class="font-semibold text-blue-800">
                {{ $tarea->empleado->nombre }} {{ $tarea->empleado->primer_apellido }}
            </div>
            <div class="text-sm text-gray-600">
                {{ $orden->vehiculo ?? 'Vehículo no especificado' }}
            </div>
            <div class="text-sm mt-1 text-gray-700 border border-dashed border-gray-400 p-2 rounded">
                {{ $tarea->descripcion ?? 'Sin descripción' }}
            </div>
        </div>
        @empty
        <p class="text-sm text-gray-500">No hay tareas asignadas a esta orden.</p>
        @endforelse
    </div>
    @endforeach

</div>
@endsection
