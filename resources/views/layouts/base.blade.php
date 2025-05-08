<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión Taller</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>


<body class="bg-[#f5f5f5] text-[#1d1d1b] font-sans antialiased">

    <!-- NAVBAR -->
    <nav class="bg-[#1d1d1b] border-b border-[#317080] shadow">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">

                <!-- Logo y título -->
                <div class="flex items-center gap-4">
                    <img src="{{ asset('storage/fotos/Recurso 25.png') }}" alt="Logo Tsa" class="h-8">
                    <img src="{{ asset('storage/fotos/Recurso 33.png') }}" alt="Logo Fistex" class="h-8">
                </div>





                <!-- Menú Empleados -->
                <!-- Menú Empleados con click -->
                <div x-data="{ open: false }" class="relative">
                    <button @click="open = !open" class="!text-white bg-[#872829] px-4 py-2 rounded hover:bg-[#d23e5d] transition">
                        Empleados ▾
                    </button>

                    <div x-show="open" @click.away="open = false" x-transition
                        class="absolute mt-2 w-56 bg-white text-[#1d1d1b] rounded shadow-lg z-50">
                        <a href="{{ route('asistencias.index') }}" class="block px-4 py-2 hover:bg-[#7ebdb3]">Gestionar faltas</a>
                        <a href="{{ route('empleados.create') }}" class="block px-4 py-2 hover:bg-[#7ebdb3]">Registrar empleados</a>
                        <a href="{{ route('empleados.index') }}" class="block px-4 py-2 hover:bg-[#7ebdb3]">Gestionar empleados</a>
                        <a href="{{ route('faltas.graficas.global') }}" class="block px-4 py-2 hover:bg-[#7ebdb3]">Gráficas empleados</a>

                    </div>
                </div>

                {{-- Dropdown para Órdenes con Alpine.js --}}
                <div x-data="{ openOrdenes: false }" class="relative">
                    <button @click="openOrdenes = !openOrdenes" class="!text-white bg-[#317080] px-4 py-2 rounded hover:bg-[#7ebdb3] transition">
                        Órdenes ▾
                    </button>

                    <div x-show="openOrdenes" @click.away="openOrdenes = false" x-transition
                        class="absolute mt-2 w-64 bg-white text-[#1d1d1b] rounded shadow-lg z-50">
                        <a href="{{ route('ordenes.index') }}" class="block px-4 py-2 hover:bg-[#7ebdb3]">Gestionar órdenes de reparación</a>
                        <a href="{{ route('ordenes.create') }}" class="block px-4 py-2 hover:bg-[#7ebdb3]">Registrar nueva orden de reparación</a>
                        <a href="{{ route('tareas.index') }}" class="block px-4 py-2 hover:bg-[#7ebdb3]">Ver tareas por orden</a>
                    </div>
                </div>

                <!-- Usuario -->
                <div class="text-sm font-medium !text-white flex items-center gap-4">
                    {{ Auth::user()->name ?? 'Invitado' }}
                    @auth
                    <form method="POST" action="{{ route('logout') }}" class="inline">
                        @csrf
                        <button type="submit" class="underline text-sm hover:text-gray-300">Cerrar sesión</button>
                    </form>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    <!-- CONTENIDO -->
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        @yield('content')
    </main>
    @stack('scripts')

</body>

</html>
