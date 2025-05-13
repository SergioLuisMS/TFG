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

    <h2 class="text-2xl font-bold mb-6">Registrar nueva orden</h2>

    <form action="{{ route('ordenes.store') }}" method="POST">
        @csrf

        {{-- Número de orden --}}
        <div class="mb-4">
            <label for="pdf" class="block text-gray-700">Archivo PDF (opcional)</label>
            <input type="file" name="pdf" id="pdf" accept="application/pdf" class="mt-1 block w-full">
        </div>

        {{-- Vehículo --}}
        <div class="mb-4">
            <label for="vehiculo" class="block font-medium">Vehículo</label>
            <input type="text" name="vehiculo" id="vehiculo"
                class="w-full border border-gray-300 rounded px-3 py-2 mt-1" />
        </div>

        {{-- Matrícula --}}
        <div class="mb-4">
            <label for="matricula" class="block font-medium">Matrícula</label>
            <input type="text" name="matricula" id="matricula"
                class="w-full border border-gray-300 rounded px-3 py-2 mt-1" />
        </div>

        {{-- Tipo de vehículo --}}
        <div class="mb-4">
            <label for="tipo_vehiculo" class="block font-medium">Tipo de vehículo</label>
            <input type="text" name="tipo_vehiculo" id="tipo_vehiculo"
                class="w-full border border-gray-300 rounded px-3 py-2 mt-1" />
        </div>

        {{-- Tipo de intervención (estado) --}}
        <div class="mb-4">
            <label for="tipo_intervencion" class="block font-medium">Estado / Tipo de intervención</label>
            <input type="text" name="tipo_intervencion" id="tipo_intervencion"
                class="w-full border border-gray-300 rounded px-3 py-2 mt-1" />
        </div>

        {{-- Fecha entrada --}}
        <div class="mb-4">
            <label for="fecha_entrada" class="block font-medium">Fecha de entrada</label>
            <input type="datetime-local" name="fecha_entrada" id="fecha_entrada"
                class="w-full border border-gray-300 rounded px-3 py-2 mt-1" />
        </div>

        {{-- Fecha salida --}}
        <div class="mb-4">
            <label for="fecha_salida" class="block font-medium">Fecha de salida</label>
            <input type="datetime-local" name="fecha_salida" id="fecha_salida"
                class="w-full border border-gray-300 rounded px-3 py-2 mt-1" />
        </div>

        {{-- Cliente --}}
        <div class="mb-4">
            <label for="cliente" class="block font-medium">Cliente</label>
            <input type="text" name="cliente" id="cliente"
                class="w-full border border-gray-300 rounded px-3 py-2 mt-1" />
        </div>

        {{-- Teléfono --}}
        <div class="mb-4">
            <label for="telefono" class="block font-medium">Teléfono</label>
            <input type="text" name="telefono" id="telefono"
                class="w-full border border-gray-300 rounded px-3 py-2 mt-1" />
        </div>

        {{-- Kilómetros --}}
        <div class="mb-4">
            <label for="kilometros" class="block font-medium">Kilómetros</label>
            <input type="number" name="kilometros" id="kilometros"
                class="w-full border border-gray-300 rounded px-3 py-2 mt-1" />
        </div>

        {{-- Nº Factura --}}
        <div class="mb-4">
            <label for="numero_factura" class="block font-medium">Nº Factura</label>
            <input type="text" name="numero_factura" id="numero_factura"
                class="w-full border border-gray-300 rounded px-3 py-2 mt-1" />
        </div>

        {{-- Nº Presupuesto --}}
        <div class="mb-4">
            <label for="numero_presupuesto" class="block font-medium">Nº Presupuesto</label>
            <input type="text" name="numero_presupuesto" id="numero_presupuesto"
                class="w-full border border-gray-300 rounded px-3 py-2 mt-1" />
        </div>

        {{-- Nº Resguardo --}}
        <div class="mb-4">
            <label for="numero_resguardo" class="block font-medium">Nº Resguardo</label>
            <input type="text" name="numero_resguardo" id="numero_resguardo"
                class="w-full border border-gray-300 rounded px-3 py-2 mt-1" />
        </div>

        {{-- Nº Albarán --}}
        <div class="mb-4">
            <label for="numero_albaran" class="block font-medium">Nº Albarán</label>
            <input type="text" name="numero_albaran" id="numero_albaran"
                class="w-full border border-gray-300 rounded px-3 py-2 mt-1" />
        </div>

        {{-- Situación vehículo --}}
        <div class="mb-4">
            <label for="situacion_vehiculo" class="block font-medium">Situación del vehículo</label>
            <input type="text" name="situacion_vehiculo" id="situacion_vehiculo"
                class="w-full border border-gray-300 rounded px-3 py-2 mt-1" />
        </div>

        {{-- Próxima ITV --}}
        <div class="mb-4">
            <label for="proxima_itv" class="block font-medium">Próxima ITV</label>
            <input type="date" name="proxima_itv" id="proxima_itv"
                class="w-full border border-gray-300 rounded px-3 py-2 mt-1" />
        </div>

        {{-- Nº Bastidor --}}
        <div class="mb-4">
            <label for="numero_bastidor" class="block font-medium">Nº Bastidor</label>
            <input type="text" name="numero_bastidor" id="numero_bastidor"
                class="w-full border border-gray-300 rounded px-3 py-2 mt-1" />
        </div>

        {{-- Descripción revisión --}}
        <div class="mb-4">
            <label for="descripcion_revision" class="block font-medium">Descripción / Revisión</label>
            <textarea name="descripcion_revision" id="descripcion_revision" rows="3"
                class="w-full border border-gray-300 rounded px-3 py-2 mt-1"></textarea>
        </div>

        {{-- Botón guardar --}}
        <div class="mt-6">
            <button type="submit"
                class="bg-verde hover:bg-azul text-negro px-6 py-2 rounded-lg font-semibold shadow-md transition duration-200">
                Guardar orden
            </button>
        </div>

    </form>
</div>
@endsection
