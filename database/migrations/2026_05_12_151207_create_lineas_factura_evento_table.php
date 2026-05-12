<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('lineas_factura_evento', function (Blueprint $table) {
            $table->id();

            $table->foreignId('factura_evento_id')
                  ->constrained('facturas_evento')
                  ->cascadeOnDelete();

            $table->unsignedTinyInteger('orden')->default(1);
            $table->enum('tipo', ['venta', 'comision', 'iva', 'descuento', 'otro'])->default('venta');
            $table->string('concepto', 500);
            $table->integer('cantidad')->default(1);
            $table->decimal('precio_unitario', 12, 4)->default(0.0000);
            $table->decimal('subtotal', 12, 2)->default(0.00);

            $table->timestamps();

            $table->index('factura_evento_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('lineas_factura_evento');
    }
};
