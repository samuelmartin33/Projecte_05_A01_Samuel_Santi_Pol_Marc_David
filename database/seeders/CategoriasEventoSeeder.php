<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategoriasEventoSeeder extends Seeder
{
    public function run(): void
    {
        $ahora = now();

        DB::table('categorias_evento')->insert([
            ['nombre' => 'Música', 'descripcion' => 'Conciertos, festivales y eventos musicales', 'icono_url' => null, 'estado' => 1, 'fecha_creacion' => $ahora, 'fecha_actualizacion' => null],
            ['nombre' => 'Cultura', 'descripcion' => 'Teatro, exposiciones, museos y arte', 'icono_url' => null, 'estado' => 1, 'fecha_creacion' => $ahora, 'fecha_actualizacion' => null],
            ['nombre' => 'Deporte', 'descripcion' => 'Competiciones, torneos y eventos deportivos', 'icono_url' => null, 'estado' => 1, 'fecha_creacion' => $ahora, 'fecha_actualizacion' => null],
            ['nombre' => 'Gastronomía', 'descripcion' => 'Ferias de comida, catas y eventos culinarios', 'icono_url' => null, 'estado' => 1, 'fecha_creacion' => $ahora, 'fecha_actualizacion' => null],
            ['nombre' => 'Networking', 'descripcion' => 'Eventos profesionales y de networking', 'icono_url' => null, 'estado' => 1, 'fecha_creacion' => $ahora, 'fecha_actualizacion' => null],
            ['nombre' => 'Tecnología', 'descripcion' => 'Conferencias, hackathons y eventos tech', 'icono_url' => null, 'estado' => 1, 'fecha_creacion' => $ahora, 'fecha_actualizacion' => null],
            ['nombre' => 'Moda', 'descripcion' => 'Desfiles, showrooms y eventos de moda', 'icono_url' => null, 'estado' => 1, 'fecha_creacion' => $ahora, 'fecha_actualizacion' => null],
            ['nombre' => 'Infantil', 'descripcion' => 'Actividades y espectáculos para niños', 'icono_url' => null, 'estado' => 1, 'fecha_creacion' => $ahora, 'fecha_actualizacion' => null],
        ]);
    }
}
