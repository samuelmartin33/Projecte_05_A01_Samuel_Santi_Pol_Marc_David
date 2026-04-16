<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cupones_uso', function (Blueprint $table) {
            $table->unsignedInteger('id')->autoIncrement();
            $table->unsignedInteger('cupon_id');
            $table->unsignedInteger('pedido_id');
            $table->decimal('descuento_aplicado', 10, 2);
            $table->tinyInteger('estado')->default(1);
            $table->dateTime('fecha_creacion');
            $table->dateTime('fecha_actualizacion')->nullable();

            $table->foreign('cupon_id', 'fk_cupones_uso_cupon')
                  ->references('id')->on('cupones');
            $table->foreign('pedido_id', 'fk_cupones_uso_pedido')
                  ->references('id')->on('pedidos');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cupones_uso');
    }
};
