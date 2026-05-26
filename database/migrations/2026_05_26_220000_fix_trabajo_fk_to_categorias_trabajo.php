<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('candidaturas_trabajo', function (Blueprint $table) {
            // Eliminar el FK antiguo que apuntaba a la tabla 'trabajos'
            $table->dropForeign('fk_candidaturas_trabajo_id');

            // Añadir nuevo FK apuntando a 'categorias_trabajo'
            $table->foreign('trabajo_id', 'fk_candidaturas_categoria_trabajo')
                  ->references('id')->on('categorias_trabajo')
                  ->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::table('candidaturas_trabajo', function (Blueprint $table) {
            $table->dropForeign('fk_candidaturas_categoria_trabajo');

            $table->foreign('trabajo_id', 'fk_candidaturas_trabajo_id')
                  ->references('id')->on('trabajos')
                  ->onDelete('set null');
        });
    }
};
