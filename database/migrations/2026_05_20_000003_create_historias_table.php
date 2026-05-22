<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('historias', function (Blueprint $table) {
            $table->unsignedInteger('id')->autoIncrement();
            $table->unsignedInteger('usuario_id');
            $table->string('media_url', 500);
            $table->string('texto', 300)->nullable();
            $table->unsignedInteger('evento_id')->nullable();
            // expira_en = fecha_creacion + 24h, indexado para filtrar activas rápido
            $table->dateTime('expira_en');
            $table->unsignedInteger('vistas')->default(0);
            $table->tinyInteger('estado')->default(1);
            $table->dateTime('fecha_creacion');
            $table->dateTime('fecha_actualizacion')->nullable();

            $table->foreign('usuario_id', 'fk_historias_usuario')
                  ->references('id')->on('usuarios');
            $table->foreign('evento_id', 'fk_historias_evento')
                  ->references('id')->on('eventos')->nullOnDelete();

            $table->index(['estado', 'expira_en'], 'idx_historias_estado_expira');
            $table->index(['usuario_id', 'expira_en'], 'idx_historias_usuario_expira');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('historias');
    }
};
