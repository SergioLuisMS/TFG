@extends('layouts.base')

@section('content')
<div class="bg-white p-6 rounded shadow-md">

    {{-- Botón de retroceso --}}
    <a href="{{ route('ordenes.index') }}" class="text-sm text-azul hover:underline flex items-center mb-4">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" viewBox="0 0 20 20" fill="currentColor">
            <path fill-rule="evenodd" d="M12.293 16.293a1 1 0 010 1.414l-6-6a1 1 0 010-1.414l6-6a1 1 0 111.414 1.414L8.414 10l5.293 5.293a1 1 0 010 1.414z" clip-rule="evenodd" />
        </svg>
        Volver al listado de órdenes
    </a>

    <h2 class="text-2xl font-bold mb-6">Detalles de la orden: {{ $orden->numero_orden }}</h2>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div><strong>Vehículo:</strong> {{ $orden->vehiculo ?? '-' }}</div>
        <div><strong>Matrícula:</strong> {{ $orden->matricula ?? '-' }}</div>
        <div><strong>Fecha de entrada:</strong> {{ $orden->fecha_entrada ?? '-' }}</div>
        <div><strong>Fecha de salida:</strong> {{ $orden->fecha_salida ?? '-' }}</div>
        <div><strong>Cliente:</strong> {{ $orden->cliente ?? '-' }}</div>
        <div><strong>Teléfono:</strong> {{ $orden->telefono ?? '-' }}</div>
        <div><strong>Kilómetros:</strong> {{ $orden->kilometros ?? '-' }}</div>
        <div><strong>Tipo de intervención:</strong> {{ $orden->tipo_intervencion ?? '-' }}</div>
        <div><strong>Nº Factura:</strong> {{ $orden->numero_factura ?? '-' }}</div>
        <div><strong>Nº Presupuesto:</strong> {{ $orden->numero_presupuesto ?? '-' }}</div>
        <div><strong>Nº Resguardo:</strong> {{ $orden->numero_resguardo ?? '-' }}</div>
        <div><strong>Nº Albarán:</strong> {{ $orden->numero_albaran ?? '-' }}</div>
        <div><strong>Situación del vehículo:</strong> {{ $orden->situacion_vehiculo ?? '-' }}</div>
        <div><strong>Próxima ITV:</strong> {{ $orden->proxima_itv ?? '-' }}</div>
        <div><strong>Nº Bastidor:</strong> {{ $orden->numero_bastidor ?? '-' }}</div>
        <div class="md:col-span-2"><strong>Descripción / Revisión:</strong><br>{{ $orden->descripcion_revision ?? '-' }}</div>
    </div>

    {{-- Sección para mostrar el PDF si existe --}}
    <div class="mt-6">
        <strong>Archivo adjunto:</strong><br>
        @if ($orden->pdf)
            <a href="{{ Storage::url($orden->pdf) }}" target="_blank" class="text-blue-600 hover:underline">
                Ver archivo PDF adjunto
            </a>
        @else
            <p class="text-gray-500">No hay archivo PDF adjunto.</p>
        @endif
    </div>

   
</div>
@endsection
