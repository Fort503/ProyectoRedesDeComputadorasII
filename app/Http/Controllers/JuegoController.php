<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\Partida;
use App\Models\User;

class JuegoController extends Controller
{
    public function index() 
    {
        $user = Auth::user();
        $partidasRestantes = 5 - $user->games_played;
        
        return view('juego', [
            'partidasRestantes' => $partidasRestantes
        ]);
    }

    public function guardarPartida(Request $request)
    {
        $request->validate([
            'partidas_jugadas' => 'required|integer|min:1',
            'ganadas' => 'required|integer|min:0',
            'perdidas' => 'required|integer|min:0',
            'empates' => 'required|integer|min:0',
            'banca_final' => 'required|integer',
            'apuestas_totales' => 'required|integer|min:0',
            'apuestas_ganadas' => 'required|integer|min:0',
            'apuestas_perdidas' => 'required|integer|min:0',
        ]);

        $user = Auth::user();
        
        // Verificar si puede jugar antes de guardar
        if ($user->games_played >= 5) {
            return response()->json([
                'success' => false,
                'message' => 'Has alcanzado el límite de partidas'
            ], 403);
        }

        $resultado_general = $this->calcularResultadoGeneral($request);

        // Crear la partida
        Partida::create([
            'user_id' => $user->id,
            'manos_jugadas' => $request->partidas_jugadas,
            'ganadas' => $request->ganadas,
            'perdidas' => $request->perdidas,
            'empates' => $request->empates,
            'banca_inicial' => 1000,  
            'banca_final' => $request->banca_final,
            'apuestas_totales' => $request->apuestas_totales,
            'apuestas_ganadas' => $request->apuestas_ganadas,
            'apuestas_perdidas' => $request->apuestas_perdidas,
            'resultado_general' => $resultado_general,
        ]);

        // Incrementar el contador
        User::where('id', $user->id)->increment('games_played');
        
        return response()->json([
            'success' => true,
            'partidas_restantes' => 5 - $user->games_played,
            'message' => 'Partida guardada correctamente'
        ]);
    }

    private function calcularResultadoGeneral($request)
    {
        if ($request->ganadas > $request->perdidas) return 'ganancia';
        if ($request->ganadas < $request->perdidas) return 'pérdida';
        return 'igual';
    }
}