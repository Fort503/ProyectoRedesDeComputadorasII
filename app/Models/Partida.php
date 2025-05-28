<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Partida extends Model
{
    protected $fillable = [
        'user_id',
        'puntos_jugador',
        'puntos_crupier',
        'resultado',
        'apuesta',
        'banca_final',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
