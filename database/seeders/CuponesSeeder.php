<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CuponesSeeder extends Seeder
{
    public function run(): void
    {
        $ahora = now();

        DB::table('cupones')->insert([
            [
                'empresa_id'              => 1,
                'organizador_id'          => null,
                'codigo'                  => 'VIBEZ10',
                'descripcion'             => '10% de descuento en todas las entradas del festival',
                'valor_descuento'         => 10.00,
                'fecha_inicio'            => '2026-06-01 00:00:00',
                'fecha_fin'               => '2026-07-19 23:59:59',
                'limite_usos_total'       => 200,
                'limite_usos_por_usuario' => 1,
                'usos_actuales'           => 0,
                'estado'                  => 1,
                'fecha_creacion'          => $ahora,
                'fecha_actualizacion'     => null,
            ],
            [
                'empresa_id'              => null,
                'organizador_id'          => 1,
                'codigo'                  => 'SUMMERFREE',
                'descripcion'             => 'Entrada gratuita para los primeros 50 inscritos al Summit',
                'valor_descuento'         => 0.00,
                'fecha_inicio'            => '2026-04-01 00:00:00',
                'fecha_fin'               => '2026-05-14 23:59:59',
                'limite_usos_total'       => 50,
                'limite_usos_por_usuario' => 1,
                'usos_actuales'           => 0,
                'estado'                  => 1,
                'fecha_creacion'          => $ahora,
                'fecha_actualizacion'     => null,
            ],
        ]);
    }
}
