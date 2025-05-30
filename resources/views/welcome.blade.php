<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Blackjack</title>
    <script src="https://cdn.tailwindcss.com"></script>

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
<body class="bg-green-950 text-white font-sans animate-fade-in">
    <nav class="bg-green-800 p-4 shadow-lg transition-all duration-300 ease-in-out">
        <div class="container mx-auto flex flex-col md:flex-row justify-between items-center">
        <a href="#" class="text-2xl font-bold text-yellow-300 hover:text-yellow-400 transition duration-300 ease-in-out mb-2 md:mb-0">
            Blackjack
        </a>
        <div class="space-x-4">
            @auth
            <span class="text-yellow-300 font-semibold transform hover:scale-105 transition-all duration-300">
                Hola, {{ Auth::user()->name }}
            </span>

            <form action="{{ route('logout') }}" method="POST" class="inline">
                @csrf
                <button type="submit" class="bg-red-500 px-4 py-2 rounded-lg hover:bg-red-600 transition-all duration-300">
                Cerrar Sesión
                </button>
            </form>

            <a href="{{ route('juego') }}" class="bg-yellow-500 px-4 py-2 rounded-lg hover:bg-yellow-600 transition-all duration-300">
                Jugar
            </a>
            @endauth

            @guest
            <a href="{{ route('login') }}" class="text-white hover:text-yellow-300 transition-all duration-300 transform hover:scale-105">
                Iniciar Sesión
            </a>
            <a href="{{ route('register') }}" class="text-white hover:text-yellow-300 transition-all duration-300 transform hover:scale-105">
                Registrarse
            </a>
            <a href="{{ route('juego') }}" class="bg-yellow-500 px-4 py-2 rounded-lg hover:bg-yellow-600 transition-all duration-300">
                Jugar
            </a>
            @endguest
        </div>
        </div>
    </nav>

    <div class="container mx-auto p-8 text-center animate-fade-in">
        <h1 class="text-4xl font-bold text-yellow-300 mb-6 transition-transform transform hover:scale-105 duration-300">
        Bienvenido al Blackjack
        </h1>
        <p class="text-lg mb-6">Aprende las reglas y prepárate para jugar.</p>
        
        <div class="bg-green-800 p-6 rounded-lg shadow-lg max-w-3xl mx-auto transition-shadow hover:shadow-2xl duration-300">
        <h2 class="text-2xl font-semibold text-yellow-300 mb-4">Reglas del Blackjack</h2>
        <ul class="text-left space-y-3 list-disc pl-5">
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
</body>
</html>
