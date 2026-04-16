<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TrabajadoresSeeder extends Seeder
{
    public function run(): void
    {
        $ahora = now();

        // María (usuario_id=4) es trabajadora
        DB::table('trabajadores')->insert([
            [
                'usuario_id'          => 4,
                'cv_url'              => null,
                'disponibilidad'      => 1,
                'localidad'           => 'Barcelona',
                'estado'              => 1,
                'fecha_creacion'      => $ahora,
                'fecha_actualizacion' => null,
            ],
        ]);
    }
}
