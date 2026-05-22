<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TrabajadoresSeeder extends Seeder
{
    public function run(): void
    {
        $ahora = now();

        DB::table('trabajadores')->insert([
            // María (usuario_id=4) — fotógrafa freelance
            [
                'usuario_id'          => 4,
                'cv_url'              => null,
                'disponibilidad'      => 1,
                'localidad'           => 'Barcelona',
                'estado'              => 1,
                'fecha_creacion'      => $ahora,
                'fecha_actualizacion' => null,
            ],
            // Sofía (usuario_id=8) — videógrafa de eventos
            [
                'usuario_id'          => 8,
                'cv_url'              => null,
                'disponibilidad'      => 1,
                'localidad'           => 'Madrid',
                'estado'              => 1,
                'fecha_creacion'      => $ahora,
                'fecha_actualizacion' => null,
            ],
        ]);
    }
}
