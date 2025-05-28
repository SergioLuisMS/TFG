@extends('layouts.base')

@section('content')
<div class="bg-white p-6 rounded shadow-md">
    {{-- Enlace de retorno al dashboard --}}
    <a href="{{ route('dashboard') }}" class="inline-flex items-center text-azul hover:underline mb-4">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" viewBox="0 0 20 20" fill="currentColor">
            <path fill-rule="evenodd" d="M12.707 15.707a1 1 0 01-1.414 0L6.586 11H17a1 1 0 110 2H6.586l4.707 4.707a1 1 0 01-1.414 1.414l-6.414-6.414a1 1 0 010-1.414l6.414-6.414a1 1 0 011.414 1.414L6.586 9H17a1 1 0 110 2H6.586l4.707 4.707a1 1 0 010 1.414z" clip-rule="evenodd" />
        </svg>
        Volver al dashboard
    </a>

    {{-- Título de la página --}}
    <h2 class="text-2xl font-bold mb-6">Registrar nuevo empleado</h2>

    {{-- Formulario de creación de empleado --}}
    <form action="{{ route('empleados.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

            {{-- Foto de perfil con previsualización --}}
            <div class="mb-4">
                <label for="foto" class="block text-gray-700">Foto de perfil</label>
                <input type="file" name="foto" id="foto" accept="image/*"
                       onchange="mostrarPrevisualizacion()"
                       class="mt-1 block w-full border rounded px-3 py-2">
                <div class="mt-2">
                    <img id="preview" class="hidden mt-2 rounded-full object-cover border border-gray-300 shadow"
                         style="width: 150px; height: 150px;" alt="Previsualización de foto">
                </div>
            </div>

            {{-- Información personal básica --}}
            <div>
                <label for="nombre" class="block font-medium">Nombre *</label>
                <input type="text" name="nombre" id="nombre" required class="w-full border border-gray-300 rounded px-3 py-2 mt-1" />
            </div>

            <div>
                <label for="alias" class="block font-medium">Alias</label>
                <input type="text" name="alias" id="alias" class="w-full border border-gray-300 rounded px-3 py-2 mt-1" />
            </div>

            <div>
                <label for="nif" class="block font-medium">NIF</label>
                <input type="text" name="nif" id="nif" class="w-full border border-gray-300 rounded px-3 py-2 mt-1" />
            </div>

            <div>
                <label for="primer_apellido" class="block font-medium">Primer Apellido</label>
                <input type="text" name="primer_apellido" id="primer_apellido" class="w-full border border-gray-300 rounded px-3 py-2 mt-1" />
            </div>

            <div>
                <label for="segundo_apellido" class="block font-medium">Segundo Apellido</label>
                <input type="text" name="segundo_apellido" id="segundo_apellido" class="w-full border border-gray-300 rounded px-3 py-2 mt-1" />
            </div>

            {{-- Hora contractual de entrada --}}
            <div class="mb-4">
                <label for="hora_entrada_contrato" class="block text-gray-700">Hora de entrada (Contrato)</label>
                <input type="time"
                       id="hora_entrada_contrato"
                       name="hora_entrada_contrato"
                       value="{{ old('hora_entrada_contrato', $empleado->hora_entrada_contrato ?? '') }}"
                       class="mt-1 block w-full border rounded px-3 py-2">
            </div>

            {{-- Contacto --}}
            <div>
                <label for="telefono" class="block font-medium">Teléfono</label>
                <input type="text" name="telefono" id="telefono" class="w-full border border-gray-300 rounded px-3 py-2 mt-1" />
            </div>

            <div>
                <label for="telefono_movil" class="block font-medium">Teléfono móvil</label>
                <input type="text" name="telefono_movil" id="telefono_movil" class="w-full border border-gray-300 rounded px-3 py-2 mt-1" />
            </div>

            {{-- Dirección --}}
            <div>
                <label for="direccion" class="block font-medium">Dirección</label>
                <input type="text" name="direccion" id="direccion" class="w-full border border-gray-300 rounded px-3 py-2 mt-1" />
            </div>

            <div>
                <label for="codigo_postal" class="block font-medium">Código Postal</label>
                <input type="text" name="codigo_postal" id="codigo_postal" class="w-full border border-gray-300 rounded px-3 py-2 mt-1" />
            </div>

            <div>
                <label for="poblacion" class="block font-medium">Población</label>
                <input type="text" name="poblacion" id="poblacion" class="w-full border border-gray-300 rounded px-3 py-2 mt-1" />
            </div>

            <div>
                <label for="provincia" class="block font-medium">Provincia</label>
                <input type="text" name="provincia" id="provincia" class="w-full border border-gray-300 rounded px-3 py-2 mt-1" />
            </div>

            {{-- Cumpleaños --}}
            <div>
                <label for="cumple_dia" class="block font-medium">Día de cumpleaños</label>
                <input type="number" name="cumple_dia" id="cumple_dia" min="1" max="31" class="w-full border border-gray-300 rounded px-3 py-2 mt-1" />
            </div>

            <div>
                <label for="cumple_mes" class="block font-medium">Mes de cumpleaños</label>
                <input type="number" name="cumple_mes" id="cumple_mes" min="1" max="12" class="w-full border border-gray-300 rounded px-3 py-2 mt-1" />
            </div>

            {{-- Email --}}
            <div>
                <label for="email" class="block font-medium">Correo electrónico</label>
                <input type="email" name="email" id="email" class="w-full border border-gray-300 rounded px-3 py-2 mt-1" />
            </div>

            {{-- Bloqueado --}}
            <div class="flex items-center mt-6">
                <input type="checkbox" name="bloqueado" id="bloqueado" class="mr-2" />
                <label for="bloqueado" class="font-medium">Bloqueado</label>
            </div>

            {{-- Observaciones --}}
            <div class="col-span-1 md:col-span-2">
                <label for="observaciones" class="block font-medium">Observaciones</label>
                <textarea name="observaciones" id="observaciones" rows="3" class="w-full border border-gray-300 rounded px-3 py-2 mt-1"></textarea>
            </div>

            {{-- Campo oculto para edición futura (crop) --}}
            <input type="hidden" name="imagen_crop" id="imagen_crop">

            {{-- Botón de enviar --}}
            <div class="mt-6">
                <button type="submit" class="bg-verde hover:bg-azul text-negro px-6 py-2 rounded-lg font-semibold shadow-md transition duration-200">
                    Guardar empleado
                </button>
            </div>
        </div>
    </form>
</div>
@endsection

@push('scripts')
{{-- Script para previsualizar imagen cargada --}}
<script>
    function mostrarPrevisualizacion() {
        const input = document.getElementById('foto');
        const preview = document.getElementById('preview');
        const file = input.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                preview.src = e.target.result;
                preview.classList.remove('hidden');
            };
            reader.readAsDataURL(file);
        }
    }
</script>
@endpush
