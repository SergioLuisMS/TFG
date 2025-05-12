@extends('layouts.base')

@section('content')
<div class="bg-white p-6 rounded shadow-md">

    <a href="{{ route('empleados.index') }}" class="inline-flex items-center text-azul hover:underline mb-4">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" viewBox="0 0 20 20" fill="currentColor">
            <path fill-rule="evenodd" d="M12.707 15.707a1 1 0 01-1.414 0L6.586 11H17a1 1 0 110 2H6.586l4.707 4.707a1 1 0 01-1.414 1.414l-6.414-6.414a1 1 0 010-1.414l6.414-6.414a1 1 0 011.414 1.414L6.586 9H17a1 1 0 110 2H6.586l4.707 4.707a1 1 0 010 1.414z" clip-rule="evenodd" />
        </svg>
        Volver al listado
    </a>

    <h2 class="text-2xl font-bold mb-6">Editar empleado</h2>

    <form action="{{ route('empleados.update', $empleado->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label for="nombre" class="block font-medium">Nombre *</label>
                <input type="text" name="nombre" id="nombre" required value="{{ old('nombre', $empleado->nombre) }}"
                    class="w-full border border-gray-300 rounded px-3 py-2 mt-1 text-negro" />
            </div>

            <div>
                <label for="alias" class="block font-medium">Alias</label>
                <input type="text" name="alias" id="alias" value="{{ old('alias', $empleado->alias) }}"
                    class="w-full border border-gray-300 rounded px-3 py-2 mt-1 text-negro" />
            </div>

            <div>
                <label for="nif" class="block font-medium">NIF</label>
                <input type="text" name="nif" id="nif" value="{{ old('nif', $empleado->nif) }}"
                    class="w-full border border-gray-300 rounded px-3 py-2 mt-1 text-negro" />
            </div>

            <div>
                <label for="primer_apellido" class="block font-medium">Primer Apellido</label>
                <input type="text" name="primer_apellido" id="primer_apellido" value="{{ old('primer_apellido', $empleado->primer_apellido) }}"
                    class="w-full border border-gray-300 rounded px-3 py-2 mt-1 text-negro" />
            </div>

            <div>
                <label for="segundo_apellido" class="block font-medium">Segundo Apellido</label>
                <input type="text" name="segundo_apellido" id="segundo_apellido" value="{{ old('segundo_apellido', $empleado->segundo_apellido) }}"
                    class="w-full border border-gray-300 rounded px-3 py-2 mt-1 text-negro" />
            </div>

            <div class="mb-4">
                <label for="hora_entrada_contrato" class="block text-gray-700">Hora de entrada (Contrato)</label>
                <input
                    type="time"
                    id="hora_entrada_contrato"
                    name="hora_entrada_contrato"
                    value="{{ old('hora_entrada_contrato', $empleado->hora_entrada_contrato ?? '') }}"
                    class="mt-1 block w-full border rounded px-3 py-2">
            </div>

            <div>
                <label for="telefono" class="block font-medium">Teléfono</label>
                <input type="text" name="telefono" id="telefono" value="{{ old('telefono', $empleado->telefono) }}"
                    class="w-full border border-gray-300 rounded px-3 py-2 mt-1 text-negro" />
            </div>

            <div>
                <label for="telefono_movil" class="block font-medium">Teléfono móvil</label>
                <input type="text" name="telefono_movil" id="telefono_movil" value="{{ old('telefono_movil', $empleado->telefono_movil) }}"
                    class="w-full border border-gray-300 rounded px-3 py-2 mt-1 text-negro" />
            </div>

            <div>
                <label for="direccion" class="block font-medium">Dirección</label>
                <input type="text" name="direccion" id="direccion" value="{{ old('direccion', $empleado->direccion) }}"
                    class="w-full border border-gray-300 rounded px-3 py-2 mt-1 text-negro" />
            </div>

            <div>
                <label for="codigo_postal" class="block font-medium">Código Postal</label>
                <input type="text" name="codigo_postal" id="codigo_postal" value="{{ old('codigo_postal', $empleado->codigo_postal) }}"
                    class="w-full border border-gray-300 rounded px-3 py-2 mt-1 text-negro" />
            </div>

            <div>
                <label for="poblacion" class="block font-medium">Población</label>
                <input type="text" name="poblacion" id="poblacion" value="{{ old('poblacion', $empleado->poblacion) }}"
                    class="w-full border border-gray-300 rounded px-3 py-2 mt-1 text-negro" />
            </div>

            <div>
                <label for="provincia" class="block font-medium">Provincia</label>
                <input type="text" name="provincia" id="provincia" value="{{ old('provincia', $empleado->provincia) }}"
                    class="w-full border border-gray-300 rounded px-3 py-2 mt-1 text-negro" />
            </div>

            <div>
                <label for="cumple_dia" class="block font-medium">Día de cumpleaños</label>
                <input type="number" name="cumple_dia" id="cumple_dia" min="1" max="31" value="{{ old('cumple_dia', $empleado->cumple_dia) }}"
                    class="w-full border border-gray-300 rounded px-3 py-2 mt-1 text-negro" />
            </div>

            <div>
                <label for="cumple_mes" class="block font-medium">Mes de cumpleaños</label>
                <input type="number" name="cumple_mes" id="cumple_mes" min="1" max="12" value="{{ old('cumple_mes', $empleado->cumple_mes) }}"
                    class="w-full border border-gray-300 rounded px-3 py-2 mt-1 text-negro" />
            </div>

            <div>
                <label for="email" class="block font-medium">Correo electrónico</label>
                <input type="email" name="email" id="email" value="{{ old('email', $empleado->email) }}"
                    class="w-full border border-gray-300 rounded px-3 py-2 mt-1 text-negro" />
            </div>

            <div class="flex items-center mt-6">
                <input type="checkbox" name="bloqueado" id="bloqueado" class="mr-2"
                    {{ old('bloqueado', $empleado->bloqueado) ? 'checked' : '' }} />
                <label for="bloqueado" class="font-medium">Bloqueado</label>
            </div>

            <div class="col-span-1 md:col-span-2">
                <label for="observaciones" class="block font-medium">Observaciones</label>
                <textarea name="observaciones" id="observaciones" rows="3"
                    class="w-full border border-gray-300 rounded px-3 py-2 mt-1 text-negro">{{ old('observaciones', $empleado->observaciones) }}</textarea>
            </div>

            {{-- FOTO --}}
            <div class="col-span-1 md:col-span-2">
                <label for="foto" class="block font-medium">Foto de perfil</label>

                @if ($empleado->foto)
                <div class="w-32 h-32 rounded-full overflow-hidden shadow mb-2">
                    <img src="{{ asset('storage/' . $empleado->foto) }}" alt="Foto actual"
                        class="w-full h-full object-cover">
                </div>
                @endif

                <input type="file" name="foto" id="foto" accept="image/*"
                    class="w-full border border-gray-300 rounded px-3 py-2 mt-1" />

            </div>

            <div class="col-span-1 md:col-span-2 mb-2">
                <div class="w-48 h-48 mx-auto overflow-hidden rounded-full border border-gray-300 shadow">
                    <img id="preview" class="object-cover w-full h-full hidden" alt="Previsualización">
                </div>
            </div>

            <div class="text-center col-span-1 md:col-span-2 mb-4">
                <button type="button"
                    onclick="recortarImagen()"
                    class="bg-verde hover:bg-azul text-negro px-4 py-2 rounded-lg font-semibold shadow transition">
                    Usar esta imagen
                </button>
                <input type="hidden" name="imagen_crop" id="imagen_crop">
            </div>
        </div>

        <div class="mt-6">
            <button type="submit"
                class="bg-azul hover:bg-granate text-white px-6 py-2 rounded-lg font-semibold shadow transition">
                Actualizar empleado
            </button>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/cropperjs@1.5.13/dist/cropper.min.css" />
<script src="https://cdn.jsdelivr.net/npm/cropperjs@1.5.13/dist/cropper.min.js"></script>

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
