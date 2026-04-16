<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CandidaturasTrabajoSeeder extends Seeder
{
    public function run(): void
    {
        $ahora = now();

        // María (trabajador_id=1) se candidatura a la oferta de fotógrafo (oferta_id=1)
        DB::table('candidaturas_trabajo')->insert([
            [
                'oferta_id'            => 1,
                'trabajador_id'        => 1,
                'estado_candidatura'   => 1,
                'carta_presentacion'   => 'Soy fotógrafa con más de 5 años de experiencia en festivales y eventos de música. Adjunto mi portfolio con trabajos recientes.',
                'cv_url'               => null,
                'estado'               => 1,
                'fecha_creacion'       => $ahora,
                'fecha_actualizacion'  => null,
            ],
        ]);
    }
}
