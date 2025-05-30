<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
   public function up(): void
    {
        Schema::table('partidas', function (Blueprint $table) {
            $table->dropColumn([
                'puntos_jugador',
                'puntos_crupier',
                'resultado',
                'apuesta'
            ]);
        });
    }
    
    public function down(): void
    {
        Schema::table('partidas', function (Blueprint $table) {
            $table->integer('puntos_jugador')->nullable();
            $table->integer('puntos_crupier')->nullable();
            $table->string('resultado')->nullable();
            $table->integer('apuesta')->nullable();
        });
    }
};
