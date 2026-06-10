<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tipos_bebida', function (Blueprint $table) {
            $table->id();
            $table->string('nombre', 100);          // Ej: "Cocktail", "Cerveza", "Vino"
            $table->string('icono', 20)->default('🍹'); // Emoji representativo
            $table->boolean('activo')->default(true);
            $table->timestamps();
        });

        // Insertar los tres tipos por defecto
        DB::table('tipos_bebida')->insert([
            ['nombre' => 'Cocktail',    'icono' => '🍸', 'activo' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['nombre' => 'Destilado',   'icono' => '🥃', 'activo' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['nombre' => 'Sin alcohol', 'icono' => '🧃', 'activo' => 1, 'created_at' => now(), 'updated_at' => now()],
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('tipos_bebida');
    }
};
