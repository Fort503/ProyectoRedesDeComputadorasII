<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Top 10 Mejores Partidas</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 text-gray-900">
    <div class="container mx-auto px-4 py-8">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-3xl font-bold text-center w-full">Top 10 Mejores Partidas</h1>
            <a href="{{ route('welcome') }}" class="absolute left-4 bg-indigo-700 hover:bg-indigo-600 text-white font-semibold py-2 px-4 rounded">
                Inicio
            </a>
        </div>

        <div class="overflow-x-auto">
            <table class="table-auto w-full border border-gray-300 bg-white shadow-md rounded-lg">
                <thead>
                    <tr class="bg-gray-200 text-gray-700">
                        <th class="px-4 py-2 border">Jugador</th>
                        <th class="px-4 py-2 border">Banca Final</th>
                        <th class="px-4 py-2 border">Manos Jugadas</th>
                        <th class="px-4 py-2 border">Ganadas</th>
                        <th class="px-4 py-2 border">Perdidas</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($partidas as $partida)
                        <tr class="text-center border-t hover:bg-gray-100">
                            <td class="px-4 py-2 border">{{ $partida->user->name }}</td>
                            <td class="px-4 py-2 border">{{ $partida->banca_final }}</td>
                            <td class="px-4 py-2 border">{{ $partida->manos_jugadas }}</td>
                            <td class="px-4 py-2 border">{{ $partida->ganadas }}</td>
                            <td class="px-4 py-2 border">{{ $partida->perdidas }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>
