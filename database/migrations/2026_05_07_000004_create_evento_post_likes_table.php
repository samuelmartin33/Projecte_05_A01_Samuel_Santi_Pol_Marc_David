<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('evento_post_likes', function (Blueprint $table) {
            $table->unsignedInteger('id')->autoIncrement();
            $table->unsignedInteger('evento_post_id');
            $table->unsignedInteger('usuario_id');
            $table->tinyInteger('estado')->default(1);
            $table->dateTime('fecha_creacion');
            $table->dateTime('fecha_actualizacion')->nullable();

            $table->foreign('evento_post_id', 'fk_post_likes_post')
                  ->references('id')->on('evento_posts');
            $table->foreign('usuario_id', 'fk_post_likes_usuario')
                  ->references('id')->on('usuarios');

            $table->unique(['evento_post_id', 'usuario_id'], 'uk_post_likes_unique');
            $table->index(['evento_post_id', 'estado'], 'idx_post_likes_post_estado');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('evento_post_likes');
    }
};
