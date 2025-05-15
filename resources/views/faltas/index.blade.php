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
    <h2 class="text-2xl font-bold mb-6">Gestionar faltas</h2>

    @php
    use Illuminate\Support\Carbon;
    $hoyCarbon = Carbon::now();
    $hoyTexto = $hoyCarbon->locale('es')->translatedFormat('d \d\e F \d\e Y');
    @endphp

    <p class="text-lg font-semibold text-negro mb-4">
        Selecciona los empleados que han faltado hoy: <span class="text-azul">{{ $hoyTexto }}</span>
    </p>

    <form method="POST" action="{{ route('faltas.store') }}">
        @csrf

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            @foreach ($empleados as $empleado)
            <div class="flex items-center mb-2">
                <input type="checkbox" name="faltas[]" value="{{ $empleado->id }}" id="falta_{{ $empleado->id }}"
                    class="mr-2 falta-checkbox" data-hora="#hora_entrada_{{ $empleado->id }}"
                    {{ in_array($empleado->id, $faltasDeHoy) ? 'checked' : '' }}>

                <label for="falta_{{ $empleado->id }}" class="mr-4">{{ $empleado->nombre }}</label>

                <label for="hora_entrada_{{ $empleado->id }}" class="mr-2">Hora entrada:</label>
                <input type="time"
                    name="horas_entrada[{{ $empleado->id }}]"
                    id="hora_entrada_{{ $empleado->id }}"
                    value="{{ $horasEntradaDeHoy[$empleado->id] ?? '' }}"
                    class="border rounded px-2 py-1 hora-entrada">

            </div>
            @endforeach


        </div>

        <div class="mt-6">
            <button type="submit"
                class="bg-azul hover:bg-granate text-white px-6 py-2 rounded-lg font-semibold shadow transition">
                Guardar faltas
            </button>


            <a href="{{ route('faltas.crear.manual') }}"
                class="ml-4 inline-block bg-azul hover:bg-granate text-white px-6 py-2 rounded-lg font-semibold shadow transition">
                Añadir falta en otra fecha
            </a>

        </div>
    </form>

    

    <script>
        document.querySelectorAll('.falta-checkbox').forEach(checkbox => {
            const horaInput = document.querySelector(checkbox.dataset.hora);
            if (checkbox.checked) {
                horaInput.disabled = true;
                horaInput.classList.add('bg-gray-300', 'cursor-not-allowed');
            }

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
