<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('usuarios', function (Blueprint $table) {
            $table->enum('tipo_cuenta', ['cliente', 'empresa'])
                  ->default('cliente')
                  ->after('email_verificado');

            $table->enum('estado_registro', ['pendiente', 'aprobado', 'rechazado'])
                  ->default('pendiente')
                  ->after('tipo_cuenta');
        });
    }

    public function down(): void
    {
        Schema::table('usuarios', function (Blueprint $table) {
            $table->dropColumn(['tipo_cuenta', 'estado_registro']);
        });
    }
};
