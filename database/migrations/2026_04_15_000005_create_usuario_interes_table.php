<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('usuario_interes', function (Blueprint $table) {
            $table->unsignedInteger('id')->autoIncrement();
            $table->unsignedInteger('usuario_id');
            $table->unsignedInteger('interes_id');
            $table->tinyInteger('estado')->default(1);
            $table->dateTime('fecha_creacion');
            $table->dateTime('fecha_actualizacion')->nullable();

            $table->foreign('usuario_id', 'fk_usuario_interes_usuario')
                  ->references('id')->on('usuarios');
            $table->foreign('interes_id', 'fk_usuario_interes_interes')
                  ->references('id')->on('intereses');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('usuario_interes');
    }
};
