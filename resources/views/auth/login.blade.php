<x-guest-layout>
    <div class="min-h-screen flex items-center justify-center bg-green-900 p-6">
        <!-- Contenedor del formulario -->
        <div class="bg-gray-800 bg-opacity-75 backdrop-blur-md rounded-2xl shadow-xl w-full max-w-md p-8 animate-fade-in">
            <div class="text-center mb-6">
                <h2 class="text-3xl font-extrabold text-yellow-300">Iniciar Sesión</h2>
                <p class="text-green-300">Ingresa y comienza la partida</p>
            </div>

            <!-- Sesión Status -->
            <x-auth-session-status class="mb-4 text-yellow-400 text-center" :status="session('status')" />

            <form method="POST" action="{{ route('login') }}" class="space-y-4">
                @csrf

                <!-- Email -->
                <div>
                    <x-input-label for="email" :value="__('Email')" class="text-green-200"/>
                    <x-text-input id="email" class="block mt-1 w-full bg-gray-700 text-white placeholder-green-300 border border-green-600 focus:border-yellow-300 focus:ring-yellow-300 rounded-md" 
                                type="email" name="email" :value="old('email')" required autofocus autocomplete="username" placeholder="tu@correo.com"/>
                    <x-input-error :messages="$errors->get('email')" class="mt-1 text-red-500"/>
                </div>

                <!-- Password -->
                <div>
                    <x-input-label for="password" :value="__('Password')" class="text-green-200"/>
                    <x-text-input id="password" class="block mt-1 w-full bg-gray-700 text-white placeholder-green-300 border border-green-600 focus:border-yellow-300 focus:ring-yellow-300 rounded-md"
                                type="password" name="password" required autocomplete="current-password" placeholder="********"/>
                    <x-input-error :messages="$errors->get('password')" class="mt-1 text-red-500"/>
                </div>

                <!-- Remember Me -->
                <div class="flex items-center mt-4">
                    <label for="remember_me" class="inline-flex items-center">
                        <input id="remember_me" type="checkbox" class="rounded border-green-600 text-yellow-300 shadow-sm focus:ring-yellow-300" name="remember">
                        <span class="ms-2 text-sm text-green-300">{{ __('Recordarme') }}</span>
                    </label>
                </div>

                <div class="flex items-center justify-between mt-4">
                    @if (Route::has('password.request'))
                        <a class="text-sm text-white hover:underline pr-4" href="{{ route('password.request') }}">
                            {{ __('¿Olvidaste tu contraseña?') }}
                        </a>
                    @endif

                    <x-primary-button class="bg-green-600 hover:bg-yellow-500 text-gray-900 font-bold px-4 py-2 rounded-md shadow-lg transition-all hover:scale-105">
                        {{ __('Iniciar Sesión') }}
                    </x-primary-button>
                </div>
            </form>
        </div>
    </div>
</x-guest-layout>
