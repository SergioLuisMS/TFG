<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Tareas - Empleado</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-[#f5f5f5] text-[#1d1d1b] font-sans antialiased flex flex-col min-h-screen overflow-y-auto">

    <!-- Navbar exclusivo para empleados -->
    <nav class="bg-[#1d1d1b] border-b border-[#317080] shadow h-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-full">
            <div class="flex justify-between items-center h-full">
                <div class="flex items-center gap-4">
                    <img src="{{ asset('storage/fotos/Recurso 25.png') }}" alt="Logo Tsa" class="h-8">
                    <img src="{{ asset('storage/fotos/Recurso 33.png') }}" alt="Logo Fistex" class="h-8">
                </div>

                <div class="text-sm font-medium text-white flex items-center gap-4">
                    {{ Auth::user()->name ?? 'Invitado' }}
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="underline text-sm hover:text-gray-300">Cerrar sesión</button>
                    </form>
                </div>
            </div>
        </div>
    </nav>

    <!-- CONTENIDO -->
    <main class="flex-1 px-4 sm:px-6 lg:px-8 py-8">
        @yield('content')
    </main>

    @stack('scripts')
</body>

</html>
