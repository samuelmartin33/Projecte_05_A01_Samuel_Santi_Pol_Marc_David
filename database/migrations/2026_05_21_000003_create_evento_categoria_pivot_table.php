<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('evento_categoria', function (Blueprint $table) {
            $table->unsignedInteger('evento_id');
            $table->unsignedInteger('categoria_evento_id');

            $table->primary(['evento_id', 'categoria_evento_id']);

            $table->foreign('evento_id')
                  ->references('id')->on('eventos')
                  ->onDelete('cascade');

            $table->foreign('categoria_evento_id')
                  ->references('id')->on('categorias_evento')
                  ->onDelete('cascade');
        });

        // Migrar datos existentes: cada evento con categoria_evento_id pasa a la pivot
        DB::statement('
            INSERT INTO evento_categoria (evento_id, categoria_evento_id)
            SELECT id, categoria_evento_id
            FROM eventos
            WHERE categoria_evento_id IS NOT NULL
        ');

        // Hacer nullable la columna antigua (ya no es la fuente de verdad)
        Schema::table('eventos', function (Blueprint $table) {
            $table->unsignedInteger('categoria_evento_id')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('evento_categoria');

        Schema::table('eventos', function (Blueprint $table) {
            $table->unsignedInteger('categoria_evento_id')->nullable(false)->change();
        });
    }
};
