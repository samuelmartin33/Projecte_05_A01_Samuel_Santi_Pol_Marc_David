<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('registro_horas', function (Blueprint $table) {
            $table->unsignedInteger('id')->autoIncrement();

            // Usuario que registra las horas (portero u organizador)
            $table->unsignedInteger('usuario_id');
            $table->foreign('usuario_id', 'fk_horas_usuario')
                  ->references('id')->on('usuarios')
                  ->onDelete('cascade');

            $table->date('fecha');                         // Día trabajado
            $table->decimal('horas', 4, 1);                // Ej: 7.5 horas
            $table->string('descripcion', 500)->nullable(); // Tareas realizadas (opcional)

            $table->dateTime('fecha_creacion');
            $table->dateTime('fecha_actualizacion')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('registro_horas');
    }
};
