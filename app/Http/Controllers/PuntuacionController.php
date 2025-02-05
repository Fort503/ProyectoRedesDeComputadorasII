<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PuntuacionModel;

class PuntuacionController extends Controller
{
    public function store(Request $request) {
        $request->validate([
            'usuario' => 'required|string|max:255',
            'puntuacion' => 'required|integer|min:0',
        ]);

        Puntuacion::create([
            'usuario' => $request->usuario,
            'puntuacion' => $request->puntuacion,
        ]);

        return response()->json(['message' => 'Puntuación guardada correctamente']);
    }
}
