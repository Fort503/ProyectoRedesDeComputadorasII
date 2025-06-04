<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Blackjack</title>
    <script src="https://cdn.tailwindcss.com"></script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="text-white font-sans animate-fade-in min-h-screen" style="background-image: url('/juego/tables/red-carpet.png'); background-size: cover; background-position: center; background-repeat: no-repeat;">
    <x-loading/>
    <div id="contenido" class="hidden">
        <!-- Barra de navegación -->
        <nav x-data="{ open: false }" class="p-4 shadow-lg transition-all duration-300 ease-in-out">
            <div class="container mx-auto flex items-center justify-between flex-wrap">

                <!-- Logo -->
                <a href="{{ route('welcome') }}" class="text-2xl font-bold text-yellow-300 hover:text-yellow-400 transition-colors duration-300 ease-in-out">
                    Blackjack
                </a>

                <!-- Botón hamburguesa-->
                <button @click="open = !open"
                        class="md:hidden inline-flex items-center p-2 text-yellow-300 hover:text-yellow-400 hover:bg-green-700 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-200 transition-all duration-200"
                        :class="{ 'rotate-90': open }">
                    <span class="sr-only">Abrir menú</span>
                    <svg class="w-6 h-6 transform transition-transform duration-200":class="{ 'rotate-90': open }"fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path x-show="!open" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"d="M4 6h16M4 12h16M4 18h16" />
                        <path x-show="open" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>

                <!-- Menú escritoriomedio -->
                <div class="hidden md:flex items-center space-x-4">
                    @auth
                        <span class="text-white font-semibold transition-transform duration-200 transform hover:scale-105">
                            Hola, {{ Auth::user()->name }}
                        </span>
                        <a href="{{ route('juego') }}"class="bg-yellow-500 text-white px-4 py-2 rounded-lg hover:bg-yellow-600 transition-colors duration-200 transform hover:scale-105">
                            Jugar
                        </a>
                        <a href="{{ route('partidas') }}"class="bg-yellow-500 text-white px-4 py-2 rounded-lg hover:bg-yellow-600 transition-colors duration-200 transform hover:scale-105">
                            Ver puntuaciones
                        </a>
                        <form method="POST" action="{{ route('logout') }}" class="inline">
                            @csrf
                            <button type="submit"
                                    class="bg-yellow-500 text-white px-4 py-2 rounded-lg hover:bg-yellow-600 transition-colors duration-200">
                                Cerrar Sesión
                            </button>
                        </form>
                    @endauth

                    @guest
                        <a href="{{ route('login') }}"class="bg-yellow-500 text-white px-4 py-2 rounded-lg hover:bg-yellow-600 transition-colors duration-200 transform hover:scale-105">
                            Iniciar Sesión
                        </a>
                        <a href="{{ route('register') }}"class="bg-yellow-500 text-white px-4 py-2 rounded-lg hover:bg-yellow-600 transition-colors duration-200 transform hover:scale-105">
                            Registrarse
                        </a>
                        <a href="{{ route('juego') }}"class="bg-yellow-500 text-white px-4 py-2 rounded-lg hover:bg-yellow-600 transition-colors duration-200 transform hover:scale-105">
                            Jugar
                        </a>
                    @endguest
                </div>

                <!-- Menú movil -->
                <div
                    x-show="open"
                    x-transition:enter="transition ease-out duration-300"
                    x-transition:enter-start="opacity-0 -translate-y-4"
                    x-transition:enter-end="opacity-100 translate-y-0"
                    x-transition:leave="transition ease-in duration-200"
                    x-transition:leave-start="opacity-100 translate-y-0"
                    x-transition:leave-end="opacity-0 -translate-y-4"
                    class="md:hidden w-full mt-3 bg-black/20 rounded-lg shadow-inner overflow-hidden"
                >
                    <div class="flex flex-col divide-y divide-yellow-500">
                        @auth
                            <span class="block text-left px-4 py-3 text-yellow-300 hover:bg-green-600 transition-colors duration-150 transform hover:scale-105">
                                Hola, {{ Auth::user()->name }}
                            </span>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit"
                                        class="text-yellow-300 block w-full text-left px-4 py-3 hover:bg-green-600 transition-colors duration-150 transform hover:scale-105">
                                    Cerrar Sesión
                                </button>
                            </form>
                            <a href="{{ route('partidas') }}"class="block text-left px-4 py-3 text-yellow-300 hover:bg-green-600 transition-colors duration-150 transform hover:scale-105">
                                Ver puntuaciones
                            </a>
                            <a href="{{ route('juego') }}"class="block text-left px-4 py-3 text-yellow-300 hover:bg-green-600 transition-colors duration-150 transform hover:scale-105">
                                Jugar
                            </a>
                        @endauth

                        @guest
                            <a href="{{ route('login') }}"class="block text-left px-4 py-3 text-yellow-300 hover:bg-green-600 transition-colors duration-150 transform hover:scale-105">
                                Iniciar Sesión
                            </a>
                            <a href="{{ route('register') }}"class="block text-left px-4 py-3 text-yellow-300 hover:bg-green-600 transition-colors duration-150 transform hover:scale-105">
                                Registrarse
                            </a>
                            <a href="{{ route('juego') }}"class="block text-left px-4 py-3 text-yellow-300 hover:bg-green-600 transition-colors duration-150 transform hover:scale-105">
                                Jugar
                            </a>
                        @endguest
                    </div>
                </div>
            </div>
        </nav>

        <!-- Contenido principal -->
        <div class="container mx-auto p-8 text-center animate-fade-in">
            <div class="flex flex-row w-full items-center justify-center mb-5">
                <img src="/juego/cards/6-H.png" alt="7-C" class="w-20 rotate-[-10deg] hover:-translate-y-2 transition-transform duration-300">
                <img src="/juego/cards/K-S.png" alt="7-C" class="w-20 absolute hover:-translate-y-2 transition-transform duration-300">
                <img src="/juego/cards/5-D.png" alt="7-C" class="w-20 rotate-[10deg] hover:-translate-y-2 transition-transform duration-300">
            </div>
            <h1 class="text-4xl font-bold text-yellow-300 mb-6 transition-transform transform hover:scale-105 duration-300">
                Bienvenido a USO Blackjack
            </h1>
            
            <!-- Reglas del Blackjack -->
            <div class="p-5 rounded-lg max-w-3xl mx-auto transition-shadow duration-300 bg-black/30 border border-yellow-400 backdrop-blur-sm">
                <h2 class="text-2xl font-semibold text-yellow-300 mb-4">Reglas del Blackjack</h2>
                <ul class="text-left space-y-3 list-disc marker:text-yellow-300 pl-4">
                    <li>El objetivo es alcanzar una suma de 21 o acercarse sin pasarse.</li>
                    <li>Las cartas del 2 al 10 tienen su valor nominal.</li>
                    <li>Las cartas J, Q y K valen 10 puntos.</li>
                    <li>El As puede valer 1 u 11 puntos según convenga.</li>
                    <li>Puedes "Pedir" para recibir otra carta o "Quedarte" para mantener tu total.</li>
                    <li>El crupier debe pedir hasta alcanzar 17 o más.</li>
                    <li>Si superas 21, pierdes automáticamente.</li>
                    <li>Si tu suma es mayor que la del crupier sin pasarte de 21, ganas.</li>
                </ul>
            </div>
        </div>
    </div>
</body>
</html>
