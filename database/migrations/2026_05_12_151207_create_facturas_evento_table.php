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
        Schema::create('facturas_evento', function (Blueprint $table) {
            $table->id();
            $table->string('numero_factura', 30)->unique();

            $table->unsignedInteger('evento_id');
            $table->foreign('evento_id')->references('id')->on('eventos')->restrictOnDelete();

            $table->unsignedInteger('empresa_id')->nullable();
            $table->foreign('empresa_id')->references('id')->on('empresas')->nullOnDelete();

            $table->unsignedInteger('generada_por_usuario_id')->nullable();
            $table->foreign('generada_por_usuario_id')->references('id')->on('usuarios')->nullOnDelete();

            $table->enum('estado', ['emitida', 'anulada', 'error'])->default('emitida');

            // Datos fiscales congelados al momento de emisión
            $table->string('nombre_empresa_frozen', 200);
            $table->string('razon_social_frozen', 300)->nullable();
            $table->string('nif_cif_frozen', 20)->nullable();
            $table->string('direccion_frozen', 500)->nullable();

            // Datos del evento congelados
            $table->string('nombre_evento_frozen', 300);
            $table->dateTime('fecha_evento_frozen');

            // Importes calculados y congelados
            $table->unsignedInteger('total_entradas_vendidas')->default(0);
            $table->decimal('importe_bruto', 12, 2)->default(0.00);
            $table->decimal('porcentaje_comision', 5, 2)->default(0.00);
            $table->decimal('importe_comision', 12, 2)->default(0.00);
            $table->decimal('tipo_iva', 5, 2)->default(21.00);
            $table->decimal('cuota_iva', 12, 2)->default(0.00);
            $table->decimal('total_cargos_plataforma', 12, 2)->default(0.00);
            $table->decimal('importe_neto_empresa', 12, 2)->default(0.00);

            $table->text('notas')->nullable();
            $table->string('pdf_path', 500)->nullable();
            $table->dateTime('fecha_emision');

            $table->timestamps();

            $table->index('evento_id');
            $table->index('empresa_id');
            $table->index('estado');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('facturas_evento');
    }
};
