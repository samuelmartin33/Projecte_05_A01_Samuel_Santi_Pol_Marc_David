<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('empresas', function (Blueprint $table) {
            $table->unsignedInteger('id')->autoIncrement();
            $table->unsignedInteger('usuario_id');
            $table->string('nombre_empresa', 200);
            $table->string('razon_social', 300)->nullable();
            $table->string('nif_cif', 20)->nullable();
            $table->text('descripcion')->nullable();
            $table->string('logo_url', 500)->nullable();
            $table->string('sitio_web', 500)->nullable();
            $table->string('telefono_contacto', 20)->nullable();
            $table->string('direccion', 500)->nullable();
            $table->tinyInteger('estado')->default(1);
            $table->dateTime('fecha_creacion');
            $table->dateTime('fecha_actualizacion')->nullable();

            $table->foreign('usuario_id', 'fk_empresas_usuario')
                  ->references('id')->on('usuarios');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('empresas');
    }
};
