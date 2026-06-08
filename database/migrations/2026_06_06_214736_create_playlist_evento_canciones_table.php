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
        Schema::create('playlist_evento_canciones', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('evento_id');
            $table->foreign('evento_id')->references('id')->on('eventos');

            $table->unsignedInteger('usuario_id');
            $table->foreign('usuario_id')->references('id')->on('usuarios');

            $table->foreignId('cancion_id')->constrained('canciones'); // canciones.id es BIGINT (usa $table->id())
            $table->unsignedInteger('orden'); //compra y reproduccion
            $table->decimal('precio_pagado', 8,2);
            $table->unique(['evento_id', 'usuario_id', 'cancion_id']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('playlist_evento_canciones');
    }
};
