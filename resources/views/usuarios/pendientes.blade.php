@extends('layouts.base')

@section('content')

{{-- Botón de volver --}}
<a href="{{ route('dashboard') }}" class="text-sm text-azul hover:underline flex items-center mb-4">
    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" viewBox="0 0 20 20" fill="currentColor">
        <path fill-rule="evenodd" d="M12.293 16.293a1 1 0 010 1.414l-6-6a1 1 0 010-1.414l6-6a1 1 0 111.414 1.414L8.414 10l5.293 5.293a1 1 0 010 1.414z" clip-rule="evenodd" />
    </svg>
    Volver al dashboard
</a>

<div class="bg-white p-6 rounded shadow-md">
    <h2 class="text-2xl font-bold mb-4">Usuarios pendientes de activación</h2>

    @if (session('success'))
    <div class="mb-4 p-2 bg-green-100 text-green-700 rounded">
        {{ session('success') }}
    </div>
    @endif

    @if ($usuarios->isEmpty())
        <p>No hay usuarios pendientes de activación.</p>
    @else
    <table class="w-full table-auto border-collapse border border-gray-300">
        <thead>
            <tr class="bg-gray-100">
                <th class="border px-4 py-2">ID</th>
                <th class="border px-4 py-2">Nombre</th>
                <th class="border px-4 py-2">Email</th>
                <th class="border px-4 py-2">Asignar Rol</th>
                <th class="border px-4 py-2">Asignar Empleado</th>
                <th class="border px-4 py-2">Acción</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($usuarios as $usuario)
            <tr x-data="{ rol: 'empleado' }">
                <td class="border px-4 py-2">{{ $usuario->id }}</td>
                <td class="border px-4 py-2">{{ $usuario->name }}</td>
                <td class="border px-4 py-2">{{ $usuario->email }}</td>

                <td class="border px-4 py-2">
                    <form action="{{ route('usuarios.asignarRol', $usuario) }}" method="POST">
                        @csrf
                        <select name="rol" class="border rounded px-2 py-1 w-full" x-model="rol">
                            <option value="admin">Administrador</option>
                            <option value="empleado">Empleado</option>
                        </select>
                </td>

                <td class="border px-4 py-2">
                    <select name="empleado_id"
                        :disabled="rol == 'admin'"
                        :class="{ 'bg-gray-200 cursor-not-allowed': rol == 'admin' }"
                        class="border rounded px-2 py-1 w-full">
                        <option value="">-- Ninguno --</option>
                        @foreach ($empleados as $empleado)
                        <option value="{{ $empleado->id }}">
                            {{ $empleado->nombre }} ({{ $empleado->id }})
                        </option>
                        @endforeach
                    </select>
                </td>

                <td class="border px-4 py-2">
                    <button type="submit" class="bg-azul hover:bg-granate text-white px-3 py-1 rounded">
                        Asignar
                    </button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @endif
</div>
@endsection
