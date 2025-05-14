@extends('layouts.empleado')

@section('content')
    <h1 class="text-2xl font-bold mb-4">Bienvenido, {{ Auth::user()->name }}</h1>
    <p class="text-gray-700">Aquí podrás gestionar tus tareas asignadas.</p>
@endsection
