<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PuntuacionModel extends Model
{
    use HasFactory;
    protected $fillable = ['usuario', 'puntuacion']; 
}
