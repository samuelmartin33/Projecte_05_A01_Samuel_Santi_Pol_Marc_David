<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('evento_post_comentarios', function (Blueprint $table) {
            $table->unsignedInteger('id')->autoIncrement();
            $table->unsignedInteger('evento_post_id');
            $table->unsignedInteger('usuario_id');
            $table->text('contenido');
            $table->tinyInteger('estado')->default(1);
            $table->dateTime('fecha_creacion');
            $table->dateTime('fecha_actualizacion')->nullable();

            $table->foreign('evento_post_id', 'fk_post_comentarios_post')
                  ->references('id')->on('evento_posts');
            $table->foreign('usuario_id', 'fk_post_comentarios_usuario')
                  ->references('id')->on('usuarios');

            $table->index(['evento_post_id', 'fecha_creacion'], 'idx_post_comentarios_post_fecha');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('evento_post_comentarios');
    }
};
