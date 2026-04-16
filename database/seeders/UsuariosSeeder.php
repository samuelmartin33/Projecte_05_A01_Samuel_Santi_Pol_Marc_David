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
            // Admin de la plataforma
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
                'es_admin'          => 1,
                'ultimo_acceso'     => $ahora,
                'estado'            => 1,
                'fecha_creacion'    => $ahora,
                'fecha_actualizacion' => null,
            ],
            // Usuario empresa
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
                'es_admin'          => 0,
                'ultimo_acceso'     => $ahora,
                'estado'            => 1,
                'fecha_creacion'    => $ahora,
                'fecha_actualizacion' => null,
            ],
            // Organizador
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
                'es_admin'          => 0,
                'ultimo_acceso'     => $ahora,
                'estado'            => 1,
                'fecha_creacion'    => $ahora,
                'fecha_actualizacion' => null,
            ],
            // Trabajador
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
                'es_admin'          => 0,
                'ultimo_acceso'     => $ahora,
                'estado'            => 1,
                'fecha_creacion'    => $ahora,
                'fecha_actualizacion' => null,
            ],
            // Usuario asistente
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
                'es_admin'          => 0,
                'ultimo_acceso'     => $ahora,
                'estado'            => 1,
                'fecha_creacion'    => $ahora,
                'fecha_actualizacion' => null,
            ],
        ]);
    }
}
