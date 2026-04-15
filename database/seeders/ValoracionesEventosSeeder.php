<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ValoracionesEventosSeeder extends Seeder
{
    public function run(): void
    {
        $ahora = now();

        DB::table('valoraciones_eventos')->insert([
            [
                'usuario_id'          => 5,
                'evento_id'           => 1,
                'puntuacion'          => 5,
                'comentario'          => 'Experiencia increíble, una organización impecable y una programación de primer nivel.',
                'estado'              => 1,
                'fecha_creacion'      => $ahora,
                'fecha_actualizacion' => null,
            ],
        ]);
    }
}
