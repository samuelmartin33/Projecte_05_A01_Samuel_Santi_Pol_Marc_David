<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('chat_miembros', function (Blueprint $table) {
            $table->unsignedInteger('id')->autoIncrement();
            $table->unsignedInteger('chat_id');
            $table->unsignedInteger('usuario_id');
            $table->tinyInteger('es_admin')->default(0);
            $table->dateTime('fecha_union');

            $table->foreign('chat_id',    'fk_chat_miembros_chat')
                  ->references('id')->on('chats');
            $table->foreign('usuario_id', 'fk_chat_miembros_usuario')
                  ->references('id')->on('usuarios');

            $table->unique(['chat_id', 'usuario_id'], 'uq_chat_miembro');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('chat_miembros');
    }
};
