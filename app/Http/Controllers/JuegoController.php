<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Partida;

class JuegoController extends Controller
{
    public function index() {
        return view ('juego');
    }

    public function guardarPartida(Request $request)
    {
        $request->validate([
            'puntos_jugador' => 'required|integer',
            'puntos_crupier' => 'required|integer',
            'resultado' => 'required|in:ganado,perdido,empate',
            'apuesta' => 'required|integer',
            'banca_final' => 'required|integer',
        ]);

        $partida = new Partida();
        $partida->user_id = 1;
        $partida->puntos_jugador = $request->puntos_jugador;
        $partida->puntos_crupier = $request->puntos_crupier;
        $partida->resultado = $request->resultado;
        $partida->apuesta = $request->apuesta;
        $partida->banca_final = $request->banca_final;
        $partida->save();

        return response()->json(['success' => true]);
    }
}


