<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * La tabla notificaciones ya existe en la BD.
 * Esta migración solo la registra en el historial de Laravel.
 */
return new class extends Migration
{
    public function up(): void
    {
        // La tabla ya existe — no hacer nada
        if (Schema::hasTable('notificaciones')) {
            return;
        }

        Schema::create('notificaciones', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('usuario_id');
            $table->tinyInteger('tipo_notificacion')->default(1);
            $table->string('titulo', 300);
            $table->text('mensaje')->nullable();
            $table->string('url_accion', 500)->nullable();
            $table->tinyInteger('leida')->default(0);
            $table->tinyInteger('estado')->default(1);
            $table->dateTime('fecha_creacion')->nullable();
            $table->dateTime('fecha_actualizacion')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('notificaciones');
    }
};
