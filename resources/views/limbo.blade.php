@extends('layouts.limbo')

@section('content')
    <h1 class="text-2xl font-bold mb-4">Tu cuenta está pendiente de activación</h1>
    <p class="mb-4">Has completado el registro correctamente, pero un administrador debe asignarte un rol para poder continuar.</p>
    <p>Por favor, vuelve a intentarlo más tarde o contacta con un administrador.</p>

    <form action="{{ route('logout') }}" method="POST" class="mt-4">
        @csrf
        <button type="submit" class="bg-azul hover:bg-granate text-white px-4 py-2 rounded">Cerrar sesión</button>
    </form>
@endsection
