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
<<<<<<< HEAD
            // Carlos (usuario_id=3) — organizador principal de SoundWave
=======
            // Carlos (usuario_id=3) → SoundWave (empresa_id=1)
>>>>>>> f1367d008a757bba14d54f01a53fcb743cdefeb9
            [
                'usuario_id'          => 3,
                'empresa_id'          => 1,
                'rol'                 => 'organizador',
                'estado'              => 1,
                'fecha_creacion'      => $ahora,
                'fecha_actualizacion' => null,
            ],
<<<<<<< HEAD
            // Javier (usuario_id=9) — portero / control de acceso
            [
                'usuario_id'          => 9,
                'empresa_id'          => 1,
                'rol'                 => 'portero',
=======
            // Marc (usuario_id=6) → CarniaFest (empresa_id=2)
            [
                'usuario_id'          => 6,
                'empresa_id'          => 2,
                'rol'                 => 'organizador',
>>>>>>> f1367d008a757bba14d54f01a53fcb743cdefeb9
                'estado'              => 1,
                'fecha_creacion'      => $ahora,
                'fecha_actualizacion' => null,
            ],
        ]);
    }
}
