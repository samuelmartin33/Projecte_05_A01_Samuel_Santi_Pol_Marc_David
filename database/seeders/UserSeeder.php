<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Crea los usuarios de prueba para el sistema de autenticación.
     *
     * Usa firstOrCreate para que el seeder sea idempotente:
     * se puede ejecutar varias veces sin duplicar registros.
     *
     * El modelo User tiene el cast 'hashed' en password,
     * por lo que las contraseñas se encriptan automáticamente.
     *
     * Credenciales:
     *   admin@vibez.com   / password123
     *   samuel@vibez.com  / password123
     */
    public function run(): void
    {
        // Usuario administrador de prueba
        User::firstOrCreate(
            ['email' => 'admin@vibez.com'],
            [
                'name'              => 'Admin VIBEZ',
                'password'          => 'password123',
                'email_verified_at' => now(),
            ]
        );

        // Usuario estándar de prueba
        User::firstOrCreate(
            ['email' => 'samuel@vibez.com'],
            [
                'name'              => 'Samuel Martín',
                'password'          => 'password123',
                'email_verified_at' => now(),
            ]
        );

        $this->command->info('✓ UserSeeder: 2 usuarios de prueba creados/verificados.');
    }
}
