<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('valoraciones_trabajo', function (Blueprint $table) {
            $table->unsignedInteger('id')->autoIncrement();
            $table->unsignedInteger('candidatura_id');
            $table->tinyInteger('direccion');
            $table->unsignedTinyInteger('puntuacion');
            $table->text('comentario')->nullable();
            $table->tinyInteger('estado')->default(1);
            $table->dateTime('fecha_creacion');
            $table->dateTime('fecha_actualizacion')->nullable();

            $table->foreign('candidatura_id', 'fk_val_trabajo_candidatura')
                  ->references('id')->on('candidaturas_trabajo');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('valoraciones_trabajo');
    }
};
