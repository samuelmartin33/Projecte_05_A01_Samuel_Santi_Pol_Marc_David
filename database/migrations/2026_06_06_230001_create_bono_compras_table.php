<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bono_compras', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('usuario_id');
            $table->foreign('usuario_id')->references('id')->on('usuarios');
            // FK → bonos_tipos (tabla nueva con id())
            $table->foreignId('bono_tipo_id')->constrained('bonos_tipos');
            // FK → eventos (tabla antigua → unsignedInteger)
            $table->unsignedInteger('evento_id');
            $table->foreign('evento_id')->references('id')->on('eventos');
            // Token único que se convierte en QR — se genera al comprar
            $table->string('codigo_qr')->unique();
            // Bebidas disponibles: empieza en cantidad_bebidas del tipo, baja con cada consumición
            $table->unsignedTinyInteger('bebidas_restantes');
            $table->timestamps(); // created_at = fecha de compra
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bono_compras');
    }
};
