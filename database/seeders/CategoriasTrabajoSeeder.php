<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategoriasTrabajoSeeder extends Seeder
{
    public function run(): void
    {
        $ahora = now();

        DB::table('categorias_trabajo')->insert([
            ['nombre' => 'Camarero/a', 'descripcion' => 'Servicio de bebidas y atención en barra', 'estado' => 1, 'fecha_creacion' => $ahora, 'fecha_actualizacion' => null],
            ['nombre' => 'Técnico de sonido', 'descripcion' => 'Montaje y operación de equipos de audio', 'estado' => 1, 'fecha_creacion' => $ahora, 'fecha_actualizacion' => null],
            ['nombre' => 'Técnico de iluminación', 'descripcion' => 'Montaje y operación de equipos de luz', 'estado' => 1, 'fecha_creacion' => $ahora, 'fecha_actualizacion' => null],
            ['nombre' => 'Seguridad', 'descripcion' => 'Control de acceso y vigilancia en eventos', 'estado' => 1, 'fecha_creacion' => $ahora, 'fecha_actualizacion' => null],
            ['nombre' => 'Fotógrafo/a', 'descripcion' => 'Cobertura fotográfica de eventos', 'estado' => 1, 'fecha_creacion' => $ahora, 'fecha_actualizacion' => null],
            ['nombre' => 'Videógrafo/a', 'descripcion' => 'Grabación y edición de vídeo en eventos', 'estado' => 1, 'fecha_creacion' => $ahora, 'fecha_actualizacion' => null],
            ['nombre' => 'Auxiliar de producción', 'descripcion' => 'Apoyo general en la organización y montaje', 'estado' => 1, 'fecha_creacion' => $ahora, 'fecha_actualizacion' => null],
            ['nombre' => 'Decorador/a', 'descripcion' => 'Ambientación y decoración de espacios', 'estado' => 1, 'fecha_creacion' => $ahora, 'fecha_actualizacion' => null],
            ['nombre' => 'Community manager', 'descripcion' => 'Gestión de redes sociales en directo durante eventos', 'estado' => 1, 'fecha_creacion' => $ahora, 'fecha_actualizacion' => null],
            ['nombre' => 'Relaciones públicas', 'descripcion' => 'Promoción del evento y atención al público VIP', 'estado' => 1, 'fecha_creacion' => $ahora, 'fecha_actualizacion' => null],
        ]);
    }
}
