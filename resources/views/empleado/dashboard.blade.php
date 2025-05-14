@extends('layouts.empleado')

@section('content')
<div class="flex flex-col items-center justify-center text-center">

    {{-- Foto del empleado --}}
    @if (Auth::user()->empleado && Auth::user()->empleado->foto)
    <img src="{{ asset('storage/' . Auth::user()->empleado->foto) }}" alt="Foto de perfil"
        class="rounded-full object-cover shadow-md"
        style="width: 240px; height: 240px; max-width: 240px; max-height: 240px;">
    @else
    <div class="rounded-full bg-gray-300 flex items-center justify-center shadow-md"
        style="width: 240px; height: 240px;">
        <span class="text-base text-gray-600">Sin Foto</span>
    </div>
    @endif





    {{-- Mensaje de bienvenida --}}
    <h1 class="text-3xl font-bold">Bienvenido, {{ Auth::user()->name }}</h1>

</div>
@endsection
