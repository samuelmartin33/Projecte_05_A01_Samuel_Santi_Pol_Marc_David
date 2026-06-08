<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pedidos_proveedor', function (Blueprint $table) {
            $table->id();

            // FK → productos_barra: qué producto se está reponiendo
            $table->foreignId('producto_barra_id')->constrained('productos_barra');

            $table->unsignedInteger('cantidad');                  // unidades pedidas al proveedor
            $table->decimal('precio_total', 8, 2);               // coste total de la reposición

            // 'pendiente' = pedido generado pero no pagado
            // 'pagado'    = camarero ha pagado, stock repuesto
            $table->enum('estado', ['pendiente', 'pagado'])->default('pendiente');

            $table->timestamps(); // created_at = fecha del pedido
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pedidos_proveedor');
    }
};
