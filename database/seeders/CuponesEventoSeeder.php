<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CuponesEventoSeeder extends Seeder
{
    public function run(): void
    {
        $ahora = now();

        DB::table('cupones_evento')->insert([
            ['cupon_id' => 1, 'evento_id' => 1, 'estado' => 1, 'fecha_creacion' => $ahora, 'fecha_actualizacion' => null],
            ['cupon_id' => 2, 'evento_id' => 2, 'estado' => 1, 'fecha_creacion' => $ahora, 'fecha_actualizacion' => null],
        ]);
    }
}
