<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('entradas', function (Blueprint $table) {
            $table->unsignedInteger('id')->autoIncrement();
            $table->unsignedInteger('pedido_id');
            $table->unsignedInteger('evento_id');
            $table->tinyInteger('estado_entrada')->default(1);
            $table->string('codigo_qr', 255);
            $table->decimal('precio_unitario', 10, 2)->default(0.00);
            $table->decimal('precio_pagado', 10, 2)->default(0.00);
            $table->dateTime('fecha_uso')->nullable();
            $table->tinyInteger('estado')->default(1);
            $table->dateTime('fecha_creacion');
            $table->dateTime('fecha_actualizacion')->nullable();

            $table->foreign('pedido_id', 'fk_entradas_pedido')
                  ->references('id')->on('pedidos');
            $table->foreign('evento_id', 'fk_entradas_evento')
                  ->references('id')->on('eventos');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('entradas');
    }
};
