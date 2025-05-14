@extends('layouts.empleado' , ['noSplash' => true])

@section('content')

{{-- Botón para volver al Dashboard --}}
<a href="{{ route('empleado.dashboard') }}" class="inline-block mb-4 px-4 py-2 bg-azul text-white rounded hover:bg-granate transition">
    ← Volver al Panel Principal
</a>

<h1 class="text-2xl font-bold mb-4">Tus Tareas Asignadas</h1>

@forelse ($tareas as $tarea)
<div class="bg-white p-4 rounded shadow mb-4">
    <h2 class="font-semibold">{{ $tarea->descripcion }}</h2>
    <p class="text-sm text-gray-600">Fecha de inicio: {{ $tarea->fecha_inicio }}</p>
    <p class="text-sm text-gray-600">Estado: {{ $tarea->estado }}</p>
</div>
@empty
<p>No tienes tareas asignadas actualmente.</p>
@endforelse
@endsection
