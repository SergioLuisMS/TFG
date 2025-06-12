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
    <nav class="bg-[#1d1d1b] border-b border-[#317080] shadow" x-data="{ mobileMenuOpen: false }">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">

                <!-- Logos -->
                <div class="flex items-center gap-4">
                    <img src="{{ asset('storage/fotos/Recurso 25.png') }}" alt="Logo Tsa" class="h-8">
                    <img src="{{ asset('storage/fotos/Recurso 33.png') }}" alt="Logo Fistex" class="h-8">
                </div>

                <!-- Botón hamburguesa (móvil) -->
                <div class="lg:hidden">
                    <button @click="mobileMenuOpen = !mobileMenuOpen" class="text-white focus:outline-none">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path x-show="!mobileMenuOpen" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 6h16M4 12h16M4 18h16" />
                            <path x-show="mobileMenuOpen" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <!-- Menú en escritorio -->
                <div class="hidden lg:flex items-center gap-4">
                    <!-- Empleados -->
                    <div x-data="{ open: false }" class="relative">
                        <button @click="open = !open"
                            class="transform transition duration-200 hover:scale-105 text-white bg-[#872829] px-4 py-2 rounded hover:bg-[#d23e5d]">
                            Empleados ▾
                        </button>
                        <div x-show="open" @click.away="open = false" x-transition
                            class="absolute mt-2 w-56 bg-white text-[#1d1d1b] rounded shadow-lg z-50">
                            <a href="{{ route('asistencias.index') }}" class="transform transition duration-200 hover:scale-105 block px-4 py-2 hover:bg-[#7ebdb3]">Gestionar faltas</a>
                            <a href="{{ route('empleados.create') }}" class="transform transition duration-200 hover:scale-105 block px-4 py-2 hover:bg-[#7ebdb3]">Registrar empleados</a>
                            <a href="{{ route('empleados.index') }}" class="transform transition duration-200 hover:scale-105 block px-4 py-2 hover:bg-[#7ebdb3]">Gestionar empleados</a>
                            <a href="{{ route('faltas.graficas.global') }}" class="transform transition duration-200 hover:scale-105 block px-4 py-2 hover:bg-[#7ebdb3]">Gráficas empleados</a>
                        </div>
                    </div>

                    <!-- Usuarios -->
                    <div x-data="{ open: false }" class="relative">
                        <button @click="open = !open"
                            class="transform transition duration-200 hover:scale-105 text-white bg-[#872829] px-4 py-2 rounded hover:bg-[#d23e5d]">
                            Usuarios ▾
                        </button>
                        <div x-show="open" @click.away="open = false" x-transition
                            class="absolute mt-2 w-56 bg-white text-[#1d1d1b] rounded shadow-lg z-50">
                            <a href="{{ route('usuarios.pendientes') }}" class="transform transition duration-200 hover:scale-105 block px-4 py-2 hover:bg-[#7ebdb3]">Gestionar Roles</a>
                        </div>
                    </div>

                    <!-- Órdenes -->
                    <div x-data="{ openOrdenes: false }" class="relative">
                        <button @click="openOrdenes = !openOrdenes"
                            class="transform transition duration-200 hover:scale-105 text-white bg-[#317080] px-4 py-2 rounded hover:bg-[#7ebdb3]">
                            Órdenes ▾
                        </button>
                        <div x-show="openOrdenes" @click.away="openOrdenes = false" x-transition
                            class="absolute mt-2 w-64 bg-white text-[#1d1d1b] rounded shadow-lg z-50">
                            <a href="{{ route('ordenes.index') }}" class="transform transition duration-200 hover:scale-105 block px-4 py-2 hover:bg-[#7ebdb3]">Gestionar órdenes</a>
                            <a href="{{ route('ordenes.create') }}" class="transform transition duration-200 hover:scale-105 block px-4 py-2 hover:bg-[#7ebdb3]">Registrar orden</a>
                            <a href="{{ route('tareas.index') }}" class="transform transition duration-200 hover:scale-105 block px-4 py-2 hover:bg-[#7ebdb3]">Ver tareas</a>
                        </div>
                    </div>
                </div>

                <!-- Usuario (escritorio) -->
                <div class="hidden lg:flex text-sm font-medium text-white items-center gap-4">
                    {{ Auth::user()->name ?? 'Invitado' }}
                    @auth
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit"
                            class="transform transition duration-200 hover:scale-105 underline text-sm hover:text-gray-300">
                            Cerrar sesión
                        </button>
                    </form>
                    @endauth
                </div>
            </div>

            <!-- Menú hamburguesa (móvil) -->
            <div x-show="mobileMenuOpen" x-transition class="lg:hidden mt-4 space-y-2">
                <a href="{{ route('asistencias.index') }}" class="transform transition duration-200 hover:scale-105 block px-4 py-2 bg-[#872829] text-white rounded">Gestionar faltas</a>
                <a href="{{ route('empleados.create') }}" class="transform transition duration-200 hover:scale-105 block px-4 py-2 bg-[#872829] text-white rounded">Registrar empleados</a>
                <a href="{{ route('empleados.index') }}" class="transform transition duration-200 hover:scale-105 block px-4 py-2 bg-[#872829] text-white rounded">Gestionar empleados</a>
                <a href="{{ route('faltas.graficas.global') }}" class="transform transition duration-200 hover:scale-105 block px-4 py-2 bg-[#872829] text-white rounded">Gráficas empleados</a>
                <a href="{{ route('usuarios.pendientes') }}" class="transform transition duration-200 hover:scale-105 block px-4 py-2 bg-[#872829] text-white rounded">Gestionar Roles</a>
                <a href="{{ route('ordenes.index') }}" class="transform transition duration-200 hover:scale-105 block px-4 py-2 bg-[#317080] text-white rounded">Gestionar órdenes</a>
                <a href="{{ route('ordenes.create') }}" class="transform transition duration-200 hover:scale-105 block px-4 py-2 bg-[#317080] text-white rounded">Registrar orden</a>
                <a href="{{ route('tareas.index') }}" class="transform transition duration-200 hover:scale-105 block px-4 py-2 bg-[#317080] text-white rounded">Ver tareas</a>

                @auth
                <form method="POST" action="{{ route('logout') }}" class="px-4">
                    @csrf
                    <button type="submit"
                        class="transform transition duration-200 hover:scale-105 block w-full text-left text-white underline hover:text-gray-300">
                        Cerrar sesión
                    </button>
                </form>
                @endauth
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
