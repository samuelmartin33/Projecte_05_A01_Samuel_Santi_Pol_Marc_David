<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('chat_participantes', function (Blueprint $table) {
            $table->unsignedInteger('id')->autoIncrement();
            $table->unsignedInteger('chat_id');
            $table->unsignedInteger('usuario_id');
            $table->dateTime('fecha_union');
            // 1 = activo, 0 = abandonó el crew
            $table->tinyInteger('estado')->default(1);

            $table->unique(['chat_id', 'usuario_id'], 'uq_chat_participante');

            $table->foreign('chat_id', 'fk_cp_chat')
                  ->references('id')->on('chats')->onDelete('cascade');
            $table->foreign('usuario_id', 'fk_cp_usuario')
                  ->references('id')->on('usuarios');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('chat_participantes');
    }
};
