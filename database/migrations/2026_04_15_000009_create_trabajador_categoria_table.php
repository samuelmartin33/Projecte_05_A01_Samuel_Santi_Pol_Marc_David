<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('trabajador_categoria', function (Blueprint $table) {
            $table->unsignedInteger('id')->autoIncrement();
            $table->unsignedInteger('trabajador_id');
            $table->unsignedInteger('categoria_trabajo_id');
            $table->tinyInteger('estado')->default(1);
            $table->dateTime('fecha_creacion');
            $table->dateTime('fecha_actualizacion')->nullable();

            $table->foreign('trabajador_id', 'fk_trabajador_cat_trabajador')
                  ->references('id')->on('trabajadores');
            $table->foreign('categoria_trabajo_id', 'fk_trabajador_cat_categoria')
                  ->references('id')->on('categorias_trabajo');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('trabajador_categoria');
    }
};
