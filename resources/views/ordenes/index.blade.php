@extends('layouts.base')

@section('content')
<div class="bg-white p-6 rounded shadow-md">

    {{-- Botón de retroceso --}}
    <a href="{{ route('dashboard') }}" class="text-sm text-azul hover:underline flex items-center mb-4">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" viewBox="0 0 20 20" fill="currentColor">
            <path fill-rule="evenodd" d="M12.293 16.293a1 1 0 010 1.414l-6-6a1 1 0 010-1.414l6-6a1 1 0 111.414 1.414L8.414 10l5.293 5.293a1 1 0 010 1.414z" clip-rule="evenodd" />
        </svg>
        Volver al dashboard
    </a>

    <div class="flex justify-between items-center mb-4">
        <h2 class="text-2xl font-bold">Listado de órdenes</h2>
        <a href="{{ route('ordenes.create') }}"
            class="bg-verde hover:bg-azul text-negro font-semibold px-4 py-2 rounded shadow transition">
            Nueva orden
        </a>
    </div>

    <table class="w-full table-auto border-collapse">
        <thead>
            <tr class="bg-gray-100 text-left">
                <th class="border px-4 py-2">Número de orden</th>
                <th class="border px-4 py-2">Matrícula</th>
                <th class="border px-4 py-2">Tipo de intervención</th>
                <th class="border px-4 py-2">Cliente</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($ordenes as $orden)
            <tr>
                <td class="border px-4 py-2">
                    <a href="{{ route('ordenes.show', $orden) }}" class="text-blue-600 hover:underline">
                        {{ $orden->numero_orden }}
                    </a>
                </td>
                <td class="border px-4 py-2">{{ $orden->matricula ?? '-' }}</td>
                <td class="border px-4 py-2">{{ $orden->tipo_intervencion ?? '-' }}</td>
                <td class="border px-4 py-2">{{ $orden->cliente ?? '-' }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
