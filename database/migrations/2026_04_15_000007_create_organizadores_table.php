<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('organizadores', function (Blueprint $table) {
            $table->unsignedInteger('id')->autoIncrement();
            $table->unsignedInteger('usuario_id');
            $table->unsignedInteger('empresa_id');
            $table->tinyInteger('estado')->default(1);
            $table->dateTime('fecha_creacion');
            $table->dateTime('fecha_actualizacion')->nullable();

            $table->foreign('usuario_id', 'fk_organizadores_usuario')
                  ->references('id')->on('usuarios');
            $table->foreign('empresa_id', 'fk_organizadores_empresa')
                  ->references('id')->on('empresas');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('organizadores');
    }
};
