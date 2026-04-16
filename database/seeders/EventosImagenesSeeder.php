<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EventosImagenesSeeder extends Seeder
{
    public function run(): void
    {
        $ahora = now();

        DB::table('eventos_imagenes')->insert([
            ['evento_id' => 1, 'imagen_url' => 'https://images.vibez.com/eventos/1/portada.jpg', 'descripcion' => 'Imagen principal del festival', 'es_portada' => 1, 'estado' => 1, 'fecha_creacion' => $ahora, 'fecha_actualizacion' => null],
            ['evento_id' => 1, 'imagen_url' => 'https://images.vibez.com/eventos/1/escenario.jpg', 'descripcion' => 'Escenario principal', 'es_portada' => 0, 'estado' => 1, 'fecha_creacion' => $ahora, 'fecha_actualizacion' => null],
            ['evento_id' => 2, 'imagen_url' => 'https://images.vibez.com/eventos/2/portada.jpg', 'descripcion' => 'Imagen del summit', 'es_portada' => 1, 'estado' => 1, 'fecha_creacion' => $ahora, 'fecha_actualizacion' => null],
        ]);
    }
}
