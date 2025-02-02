<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\JuegoController;

Route::get('/', [JuegoController::class, 'index']);
