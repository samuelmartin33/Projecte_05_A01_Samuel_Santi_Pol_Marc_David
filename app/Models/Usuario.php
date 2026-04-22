<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Usuario extends Authenticatable
{
    use Notifiable;

    /* ——— Tabla y timestamps ——— */
    protected $table      = 'usuarios';
    public    $timestamps = false;          // la tabla usa fecha_creacion / fecha_actualizacion

    /* ——— Campos asignables en masa ——— */
    protected $fillable = [
        'nombre',
        'apellido1',
        'apellido2',
        'email',
        'password_hash',
        'foto_url',
        'biografia',
        'mood',
        'fecha_nacimiento',
        'telefono',
        'email_verificado',
        'tipo_cuenta',
        'estado_registro',
        'es_admin',
        'ultimo_acceso',
        'estado',
        'fecha_creacion',
        'fecha_actualizacion',
    ];

    /* ——— Campos ocultos en serialización ——— */
    protected $hidden = ['password_hash'];

    /* ——— Casts ——— */
    protected function casts(): array
    {
        return [
            'password_hash'    => 'hashed',
            'email_verificado' => 'boolean',
            'es_admin'         => 'boolean',
        ];
    }


    /* ——— Métodos que Laravel Auth necesita sobreescribir ——— */

    /**
     * Indica qué columna contiene la contraseña hasheada.
     * Auth::attempt() y Hash::check() usan este nombre.
     */
    public function getAuthPasswordName(): string
    {
        return 'password_hash';
    }

    /**
     * Desactiva el "remember me": la tabla no tiene columna remember_token.
     */
    public function getRememberTokenName(): ?string
    {
        return null;
    }
}
