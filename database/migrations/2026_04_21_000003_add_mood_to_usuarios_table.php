<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Añade la columna 'mood' a la tabla usuarios.
     * Almacena el estado de ánimo del usuario (ej: "🥳 De fiesta").
     */
    public function up(): void
    {
        // La columna puede haberse creado manualmente; solo añadir si no existe
        if (!Schema::hasColumn('usuarios', 'mood')) {
            Schema::table('usuarios', function (Blueprint $table) {
                $table->string('mood', 100)->nullable()->after('biografia');
            });
        }
    }

    /**
     * Elimina la columna 'mood' si se revierte la migración.
     */
    public function down(): void
    {
        Schema::table('usuarios', function (Blueprint $table) {
            $table->dropColumn('mood');
        });
    }
};
