<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('seguimientos_empresa', function (Blueprint $table) {
            $table->unsignedInteger('id')->autoIncrement();
            $table->unsignedInteger('usuario_id');
            $table->unsignedInteger('empresa_id');
            $table->timestamp('fecha_creacion')->useCurrent();

            $table->foreign('usuario_id')->references('id')->on('usuarios')->onDelete('cascade');
            $table->foreign('empresa_id')->references('id')->on('empresas')->onDelete('cascade');

            // Un usuario solo puede seguir una vez a la misma empresa
            $table->unique(['usuario_id', 'empresa_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('seguimientos_empresa');
    }
};
