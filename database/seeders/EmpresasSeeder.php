<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EmpresasSeeder extends Seeder
{
    public function run(): void
    {
        $ahora = now();

        // Laura (usuario_id=2) es la propietaria de la empresa
        DB::table('empresas')->insert([
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
        ]);
    }
}
