@extends('layouts.base')

@section('content')

<div class="min-h-screen flex items-center justify-center bg-[#f5f5f5]">
    <div class="bg-[#1d1d1b] p-8 rounded-2xl shadow-lg w-full max-w-md">
        <h1 class="text-2xl font-bold mb-6 text-white text-center">Crear cuenta</h1>

        <form method="POST" action="{{ route('register') }}">
            @csrf

            @if ($errors->any())
            <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded">
                <ul class="list-disc pl-5">
                    @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            <div class="mb-4">
                <label class="block mb-1 font-semibold text-white" for="name">Nombre</label>
                <input id="name" type="text" name="name" required class="w-full px-4 py-2 border border-[#7ebdb3] rounded bg-transparent text-white focus:outline-none focus:ring-2 focus:ring-[#7ebdb3]">
            </div>

            <div class="mb-4">
                <label class="block mb-1 font-semibold text-white" for="email">Correo electrónico</label>
                <input id="email" type="email" name="email" required class="w-full px-4 py-2 border border-[#7ebdb3] rounded bg-transparent text-white focus:outline-none focus:ring-2 focus:ring-[#7ebdb3]">
            </div>

            <div class="mb-4">
                <label class="block mb-1 font-semibold text-white" for="password">Contraseña</label>
                <input id="password" type="password" name="password" required class="w-full px-4 py-2 border border-[#7ebdb3] rounded bg-transparent text-white focus:outline-none focus:ring-2 focus:ring-[#7ebdb3]">
            </div>

            <div class="mb-6">
                <label class="block mb-1 font-semibold text-white" for="password_confirmation">Confirmar contraseña</label>
                <input id="password_confirmation" type="password" name="password_confirmation" required class="w-full px-4 py-2 border border-[#7ebdb3] rounded bg-transparent text-white focus:outline-none focus:ring-2 focus:ring-[#7ebdb3]">
            </div>

            <button type="submit" class="w-full bg-[#872829] text-white py-2 rounded hover:bg-[#d23esd] transition duration-300">
                Registrarse
            </button>
            

            <p class="mt-4 text-center text-sm text-white">
                ¿Ya tienes cuenta?
                <a href="{{ route('login') }}" class="text-[#7ebdb3] underline hover:text-[#317080]">
                    Inicia sesión
                </a>
            </p>

        </form>
    </div>
</div>


@endsection
