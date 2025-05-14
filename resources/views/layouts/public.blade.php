<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión Taller</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-[#f5f5f5] text-[#1d1d1b] font-sans antialiased">

    <!-- NAVBAR BÁSICO -->
    <nav class="bg-[#1d1d1b] border-b border-[#317080] shadow">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">

                <!-- Logo -->
                <div class="flex items-center gap-4">
                    <img src="{{ asset('storage/fotos/Recurso 25.png') }}" alt="Logo Tsa" class="h-8">
                    <img src="{{ asset('storage/fotos/Recurso 33.png') }}" alt="Logo Fistex" class="h-8">
                </div>

                <!-- Usuario / Logout -->
                <div class="text-sm font-medium !text-white flex items-center gap-4">
                    {{ Auth::user()->name ?? 'Invitado' }}
                    @auth
                        <form method="POST" action="{{ route('logout') }}" id="logout-form">
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
