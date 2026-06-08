<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bono_consumos', function (Blueprint $table) {
            $table->id();

            // FK → bono_compras (tabla nueva → foreignId compatible)
            $table->foreignId('bono_compra_id')->constrained('bono_compras');

            // Qué tipo de bebida pidió el cliente al canjear
            $table->enum('tipo_producto', ['cocktail', 'destilado', 'sin_alcohol']);

            // FK → organizadores: el camarero que escaneó y validó el bono
            $table->unsignedInteger('camarero_id');
            $table->foreign('camarero_id')->references('id')->on('organizadores');

            $table->timestamps(); // created_at = momento exacto del canje
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bono_consumos');
    }
};
