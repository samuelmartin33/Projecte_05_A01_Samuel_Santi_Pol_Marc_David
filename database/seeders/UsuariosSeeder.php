<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UsuariosSeeder extends Seeder
{
    public function run(): void
    {
        $ahora = now();

        DB::table('usuarios')->insert([

            /* ── Tipo: Admin (id=1) ────────────────────────────── */
            [
                'nombre'            => 'Admin',
                'apellido1'         => 'Vibez',
                'apellido2'         => null,
                'email'             => 'admin@vibez.com',
                'password_hash'     => bcrypt('Admin1234!'),
                'foto_url'          => null,
                'biografia'         => 'Administrador de la plataforma Vibez.',
                'fecha_nacimiento'  => '1985-01-15',
                'telefono'          => '600000000',
                'email_verificado'  => 1,
                'tipo_cuenta'       => 'cliente',
                'estado_registro'   => 'aprobado',
                'es_admin'          => 1,
                'ultimo_acceso'     => $ahora,
                'estado'            => 1,
                'fecha_creacion'    => $ahora,
                'fecha_actualizacion' => null,
            ],

            /* ── Tipo: Empresa (id=2) — propietaria de SoundWave ── */
            [
                'nombre'            => 'Laura',
                'apellido1'         => 'Martínez',
                'apellido2'         => 'García',
                'email'             => 'laura.empresa@vibez.com',
                'password_hash'     => bcrypt('Laura1234!'),
                'foto_url'          => null,
                'biografia'         => 'Responsable de eventos en SoundWave Events.',
                'fecha_nacimiento'  => '1990-06-22',
                'telefono'          => '611222333',
                'email_verificado'  => 1,
                'tipo_cuenta'       => 'empresa',
                'estado_registro'   => 'aprobado',
                'es_admin'          => 0,
                'ultimo_acceso'     => $ahora,
                'estado'            => 1,
                'fecha_creacion'    => $ahora,
                'fecha_actualizacion' => null,
            ],

            /* ── Tipo: Organizador (id=3) — staff de SoundWave ─── */
            [
                'nombre'            => 'Carlos',
                'apellido1'         => 'Ruiz',
                'apellido2'         => 'López',
                'email'             => 'carlos.organizador@vibez.com',
                'password_hash'     => bcrypt('Carlos1234!'),
                'foto_url'          => null,
                'biografia'         => 'Organizador de eventos de música electrónica.',
                'fecha_nacimiento'  => '1992-03-10',
                'telefono'          => '622333444',
                'email_verificado'  => 1,
                'tipo_cuenta'       => 'empresa',
                'estado_registro'   => 'aprobado',
                'es_admin'          => 0,
                'ultimo_acceso'     => $ahora,
                'estado'            => 1,
                'fecha_creacion'    => $ahora,
                'fecha_actualizacion' => null,
            ],

            /* ── Tipo: Trabajador (id=4) — fotógrafa freelance ─── */
            [
                'nombre'            => 'María',
                'apellido1'         => 'Sánchez',
                'apellido2'         => 'Pérez',
                'email'             => 'maria.trabajadora@vibez.com',
                'password_hash'     => bcrypt('Maria1234!'),
                'foto_url'          => null,
                'biografia'         => 'Fotógrafa freelance especializada en eventos.',
                'fecha_nacimiento'  => '1995-11-05',
                'telefono'          => '633444555',
                'email_verificado'  => 1,
                'tipo_cuenta'       => 'cliente',
                'estado_registro'   => 'aprobado',
                'es_admin'          => 0,
                'ultimo_acceso'     => $ahora,
                'estado'            => 1,
                'fecha_creacion'    => $ahora,
                'fecha_actualizacion' => null,
            ],

            /* ── Tipo: Asistente (id=5) — usuario estándar ──────── */
            [
                'nombre'            => 'Pablo',
                'apellido1'         => 'Fernández',
                'apellido2'         => 'Torres',
                'email'             => 'pablo.asistente@vibez.com',
                'password_hash'     => bcrypt('Pablo1234!'),
                'foto_url'          => null,
                'biografia'         => 'Aficionado a la música y los festivales.',
                'fecha_nacimiento'  => '1998-07-18',
                'telefono'          => '644555666',
                'email_verificado'  => 1,
                'tipo_cuenta'       => 'cliente',
                'estado_registro'   => 'aprobado',
                'es_admin'          => 0,
                'ultimo_acceso'     => $ahora,
                'estado'            => 1,
                'fecha_creacion'    => $ahora,
                'fecha_actualizacion' => null,
            ],
            // Empresa con Stripe Connect activo (CarniaFest) — usuario_id=6
            [
                'nombre'            => 'Marc',
                'apellido1'         => 'Navarro',
                'apellido2'         => null,
                'email'             => 'mnavarro.landingpages@gmail.com',
                'password_hash'     => bcrypt('qwe123QWE'),
                'foto_url'          => null,
                'biografia'         => null,
                'fecha_nacimiento'  => '2005-06-18',
                'telefono'          => '662394493',
                'email_verificado'  => 1,
                'tipo_cuenta'       => 'empresa',
                'estado_registro'   => 'aprobado',
                'es_admin'          => 0,
                'ultimo_acceso'     => $ahora,
                'estado'            => 1,
                'fecha_creacion'    => $ahora,
                'fecha_actualizacion' => null,
            ],
            // Comprador de prueba para test Stripe — usuario_id=7
            [
                'nombre'            => 'Marc',
                'apellido1'         => 'Navarro',
                'apellido2'         => null,
                'email'             => 'marcnavarrojocs@gmail.com',
                'password_hash'     => bcrypt('qwe123QWE'),
                'foto_url'          => null,
                'biografia'         => null,
                'fecha_nacimiento'  => '2005-06-18',
                'telefono'          => null,
                'email_verificado'  => 1,
                'tipo_cuenta'       => 'cliente',
                'estado_registro'   => 'aprobado',
                'es_admin'          => 0,
                'ultimo_acceso'     => $ahora,
                'estado'            => 1,
                'fecha_creacion'    => $ahora,
                'fecha_actualizacion' => null,
            ],

            /* ── Tipo: Asistente (id=6) — amiga de la comunidad ── */
            [
                'nombre'              => 'Ana',
                'apellido1'           => 'Jiménez',
                'apellido2'           => 'Vega',
                'email'               => 'ana.jimenez@vibez.com',
                'password_hash'       => bcrypt('Ana12345!'),
                'foto_url'            => null,
                'biografia'           => 'Festival lover. Siempre con la cámara en mano y la sonrisa puesta.',
                'fecha_nacimiento'    => '2000-04-12',
                'telefono'            => '655666777',
                'email_verificado'    => 1,
                'es_admin'            => 0,
                'ultimo_acceso'       => $ahora,
                'estado'              => 1,
                'fecha_creacion'      => $ahora,
                'fecha_actualizacion' => null,
            ],

            /* ── Tipo: Asistente (id=7) — melómano urbano ───────── */
            [
                'nombre'              => 'Diego',
                'apellido1'           => 'Molina',
                'apellido2'           => 'Reyes',
                'email'               => 'diego.molina@vibez.com',
                'password_hash'       => bcrypt('Diego123!'),
                'foto_url'            => null,
                'biografia'           => 'Amante del techno, el house y los atardeceres con buena música.',
                'fecha_nacimiento'    => '2002-09-25',
                'telefono'            => '666777888',
                'email_verificado'    => 1,
                'es_admin'            => 0,
                'ultimo_acceso'       => $ahora,
                'estado'              => 1,
                'fecha_creacion'      => $ahora,
                'fecha_actualizacion' => null,
            ],

            /* ── Tipo: Trabajador extra (id=8) — videógrafa ──────── */
            [
                'nombre'              => 'Sofía',
                'apellido1'           => 'Castro',
                'apellido2'           => 'Morales',
                'email'               => 'sofia.castro@vibez.com',
                'password_hash'       => bcrypt('Sofia123!'),
                'foto_url'            => null,
                'biografia'           => 'Videógrafa de eventos y creadora de contenido digital.',
                'fecha_nacimiento'    => '1997-02-28',
                'telefono'            => '677888999',
                'email_verificado'    => 1,
                'es_admin'            => 0,
                'ultimo_acceso'       => $ahora,
                'estado'              => 1,
                'fecha_creacion'      => $ahora,
                'fecha_actualizacion' => null,
            ],

            /* ── Tipo: Portero/Staff (id=9) — acceso y seguridad ── */
            [
                'nombre'              => 'Javier',
                'apellido1'           => 'Ramos',
                'apellido2'           => 'Delgado',
                'email'               => 'javier.portero@vibez.com',
                'password_hash'       => bcrypt('Javier12!'),
                'foto_url'            => null,
                'biografia'           => 'Encargado de control de acceso en eventos de SoundWave.',
                'fecha_nacimiento'    => '1988-12-03',
                'telefono'            => '688999000',
                'email_verificado'    => 1,
                'es_admin'            => 0,
                'ultimo_acceso'       => $ahora,
                'estado'              => 1,
                'fecha_creacion'      => $ahora,
                'fecha_actualizacion' => null,
            ],

            /* ── Tipo: Empresa extra (id=10) — segunda promotora ── */
            [
                'nombre'              => 'Lucía',
                'apellido1'           => 'Navarro',
                'apellido2'           => 'Blanco',
                'email'               => 'lucia.promotora@vibez.com',
                'password_hash'       => bcrypt('Lucia123!'),
                'foto_url'            => null,
                'biografia'           => 'Fundadora de UrbanBeat Promotions, especialistas en eventos urbanos.',
                'fecha_nacimiento'    => '1987-08-17',
                'telefono'            => '699000111',
                'email_verificado'    => 1,
                'es_admin'            => 0,
                'ultimo_acceso'       => $ahora,
                'estado'              => 1,
                'fecha_creacion'      => $ahora,
                'fecha_actualizacion' => null,
            ],

        ]);
    }
}
