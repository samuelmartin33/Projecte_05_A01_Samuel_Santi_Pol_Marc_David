<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * Cambia tipo_producto de ENUM a VARCHAR(100) en productos_barra y bono_consumos.
 * Esto permite tipos de bebida dinámicos (no fijos en el enum).
 */
return new class extends Migration
{
    public function up(): void
    {
        // Cambio directo en MySQL: VARCHAR es compatible con los valores de enum existentes
        DB::statement("ALTER TABLE productos_barra MODIFY tipo_producto VARCHAR(100) NOT NULL");
        DB::statement("ALTER TABLE bono_consumos MODIFY tipo_producto VARCHAR(100) NOT NULL");

        // Normalizar los valores existentes al formato con mayúscula inicial
        // (la validación del CanjeBonoController usaba 'Cocktail', 'Destilado', 'Sin alcohol')
        DB::table('productos_barra')->where('tipo_producto', 'cocktail')   ->update(['tipo_producto' => 'Cocktail']);
        DB::table('productos_barra')->where('tipo_producto', 'destilado')  ->update(['tipo_producto' => 'Destilado']);
        DB::table('productos_barra')->where('tipo_producto', 'sin_alcohol')->update(['tipo_producto' => 'Sin alcohol']);

        DB::table('bono_consumos')->where('tipo_producto', 'cocktail')   ->update(['tipo_producto' => 'Cocktail']);
        DB::table('bono_consumos')->where('tipo_producto', 'destilado')  ->update(['tipo_producto' => 'Destilado']);
        DB::table('bono_consumos')->where('tipo_producto', 'sin_alcohol')->update(['tipo_producto' => 'Sin alcohol']);
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE productos_barra MODIFY tipo_producto ENUM('cocktail','destilado','sin_alcohol') NOT NULL");
        DB::statement("ALTER TABLE bono_consumos MODIFY tipo_producto ENUM('cocktail','destilado','sin_alcohol') NOT NULL");
    }
};
