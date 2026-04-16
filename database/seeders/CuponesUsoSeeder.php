<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CuponesUsoSeeder extends Seeder
{
    public function run(): void
    {
        $ahora = now();

        // Sin usos registrados por defecto (datos de ejemplo vacíos)
        // Se pueden añadir cuando se procesen pedidos con cupón aplicado
        DB::table('cupones_uso')->insert([
            [
                'cupon_id'            => 1,
                'pedido_id'           => 1,
                'descuento_aplicado'  => 4.50,
                'estado'              => 1,
                'fecha_creacion'      => $ahora,
                'fecha_actualizacion' => null,
            ],
        ]);
    }
}
