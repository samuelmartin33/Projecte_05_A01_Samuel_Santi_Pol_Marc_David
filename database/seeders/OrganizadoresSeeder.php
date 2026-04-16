<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class OrganizadoresSeeder extends Seeder
{
    public function run(): void
    {
        $ahora = now();

        // Carlos (usuario_id=3) es organizador en la empresa SoundWave (empresa_id=1)
        DB::table('organizadores')->insert([
            [
                'usuario_id'          => 3,
                'empresa_id'          => 1,
                'estado'              => 1,
                'fecha_creacion'      => $ahora,
                'fecha_actualizacion' => null,
            ],
        ]);
    }
}
