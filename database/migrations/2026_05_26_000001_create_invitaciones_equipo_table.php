<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('invitaciones_equipo', function (Blueprint $table) {
            $table->unsignedInteger('id')->autoIncrement();
            $table->string('token', 80)->unique();
            $table->unsignedInteger('candidatura_id');
            $table->unsignedInteger('empresa_id');
            $table->string('email', 200);
            $table->string('rol', 30)->default('organizador');
            $table->dateTime('expira_en');
            $table->dateTime('usado_en')->nullable();
            $table->dateTime('fecha_creacion');

            $table->foreign('candidatura_id', 'fk_inv_candidatura')
                  ->references('id')->on('candidaturas_trabajo');
            $table->foreign('empresa_id', 'fk_inv_empresa')
                  ->references('id')->on('empresas');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('invitaciones_equipo');
    }
};