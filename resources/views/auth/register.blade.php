<x-guest-layout>
    <div class="min-h-screen bg-green-900 flex items-center justify-center p-4">
        <!-- Agrega animate-fade-in aquí -->
        <div class="bg-gray-800 bg-opacity-75 backdrop-blur-md rounded-2xl shadow-xl w-full max-w-md p-6 animate-fade-in">
            <div class="text-center mb-6">
                <a href="{{ route('welcome') }}" class="text-4xl font-bold text-yellow-300 hover:text-yellow-400 transition-colors duration-300 ease-in-out">
                    Blackjack
                </a>
                <h2 class="text-2xl font-extrabold text-white">Registro Blackjack</h2>
                <p class="text-green-300">¡Únete a la mesa y controla tu suerte!</p>
            </div>
            <form method="POST" action="{{ route('register') }}" class="space-y-4">
                @csrf

                <!-- Name -->
                <div>
                    <x-input-label for="name" :value="__('Nombre')" class="text-green-200"/>
                    <x-text-input id="name" class="block mt-1 w-full bg-gray-700 text-white placeholder-green-300 border border-green-600 focus:border-green-400 focus:ring-green-400 rounded-md" 
                                type="text" name="name" :value="old('name')" required autofocus autocomplete="name" placeholder="Ingresa tu nombre"/>
                    <x-input-error :messages="$errors->get('name')" class="mt-1 text-red-500"/>
                </div>
                <!-- Email Address -->
                <div>
                    <x-input-label for="email" :value="__('Correo')" class="text-green-200"/>
                    <x-text-input id="email" class="block mt-1 w-full bg-gray-700 text-white placeholder-green-300 border border-green-600 focus:border-green-400 focus:ring-green-400 rounded-md" 
                                type="email" name="email" :value="old('email')" required autocomplete="username" placeholder="tu@correo.com"/>
                    <x-input-error :messages="$errors->get('email')" class="mt-1 text-red-500"/>
                </div>
                <!-- Password -->
                <div>
                    <x-input-label for="password" :value="__('Contraseña')" class="text-green-200"/>
                    <x-text-input id="password" class="block mt-1 w-full bg-gray-700 text-white placeholder-green-300 border border-green-600 focus:border-green-400 focus:ring-green-400 rounded-md"
                                type="password" name="password" required autocomplete="new-password" placeholder="********"/>
                    <x-input-error :messages="$errors->get('password')" class="mt-1 text-red-500"/>
                </div>
                <!-- Confirm Password -->
                <div>
                    <x-input-label for="password_confirmation" :value="__('Confirmar Contraseña')" class="text-green-200"/>
                    <x-text-input id="password_confirmation" class="block mt-1 w-full bg-gray-700 text-white placeholder-green-300 border border-green-600 focus:border-green-400 focus:ring-green-400 rounded-md"
                                type="password" name="password_confirmation" required autocomplete="new-password" placeholder="********"/>
                    <x-input-error :messages="$errors->get('password_confirmation')" class="mt-1 text-red-500"/>
                </div>

                <div class="font-sans flex flex-col md:flex-row items-center justify-end mt-4 space-y-2 md:space-y-0 md:space-x-4">
                    <a class="text-white w-full md:w-auto bg-green-600 hover:bg-yellow-500 px-4 py-2 rounded-md shadow-lg transition-all hover:scale-105 text-sm md:text-base text-center" href="{{ route('login') }}">
                        {{ __('Ya tengo una cuenta') }}
                    </a>
                    <span class="text-white">o</span>
                    <x-primary-button class="text-white font-sans w-full md:w-auto bg-green-600 hover:bg-yellow-500 px-4 py-2 rounded-md shadow-lg transition-all hover:scale-105 text-sm md:text-base">
                        {{ __('Registrarse') }}
                    </x-primary-button>
                </div>
            </form>
        </div>
    </div>
</x-guest-layout>
