<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class OrganizadoresSeeder extends Seeder
{
    public function run(): void
    {
        $ahora = now();

        DB::table('organizadores')->insert([
            // Carlos (usuario_id=3) — organizador principal de SoundWave
            [
                'usuario_id'          => 3,
                'empresa_id'          => 1,
                'rol'                 => 'organizador',
                'estado'              => 1,
                'fecha_creacion'      => $ahora,
                'fecha_actualizacion' => null,
            ],
            // Javier (usuario_id=9) — portero / control de acceso
            [
                'usuario_id'          => 9,
                'empresa_id'          => 1,
                'rol'                 => 'portero',
                'estado'              => 1,
                'fecha_creacion'      => $ahora,
                'fecha_actualizacion' => null,
            ],
        ]);
    }
}
