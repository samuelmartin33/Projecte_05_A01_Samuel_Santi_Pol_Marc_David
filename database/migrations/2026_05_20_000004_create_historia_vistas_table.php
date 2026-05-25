<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('historia_vistas', function (Blueprint $table) {
            $table->unsignedInteger('id')->autoIncrement();
            $table->unsignedInteger('historia_id');
            $table->unsignedInteger('usuario_id');
            $table->dateTime('fecha_vista');

            // Un usuario solo puede ver una historia una vez (upsert)
            $table->unique(['historia_id', 'usuario_id'], 'uq_historia_vista');

            $table->foreign('historia_id', 'fk_hvistas_historia')
                  ->references('id')->on('historias')->cascadeOnDelete();
            $table->foreign('usuario_id', 'fk_hvistas_usuario')
                  ->references('id')->on('usuarios');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('historia_vistas');
    }
};
