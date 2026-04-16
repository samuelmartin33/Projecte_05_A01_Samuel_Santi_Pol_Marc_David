<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('eventos', function (Blueprint $table) {
            $table->unsignedInteger('id')->autoIncrement();
            $table->unsignedInteger('organizador_id');
            $table->unsignedInteger('categoria_evento_id');
            $table->tinyInteger('tipo_evento')->default(1);
            $table->string('titulo', 300);
            $table->text('descripcion')->nullable();
            $table->dateTime('fecha_inicio');
            $table->dateTime('fecha_fin')->nullable();
            $table->string('ubicacion_nombre', 300)->nullable();
            $table->string('ubicacion_direccion', 500)->nullable();
            $table->decimal('latitud', 10, 7)->nullable();
            $table->decimal('longitud', 10, 7)->nullable();
            $table->decimal('precio_base', 10, 2)->default(0.00);
            $table->unsignedInteger('aforo_maximo')->nullable();
            $table->unsignedInteger('aforo_actual')->default(0);
            $table->unsignedTinyInteger('edad_minima')->nullable();
            $table->tinyInteger('es_gratuito')->default(0);
            $table->string('url_externa', 500)->nullable();
            $table->unsignedInteger('contador_comparticiones')->default(0);
            $table->tinyInteger('estado')->default(1);
            $table->dateTime('fecha_creacion');
            $table->dateTime('fecha_actualizacion')->nullable();

            $table->foreign('organizador_id', 'fk_eventos_organizador')
                  ->references('id')->on('organizadores');
            $table->foreign('categoria_evento_id', 'fk_eventos_categoria')
                  ->references('id')->on('categorias_evento');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('eventos');
    }
};
