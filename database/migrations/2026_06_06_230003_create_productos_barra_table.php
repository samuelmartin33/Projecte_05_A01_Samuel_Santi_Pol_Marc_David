<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('productos_barra', function (Blueprint $table) {
            $table->id();

            // FK → eventos: cada producto de barra pertenece a un evento Fiesta concreto
            $table->unsignedInteger('evento_id');
            $table->foreign('evento_id')->references('id')->on('eventos');

            $table->string('nombre');                              // nombre del producto (ej: "Mojito")
            $table->enum('tipo_producto', ['cocktail', 'destilado', 'sin_alcohol']);
            $table->string('proveedor');                          // nombre del proveedor
            $table->unsignedInteger('stock')->default(0);         // unidades disponibles actualmente
            $table->decimal('precio_unitario', 8, 2);            // precio de coste por unidad

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('productos_barra');
    }
};
