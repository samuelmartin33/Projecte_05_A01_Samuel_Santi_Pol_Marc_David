<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('candidaturas_trabajo', function (Blueprint $table) {
            $table->unsignedInteger('id')->autoIncrement();
            $table->unsignedInteger('oferta_id');
            $table->unsignedInteger('trabajador_id');
            $table->tinyInteger('estado_candidatura')->default(1);
            $table->text('carta_presentacion')->nullable();
            $table->string('cv_url', 500)->nullable();
            $table->tinyInteger('estado')->default(1);
            $table->dateTime('fecha_creacion');
            $table->dateTime('fecha_actualizacion')->nullable();

            $table->foreign('oferta_id', 'fk_candidaturas_oferta')
                  ->references('id')->on('bolsa_ofertas_trabajo');
            $table->foreign('trabajador_id', 'fk_candidaturas_trabajador')
                  ->references('id')->on('trabajadores');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('candidaturas_trabajo');
    }
};
