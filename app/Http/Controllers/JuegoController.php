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
            'partidas_jugadas' => 'required|integer|min:1',
            'ganadas' => 'required|integer|min:0',
            'perdidas' => 'required|integer|min:0',
            'empates' => 'required|integer|min:0',
            'banca_final' => 'required|integer',
            'apuestas_totales' => 'required|integer|min:0',
            'apuestas_ganadas' => 'required|integer|min:0',
            'apuestas_perdidas' => 'required|integer|min:0',
        ]);

        $resultado_general = $this->calcularResultadoGeneral($request);


        Partida::create([
            'user_id' => auth()->id() ?? 1,
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

        return response()->json(['success' => true]);
    }

   private function calcularResultadoGeneral($request)
    {
        if ($request->ganadas > $request->perdidas) return 'ganancia';
        if ($request->ganadas < $request->perdidas) return 'pérdida';
        return 'igual';
    }


}


