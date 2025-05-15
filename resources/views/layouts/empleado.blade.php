<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel del Empleado</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        html,
        body {
            height: 100%;
            margin: 0;
            padding: 0;
            overflow: hidden;
        }

        #splash {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: #1d1d1b;
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 50;
            animation: fadeOut 1.5s ease forwards 1.5s;
        }

        @keyframes fadeOut {
            to {
                opacity: 0;
                visibility: hidden;
            }
        }

        #splash img {
            width: 600px;
            animation: pop 1s ease forwards;
        }

        @keyframes pop {
            0% {
                transform: scale(0);
                opacity: 0;
            }

            50% {
                transform: scale(1.2);
                opacity: 1;
            }

            100% {
                transform: scale(1);
            }
        }
    </style>
</head>

<body class="bg-[#f5f5f5] text-[#1d1d1b] font-sans antialiased flex flex-col min-h-screen">

    <!-- Splash Animation -->
    @if (!isset($noSplash))
    <div id="splash">
        <img src="{{ asset('storage/fotos/Recurso 25.png') }}" alt="Logo Tsa">
    </div>
    @endif

    <!-- Navbar exclusivo para empleados -->
    <nav class="bg-[#1d1d1b] border-b border-[#317080] shadow h-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-full">
            <div class="flex justify-between items-center h-full">
                <div class="flex items-center gap-4">
                    <img src="{{ asset('storage/fotos/Recurso 25.png') }}" alt="Logo Tsa" class="h-8">
                    <img src="{{ asset('storage/fotos/Recurso 33.png') }}" alt="Logo Fistex" class="h-8">
                </div>

                <div x-data="{ open: false }" class="relative">
                    <button @click="open = !open" class="!text-white bg-[#317080] px-4 py-2 rounded hover:bg-[#7ebdb3] transition">
                        Tareas ▾
                    </button>
                    <div x-show="open" @click.away="open = false" x-transition
                        class="absolute mt-2 w-56 bg-white text-[#1d1d1b] rounded shadow-lg z-50">
                        <a href="{{ route('empleado.tareas') }}" class="block px-4 py-2 hover:bg-[#7ebdb3]">Gestionar tus tareas</a>
                    </div>
                </div>

                <div class="text-sm font-medium !text-white flex items-center gap-4">
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
