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

    <h2 class="text-2xl font-bold mb-6">Editar orden: {{ $orden->numero_orden }}</h2>

    <form method="POST" action="{{ route('ordenes.update', $orden) }}">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

            <div>
                <label class="block font-semibold">Vehículo</label>
                <input type="text" name="vehiculo" value="{{ old('vehiculo', $orden->vehiculo) }}"
                    class="w-full border rounded px-3 py-2" />
            </div>

            <div>
                <label class="block font-semibold">Matrícula</label>
                <input type="text" name="matricula" value="{{ old('matricula', $orden->matricula) }}"
                    class="w-full border rounded px-3 py-2" />
            </div>

            <div>
                <label class="block font-semibold">Fecha de entrada</label>
                <input type="datetime-local" name="fecha_entrada"
                    value="{{ old('fecha_entrada', optional($orden->fecha_entrada)->format('Y-m-d\TH:i')) }}"
                    class="w-full border rounded px-3 py-2" />
            </div>

            <div>
                <label class="block font-semibold">Fecha de salida</label>
                <input type="datetime-local" name="fecha_salida"
                    value="{{ old('fecha_salida', optional($orden->fecha_salida)->format('Y-m-d\TH:i')) }}"
                    class="w-full border rounded px-3 py-2" />
            </div>

            <div>
                <label class="block font-semibold">Cliente</label>
                <input type="text" name="cliente" value="{{ old('cliente', $orden->cliente) }}"
                    class="w-full border rounded px-3 py-2" />
            </div>

            <div>
                <label class="block font-semibold">Teléfono</label>
                <input type="text" name="telefono" value="{{ old('telefono', $orden->telefono) }}"
                    class="w-full border rounded px-3 py-2" />
            </div>

            <div>
                <label class="block font-semibold">Kilómetros</label>
                <input type="number" name="kilometros" value="{{ old('kilometros', $orden->kilometros) }}"
                    class="w-full border rounded px-3 py-2" />
            </div>

            <div>
                <label class="block font-semibold">Tipo de intervención</label>
                <input type="text" name="tipo_intervencion" value="{{ old('tipo_intervencion', $orden->tipo_intervencion) }}"
                    class="w-full border rounded px-3 py-2" />
            </div>

            <div>
                <label class="block font-semibold">Nº Factura</label>
                <input type="text" name="numero_factura" value="{{ old('numero_factura', $orden->numero_factura) }}"
                    class="w-full border rounded px-3 py-2" />
            </div>

            <div>
                <label class="block font-semibold">Nº Presupuesto</label>
                <input type="text" name="numero_presupuesto" value="{{ old('numero_presupuesto', $orden->numero_presupuesto) }}"
                    class="w-full border rounded px-3 py-2" />
            </div>

            <div>
                <label class="block font-semibold">Nº Resguardo</label>
                <input type="text" name="numero_resguardo" value="{{ old('numero_resguardo', $orden->numero_resguardo) }}"
                    class="w-full border rounded px-3 py-2" />
            </div>

            <div>
                <label class="block font-semibold">Nº Albarán</label>
                <input type="text" name="numero_albaran" value="{{ old('numero_albaran', $orden->numero_albaran) }}"
                    class="w-full border rounded px-3 py-2" />
            </div>

            <div>
                <label class="block font-semibold">Situación del vehículo</label>
                <input type="text" name="situacion_vehiculo" value="{{ old('situacion_vehiculo', $orden->situacion_vehiculo) }}"
                    class="w-full border rounded px-3 py-2" />
            </div>

            <div>
                <label class="block font-semibold">Próxima ITV</label>
                <input type="date" name="proxima_itv"
                    value="{{ old('proxima_itv', $orden->proxima_itv) }}"
                    class="w-full border rounded px-3 py-2" />
            </div>

            <div>
                <label class="block font-semibold">Nº Bastidor</label>
                <input type="text" name="numero_bastidor" value="{{ old('numero_bastidor', $orden->numero_bastidor) }}"
                    class="w-full border rounded px-3 py-2" />
            </div>

            <div class="md:col-span-2">
                <label class="block font-semibold">Descripción / Revisión</label>
                <textarea name="descripcion_revision" rows="3"
                    class="w-full border rounded px-3 py-2">{{ old('descripcion_revision', $orden->descripcion_revision) }}</textarea>
            </div>
        </div>

        <div class="mt-6">
            <button type="submit"
                class="bg-verde hover:bg-azul text-negro px-6 py-2 rounded-lg font-semibold shadow-md transition duration-200">
                Actualizar orden
            </button>
        </div>
    </form>
</div>
@endsection
