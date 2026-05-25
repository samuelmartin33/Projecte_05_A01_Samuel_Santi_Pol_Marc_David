<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Migración: añade campos de autenticación social a la tabla usuarios.
 *
 * social_provider → indica con qué proveedor inició sesión el usuario
 *                   por primera vez: 'google', 'apple' o null (registro clásico).
 *
 * social_id       → el identificador único que el proveedor asigna al usuario
 *                   (Google 'sub', Apple 'sub'). Permite reconocer al mismo
 *                   usuario incluso si cambia su email en el proveedor.
 *
 * Ambos campos son nullable porque los usuarios que se registran con el
 * formulario clásico no tienen proveedor social.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('usuarios', function (Blueprint $table) {
            // Después de email_verificado para mantener el orden lógico del schema
            $table->string('social_provider', 20)->nullable()->after('email_verificado');
            $table->string('social_id', 255)->nullable()->after('social_provider');
        });
    }

    public function down(): void
    {
        Schema::table('usuarios', function (Blueprint $table) {
            $table->dropColumn(['social_provider', 'social_id']);
        });
    }
};
