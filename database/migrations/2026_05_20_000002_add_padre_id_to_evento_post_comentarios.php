<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('evento_post_comentarios', function (Blueprint $table) {
            // Columna para respuestas anidadas (un nivel de profundidad)
            $table->unsignedInteger('padre_id')->nullable()->after('evento_post_id');
            $table->foreign('padre_id', 'fk_comentarios_padre')
                  ->references('id')->on('evento_post_comentarios')
                  ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('evento_post_comentarios', function (Blueprint $table) {
            $table->dropForeign('fk_comentarios_padre');
            $table->dropColumn('padre_id');
        });
    }
};
