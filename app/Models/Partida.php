<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Partida extends Model
{
        protected $fillable = [
        'user_id',
        'manos_jugadas',
        'ganadas',
        'perdidas',
        'empates',
        'banca_inicial',
        'banca_final',
        'apuestas_totales',
        'apuestas_ganadas',
        'apuestas_perdidas',
        'resultado_general',
    ];


    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
