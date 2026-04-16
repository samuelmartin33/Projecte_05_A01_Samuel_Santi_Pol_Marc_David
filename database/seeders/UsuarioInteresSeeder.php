<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UsuarioInteresSeeder extends Seeder
{
    public function run(): void
    {
        $ahora = now();

        // Pablo (id=5) apunta intereses: Música electrónica (1), Gastronomía (7), Tecnología (9)
        // Carlos (id=3) apunta intereses: Música electrónica (1), Rock y metal (2), Networking (10)
        DB::table('usuario_interes')->insert([
            ['usuario_id' => 5, 'interes_id' => 1, 'estado' => 1, 'fecha_creacion' => $ahora, 'fecha_actualizacion' => null],
            ['usuario_id' => 5, 'interes_id' => 7, 'estado' => 1, 'fecha_creacion' => $ahora, 'fecha_actualizacion' => null],
            ['usuario_id' => 5, 'interes_id' => 9, 'estado' => 1, 'fecha_creacion' => $ahora, 'fecha_actualizacion' => null],
            ['usuario_id' => 3, 'interes_id' => 1, 'estado' => 1, 'fecha_creacion' => $ahora, 'fecha_actualizacion' => null],
            ['usuario_id' => 3, 'interes_id' => 2, 'estado' => 1, 'fecha_creacion' => $ahora, 'fecha_actualizacion' => null],
            ['usuario_id' => 3, 'interes_id' => 10, 'estado' => 1, 'fecha_creacion' => $ahora, 'fecha_actualizacion' => null],
        ]);
    }
}
