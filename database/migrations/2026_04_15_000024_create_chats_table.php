<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('chats', function (Blueprint $table) {
            $table->unsignedInteger('id')->autoIncrement();
            $table->tinyInteger('tipo_chat');
            $table->unsignedInteger('evento_id')->nullable();
            $table->unsignedInteger('candidatura_id')->nullable();
            $table->string('nombre', 200)->nullable();
            $table->tinyInteger('estado')->default(1);
            $table->dateTime('fecha_creacion');
            $table->dateTime('fecha_actualizacion')->nullable();

            $table->foreign('evento_id', 'fk_chats_evento')
                  ->references('id')->on('eventos');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('chats');
    }
};
