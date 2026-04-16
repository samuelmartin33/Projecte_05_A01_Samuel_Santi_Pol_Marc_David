<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SeguimientosSeeder extends Seeder
{
    public function run(): void
    {
        $ahora = now();

        // Pablo (id=5) sigue a Carlos (id=3)
        // Carlos (id=3) sigue a Pablo (id=5)
        DB::table('seguimientos')->insert([
            ['seguidor_id' => 5, 'seguido_id' => 3, 'estado' => 1, 'fecha_creacion' => $ahora, 'fecha_actualizacion' => null],
            ['seguidor_id' => 3, 'seguido_id' => 5, 'estado' => 1, 'fecha_creacion' => $ahora, 'fecha_actualizacion' => null],
        ]);
    }
}
