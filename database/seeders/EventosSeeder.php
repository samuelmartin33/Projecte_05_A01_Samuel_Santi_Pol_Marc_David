<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EventosSeeder extends Seeder
{
    public function run(): void
    {
        $ahora = now();

        DB::table('eventos')->insert([
            [
                'organizador_id'      => 1,
                'categoria_evento_id' => 1,
                'tipo_evento'         => 1,
                'titulo'              => 'Vibez Summer Festival 2026',
                'descripcion'         => 'El mayor festival de música electrónica del verano. Cuatro escenarios, más de 30 artistas y una experiencia única.',
                'fecha_inicio'        => '2026-07-20 18:00:00',
                'fecha_fin'           => '2026-07-21 06:00:00',
                'ubicacion_nombre'    => 'Recinto Ferial de Madrid',
                'ubicacion_direccion' => 'Av. del Partenón 5, 28042 Madrid',
                'latitud'             => 40.4617500,
                'longitud'            => -3.6887700,
                'precio_base'         => 45.00,
                'aforo_maximo'        => 5000,
                'aforo_actual'        => 0,
                'edad_minima'         => 18,
                'es_gratuito'         => 0,
                'url_externa'         => null,
                'contador_comparticiones' => 0,
                'estado'              => 1,
                'fecha_creacion'      => $ahora,
                'fecha_actualizacion' => null,
            ],
            [
                'organizador_id'      => 1,
                'categoria_evento_id' => 5,
                'tipo_evento'         => 2,
                'titulo'              => 'Networking Tech Summit Barcelona',
                'descripcion'         => 'Conferencia online para profesionales del sector tecnológico. Ponencias, mesas redondas y espacios de networking virtual.',
                'fecha_inicio'        => '2026-05-15 10:00:00',
                'fecha_fin'           => '2026-05-15 18:00:00',
                'ubicacion_nombre'    => null,
                'ubicacion_direccion' => null,
                'latitud'             => null,
                'longitud'            => null,
                'precio_base'         => 0.00,
                'aforo_maximo'        => 500,
                'aforo_actual'        => 0,
                'edad_minima'         => null,
                'es_gratuito'         => 1,
                'url_externa'         => 'https://techsummit.vibez.com',
                'contador_comparticiones' => 0,
                'estado'              => 1,
                'fecha_creacion'      => $ahora,
                'fecha_actualizacion' => null,
            ],
        ]);
    }
}
