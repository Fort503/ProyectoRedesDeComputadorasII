<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\JuegoController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PartidaController;

Route::get('/', function () {
    return view('welcome');
})->name('welcome');

require __DIR__.'/auth.php';

    Route::middleware('auth')->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::post('/guardar-puntuacion', [JuegoController::class, 'guardarPuntuacion'])->middleware('auth')->name('guardar.puntuacion');
    Route::get('/play', [JuegoController::class, 'index'])->name('juego');
    Route::post('/guardar-partida', [JuegoController::class, 'guardarPartida'])->middleware('auth');
    Route::get('/partidas', [PartidaController::class, 'mejoresPartidas'])->name('partidas');
    Route::get('/mi-puntuacion', [ProfileController::class, 'miPuntuacion'])->name('mi.puntuacion');
});
