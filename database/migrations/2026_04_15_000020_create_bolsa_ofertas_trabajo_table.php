<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bolsa_ofertas_trabajo', function (Blueprint $table) {
            $table->unsignedInteger('id')->autoIncrement();
            $table->unsignedInteger('organizador_id');
            $table->unsignedInteger('evento_id')->nullable();
            $table->unsignedInteger('categoria_trabajo_id');
            $table->string('titulo', 300);
            $table->text('descripcion')->nullable();
            $table->text('requisitos')->nullable();
            $table->string('ubicacion', 300)->nullable();
            $table->decimal('salario_min', 10, 2)->nullable();
            $table->decimal('salario_max', 10, 2)->nullable();
            $table->dateTime('fecha_inicio_trabajo')->nullable();
            $table->dateTime('fecha_fin_trabajo')->nullable();
            $table->unsignedInteger('vacantes')->default(1);
            $table->tinyInteger('estado')->default(1);
            $table->dateTime('fecha_creacion');
            $table->dateTime('fecha_actualizacion')->nullable();

            $table->foreign('organizador_id', 'fk_ofertas_organizador')
                  ->references('id')->on('organizadores');
            $table->foreign('evento_id', 'fk_ofertas_evento')
                  ->references('id')->on('eventos');
            $table->foreign('categoria_trabajo_id', 'fk_ofertas_cat_trabajo')
                  ->references('id')->on('categorias_trabajo');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bolsa_ofertas_trabajo');
    }
};
