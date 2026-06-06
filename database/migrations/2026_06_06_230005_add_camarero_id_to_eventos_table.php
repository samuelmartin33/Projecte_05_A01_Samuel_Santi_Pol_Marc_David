<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('eventos', function (Blueprint $table) {
            // Camarero asignado al evento Fiesta (obligatorio para esa categoría)
            // nullable porque los eventos de otras categorías no tienen camarero
            $table->unsignedInteger('camarero_id')->nullable()->after('estado');
            $table->foreign('camarero_id')->references('id')->on('organizadores');
        });
    }

    public function down(): void
    {
        Schema::table('eventos', function (Blueprint $table) {
            $table->dropForeign(['camarero_id']);
            $table->dropColumn('camarero_id');
        });
    }
};
