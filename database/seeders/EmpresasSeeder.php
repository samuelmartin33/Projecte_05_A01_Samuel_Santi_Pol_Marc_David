<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EmpresasSeeder extends Seeder
{
    public function run(): void
    {
        $ahora = now();

        DB::table('empresas')->insert([
            // Laura (usuario_id=2) — SoundWave Events (empresa_id=1)
            [
                'usuario_id'          => 2,
                'nombre_empresa'      => 'SoundWave Events S.L.',
                'razon_social'        => 'SoundWave Events Sociedad Limitada',
                'nif_cif'             => 'B12345678',
                'descripcion'         => 'Empresa especializada en organización de eventos musicales y festivales.',
                'logo_url'            => null,
                'sitio_web'           => 'https://soundwaveevents.es',
                'telefono_contacto'   => '900100200',
                'direccion'           => 'Calle Gran Vía 45, 28013 Madrid',
                'estado'              => 1,
                'fecha_creacion'      => $ahora,
                'fecha_actualizacion' => null,
            ],
            // Lucía (usuario_id=10) — UrbanBeat Promotions (empresa_id=2)
            [
                'usuario_id'          => 10,
                'nombre_empresa'      => 'UrbanBeat Promotions S.L.',
                'razon_social'        => 'UrbanBeat Promotions Sociedad Limitada',
                'nif_cif'             => 'B87654321',
                'descripcion'         => 'Promotora de eventos urbanos, conciertos de rap y cultura de calle.',
                'logo_url'            => null,
                'sitio_web'           => 'https://urbanbeatpromotions.es',
                'telefono_contacto'   => '900200300',
                'direccion'           => 'Calle Fuencarral 88, 28004 Madrid',
                'estado'              => 1,
                'fecha_creacion'      => $ahora,
                'fecha_actualizacion' => null,
            ],
        ]);
    }
}
