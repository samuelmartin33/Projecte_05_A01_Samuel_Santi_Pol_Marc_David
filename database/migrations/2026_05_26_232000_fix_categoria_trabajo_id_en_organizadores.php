<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Corrección: la migración anterior añadió categoria_trabajo_id como BIGINT
 * pero categorias_trabajo.id es INT. MySQL no permite la FK con tipos distintos.
 * Esta migración elimina la columna y la vuelve a crear con el tipo correcto.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('organizadores', function (Blueprint $table) {
            // Eliminar la columna mal tipada que dejó la migración fallida
            $table->dropColumn('categoria_trabajo_id');
        });

        Schema::table('organizadores', function (Blueprint $table) {
            // Añadir con el tipo correcto (INT, igual que categorias_trabajo.id)
            $table->unsignedInteger('categoria_trabajo_id')
                  ->nullable()
                  ->after('rol');

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
    }
};
