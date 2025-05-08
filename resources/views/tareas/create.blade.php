@extends('layouts.base')

@section('content')
<div class="bg-white p-6 rounded shadow-md max-w-3xl mx-auto">

    {{-- Botón de volver --}}
    <a href="{{ route('tareas.index') }}" class="text-sm text-azul hover:underline flex items-center mb-4">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" viewBox="0 0 20 20" fill="currentColor">
            <path fill-rule="evenodd" d="M12.293 16.293a1 1 0 010 1.414l-6-6a1 1 0 010-1.414l6-6a1 1 0 111.414 1.414L8.414 10l5.293 5.293a1 1 0 010 1.414z" clip-rule="evenodd" />
        </svg>
        Volver al listado de órdenes
    </a>

    <h2 class="text-2xl font-bold mb-6">Registrar nueva tarea</h2>

    @if ($errors->any())
    <div class="mb-4 text-red-600">
        <ul>
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <form method="POST" action="{{ route('tareas.store') }}">
        @csrf

        {{-- Campo oculto con el número de orden --}}
        <input type="hidden" name="orden_id" value="{{ $orden->id }}">

        {{-- Selección de empleado --}}
        <div class="mb-4">
            <label for="empleado_id" class="block font-semibold">Empleado asignado</label>
            <select name="empleado_id" id="empleado_id" required class="w-full border rounded px-3 py-2">
                <option value="">-- Selecciona un empleado --</option>
                @foreach ($empleados as $empleado)
                <option value="{{ $empleado->id }}">{{ $empleado->nombre }} {{ $empleado->primer_apellido }}</option>
                @endforeach
            </select>
        </div>

        {{-- Fecha de inicio --}}
        <div class="mb-4">
            <label for="fecha_inicio" class="block font-semibold">Fecha de inicio</label>
            <input type="date" name="fecha_inicio" id="fecha_inicio" required
                class="w-full border rounded px-3 py-2">
        </div>

        {{-- Fecha de fin --}}
        <div class="mb-4">
            <label for="fecha_fin" class="block font-semibold">Fecha de fin (opcional)</label>
            <input type="date" name="fecha_fin" id="fecha_fin"
                class="w-full border rounded px-3 py-2">
        </div>

        {{-- Descripción --}}
        <div class="mb-4">
            <label for="descripcion" class="block font-semibold">Descripción de la tarea</label>
            <textarea name="descripcion" id="descripcion" rows="3"
                class="w-full border rounded px-3 py-2"></textarea>
        </div>

        {{-- Tiempo previsto --}}
        <div class="mb-4">
            <label for="tiempo_previsto" class="block font-semibold">Tiempo previsto (minutos)</label>
            <input type="number" name="tiempo_previsto" id="tiempo_previsto"
                class="w-full border rounded px-3 py-2" min="0">
        </div>

        {{-- Botón de guardar --}}
        <div class="mt-6">
            <button type="submit"
                class="bg-verde hover:bg-azul text-negro font-semibold px-6 py-2 rounded shadow transition">
                Guardar tarea
            </button>
        </div>

    </form>
</div>
@endsection
