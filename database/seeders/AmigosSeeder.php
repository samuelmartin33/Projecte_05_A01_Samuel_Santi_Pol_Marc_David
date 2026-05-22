<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

/**
 * Siembra relaciones de amistad aceptadas (estado=1) entre usuarios.
 * Cada fila representa una amistad bidireccional: el controlador busca
 * registros donde el usuario sea solicitante O receptor.
 */
class AmigosSeeder extends Seeder
{
    public function run(): void
    {
        $ahora = now();

        DB::table('amigos')->insert([

            // Pablo(5) y María(4) — amigos desde el primer evento
            [
                'solicitante_id'      => 5,
                'receptor_id'         => 4,
                'estado'              => 1,
                'fecha_creacion'      => $ahora,
                'fecha_actualizacion' => $ahora,
            ],

            // Pablo(5) y Carlos(3) — asistente conoce al organizador
            [
                'solicitante_id'      => 5,
                'receptor_id'         => 3,
                'estado'              => 1,
                'fecha_creacion'      => $ahora,
                'fecha_actualizacion' => $ahora,
            ],

            // Ana(6) y Pablo(5) — amigos del entorno musical
            [
                'solicitante_id'      => 6,
                'receptor_id'         => 5,
                'estado'              => 1,
                'fecha_creacion'      => $ahora,
                'fecha_actualizacion' => $ahora,
            ],

            // Diego(7) y Pablo(5) — se conocieron en un festival
            [
                'solicitante_id'      => 7,
                'receptor_id'         => 5,
                'estado'              => 1,
                'fecha_creacion'      => $ahora,
                'fecha_actualizacion' => $ahora,
            ],

            // Ana(6) y María(4) — amigas, se conocen por la fotografía
            [
                'solicitante_id'      => 4,
                'receptor_id'         => 6,
                'estado'              => 1,
                'fecha_creacion'      => $ahora,
                'fecha_actualizacion' => $ahora,
            ],

            // Carlos(3) y María(4) — compañeros de trabajo en eventos
            [
                'solicitante_id'      => 3,
                'receptor_id'         => 4,
                'estado'              => 1,
                'fecha_creacion'      => $ahora,
                'fecha_actualizacion' => $ahora,
            ],

            // Diego(7) y Ana(6) — amigos de la escena techno
            [
                'solicitante_id'      => 6,
                'receptor_id'         => 7,
                'estado'              => 1,
                'fecha_creacion'      => $ahora,
                'fecha_actualizacion' => $ahora,
            ],

            // Sofía(8) y Ana(6) — colaboradoras en contenido de eventos
            [
                'solicitante_id'      => 8,
                'receptor_id'         => 6,
                'estado'              => 1,
                'fecha_creacion'      => $ahora,
                'fecha_actualizacion' => $ahora,
            ],

            // Sofía(8) y Pablo(5) — se siguen mutuamente
            [
                'solicitante_id'      => 5,
                'receptor_id'         => 8,
                'estado'              => 1,
                'fecha_creacion'      => $ahora,
                'fecha_actualizacion' => $ahora,
            ],

            // Solicitud pendiente: Diego(7) → María(4) (estado=0)
            [
                'solicitante_id'      => 7,
                'receptor_id'         => 4,
                'estado'              => 0,
                'fecha_creacion'      => $ahora,
                'fecha_actualizacion' => null,
            ],

        ]);
    }
}
