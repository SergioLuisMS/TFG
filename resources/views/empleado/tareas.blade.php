@extends('layouts.empleado', ['noSplash' => true])

@section('content')

<div class="flex flex-col gap-4 mb-8">

    {{-- Botón para volver al Dashboard --}}
    <a href="{{ route('empleado.dashboard') }}"
       class="inline-block px-4 py-2 bg-azul text-white rounded hover:bg-granate transition shadow w-fit">
        ← Volver al Panel Principal
    </a>

    {{-- Título con separación visual --}}
    <h1 class="text-2xl font-bold border-b border-gray-300 pb-2">
        Tus Tareas Asignadas
    </h1>

</div>

{{-- Listado o mensaje --}}
@forelse ($tareas as $tarea)
    <div class="bg-white p-4 rounded shadow mb-4">
        <h2 class="font-semibold">{{ $tarea->descripcion }}</h2>
        <p class="text-sm text-gray-600">Fecha de inicio: {{ $tarea->fecha_inicio }}</p>
        <p class="text-sm text-gray-600">Estado: {{ $tarea->estado }}</p>
    </div>
@empty
    <p class="text-gray-600">No tienes tareas asignadas actualmente.</p>
@endforelse

@endsection
