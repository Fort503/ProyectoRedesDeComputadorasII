<x-guest-layout>
    <div class="min-h-screen bg-green-900 flex items-center justify-center p-4">
        <div class="bg-gray-800 bg-opacity-75 backdrop-blur-md rounded-2xl shadow-xl w-full max-w-md p-6">
            <div class="text-center mb-6">
                <h2 class="text-3xl font-extrabold text-white">Registro Blackjack</h2>
                <p class="text-green-300">¡Únete a la mesa y controla tu suerte!</p>
            </div>
            <form method="POST" action="{{ route('register') }}" class="space-y-4">
                @csrf

                <!-- Name -->
                <div>
                    <x-input-label for="name" :value="__('Name')" class="text-green-200"/>
                    <x-text-input id="name" class="block mt-1 w-full bg-gray-700 text-white placeholder-green-300 border border-green-600 focus:border-green-400 focus:ring-green-400 rounded-md" 
                                type="text" name="name" :value="old('name')" required autofocus autocomplete="name" placeholder="Ingresa tu nombre"/>
                    <x-input-error :messages="$errors->get('name')" class="mt-1 text-red-500"/>
                </div>

                <!-- Email Address -->
                <div>
                    <x-input-label for="email" :value="__('Email')" class="text-green-200"/>
                    <x-text-input id="email" class="block mt-1 w-full bg-gray-700 text-white placeholder-green-300 border border-green-600 focus:border-green-400 focus:ring-green-400 rounded-md" 
                                type="email" name="email" :value="old('email')" required autocomplete="username" placeholder="tu@correo.com"/>
                    <x-input-error :messages="$errors->get('email')" class="mt-1 text-red-500"/>
                </div>

                <!-- Password -->
                <div>
                    <x-input-label for="password" :value="__('Password')" class="text-green-200"/>
                    <x-text-input id="password" class="block mt-1 w-full bg-gray-700 text-white placeholder-green-300 border border-green-600 focus:border-green-400 focus:ring-green-400 rounded-md"
                                type="password" name="password" required autocomplete="new-password" placeholder="********"/>
                    <x-input-error :messages="$errors->get('password')" class="mt-1 text-red-500"/>
                </div>

                <!-- Confirm Password -->
                <div>
                    <x-input-label for="password_confirmation" :value="__('Confirm Password')" class="text-green-200"/>
                    <x-text-input id="password_confirmation" class="block mt-1 w-full bg-gray-700 text-white placeholder-green-300 border border-green-600 focus:border-green-400 focus:ring-green-400 rounded-md"
                                type="password" name="password_confirmation" required autocomplete="new-password" placeholder="********"/>
                    <x-input-error :messages="$errors->get('password_confirmation')" class="mt-1 text-red-500"/>
                </div>

                <div class="flex items-center justify-between mt-4">
                    <a class="text-sm text-white hover:bg-green-100" href="{{ route('login') }}">
                        {{ __('Ya tengo una cuenta') }}
                    </a>
                    <x-primary-button class="bg-green-600 hover:bg-green-500 focus:ring-green-400">
                        {{ __('Registrarse') }}
                    </x-primary-button>
                </div>
            </form>
        </div>
    </div>
</x-guest-layout>
