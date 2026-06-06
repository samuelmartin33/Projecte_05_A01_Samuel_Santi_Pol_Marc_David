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
        Schema::create('bonos_tipos', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('cantidad_bebidas');          // 2, 5 o 10 bebidas
            $table->decimal('precio', 8, 2);                      // precio del bono (ej: 5.00, 10.00, 18.00)
            $table->string('descripcion')->nullable();             // texto descriptivo opcional
            $table->boolean('activo')->default(true);             // para activar/desactivar el tipo de bono
            $table->timestamps();
        });
    }
    
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bonos_tipos');
    }
};
