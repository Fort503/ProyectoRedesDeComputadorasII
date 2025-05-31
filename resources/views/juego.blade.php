<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Blackjack</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="stylesheet" href="{{ asset('juego/style.css') }}">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <script>
    var cardsUrl = "{{ asset('juego/cards') }}/";
  </script>
  @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-green-950 text-gray-800 font-sans">
  <x-loading/>
  <!-- Contenido principal oculto hasta que cargue -->
  <div id="contenido" class="hidden">
    <!-- Navbar -->
    <nav x-data="{ open: false }" class="bg-green-800 p-4 shadow-lg">
      <div class="container mx-auto flex items-center justify-between">
        <!-- Logo a la izquierda -->
        <a href="{{ route('welcome') }}"
            class="text-2xl font-bold text-yellow-300 hover:text-yellow-400 transition-colors duration-300">
          Blackjack
        </a>
        <!-- Botón hamburguesa -->
        <button @click="open = !open"
            class="inline-flex items-center justify-center p-2 text-yellow-300 hover:text-yellow-400 hover:bg-green-700 
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
            <span class="text-white font-semibold transition-transform duration-200 transform hover:scale-105">
              Hola, {{ Auth::user()->name }}
            </span>
            <a href="{{ route('welcome') }}"
                class="text-white bg-yellow-500 px-4 py-2 rounded-lg hover:text-yellow-300 transition-colors duration-200">
              Inicio
            </a>
            <form method="POST" action="{{ route('logout') }}">
              @csrf
              <button type="submit"
                  class="bg-yellow-500 px-4 py-2 rounded-lg hover:bg-yellow-600 transition-colors duration-200 text-white">
                Cerrar Sesión
              </button>
            </form>
          @endauth
          @guest
            <a href="{{ route('login') }}"
                class="text-white hover:text-yellow-300 transform hover:scale-105 transition-transform duration-200">
              Iniciar Sesión
            </a>
            <a href="{{ route('register') }}"
                class="text-white hover:text-yellow-300 transform hover:scale-105 transition-transform duration-200">
              Registrarse
            </a>
            <a href="{{ route('juego') }}"
                class="bg-yellow-500 px-4 py-2 rounded-lg hover:bg-yellow-600 transition-colors duration-200">
              Jugar
            </a>
          @endguest
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
        class="md:hidden mt-3 bg-green-700 rounded-lg shadow-inner overflow-hidden"
      >
        <div class="flex flex-col divide-y divide-green-600">
          @auth
            <a href="{{ route('welcome') }}"
                class="text-left px-4 py-3 text-yellow-300 hover:bg-green-600 transition-colors duration-150 transform hover:scale-102">
              Inicio
            </a>
            <form method="POST" action="{{ route('logout') }}">
              @csrf
              <button type="submit"
                  class="w-full text-left px-4 py-3 text-yellow-300 hover:bg-green-600 transition-colors duration-150 transform hover:scale-102">
                Cerrar Sesión
              </button>
            </form>
            <a href="{{ route('juego') }}"
                class="text-left px-4 py-3 text-yellow-300 hover:bg-green-600 transition-colors duration-150 transform hover:scale-102">
              Jugar
            </a>
          @endauth
          @guest
            <a href="{{ route('login') }}"
                class="text-left px-4 py-3 text-yellow-300 hover:bg-green-600 transition-colors duration-150 transform hover:scale-102">
              Iniciar Sesión
            </a>
            <a href="{{ route('register') }}"
                class="w-full text-left px-4 py-3 text-yellow-300 hover:bg-green-600 transition-colors duration-150 transform hover:scale-102">
              Registrarse
            </a>
            <a href="{{ route('juego') }}"
                class="text-left px-4 py-3 text-yellow-300 hover:bg-green-600 transition-colors duration-150 transform hover:scale-102">
              Jugar
            </a>
          @endguest
        </div>
      </div>
    </nav>
    <!-- Contenedor principal del juego -->
    <div class="container mx-auto p-4">
      <h1 class="text-3xl text-white font-semibold text-center mb-6 p-2">
        Blackjack
      </h1>
      <!-- Sección del juego -->
      <div id="game-container" class="max-w-4xl mx-auto bg-green-800 p-6 rounded-lg shadow-lg text-white animate-fade-in flex flex-col items-center">
        <!-- Sección del Crupier -->
        <div class="text-center mb-6">
          <h2 class="text-xl font-bold">
            Repartidor: <span id="dealer-sum" class="font-semibold text-yellow-300"></span>
          </h2>
          <div id="dealer-cards" class="flex flex-wrap gap-x-2 justify-center">
            <img src="{{ asset('juego/cards/BACK.png') }}" alt="Carta Oculta" id="hidden" class="w-16 h-24 rounded-lg transition-transform duration-300 hover:scale-105">
          </div>
        </div>
        <!-- Línea divisoria -->
        <hr class="w-full border-t border-yellow-500 my-4">
        <!-- Sección del Jugador -->
        <div class="text-center mt-6">
          <h2 class="text-xl font-bold">
            Jugador: <span id="your-sum" class="font-semibold text-yellow-300"></span>
          </h2>
          <div id="your-cards" class="flex flex-wrap gap-x-2 justify-center"></div>
        </div>
        <!-- Botones de acción -->
        <div class="flex justify-center gap-4 mt-6">
          <button id="hit" class="bg-red-600 text-white py-2 px-4 rounded-lg hover:bg-red-700 transition-colors duration-300">
            Pedir
          </button>
          <button id="stay" class="bg-yellow-600 text-white py-2 px-2 rounded-lg hover:bg-yellow-700 transition-colors duration-300">
            Quedarse
          </button>
        </div>
        <!-- Resultados parciales -->
        <p id="results" class="text-center mt-4 text-lg font-semibold text-red-400"></p>
      </div>
      <!-- Resultado Final -->
      <div id="resultado-final" class="max-w-4xl mx-auto bg-green-500 p-6 rounded-lg shadow-lg text-white text-center hidden animate-fade-in">
        <h2 class="text-2xl font-bold mb-4">Resultado Final</h2>
        <p id="resultado-mensaje" class="text-xl font-semibold text-yellow-300 mb-4"></p>
        <p class="text-lg">
          Suma Crupier: <span id="dealer-sum-final" class="font-semibold text-yellow-300"></span>
        </p>
        <p class="text-lg">
          Suma Jugador: <span id="your-sum-final" class="font-semibold text-yellow-300"></span>
        </p>
        <p id="manos-restantes-final" class="text-lg mt-2"></p>
        <p id="banca-actual-final" class="text-lg mt-2 font-semibold"></p>
        <div class="flex flex-col sm:flex-row justify-center gap-4 mt-6">
          <button onclick="Juego.inicializar()" class="bg-red-600 text-white py-2 px-4 rounded-lg hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-400 transition-colors duration-300">
            Jugar de Nuevo
          </button>
          <a href="{{ route('welcome') }}" class="bg-yellow-500 text-white py-2 px-4 rounded-lg hover:bg-yellow-600 focus:outline-none focus:ring-2 focus:ring-yellow-300 transition-colors duration-300">
            Salir al Inicio
          </a>
        </div>
      </div>
      <!-- Modal de Apuesta -->
      <div id="modal-overlay" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center h-full w-full">
        <div id="apuesta-container" class="w-full max-w-lg bg-green-800 p-6 rounded-lg shadow-lg text-white text-center animate-fade-in">
          <h2 class="text-2xl font-bold mb-4">Apuesta</h2>
          <p class="text-lg">
            Tienes un total de <span id="banca" class="font-semibold text-yellow-300"></span> monedas
          </p>
          <input type="number" id="apuesta"
            class="h-10 bg-green-700 text-white rounded-lg mt-4 p-2 w-full focus:outline-none focus:ring-2 focus:ring-yellow-400"
            placeholder="Apuesta">
            <!--<label class="cursor-pointer">
              <input type="radio" name="ficha" class="hidden">
              <img src="{{ asset('juego/cards/BACK.png') }}" :class="{ 'ring-4 ring-yellow-400': selected === 'as' }" class="w-24 h-36 rounded-lg hover:scale-105 transition-all">
            </label>-->
          <div class="flex flex-col sm:flex-row justify-center gap-4 mt-4">
            <button id="apostar"
              class="bg-yellow-500 text-white py-2 px-4 w-full sm:w-1/2 rounded-lg hover:bg-yellow-600 focus:outline-none focus:ring-2 focus:ring-yellow-400 transition-colors duration-300">
              Apostar
            </button>
            <button id="cobrar-salir"
              class="bg-red-500 text-white py-2 px-4 w-full sm:w-1/2 rounded-lg hover:bg-red-600 focus:outline-none focus:ring-2 focus:ring-red-400 transition-colors duration-300">
              Cobrar y Salir
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>
  <!-- Script principal del juego -->
  <script src="{{ asset('juego/main.js') }}"></script>
</body>
</html>
