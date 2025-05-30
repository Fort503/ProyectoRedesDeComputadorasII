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
  <style type="text/tailwindcss">
        @layer utilities {
            .animate-fade-in {
            animation: fadeIn 1s ease-out forwards;
            }
            @keyframes fadeIn {
            0% {
                opacity: 0;
                transform: translateY(10px);
            }
            100% {
                opacity: 1;
                transform: translateY(0);
            }
            }
        }
    </style>
</head>
<body class="bg-green-950 text-gray-800 font-sans">

  <!-- Navbar -->
  <nav class="bg-green-800 p-4 shadow-lg">
    <div class="container mx-auto flex flex-col md:flex-row justify-between items-center">
      <a href="{{ route('welcome') }}" class="text-2xl font-bold text-yellow-300 mb-2 md:mb-0">
        Blackjack
      </a>
      <div class="flex flex-col sm:flex-row items-center space-y-2 sm:space-y-0 sm:space-x-6">
        <span id="usuario" class="text-white font-semibold">{{ Auth::user()->name }}</span>
        <a href="{{ route('welcome') }}" class="text-white hover:text-yellow-300 transition-colors duration-300">
          Inicio
        </a>
        <form method="POST" action="{{ route('logout') }}">
          @csrf
          <button type="submit" class="bg-yellow-500 px-4 py-2 rounded-lg hover:bg-yellow-600 transition-colors duration-300">
            Cerrar Sesión
          </button>
        </form>
      </div>
    </div>
  </nav>

  <div class="container mx-auto p-4">
    <h1 class="text-3xl text-white font-semibold text-center mb-6 p-2">
      Blackjack
    </h1>
    <!-- Seccion del juego -->
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
      <div id="your-cards" class="flex flex-wrap gap-x-2 justify-center">
      </div>
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

  <script src="{{ asset('juego/main.js') }}"></script>
</body>
</html>

