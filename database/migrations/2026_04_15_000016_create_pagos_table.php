<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pagos', function (Blueprint $table) {
            $table->unsignedInteger('id')->autoIncrement();
            $table->unsignedInteger('pedido_id');
            $table->tinyInteger('metodo_pago');
            $table->tinyInteger('estado_pago')->default(1);
            $table->decimal('importe', 10, 2);
            $table->string('moneda', 3)->default('EUR');
            $table->dateTime('fecha_pago')->nullable();
            $table->dateTime('fecha_reembolso')->nullable();
            $table->decimal('importe_reembolso', 10, 2)->nullable();
            $table->string('motivo_reembolso', 500)->nullable();
            $table->tinyInteger('estado')->default(1);
            $table->dateTime('fecha_creacion');
            $table->dateTime('fecha_actualizacion')->nullable();

            $table->foreign('pedido_id', 'fk_pagos_pedido')
                  ->references('id')->on('pedidos');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pagos');
    }
};
