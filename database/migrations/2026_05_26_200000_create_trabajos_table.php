<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('trabajos', function (Blueprint $table) {
            $table->unsignedInteger('id')->autoIncrement();
            $table->string('nombre', 100);
            $table->text('descripcion')->nullable();
            $table->tinyInteger('estado')->default(1);
            $table->dateTime('fecha_creacion');
            $table->dateTime('fecha_actualizacion')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('trabajos');
    }
};
