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
    var chipsUrl = "{{ asset('juego/chips') }}/";
  </script>
  <style>
  body.game-bg {
    background-image: url('{{ asset("juego/tables/green-carpet.png") }}');
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

  /* Flip a doble cara */
  @keyframes flip {
    0%   { transform: rotateY(0); }
    50%  { transform: rotateY(90deg); }
    100% { transform: rotateY(0); }
  }
  .flip-anim {
    animation: flip 1s ease-in-out forwards;
    transform-style: preserve-3d;
  }
  </style>
  @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="game-bg bg-green-950 text-gray-800 font-sans relative">
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
            <a href="{{ route('partidas') }}"
                class="text-white bg-yellow-500 px-4 py-2 rounded-lg hover:text-yellow-300 transition-colors duration-200">
              Ver puntuaciones generales
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
                class="text-left px-4 py-3 text-yellow-300 hover:bg-green-600 transition-colors duration-150 transform hover:scale-102">
              Hola, {{ Auth::user()->name }}
            </a>
            <a href="{{ route('partidas') }}"
                class="text-left px-4 py-3 text-yellow-300 hover:bg-green-600 transition-colors duration-150 transform hover:scale-102">
              Inicio
            </a><a href="{{ route('welcome') }}"
                class="text-left px-4 py-3 text-yellow-300 hover:bg-green-600 transition-colors duration-150 transform hover:scale-102">
              Ver puntuaciones generales
            </a>
            <form method="POST" action="{{ route('logout') }}">
              @csrf
              <button type="submit"
                  class="w-full text-left px-4 py-3 text-yellow-300 hover:bg-green-600 transition-colors duration-150 transform hover:scale-102">
                Cerrar Sesión
              </button>
            </form>
          @endauth
        </div>
      </div>
    </nav>
      <section class="hero-texts container mx-auto mt-6">
        <div class="flex flex-col items-center md:flex-row md:items-center md:justify-center gap-6">
          <!-- Imagen de cabecera -->
          <div class="flex justify-center w-full">
            <img
              src="{{ asset('juego/texts/USO BlackJack.png') }}"
              alt="USO BlackJack"
              class="mx-auto w-11/12 sm:w-3/4 md:w-2/5 lg:w-1/3"
            />
          </div>
        </div>
        <div class="w-full md:w-auto flex justify-center md:justify-end">
          <div class="bg-black bg-opacity-60 backdrop-blur-sm p-4 rounded-lg shadow-lg border-2 border-yellow-400 text-center w-40">
            <span class="text-yellow-300 font-bold uppercase tracking-wider text-sm">Partidas restantes</span>
            <div class="flex justify-center gap-1 my-2">
              @for($i = 1; $i <= 5; $i++)
                <div
                  class="w-4 h-4 rounded-full transition-colors duration-200
                    {{ $i <= auth()->user()->games_played
                        ? 'bg-red-500 shadow-xl shadow-red-500/50'
                        : 'bg-gray-600' }}"></div>
              @endfor
            </div>
            <span class="text-white font-semibold text-base">
              {{ auth()->user()->games_played }}/5
            </span>
          </div>
        </div>
      </section>

      <!-- Sección del juego -->
      <div id="game-container" class="max-w-4xl mx-auto p-6 rounded-lg text-white animate-fade-in flex flex-col items-center">
        <!-- Sección del Crupier -->
        <div class="text-center">
          <h2 class="text-xl font-bold">
            Repartidor: <span id="dealer-sum" class="font-semibold text-yellow-300"></span>
          </h2>
          <div id="dealer-cards" class="flex flex-wrap gap-x-2 justify-center">
            <img src="{{ asset('juego/cards/BACK.png') }}" alt="Carta Oculta" id="hidden" class="w-16 h-24 rounded-lg transition-transform duration-300 hover:scale-105">
          </div>
        </div>
        <div class="mb-4 w-full flex justify-center">
          <img
            src="{{ asset('juego/texts/El repartidor debe plantarse en .png') }}"
            alt="Instrucciones del repartidor"
            class="mx-auto
                  w-full                     /* 100% ancho del padre en móvil */
                  max-w-md sm:max-w-lg       /* 28rem en móvil, 32rem en ≥640px  */
                  md:max-w-xl                /* 36rem en ≥768px */
                  lg:max-w-2xl               /* 42rem en ≥1024px */
                  xl:max-w-4xl               /* 56rem en ≥1280px */
                  "
          />
        </div>
        <!-- Sección del Jugador -->
        <div class="text-center mt-2">
          <h2 class="text-xl font-bold">
            Jugador: <span id="your-sum" class="font-semibold text-yellow-300"></span>
          </h2>
          <h3 class="text-lg font-bold mb-2">Tu Banca: <span id="panel-banca" class="text-yellow-300"></span></h3>
          <div class="overflow-x-auto">
            <div id="your-cards" class="flex justify-center"></div>
          </div>
        </div>
        <!-- Botones de acción -->
        <div id="action-buttons" class="hidden">
          <button id="hit" class="bg-blue-600 text-white py-2 px-4 rounded-lg hover:bg-red-700 transition-colors duration-300">
            Pedir
          </button>
          <button id="btn-double-bet"
            class="bg-green-600 text-white py-2 px-4 rounded-lg hover:bg-red-700 transition-colors duration-300 disabled:opacity-50"
            disabled>
            Doblar
          </button>
          <button id="stay" class="bg-yellow-600 text-white py-2 px-2 rounded-lg hover:bg-yellow-700 transition-colors duration-300">
            Quedarse
          </button>
        </div>
        <!-- Resultados parciales -->
        <p id="results" class="text-center mt-4 text-lg font-semibold text-red-400"></p>
      </div>
      <!-- Resultado Final -->
      <div id="resultado-final" class="max-w-4xl mx-auto p-6 rounded-lg shadow-lg text-white text-center hidden animate-fade-in">
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
          <button id="btn-restart" class="bg-red-600 text-white py-2 px-4 rounded-lg hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-400 transition-colors duration-300">
            Jugar de Nuevo
          </button>
          <button id="btn-salir" class="bg-yellow-500 text-white py-2 px-4 rounded-lg hover:bg-yellow-600 focus:outline-none focus:ring-2 focus:ring-yellow-300 transition-colors duration-300">
            Salir al Inicio
          </button>
        </div>
        </div>
        
        <section id="bet-panel"
            class="fixed bottom-4 right-4 w-64 bg-green-800 rounded-lg shadow-lg p-4 text-white z-50">
          <h3 class="text-lg font-bold mb-2">Tu Banca: <span id="panel-banca-2" class="text-yellow-300"></span></h3>
          <div id="chips-available" class="grid grid-cols-3 gap-2 mb-4">
            <!-- Aquí JS las fichas -->
          </div>

          <h4 class="font-semibold mb-1">Apuesta:</h4>
          <div id="chips-bet" class="min-h-[3rem] bg-green-700 rounded p-2 flex flex-wrap gap-1 mb-4">
            <!-- Fichas arrastradas o clicadas aquí -->
            <span id="bet-empty" class="text-green-300 italic">Sin apuesta</span>
          </div>

          <div class="flex flex-col gap-2">
            <button id="btn-confirm-bet"
                    class="bg-yellow-500 text-gray-900 py-1 rounded hover:bg-yellow-600 disabled:opacity-50"
                    disabled>Confirmar</button>
          </div>
        </section>
      </div>
      <!-- Game Over Panel -->
      <div id="game-over-panel" class="hidden fixed inset-0 items-center justify-center z-50 bg-black bg-opacity-75 p-12">
          <div class="flex flex-col items-center justify-center min-h-full w-full  bg-green-900 bg-opacity-95 rounded-xl shadow-2xl border-4 border-green-500 relative max-w-lg mx-auto p-6">

          <div class="text-center text-white">
            <h2 class="text-3xl sm:text-4xl font-extrabold mb-4">
              ¡GAME OVER!
            </h2>
            <p id="game-over-message" class="text-lg mb-6"></p>

            <div class="bg-green-800 bg-opacity-80 p-4 rounded-lg mb-6">
              <p class="font-semibold text-green-300 text-xl mb-3">Estadísticas finales</p>
              <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-left text-white">
                <div>
                  <p>Partidas ganadas: <span id="stats-wins" class="font-bold text-yellow-300">0</span></p>
                  <p>Partidas perdidas: <span id="stats-losses" class="font-bold text-red-400">0</span></p>
                </div>
                <div>
                  <p>Empates: <span id="stats-ties" class="font-bold text-yellow-300">0</span></p>
                  <p>Banca final: <span id="stats-bank" class="text-2xl font-bold text-yellow-400">$0</span></p>
                </div>
              </div>
            </div>

            <div class="flex flex-col sm:flex-row gap-4">
              <button id="btn-game-over-restart"
                      class="w-full sm:w-auto flex-1 bg-green-500 hover:bg-green-600 text-gray-900 font-bold py-2 px-6 rounded-lg shadow-md transition">
                Jugar otra vez
              </button>
              <button id="btn-game-over-exit"
                      class="w-full sm:w-auto flex-1 bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-6 rounded-lg shadow-md transition">
                Salir
              </button>
            </div>
          </div>
        </div>
      </div>

  <script src="{{ asset('juego/main.js') }}"></script>
</body>
</html>