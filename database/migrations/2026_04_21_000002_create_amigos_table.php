<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('amigos', function (Blueprint $table) {
            $table->unsignedInteger('id')->autoIncrement();
            // Quien envía la solicitud
            $table->unsignedInteger('solicitante_id');
            // Quien la recibe
            $table->unsignedInteger('receptor_id');
            // 0 = pendiente, 1 = aceptado, 2 = rechazado
            $table->tinyInteger('estado')->default(0);
            $table->dateTime('fecha_creacion');
            $table->dateTime('fecha_actualizacion')->nullable();

            $table->foreign('solicitante_id')->references('id')->on('usuarios');
            $table->foreign('receptor_id')->references('id')->on('usuarios');

            // Evitar solicitudes duplicadas
            $table->unique(['solicitante_id', 'receptor_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('amigos');
    }
};
