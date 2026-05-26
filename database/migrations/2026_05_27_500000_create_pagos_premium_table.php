<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Migración: tabla para registrar cada compra de suscripción Premium.
 *
 * Por qué es necesaria esta tabla:
 *   El flujo de pago premium usa Stripe Checkout y solo actualiza es_premium=true
 *   en el usuario. Sin esta tabla no hay forma de saber cuándo pagó, cuánto pagó
 *   ni generar facturas individuales para cada usuario premium.
 *
 * stripe_session_id tiene unique para garantizar idempotencia: si el webhook
 * llega dos veces con el mismo session_id, el segundo INSERT falla silenciosamente
 * y no duplicamos el registro.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pagos_premium', function (Blueprint $table) {
            $table->unsignedInteger('id')->autoIncrement();

            // FK al usuario que compró el premium
            $table->unsignedInteger('usuario_id');
            $table->foreign('usuario_id', 'fk_pagos_premium_usuario')
                  ->references('id')->on('usuarios');

            // IDs de Stripe para trazabilidad y posibles reembolsos futuros
            $table->string('stripe_session_id', 200)->unique();
            $table->string('stripe_payment_intent_id', 200)->nullable();

            // Importe y divisa del cobro (habitualmente 5,00 EUR)
            $table->decimal('importe', 10, 2)->default(5.00);
            $table->string('moneda', 3)->default('EUR');

            // Estado: 1 = completado, 0 = reembolsado/cancelado
            $table->tinyInteger('estado')->default(1);

            $table->dateTime('fecha_pago')->nullable();
            $table->dateTime('fecha_creacion');
            $table->dateTime('fecha_actualizacion')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pagos_premium');
    }
};
