<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('evento_posts', function (Blueprint $table) {
            // Eliminar FK existente para poder cambiar la columna
            $table->dropForeign('fk_evento_posts_evento');
            // Hacer nullable para permitir posts sin evento asociado
            $table->unsignedInteger('evento_id')->nullable()->change();
            // Recrear FK con nullOnDelete para que al borrar el evento el post quede huérfano
            $table->foreign('evento_id', 'fk_evento_posts_evento')
                  ->references('id')->on('eventos')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('evento_posts', function (Blueprint $table) {
            $table->dropForeign('fk_evento_posts_evento');
            $table->unsignedInteger('evento_id')->nullable(false)->change();
            $table->foreign('evento_id', 'fk_evento_posts_evento')
                  ->references('id')->on('eventos');
        });
    }
};
