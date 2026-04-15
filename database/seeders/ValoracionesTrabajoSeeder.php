<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ValoracionesTrabajoSeeder extends Seeder
{
    public function run(): void
    {
        $ahora = now();

        // El organizador valora a la trabajadora (dirección=1) tras finalizar el evento
        DB::table('valoraciones_trabajo')->insert([
            [
                'candidatura_id'      => 1,
                'direccion'           => 1,
                'puntuacion'          => 5,
                'comentario'          => 'Trabajo excelente, muy profesional y resolutiva durante todo el evento.',
                'estado'              => 1,
                'fecha_creacion'      => $ahora,
                'fecha_actualizacion' => null,
            ],
        ]);
    }
}
