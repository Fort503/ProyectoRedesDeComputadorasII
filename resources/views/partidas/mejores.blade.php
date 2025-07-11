<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Top 10 Mejores Partidas</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body.game-bg {
            background-image: url('{{ asset("juego/tables/blue carpet.png") }}');
            background-position: center;
            background-repeat: no-repeat;
            background-size: cover;
            background-attachment: fixed;
        }
        @keyframes deal {
            0% {
            transform: translateY(-100px);
            opacity: 0;
            }
            100% {
            transform: translateY(0);
            opacity: 1;
            }
        }
        .deal-anim {
            animation: deal 0.5s ease-out forwards;
        }
    </style>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="game-bg text-gray-900">
    <x-loading/>
    <!-- Contenido principal oculto hasta que cargue -->
    <div id="contenido" class="hidden">
        <!-- Navbar -->
        <nav x-data="{ open: false }" class=" p-4 shadow-lg">
        <div class="container mx-auto flex items-center justify-between">
            <!-- Logo a la izquierda -->
            <a href="{{ route('welcome') }}"
                class="text-2xl font-bold text-yellow-300 hover:text-yellow-400 transition-colors duration-300">
            Blackjack
            </a>
            <!-- Botón hamburguesa -->
            <button @click="open = !open"
                class="inline-flex items-center justify-center p-2 text-yellow-300 hover:text-yellow-400 
                rounded-md focus:outline-none focus:ring-2 focus:ring-green-200 transition-all duration-200
                md:hidden" 
                :class="{ 'rotate-90': open }">
            <span class="sr-only">Abrir menú</span>
            <svg class="w-6 h-6 transform transition-transform duration-200"
                :class="{ 'rotate-90': open }"
                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path x-show="!open" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M4 6h16M4 12h16M4 18h16" />
                <path x-show="open" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M6 18L18 6M6 6l12 12" />
            </svg>
            </button>
            <!-- Menú de escritorio -->
            <div class="hidden md:flex items-center space-x-4">
            @auth
                <a href="{{ route('mi.puntuacion') }}" class="text-white font-semibold transition-transform duration-200 transform hover:scale-105">
                Hola, {{ Auth::user()->name }}
                </a>
                <a href="{{ route('welcome') }}"
                    class="text-white bg-yellow-500 px-4 py-2 rounded-lg hover:text-yellow-300 transition-colors duration-200">
                Inicio
                </a>
                <a href="{{ route('juego') }}"
                    class="bg-yellow-500 px-4 py-2 rounded-lg text-white hover:bg-yellow-600 transition-colors duration-200">
                Jugar
                </a>
                <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit"
                    class="bg-yellow-500 px-4 py-2 rounded-lg hover:bg-yellow-600 transition-colors duration-200 text-white">
                    Cerrar Sesión
                </button>
                </form>
            @endauth
            </div>
        </div>
        <!-- Menú móvil-->
        <div
            x-show="open"
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 -translate-y-4"
            x-transition:enter-end="opacity-100 translate-y-0"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100 translate-y-0"
            x-transition:leave-end="opacity-0 -translate-y-4"
            class="md:hidden mt-3 rounded-lg shadow-inner overflow-hidden"
        >
            <div class="flex flex-col divide-y divide-green-600">
            @auth
                <a href="{{ route('mi.puntuacion') }}"
                    class="text-left px-4 py-3 text-yellow-300 hover:bg-blue-600 transition-colors duration-150 transform hover:scale-102">
                Hola, {{ Auth::user()->name }}
                </a>
                <a href="{{ route('welcome') }}"
                    class="text-left px-4 py-3 text-yellow-300 hover:bg-blue-600 transition-colors duration-150 transform hover:scale-102">
                Inicio
                </a>
                <a href="{{ route('juego') }}"
                    class="text-left px-4 py-3 text-yellow-300 hover:bg-blue-600 transition-colors duration-150 transform hover:scale-102">
                Jugar
                </a>
                <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit"
                    class="w-full text-left px-4 py-3 text-yellow-300 hover:bg-blue-600 transition-colors duration-150 transform hover:scale-102">
                    Cerrar Sesión
                </button>
                </form>
            @endauth
            </div>
        </div>
        </nav>
        <section class="hero-texts container mx-auto mt-6">
            <img
            src="{{ asset('juego/texts/USO BlackJack.png') }}"
            alt="USO BlackJack"
            class="mx-auto w-10/12 sm:w-8/12 md:w-6/12 lg:w-6/12"
            />
        </section>
        <div class="container mx-auto px-4 py-8">
            <div class="overflow-x-auto">
            <div class="rounded-xl shadow-2xl backdrop-blur">
                <h2 class="text-2xl md:text-3xl font-extrabold text-yellow-400 text-center py-6 tracking-wide drop-shadow-lg">
                🏆 Top 10 Mejores Partidas
                </h2>
                <table class="min-w-full divide-y divide-yellow-300">
                <thead>
                    <tr class="bg-gradient-to-r from-yellow-300 via-yellow-200 to-yellow-100 text-yellow-900">
                    <th class="px-3 py-2 text-xs md:text-base font-bold border-b border-yellow-300">Jugador</th>
                    <th class="px-3 py-2 text-xs md:text-base font-bold border-b border-yellow-300">Banca Final</th>
                    <th class="px-3 py-2 text-xs md:text-base font-bold border-b border-yellow-300">Manos Jugadas</th>
                    <th class="px-3 py-2 text-xs md:text-base font-bold border-b border-yellow-300">Ganadas</th>
                    <th class="px-3 py-2 text-xs md:text-base font-bold border-b border-yellow-300">Perdidas</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($partidas as $partida)
                    <tr class="text-center border-b border-yellow-200 hover:bg-yellow-50 transition-colors duration-150">
                        <td class="px-3 py-2 text-sm md:text-base font-semibold text-yellow-300">{{ $partida->user->name }}</td>
                        <td class="px-3 py-2 text-sm md:text-base text-green-300 font-bold">{{ $partida->banca_final }}</td>
                        <td class="px-3 py-2 text-sm md:text-base text-blue-300">{{ $partida->manos_jugadas }}</td>
                        <td class="px-3 py-2 text-sm md:text-base text-green-300 font-semibold">{{ $partida->ganadas }}</td>
                        <td class="px-3 py-2 text-sm md:text-base text-red-300 font-semibold">{{ $partida->perdidas }}</td>
                    </tr>
                    @endforeach
                </tbody>
                </table>
            </div>
            </div>
        </div>
    </div>
</body>
</html>
