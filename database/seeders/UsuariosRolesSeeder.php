<?php

namespace Database\Seeders;

use App\Models\Empresa;
use App\Models\Organizador;
use App\Models\Usuario;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

/**
 * Seeder de usuarios de prueba con todos los roles del sistema.
 *
 * Crea un usuario por cada rol:
 *   - admin@vibez.test       → administrador    (es_admin = 1)
 *   - empresa@vibez.test     → empresa          (fila en tabla empresas)
 *   - organizador@vibez.test → organizador      (fila en tabla organizadores)
 *   - usuario@vibez.test     → usuario regular  (sin registros extra)
 *
 * Contraseña de todos: password123
 *
 * Uso:
 *   php artisan db:seed --class=UsuariosRolesSeeder
 */
class UsuariosRolesSeeder extends Seeder
{
    public function run(): void
    {
        $ahora = now();
        $password = Hash::make('password123');

        /* ——————————————————————————————
           1. ADMINISTRADOR
           —————————————————————————————— */
        Usuario::updateOrCreate(
            ['email' => 'admin@vibez.test'],
            [
                'nombre'           => 'Admin',
                'apellido1'        => 'VIBEZ',
                'apellido2'        => 'Sistema',
                'password_hash'    => $password,
                'es_admin'         => 1,
                'estado'           => 1,
                'email_verificado' => 1,
                'fecha_creacion'   => $ahora,
            ]
        );

        /* ——————————————————————————————
           2. EMPRESA (propietaria de empresa)
           —————————————————————————————— */
        $usuarioEmpresa = Usuario::updateOrCreate(
            ['email' => 'empresa@vibez.test'],
            [
                'nombre'           => 'Laura',
                'apellido1'        => 'Empresa',
                'apellido2'        => 'Test',
                'password_hash'    => $password,
                'es_admin'         => 0,
                'estado'           => 1,
                'email_verificado' => 1,
                'fecha_creacion'   => $ahora,
            ]
        );

        // Crear o actualizar la fila en empresas para este usuario
        Empresa::updateOrCreate(
            ['usuario_id' => $usuarioEmpresa->id],
            [
                'nombre_empresa' => 'VIBEZ Producciones S.L.',
                'nif_cif'        => 'B12345678',
                'descripcion'    => 'Empresa colaboradora y patrocinadora de eventos VIBEZ.',
                'estado'         => 1,
                'fecha_creacion' => $ahora,
            ]
        );

        /* ——————————————————————————————
           3. ORGANIZADOR (gestiona eventos para la empresa anterior)
           —————————————————————————————— */
        $usuarioOrganizador = Usuario::updateOrCreate(
            ['email' => 'organizador@vibez.test'],
            [
                'nombre'           => 'Marc',
                'apellido1'        => 'Organizador',
                'apellido2'        => 'Test',
                'password_hash'    => $password,
                'es_admin'         => 0,
                'estado'           => 1,
                'email_verificado' => 1,
                'fecha_creacion'   => $ahora,
            ]
        );

        // Para crear un organizador necesitamos una empresa (FK obligatoria)
        $empresa = Empresa::where('usuario_id', $usuarioEmpresa->id)->first();

        Organizador::updateOrCreate(
            ['usuario_id' => $usuarioOrganizador->id],
            [
                'empresa_id'     => $empresa->id,
                'estado'         => 1,
                'fecha_creacion' => $ahora,
            ]
        );

        /* ——————————————————————————————
           4. USUARIO REGULAR
           —————————————————————————————— */
        Usuario::updateOrCreate(
            ['email' => 'usuario@vibez.test'],
            [
                'nombre'           => 'Pau',
                'apellido1'        => 'Usuario',
                'apellido2'        => 'Test',
                'password_hash'    => $password,
                'es_admin'         => 0,
                'estado'           => 1,
                'email_verificado' => 1,
                'fecha_creacion'   => $ahora,
            ]
        );

        $this->command->info('✓ Usuarios de prueba creados:');
        $this->command->table(
            ['Email', 'Rol', 'Contraseña'],
            [
                ['admin@vibez.test',       'admin',       'password123'],
                ['empresa@vibez.test',     'empresa',     'password123'],
                ['organizador@vibez.test', 'organizador', 'password123'],
                ['usuario@vibez.test',     'usuario',     'password123'],
            ]
        );
    }
}
