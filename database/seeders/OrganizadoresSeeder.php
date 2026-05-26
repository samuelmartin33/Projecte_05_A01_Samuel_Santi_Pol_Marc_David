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
            // Carlos (usuario_id=3) — organizador principal de SoundWave (empresa_id=1)
            [
                'usuario_id'          => 3,
                'empresa_id'          => 1,
                'rol'                 => 'organizador',
                'estado'              => 1,
                'fecha_creacion'      => $ahora,
                'fecha_actualizacion' => null,
            ],
            // Marc (usuario_id=6) — organizador de CarniaFest (empresa_id=2)
            [
                'usuario_id'          => 6,
                'empresa_id'          => 2,
                'rol'                 => 'organizador',
                'estado'              => 1,
                'fecha_creacion'      => $ahora,
                'fecha_actualizacion' => null,
            ],
            // Javier (usuario_id=11) — portero / control de acceso en SoundWave (empresa_id=1)
            [
                'usuario_id'          => 11,
                'empresa_id'          => 1,
                'rol'                 => 'portero',
                'estado'              => 1,
                'fecha_creacion'      => $ahora,
                'fecha_actualizacion' => null,
            ],
        ]);
    }
}