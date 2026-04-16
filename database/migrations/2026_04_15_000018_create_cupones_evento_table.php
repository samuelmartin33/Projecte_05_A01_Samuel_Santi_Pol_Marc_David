<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cupones_evento', function (Blueprint $table) {
            $table->unsignedInteger('id')->autoIncrement();
            $table->unsignedInteger('cupon_id');
            $table->unsignedInteger('evento_id');
            $table->tinyInteger('estado')->default(1);
            $table->dateTime('fecha_creacion');
            $table->dateTime('fecha_actualizacion')->nullable();

            $table->foreign('cupon_id', 'fk_cupones_evento_cupon')
                  ->references('id')->on('cupones');
            $table->foreign('evento_id', 'fk_cupones_evento_evento')
                  ->references('id')->on('eventos');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cupones_evento');
    }
};
