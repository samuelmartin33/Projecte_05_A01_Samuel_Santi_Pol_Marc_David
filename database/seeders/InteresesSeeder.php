<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class InteresesSeeder extends Seeder
{
    public function run(): void
    {
        $ahora = now();

        DB::table('intereses')->insert([
            ['nombre' => 'Música electrónica', 'descripcion' => 'DJs, techno, house y géneros electrónicos', 'estado' => 1, 'fecha_creacion' => $ahora, 'fecha_actualizacion' => null],
            ['nombre' => 'Rock y metal', 'descripcion' => 'Conciertos de rock, metal y derivados', 'estado' => 1, 'fecha_creacion' => $ahora, 'fecha_actualizacion' => null],
            ['nombre' => 'Artes escénicas', 'descripcion' => 'Teatro, danza y performance', 'estado' => 1, 'fecha_creacion' => $ahora, 'fecha_actualizacion' => null],
            ['nombre' => 'Fotografía', 'descripcion' => 'Exposiciones y talleres de fotografía', 'estado' => 1, 'fecha_creacion' => $ahora, 'fecha_actualizacion' => null],
            ['nombre' => 'Fútbol', 'descripcion' => 'Partidos, torneos y eventos de fútbol', 'estado' => 1, 'fecha_creacion' => $ahora, 'fecha_actualizacion' => null],
            ['nombre' => 'Running', 'descripcion' => 'Carreras populares y maratones', 'estado' => 1, 'fecha_creacion' => $ahora, 'fecha_actualizacion' => null],
            ['nombre' => 'Gastronomía', 'descripcion' => 'Eventos culinarios y de restauración', 'estado' => 1, 'fecha_creacion' => $ahora, 'fecha_actualizacion' => null],
            ['nombre' => 'Vinos y catas', 'descripcion' => 'Catas de vinos, cervezas artesanas y maridajes', 'estado' => 1, 'fecha_creacion' => $ahora, 'fecha_actualizacion' => null],
            ['nombre' => 'Tecnología', 'descripcion' => 'Startups, IA y tendencias tecnológicas', 'estado' => 1, 'fecha_creacion' => $ahora, 'fecha_actualizacion' => null],
            ['nombre' => 'Networking profesional', 'descripcion' => 'Eventos de contactos y desarrollo profesional', 'estado' => 1, 'fecha_creacion' => $ahora, 'fecha_actualizacion' => null],
        ]);
    }
}
