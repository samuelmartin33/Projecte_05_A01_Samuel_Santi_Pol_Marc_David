<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PedidosSeeder extends Seeder
{
    public function run(): void
    {
        $ahora = now();

        // Pablo (usuario_id=5) realiza un pedido
        DB::table('pedidos')->insert([
            [
                'usuario_id'          => 5,
                'total'               => 45.00,
                'total_descuento'     => 0.00,
                'total_final'         => 45.00,
                'estado'              => 1,
                'fecha_creacion'      => $ahora,
                'fecha_actualizacion' => null,
            ],
        ]);
    }
}
