<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('partidas', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->default(1);
            $table->integer('manos_jugadas');
            $table->integer('ganadas');
            $table->integer('perdidas');
            $table->integer('empates');
            $table->integer('banca_inicial');
            $table->integer('banca_final');
            $table->integer('apuestas_totales');
            $table->string('resultado_general');
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('new_partidas');
    }
};
