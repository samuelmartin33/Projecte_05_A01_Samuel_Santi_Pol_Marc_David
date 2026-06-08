<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('canciones', function (Blueprint $table) {
            $table->id();                                         // bigint, PK autoincremental
            $table->string('titulo');                             // nombre de la canción (obligatorio)
            $table->string('artista');                            // nombre del artista (obligatorio)
            $table->json('generos');                              // géneros musicales (array: ["Pop","Reggaeton"])
            $table->unsignedInteger('duracion_segundos');         // duración en segundos (ej: 210 = 3:30)
            $table->decimal('precio', 8, 2);                     // precio que paga el cliente (ej: 1.99)
            $table->boolean('activa')->default(true);             // false = desactivada, no se muestra
            $table->timestamps();                                 // created_at y updated_at automáticos
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('canciones');
    }
};
