<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('mensajes', function (Blueprint $table) {
            $table->unsignedInteger('id')->autoIncrement();
            $table->unsignedInteger('chat_id');
            $table->unsignedInteger('usuario_id');
            $table->text('contenido');
            $table->tinyInteger('leido')->default(0);
            $table->tinyInteger('estado')->default(1);
            $table->dateTime('fecha_creacion');
            $table->dateTime('fecha_actualizacion')->nullable();

            $table->foreign('chat_id', 'fk_mensajes_chat')
                  ->references('id')->on('chats');
            $table->foreign('usuario_id', 'fk_mensajes_usuario')
                  ->references('id')->on('usuarios');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('mensajes');
    }
};
