@extends('layouts.empleado')

@section('content')
<div class="flex flex-col items-center justify-center text-center">

    <style>
        [x-cloak] {
            display: none !important;
        }

        /* Animación personalizada opcional */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .animate-fade-in-up {
            animation: fadeInUp 0.5s ease-out both;
        }
    </style>

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
    <h1 class="text-3xl font-bold mt-4">Bienvenido, {{ Auth::user()->name }}</h1>
</div>

{{-- Valoraciones del mes --}}
@if($comentariosValorados->count())
<div class="mt-10 max-w-3xl mx-auto px-4 animate-fade-in-up">
    <div class="bg-white p-5 rounded-xl shadow transition-all duration-500 transform hover:scale-[1.01] hover:shadow-lg">
        <h2 class="text-xl font-semibold text-gray-800 mb-4 text-center">
            🗨 Comentarios valorados este mes
        </h2>

        <ul class="space-y-3 text-sm text-gray-800">
            @foreach($comentariosValorados as $comentario)
            <li class="border border-gray-200 rounded p-3 bg-gray-50 transition-all duration-300 ease-in-out transform hover:scale-[1.02] hover:shadow-md">
                <div class="flex justify-between items-center">
                    <div>
                        <span class="text-blue-700 font-semibold">
                            {{ $comentario->tarea->orden->matricula ?? 'Sin matrícula' }}
                        </span>
                        <span class="text-gray-600">– {{ $comentario->tarea->descripcion }}</span>
                    </div>

                    @php
                    $v = intval($comentario->valoracion ?? 0);
                    @endphp
                    @if($v > 0)
                    <div class="text-yellow-600 font-bold text-xs">
                        {!! str_repeat('⭐', $v) !!}
                    </div>
                    @endif
                </div>
                <div class="mt-1 text-gray-700">{{ $comentario->contenido }}</div>
            </li>
            @endforeach
        </ul>
    </div>
</div>
@else
<div class="mt-10 text-center text-gray-500 text-sm">
    No tienes comentarios valorados este mes.
</div>
@endif
@endsection
