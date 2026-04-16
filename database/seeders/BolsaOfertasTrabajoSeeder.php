<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BolsaOfertasTrabajoSeeder extends Seeder
{
    public function run(): void
    {
        $ahora = now();

        DB::table('bolsa_ofertas_trabajo')->insert([
            [
                'organizador_id'       => 1,
                'evento_id'            => 1,
                'categoria_trabajo_id' => 5,
                'titulo'               => 'Fotógrafo/a para Vibez Summer Festival 2026',
                'descripcion'          => 'Buscamos fotógrafo/a con experiencia en eventos de música para cobertura completa del festival.',
                'requisitos'           => 'Mínimo 2 años de experiencia. Equipo propio. Portfolio demostrable.',
                'ubicacion'            => 'Madrid',
                'salario_min'          => 300.00,
                'salario_max'          => 500.00,
                'fecha_inicio_trabajo' => '2026-07-20 16:00:00',
                'fecha_fin_trabajo'    => '2026-07-21 08:00:00',
                'vacantes'             => 3,
                'estado'               => 1,
                'fecha_creacion'       => $ahora,
                'fecha_actualizacion'  => null,
            ],
            [
                'organizador_id'       => 1,
                'evento_id'            => 1,
                'categoria_trabajo_id' => 4,
                'titulo'               => 'Personal de seguridad para festival',
                'descripcion'          => 'Se necesita personal de seguridad para control de acceso y vigilancia durante el festival.',
                'requisitos'           => 'Habilitación de seguridad vigente. Buena presencia. Disponibilidad horaria total.',
                'ubicacion'            => 'Madrid',
                'salario_min'          => 200.00,
                'salario_max'          => 350.00,
                'fecha_inicio_trabajo' => '2026-07-20 14:00:00',
                'fecha_fin_trabajo'    => '2026-07-21 10:00:00',
                'vacantes'             => 10,
                'estado'               => 1,
                'fecha_creacion'       => $ahora,
                'fecha_actualizacion'  => null,
            ],
        ]);
    }
}
