<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('eventos_favoritos', function (Blueprint $table) {
            $table->unsignedInteger('id')->autoIncrement();
            $table->unsignedInteger('usuario_id');
            $table->unsignedInteger('evento_id');
            $table->tinyInteger('estado')->default(1);
            $table->dateTime('fecha_creacion');
            $table->dateTime('fecha_actualizacion')->nullable();

            $table->foreign('usuario_id', 'fk_eventos_fav_usuario')
                  ->references('id')->on('usuarios');
            $table->foreign('evento_id', 'fk_eventos_fav_evento')
                  ->references('id')->on('eventos');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('eventos_favoritos');
    }
};
