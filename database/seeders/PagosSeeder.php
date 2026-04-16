<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PagosSeeder extends Seeder
{
    public function run(): void
    {
        $ahora = now();

        DB::table('pagos')->insert([
            [
                'pedido_id'           => 1,
                'metodo_pago'         => 1,
                'estado_pago'         => 2,
                'importe'             => 45.00,
                'moneda'              => 'EUR',
                'fecha_pago'          => $ahora,
                'fecha_reembolso'     => null,
                'importe_reembolso'   => null,
                'motivo_reembolso'    => null,
                'estado'              => 1,
                'fecha_creacion'      => $ahora,
                'fecha_actualizacion' => null,
            ],
        ]);
    }
}
