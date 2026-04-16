<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('trabajadores', function (Blueprint $table) {
            $table->unsignedInteger('id')->autoIncrement();
            $table->unsignedInteger('usuario_id');
            $table->string('cv_url', 500)->nullable();
            $table->tinyInteger('disponibilidad')->default(1);
            $table->string('localidad', 200)->nullable();
            $table->tinyInteger('estado')->default(1);
            $table->dateTime('fecha_creacion');
            $table->dateTime('fecha_actualizacion')->nullable();

            $table->foreign('usuario_id', 'fk_trabajadores_usuario')
                  ->references('id')->on('usuarios');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('trabajadores');
    }
};
