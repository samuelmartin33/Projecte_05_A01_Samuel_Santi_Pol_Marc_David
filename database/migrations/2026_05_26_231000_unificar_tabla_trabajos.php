<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Unifica las dos tablas de tipos de trabajo en una sola (categorias_trabajo).
 *
 * Antes existían:
 *   - trabajos          (tabla antigua, ya sin uso — modelo Trabajo.php huérfano)
 *   - categorias_trabajo (activa: ofertas, candidaturas, panel admin)
 *
 * Esta migración:
 *   1. Elimina la tabla `trabajos` (estaba duplicada y ya no se usaba)
 *   2. Añade `categoria_trabajo_id` a `organizadores` para que los miembros
 *      del equipo también usen la misma tabla de tipos de trabajo.
 */
return new class extends Migration
{
    public function up(): void
    {
        // 1. Eliminar tabla antigua duplicada
        Schema::dropIfExists('trabajos');

        // 2. Añadir puesto de trabajo a los miembros del equipo
        Schema::table('organizadores', function (Blueprint $table) {
            $table->unsignedInteger('categoria_trabajo_id')
                  ->nullable()
                  ->after('rol')
                  ->comment('Puesto visual del miembro (Camarero, Barman…) — independiente del rol de permiso');

            $table->foreign('categoria_trabajo_id', 'fk_org_categoria_trabajo')
                  ->references('id')
                  ->on('categorias_trabajo')
                  ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('organizadores', function (Blueprint $table) {
            $table->dropForeign('fk_org_categoria_trabajo');
            $table->dropColumn('categoria_trabajo_id');
        });

        // No se restaura `trabajos` — la tabla estaba en desuso
    }
};
