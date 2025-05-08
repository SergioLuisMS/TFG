@extends('layouts.base')

@section('content')
<div class="bg-white p-6 rounded shadow-md">
    <h2 class="text-2xl font-bold mb-6">Registrar nuevo empleado</h2>

    <form action="{{ route('empleados.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            {{-- Campos principales --}}
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

            <div>
                <label for="telefono" class="block font-medium">Teléfono</label>
                <input type="text" name="telefono" id="telefono" class="w-full border border-gray-300 rounded px-3 py-2 mt-1" />
            </div>

            <div>
                <label for="telefono_movil" class="block font-medium">Teléfono móvil</label>
                <input type="text" name="telefono_movil" id="telefono_movil" class="w-full border border-gray-300 rounded px-3 py-2 mt-1" />
            </div>

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

            <div>
                <label for="cumple_dia" class="block font-medium">Día de cumpleaños</label>
                <input type="number" name="cumple_dia" id="cumple_dia" min="1" max="31" class="w-full border border-gray-300 rounded px-3 py-2 mt-1" />
            </div>

            <div>
                <label for="cumple_mes" class="block font-medium">Mes de cumpleaños</label>
                <input type="number" name="cumple_mes" id="cumple_mes" min="1" max="12" class="w-full border border-gray-300 rounded px-3 py-2 mt-1" />
            </div>

            <div>
                <label for="email" class="block font-medium">Correo electrónico</label>
                <input type="email" name="email" id="email" class="w-full border border-gray-300 rounded px-3 py-2 mt-1" />
            </div>

            <div class="flex items-center mt-6">
                <input type="checkbox" name="bloqueado" id="bloqueado" class="mr-2" />
                <label for="bloqueado" class="font-medium">Bloqueado</label>
            </div>

            <div class="col-span-1 md:col-span-2">
                <label for="observaciones" class="block font-medium">Observaciones</label>
                <textarea name="observaciones" id="observaciones" rows="3" class="w-full border border-gray-300 rounded px-3 py-2 mt-1"></textarea>
            </div>

            {{-- Foto de perfil --}}
            <div class="col-span-1 md:col-span-2">
                <label for="foto" class="block font-medium">Foto de perfil</label>
                <input type="file" name="foto" id="foto" accept="image/*" onchange="mostrarPrevisualizacion()" class="w-full border border-gray-300 rounded px-3 py-2 mt-1" />
            </div>

            {{-- Vista previa --}}
            <div class="my-4">
                <div class="w-48 h-48 mx-auto overflow-hidden rounded-full border border-gray-300 shadow">
                    <img id="preview" class="object-cover w-full h-full hidden" alt="Previsualización">
                </div>
            </div>

            {{-- Botón para aplicar recorte (opcional, desactivado si ya no usas crop) --}}
            <div class="text-center">
                <button type="button" onclick="recortarImagen()" class="bg-verde hover:bg-azul text-negro px-4 py-2 rounded-lg font-semibold shadow transition">
                    Usar esta imagen
                </button>
            </div>

            {{-- Campo oculto que contendría la imagen recortada --}}
            <input type="hidden" name="imagen_crop" id="imagen_crop">

            {{-- Botón enviar --}}
            <div class="mt-6">
                <button type="submit" class="bg-verde hover:bg-azul text-negro px-6 py-2 rounded-lg font-semibold shadow-md transition duration-200">
                    Guardar empleado
                </button>
            </div>
        </div>
    </form>
</div>
@endsection

{{-- JavaScript: usar stack correcto --}}
@push('scripts')
<script>
    function mostrarPrevisualizacion() {
        const input = document.getElementById('foto');
        const preview = document.getElementById('preview');
        const file = input.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function (e) {
                preview.src = e.target.result;
                preview.classList.remove('hidden');
            };
            reader.readAsDataURL(file);
        }
    }

    function recortarImagen() {
        // Si decides volver a usar Cropper.js, aquí se integraría la lógica.
        // Actualmente, solo usamos previsualización sin recorte real.
        const preview = document.getElementById('preview');
        if (preview && preview.src) {
            document.getElementById('imagen_crop').value = preview.src;
        }
    }
</script>
@endpush
