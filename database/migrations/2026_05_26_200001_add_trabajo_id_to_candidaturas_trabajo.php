<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('candidaturas_trabajo', function (Blueprint $table) {
            // FK al tipo de trabajo específico al que se postula el candidato
            $table->unsignedInteger('trabajo_id')->nullable()->after('oferta_id');

            $table->foreign('trabajo_id', 'fk_candidaturas_trabajo_id')
                  ->references('id')->on('trabajos')
                  ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('candidaturas_trabajo', function (Blueprint $table) {
            $table->dropForeign('fk_candidaturas_trabajo_id');
            $table->dropColumn('trabajo_id');
        });
    }
};
