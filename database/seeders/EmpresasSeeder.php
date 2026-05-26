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
                'usuario_id'                => 2,
                'nombre_empresa'            => 'SoundWave Events S.L.',
                'razon_social'              => 'SoundWave Events Sociedad Limitada',
                'nif_cif'                   => 'B12345678',
                'descripcion'               => 'Empresa especializada en organización de eventos musicales y festivales.',
                'tipo_promotor'             => null,
                'tipo_empresa'              => null,
                'logo_url'                  => null,
                'sitio_web'                 => 'https://soundwaveevents.es',
                'telefono_contacto'         => '900100200',
                'direccion'                 => 'Calle Gran Vía 45, 28013 Madrid',
                'ciudad'                    => null,
                'codigo_postal'             => null,
                'provincia'                 => null,
                'pais'                      => null,
                'email_facturacion'         => null,
                'perfil_fiscal_completo'    => 0,
                'stripe_account_id'         => null,
                'stripe_onboarding_status'  => null,
                'stripe_charges_enabled'    => 0,
                'stripe_payouts_enabled'    => 0,
                'stripe_details_submitted'  => 0,
                'estado'                    => 1,
                'fecha_creacion'            => $ahora,
                'fecha_actualizacion'       => null,
            ],
            // Marc (usuario_id=6) — CarniaFest (empresa_id=2) — Stripe Connect activo
            [
                'usuario_id'                => 6,
                'nombre_empresa'            => 'CarniaFest',
                'razon_social'              => 'Carnia SL',
                'nif_cif'                   => 'A62003944',
                'descripcion'               => null,
                'tipo_promotor'             => 'sala_club',
                'tipo_empresa'              => 'sl',
                'logo_url'                  => null,
                'sitio_web'                 => null,
                'telefono_contacto'         => null,
                'direccion'                 => 'Carrer Longitudinal 7',
                'ciudad'                    => 'Barcelona',
                'codigo_postal'             => '08760',
                'provincia'                 => 'Barcelona',
                'pais'                      => 'España',
                'email_facturacion'         => 'mnavarro.landingpages@gmail.com',
                'perfil_fiscal_completo'    => 1,
                'stripe_account_id'         => 'acct_1TYrwHFFsWx2oApk',
                'stripe_onboarding_status'  => 'complete',
                'stripe_charges_enabled'    => 1,
                'stripe_payouts_enabled'    => 1,
                'stripe_details_submitted'  => 1,
                'estado'                    => 1,
                'fecha_creacion'            => $ahora,
                'fecha_actualizacion'       => null,
            ],
            // Lucía (usuario_id=12) — UrbanBeat Promotions (empresa_id=3)
            [
                'usuario_id'                => 12,
                'nombre_empresa'            => 'UrbanBeat Promotions S.L.',
                'razon_social'              => 'UrbanBeat Promotions Sociedad Limitada',
                'nif_cif'                   => 'B87654321',
                'descripcion'               => 'Promotora de eventos urbanos, conciertos de rap y cultura de calle.',
                'tipo_promotor'             => null,
                'tipo_empresa'              => 'sl',
                'logo_url'                  => null,
                'sitio_web'                 => 'https://urbanbeatpromotions.es',
                'telefono_contacto'         => '900200300',
                'direccion'                 => 'Calle Fuencarral 88, 28004 Madrid',
                'ciudad'                    => null,
                'codigo_postal'             => null,
                'provincia'                 => null,
                'pais'                      => null,
                'email_facturacion'         => null,
                'perfil_fiscal_completo'    => 0,
                'stripe_account_id'         => null,
                'stripe_onboarding_status'  => null,
                'stripe_charges_enabled'    => 0,
                'stripe_payouts_enabled'    => 0,
                'stripe_details_submitted'  => 0,
                'estado'                    => 1,
                'fecha_creacion'            => $ahora,
                'fecha_actualizacion'       => null,
            ],
        ]);
    }
}