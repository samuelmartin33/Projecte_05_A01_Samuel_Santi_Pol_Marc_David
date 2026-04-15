<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cupones', function (Blueprint $table) {
            $table->unsignedInteger('id')->autoIncrement();
            $table->unsignedInteger('empresa_id')->nullable();
            $table->unsignedInteger('organizador_id')->nullable();
            $table->string('codigo', 50);
            $table->string('descripcion', 255)->nullable();
            $table->decimal('valor_descuento', 10, 2);
            $table->dateTime('fecha_inicio');
            $table->dateTime('fecha_fin');
            $table->unsignedInteger('limite_usos_total')->nullable();
            $table->unsignedInteger('limite_usos_por_usuario')->nullable();
            $table->unsignedInteger('usos_actuales')->default(0);
            $table->tinyInteger('estado')->default(1);
            $table->dateTime('fecha_creacion');
            $table->dateTime('fecha_actualizacion')->nullable();

            $table->foreign('empresa_id', 'fk_cupones_empresa')
                  ->references('id')->on('empresas');
            $table->foreign('organizador_id', 'fk_cupones_organizador')
                  ->references('id')->on('organizadores');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cupones');
    }
};
