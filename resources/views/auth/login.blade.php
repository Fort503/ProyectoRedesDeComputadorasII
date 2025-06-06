<x-guest-layout>
    <div class="min-h-screen flex items-center justify-center p-6">
        <!-- Contenedor del formulario -->
        <div class="bg-gray-800/70 border-2 border-green-600 backdrop-blur-sm rounded-2xl shadow-xl w-full max-w-md p-8 animate-fade-in">
            <div class="text-center mb-6">
                <a href="{{ route('welcome') }}" class="text-4xl font-bold text-yellow-300 hover:text-yellow-400 transition-colors duration-300 ease-in-out">
                    Blackjack
                </a>
                <h2 class="text-2xl font-extrabold text-white">Iniciar Sesión</h2>
                <p class="text-green-300">Ingresa y comienza la partida</p>
            </div>

            <!-- Sesión Status -->
            <x-auth-session-status class="mb-4 text-yellow-400 text-center" :status="session('status')" />

                <form method="POST" action="{{ route('login') }}" autocomplete="off" class="space-y-4">
                    @csrf

                    <!-- Email -->
                    <div>
                    <x-input-label for="email" :value="__('Correo')" class="text-green-200" />
                    <x-text-input
                        id="email"
                        type="email"
                        name="email"
                        value="{{ request()->cookie('remembered_email') ?? '' }}"
                        required
                        autofocus
                        autocomplete="off"
                        placeholder="tu@correo.com"
                        class="block mt-1 w-full bg-gray-700 text-white placeholder-green-300 border border-green-600 
                            focus:border-yellow-300 focus:ring-yellow-300 rounded-md"
                    />
                    <x-input-error :messages="$errors->get('email')" class="mt-1 text-red-500" />
                    </div>

                    <!-- Password -->
                    <div>
                    <x-input-label for="password" :value="__('Contraseña')" class="text-green-200" />
                    <x-text-input
                        id="password"
                        type="password"
                        name="password"
                        required
                        autocomplete="new-password"
                        placeholder="********"
                        class="block mt-1 w-full bg-gray-700 text-white placeholder-green-300 border border-green-600 
                            focus:border-yellow-300 focus:ring-yellow-300 rounded-md"
                    />
                    <x-input-error :messages="$errors->get('password')" class="mt-1 text-red-500" />
                    </div>

                    <!-- Remember Me -->
                    <div class="flex items-center mt-4">
                    <input
                        id="remember_me"
                        type="checkbox"
                        name="remember"
                        class="rounded border-green-600 text-yellow-300 focus:ring-yellow-300"
                        {{ request()->cookie('remember_me') ? 'checked' : '' }}
                    />
                    <label for="remember_me" class="ml-2 text-sm text-green-300">
                        {{ __('Recordarme') }}
                    </label>
                    </div>

                    <div class="font-sans flex flex-col md:flex-row items-center justify-end mt-4 space-y-2 md:space-y-0 md:space-x-4">
                        
                        <x-primary-button class=" text-white font-sans w-full md:w-auto bg-green-600 hover:bg-yellow-500 px-4 py-2 
                                                rounded-md shadow-lg transition-all hover:scale-105 text-sm md:text-base">
                            {{ __('Iniciar sesión') }}
                        </x-primary-button>
                        <span class="text-white">o</span>
                        <a href="{{ route('register') }}"
                            class="text-white w-full md:w-auto bg-green-600 hover:bg-yellow-500  px-4 py-2 
                                    rounded-md shadow-lg transition-all hover:scale-105 text-sm md:text-base text-center">
                            Crear cuenta
                        </a>
                    </div>
                </form>
        </div>
    </div>
</x-guest-layout>
