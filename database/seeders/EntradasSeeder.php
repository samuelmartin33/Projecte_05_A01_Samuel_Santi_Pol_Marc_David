<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class EntradasSeeder extends Seeder
{
    public function run(): void
    {
        $ahora = now();

        DB::table('entradas')->insert([
            [
                'pedido_id'           => 1,
                'evento_id'           => 1,
                'estado_entrada'      => 1,
                'codigo_qr'           => strtoupper(Str::random(20)),
                'precio_unitario'     => 45.00,
                'precio_pagado'       => 45.00,
                'fecha_uso'           => null,
                'estado'              => 1,
                'fecha_creacion'      => $ahora,
                'fecha_actualizacion' => null,
            ],
        ]);
    }
}
