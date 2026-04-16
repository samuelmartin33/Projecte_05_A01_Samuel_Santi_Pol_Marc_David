<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('eventos_imagenes', function (Blueprint $table) {
            $table->unsignedInteger('id')->autoIncrement();
            $table->unsignedInteger('evento_id');
            $table->string('imagen_url', 500);
            $table->string('descripcion', 255)->nullable();
            $table->tinyInteger('es_portada')->default(0);
            $table->tinyInteger('estado')->default(1);
            $table->dateTime('fecha_creacion');
            $table->dateTime('fecha_actualizacion')->nullable();

            $table->foreign('evento_id', 'fk_eventos_img_evento')
                  ->references('id')->on('eventos');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('eventos_imagenes');
    }
};
