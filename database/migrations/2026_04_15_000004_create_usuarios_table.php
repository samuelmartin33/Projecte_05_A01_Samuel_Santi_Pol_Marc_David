<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('usuarios', function (Blueprint $table) {
            $table->unsignedInteger('id')->autoIncrement();
            $table->string('nombre', 100);
            $table->string('apellido1', 150)->nullable();
            $table->string('apellido2', 150)->nullable();
            $table->string('email', 255);
            $table->string('password_hash', 255);
            $table->string('foto_url', 500)->nullable();
            $table->text('biografia')->nullable();
            $table->date('fecha_nacimiento')->nullable();
            $table->string('telefono', 20)->nullable();
            $table->tinyInteger('email_verificado')->default(0);
            $table->tinyInteger('es_admin')->default(0);
            $table->dateTime('ultimo_acceso')->nullable();
            $table->tinyInteger('estado')->default(1);
            $table->dateTime('fecha_creacion');
            $table->dateTime('fecha_actualizacion')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('usuarios');
    }
};
