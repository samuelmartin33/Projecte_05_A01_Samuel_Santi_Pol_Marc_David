<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('evento_post_imagenes', function (Blueprint $table) {
            $table->unsignedInteger('id')->autoIncrement();
            $table->unsignedInteger('evento_post_id');
            $table->string('imagen_url', 500);
            $table->unsignedTinyInteger('orden')->default(0);
            $table->tinyInteger('estado')->default(1);
            $table->dateTime('fecha_creacion');
            $table->dateTime('fecha_actualizacion')->nullable();

            $table->foreign('evento_post_id', 'fk_post_imagenes_post')
                  ->references('id')->on('evento_posts');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('evento_post_imagenes');
    }
};
