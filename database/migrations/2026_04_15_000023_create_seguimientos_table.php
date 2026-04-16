<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('seguimientos', function (Blueprint $table) {
            $table->unsignedInteger('id')->autoIncrement();
            $table->unsignedInteger('seguidor_id');
            $table->unsignedInteger('seguido_id');
            $table->tinyInteger('estado')->default(1);
            $table->dateTime('fecha_creacion');
            $table->dateTime('fecha_actualizacion')->nullable();

            $table->foreign('seguidor_id', 'fk_seguimientos_usuario')
                  ->references('id')->on('usuarios');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('seguimientos');
    }
};
