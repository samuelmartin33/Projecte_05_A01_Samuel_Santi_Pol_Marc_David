<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Ampliar el enum para incluir el rol camarero
        Schema::table('organizadores', function (Blueprint $table) {
            $table->enum('rol', ['organizador', 'portero', 'camarero'])
                  ->default('organizador')
                  ->change();
        });
    }

    public function down(): void
    {
        Schema::table('organizadores', function (Blueprint $table) {
            $table->enum('rol', ['organizador', 'portero', 'camarero'])
                  ->default('organizador')
                  ->change();
        });
    }
};
