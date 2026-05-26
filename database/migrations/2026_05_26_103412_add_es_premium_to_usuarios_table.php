<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Migración: añade es_premium a la tabla usuarios.
 *
 * Por qué usamos ->after('es_admin'):
 *   Los campos booleanos de "estado del usuario" (es_admin, es_premium) quedan
 *   agrupados en la tabla, lo que facilita la lectura del esquema.
 *
 * Por qué default(false):
 *   Todos los usuarios existentes mantienen su estado "no premium" automáticamente
 *   cuando ejecutamos la migración. No necesitamos actualizar ninguna fila.
 *
 * Comando para ejecutar:
 *   php artisan migrate
 */
return new class extends Migration
{
    /**
     * Aplica la migración: añade la columna es_premium.
     */
    public function up(): void
    {
        Schema::table('usuarios', function (Blueprint $table) {
            // BOOLEAN en MySQL se almacena como TINYINT(1): 0 = false, 1 = true.
            // El cast 'boolean' del modelo Usuario convierte automáticamente el valor a bool en PHP.
            $table->boolean('es_premium')->default(false)->after('es_admin');
        });
    }

    /**
     * Revierte la migración: elimina la columna es_premium.
     * Se ejecuta con: php artisan migrate:rollback
     */
    public function down(): void
    {
        Schema::table('usuarios', function (Blueprint $table) {
            $table->dropColumn('es_premium');
        });
    }
};
