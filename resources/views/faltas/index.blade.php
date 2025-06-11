@extends('layouts.base')

@section('content')
<div class="bg-white p-6 rounded shadow-md space-y-6">

    <!-- Enlace para volver al dashboard -->
    <a href="{{ route('dashboard') }}" class="inline-flex items-center text-azul hover:underline">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" viewBox="0 0 20 20" fill="currentColor">
            <path fill-rule="evenodd"
                d="M12.707 15.707a1 1 0 01-1.414 0L6.586 11H17a1 1 0 110 2H6.586l4.707 4.707a1 1 0 01-1.414 1.414l-6.414-6.414a1 1 0 010-1.414l6.414-6.414a1 1 0 011.414 1.414L6.586 9H17a1 1 0 110 2H6.586l4.707 4.707a1 1 0 010 1.414z"
                clip-rule="evenodd" />
        </svg>
        Volver al dashboard
    </a>

    <!-- Título de la sección -->
    <h2 class="text-2xl font-bold text-negro">Gestionar faltas</h2>

    @php
    use Illuminate\Support\Carbon;
    $hoyCarbon = Carbon::now();
    $hoyTexto = $hoyCarbon->locale('es')->translatedFormat('d \d\e F \d\e Y');
    @endphp

    <!-- Descripción e instrucciones -->
    <p class="text-lg font-semibold text-negro">
        Selecciona los empleados que han faltado hoy:
        <span class="text-azul">{{ $hoyTexto }}</span>
    </p>

    <!-- Formulario de faltas -->
    <form method="POST" action="{{ route('faltas.store') }}">
        @csrf

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            @foreach ($empleados as $empleado)
            <div class="flex flex-wrap items-center bg-gray-50 p-3 rounded shadow-sm space-x-2
                    transition-transform transform hover:-translate-y-1 hover:shadow-lg">

                <!-- Checkbox de falta -->
                <input
                    type="checkbox"
                    name="faltas[]"
                    value="{{ $empleado->id }}"
                    id="falta_{{ $empleado->id }}"
                    class="falta-checkbox accent-azul"
                    data-hora="#hora_entrada_{{ $empleado->id }}"
                    {{ in_array($empleado->id, $faltasDeHoy) ? 'checked' : '' }}>

                <label for="falta_{{ $empleado->id }}" class="font-medium mr-2">
                    {{ $empleado->nombre }}
                </label>

                <!-- Hora de entrada -->
                <label for="hora_entrada_{{ $empleado->id }}" class="text-sm text-gray-700">Hora entrada:</label>

                <input
                    type="time"
                    name="horas_entrada[{{ $empleado->id }}]"
                    id="hora_entrada_{{ $empleado->id }}"
                    value="{{ $horasEntradaDeHoy[$empleado->id] ?? '' }}"
                    class="border rounded px-3 py-1 text-sm hora-entrada focus:outline-none focus:ring-2 focus:ring-azul disabled:bg-gray-300 disabled:cursor-not-allowed">
            </div>
            @endforeach
        </div>


        <!-- Botones de acción -->
        <div class="mt-8 flex flex-wrap gap-4">
            <button type="submit"
                class="bg-azul hover:bg-granate text-white px-6 py-2 rounded-lg font-semibold shadow transition">
                Guardar faltas
            </button>

            <a href="{{ route('faltas.crear.manual') }}"
                class="bg-azul hover:bg-granate text-white px-6 py-2 rounded-lg font-semibold shadow transition">
                Añadir falta en otra fecha
            </a>
        </div>
    </form>

    <!-- Script para activar/desactivar el input de hora -->
    <script>
        document.querySelectorAll('.falta-checkbox').forEach(checkbox => {
            const horaInput = document.querySelector(checkbox.dataset.hora);

            // Al cargar: desactiva la hora si el checkbox está marcado
            if (checkbox.checked) {
                horaInput.disabled = true;
                horaInput.classList.add('bg-gray-300', 'cursor-not-allowed');
            }

            // Evento al marcar/desmarcar
            checkbox.addEventListener('change', function() {
                if (this.checked) {
                    horaInput.disabled = true;
                    horaInput.classList.add('bg-gray-300', 'cursor-not-allowed');
                } else {
                    horaInput.disabled = false;
                    horaInput.classList.remove('bg-gray-300', 'cursor-not-allowed');
                }
            });
        });
    </script>

</div>
@endsection
