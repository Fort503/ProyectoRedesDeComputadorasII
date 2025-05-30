<nav x-data="{ open: false }" class="bg-green-800 p-4 shadow-lg transition-all duration-300 ease-in-out">
  <div class="container mx-auto flex items-center justify-between flex-wrap">

    {{-- Logo --}}
    <a href="{{ route('welcome') }}"
       class="text-2xl font-bold text-yellow-300 hover:text-yellow-400 transition-colors duration-300 ease-in-out">
      Blackjack
    </a>

    {{-- Botón hamburguesa (móvil) --}}
    <button @click="open = !open"
        class="md:hidden inline-flex items-center p-2 text-yellow-300 hover:text-yellow-400 hover:bg-green-700 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-200 transition-all duration-200"
        :class="{ 'rotate-90': open }">
      <span class="sr-only">Abrir menú</span>
      <svg class="w-6 h-6 transform transition-transform duration-200"
          :class="{ 'rotate-90': open }"
          fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <!-- Icono hamburguesa -->
        <path x-show="!open" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
            d="M4 6h16M4 12h16M4 18h16" />
        <!-- Icono de cerrar -->
        <path x-show="open" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
            d="M6 18L18 6M6 6l12 12" />
      </svg>
    </button>

    {{-- Menú Desktop --}}
    <div class="hidden md:flex items-center space-x-4">
      @auth
        <span class="text-white font-semibold transition-transform duration-200 transform hover:scale-105">
          Hola, {{ Auth::user()->name }}
        </span>
        <a href="{{ route('welcome') }}"
            class="bg-yellow-500 text-white px-4 py-2 rounded-lg hover:bg-yellow-600 transition-colors duration-200">
          Inicio
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
        <a href="{{ route('login') }}"
            class="bg-yellow-500 text-white px-4 py-2 rounded-lg hover:bg-yellow-600 transition-colors duration-200 transform hover:scale-105">
          Iniciar Sesión
        </a>
        <a href="{{ route('register') }}"
            class="bg-yellow-500 text-white px-4 py-2 rounded-lg hover:bg-yellow-600 transition-colors duration-200 transform hover:scale-105">
          Registrarse
        </a>
        <a href="{{ route('juego') }}"
            class="bg-yellow-500 text-white px-4 py-2 rounded-lg hover:bg-yellow-600 transition-colors duration-200 transform hover:scale-105">
          Jugar
        </a>
      @endguest
    </div>

    {{-- Menú Mobile --}}
    <div
      x-show="open"
      x-transition:enter="transition ease-out duration-300"
      x-transition:enter-start="opacity-0 -translate-y-4"
      x-transition:enter-end="opacity-100 translate-y-0"
      x-transition:leave="transition ease-in duration-200"
      x-transition:leave-start="opacity-100 translate-y-0"
      x-transition:leave-end="opacity-0 -translate-y-4"
      class="md:hidden w-full mt-3 bg-green-700 rounded-lg shadow-inner overflow-hidden"
    >
      <div class="flex flex-col divide-y divide-green-600">
        @auth
          <span class="block text-left px-4 py-3 text-yellow-300 hover:bg-green-600 transition-colors duration-150 transform hover:scale-105">
            Hola, {{ Auth::user()->name }}
          </span>
          <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit"
                class="block w-full text-left px-4 py-3 text-yellow-300 hover:bg-green-600 transition-colors duration-150 transform hover:scale-105">
              Cerrar Sesión
            </button>
          </form>
          <a href="{{ route('juego') }}"
              class="block text-left px-4 py-3 text-yellow-300 hover:bg-green-600 transition-colors duration-150 transform hover:scale-105">
            Jugar
          </a>
        @endauth

        @guest
          <a href="{{ route('login') }}"
              class="block text-left px-4 py-3 text-yellow-300 hover:bg-green-600 transition-colors duration-150 transform hover:scale-105">
            Iniciar Sesión
          </a>
          <a href="{{ route('register') }}"
              class="block text-left px-4 py-3 text-yellow-300 hover:bg-green-600 transition-colors duration-150 transform hover:scale-105">
            Registrarse
          </a>
          <a href="{{ route('juego') }}"
              class="block text-left px-4 py-3 text-yellow-300 hover:bg-green-600 transition-colors duration-150 transform hover:scale-105">
            Jugar
          </a>
        @endguest
      </div>
    </div>

  </div>
</nav>
