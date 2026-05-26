<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/* Tabla de valoraciones de empresas/promotoras por parte de usuarios autenticados */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('valoraciones_empresas', function (Blueprint $table) {
            $table->unsignedInteger('id')->autoIncrement();
            $table->unsignedInteger('usuario_id');
            $table->unsignedInteger('empresa_id');
            $table->unsignedTinyInteger('puntuacion');          // 1–5
            $table->text('comentario')->nullable();
            $table->tinyInteger('estado')->default(1);          // 1=visible, 0=oculta
            $table->dateTime('fecha_creacion');
            $table->dateTime('fecha_actualizacion')->nullable();

            $table->foreign('usuario_id', 'fk_val_emp_usuario')
                  ->references('id')->on('usuarios')->cascadeOnDelete();
            $table->foreign('empresa_id', 'fk_val_emp_empresa')
                  ->references('id')->on('empresas')->cascadeOnDelete();

            $table->unique(['usuario_id', 'empresa_id'], 'uq_valoracion_usuario_empresa');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('valoraciones_empresas');
    }
};
