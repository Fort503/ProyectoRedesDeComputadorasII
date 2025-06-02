<?php

namespace App\Http\Controllers;
use App\Models\Partida;
use Illuminate\Http\Request;

class PartidaController extends Controller
{
    public function mejoresPartidas()
    {
        $partidas = Partida::with('user')
            ->orderByDesc('banca_final')
            ->take(10)
            ->get();

        return view('partidas.mejores', compact('partidas'));
    }
}
