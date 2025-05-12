@extends('layouts.base')

@section('content')
<div class="bg-white p-6 rounded shadow-md">

    <a href="{{ route('dashboard') }}" class="inline-flex items-center text-azul hover:underline mb-4">
        <!-- Icono de flecha hacia la izquierda -->
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" viewBox="0 0 20 20" fill="currentColor">
            <path fill-rule="evenodd" d="M12.707 15.707a1 1 0 01-1.414 0L6.586 11H17a1 1 0 110 2H6.586l4.707 4.707a1 1 0 01-1.414 1.414l-6.414-6.414a1 1 0 010-1.414l6.414-6.414a1 1 0 011.414 1.414L6.586 9H17a1 1 0 110 2H6.586l4.707 4.707a1 1 0 010 1.414z" clip-rule="evenodd" />
        </svg>
        Volver al dashboard
    </a>

    <h2 class="text-2xl font-bold mb-6">Listado de empleados</h2>

    @if(session('success'))
    <div class="mb-4 text-green-700 bg-green-100 border border-green-300 px-4 py-2 rounded">
        {{ session('success') }}
    </div>
    @endif

    <table class="w-full border-collapse">
        <thead>
            <tr class="bg-[#317080] text-white">
                <th class="px-4 py-2 text-left">N.º Empleado</th>
                <th class="px-4 py-2 text-left">Nombre completo</th>
                <th class="px-4 py-2 text-left">Alias</th>
                <th class="px-4 py-2 text-left">Teléfono</th>
                <th class="border px-4 py-2">Hora de entrada</th>

            </tr>
        </thead>
        <tbody>
            @foreach ($empleados as $empleado)
            <tr class="border-b hover:bg-gray-100">
                <td class="px-4 py-2">{{ str_pad($empleado->id, 8, '0', STR_PAD_LEFT) }}</td>
                <td class="px-4 py-2">
                    <a href="{{ route('empleados.edit', $empleado->id) }}"
                        class="text-azul hover:underline font-semibold">
                        {{ $empleado->nombre }} {{ $empleado->primer_apellido }}
                    </a>
                </td>
                <td class="px-4 py-2">{{ $empleado->alias }}</td>
                <td class="px-4 py-2">{{ $empleado->telefono ?? $empleado->telefono_movil }}</td>
                <td class="border px-4 py-2">
                    {{ $empleado->hora_entrada_contrato ? \Carbon\Carbon::createFromFormat('H:i:s', $empleado->hora_entrada_contrato)->format('H:i') : '-' }}
                </td>

            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
