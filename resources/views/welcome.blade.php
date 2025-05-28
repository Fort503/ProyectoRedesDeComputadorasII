<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Blackjack</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-green-950 text-white font-sans">
    <nav class="bg-green-800 p-4 shadow-lg">
        <div class="container mx-auto flex justify-between items-center">
            <a href="#" class="text-2xl font-bold text-yellow-300">Blackjack prueba de commit</a>
            <div class="space-x-4">
                <a href="{{ route('login') }}" class="text-white hover:text-yellow-300">Iniciar Sesión</a>
                <a href="{{ route('register') }}" class="text-white hover:text-yellow-300">Registrarse</a>
                <a href="{{ route('juego') }}" class="bg-yellow-500 px-4 py-2 rounded-lg hover:bg-yellow-600">Jugar</a>
            </div>
        </div>
    </nav>

    <div class="container mx-auto p-8 text-center">
        <h1 class="text-4xl font-bold text-yellow-300 mb-6">Bienvenido al Blackjack</h1>
        <p class="text-lg mb-6">Aprende las reglas y prepárate para jugar.</p>
        
        <div class="bg-green-800 p-6 rounded-lg shadow-lg max-w-3xl mx-auto">
            <h2 class="text-2xl font-semibold text-yellow-300 mb-4">Reglas del Blackjack</h2>
            <ul class="text-left space-y-3">
                <li>&#8226; El objetivo es alcanzar una suma de 21 o acercarse sin pasarse.</li>
                <li>&#8226; Las cartas del 2 al 10 tienen su valor nominal.</li>
                <li>&#8226; Las cartas J, Q y K valen 10 puntos.</li>
                <li>&#8226; El As puede valer 1 u 11 puntos según convenga.</li>
                <li>&#8226; Puedes "Pedir" para recibir otra carta o "Quedarte" para mantener tu total.</li>
                <li>&#8226; El crupier debe pedir hasta alcanzar 17 o más.</li>
                <li>&#8226; Si superas 21, pierdes automáticamente.</li>
                <li>&#8226; Si tu suma es mayor que la del crupier sin pasarte de 21, ganas.</li>
            </ul>
        </div>
    </div>
</body>
</html>
