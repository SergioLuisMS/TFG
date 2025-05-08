@extends('layouts.base')

@section('content')
<div class="bg-white p-6 rounded shadow-md max-w-xl mx-auto">
    <a href="{{ route('asistencias.index') }}" class="inline-flex items-center text-azul hover:underline mb-4">
        <!-- Icono de flecha hacia la izquierda -->
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" viewBox="0 0 20 20" fill="currentColor">
            <path fill-rule="evenodd" d="M12.707 15.707a1 1 0 01-1.414 0L6.586 11H17a1 1 0 110 2H6.586l4.707 4.707a1 1 0 01-1.414 1.414l-6.414-6.414a1 1 0 010-1.414l6.414-6.414a1 1 0 011.414 1.414L6.586 9H17a1 1 0 110 2H6.586l4.707 4.707a1 1 0 010 1.414z" clip-rule="evenodd" />
        </svg>
        Volver a las faltas de hoy
    </a>

    <h2 class="text-2xl font-bold mb-4">Asignar falta manual</h2>

    <p class="text-negro mb-4">Selecciona al empleado y el día que no estuvo presente:</p>

    <form method="POST" action="{{ route('faltas.guardar.manual') }}">
        @csrf

        <div class="mb-4">
            <label for="empleado_id" class="block font-semibold text-negro mb-1">Empleado:</label>
            <select name="empleado_id" id="empleado_id" required
                class="w-full border border-gray-300 rounded px-3 py-2 text-negro">
                <option value="">-- Selecciona un empleado --</option>
                @foreach ($empleados as $empleado)
                <option value="{{ $empleado->id }}">{{ $empleado->nombre }} {{ $empleado->primer_apellido }}</option>
                @endforeach
            </select>
        </div>

        <div class="mb-4">
            <label for="fecha" class="block font-semibold text-negro mb-1">Fecha de la falta:</label>
            <input type="date" name="fecha" id="fecha"
                class="w-full border border-gray-300 rounded px-3 py-2 text-negro" required>
        </div>

        <div class="mt-6">
            <button type="submit"
                class="bg-verde hover:bg-azul text-negro px-6 py-2 rounded-lg font-semibold shadow transition">
                Asignar falta
            </button>
        </div>
    </form>
</div>
@endsection
