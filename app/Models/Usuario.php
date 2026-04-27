<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
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

    /* ——— Relaciones ——— */

    /**
     * Relación con la tabla organizadores.
     * Si existe una fila con este usuario_id, el usuario tiene rol organizador.
     */
    public function organizador(): HasOne
    {
        return $this->hasOne(Organizador::class, 'usuario_id');
    }

    /**
     * Relación con la tabla empresas.
     * Si existe una fila con este usuario_id, el usuario es propietario de una empresa.
     */
    public function empresa(): HasOne
    {
        return $this->hasOne(Empresa::class, 'usuario_id');
    }

    /**
     * Eventos marcados como favoritos por el usuario.
     */
    public function favoritos(): BelongsToMany
    {
        return $this->belongsToMany(Evento::class, 'eventos_favoritos', 'usuario_id', 'evento_id')
            ->wherePivot('estado', 1)
            ->withPivot(['estado', 'fecha_creacion', 'fecha_actualizacion']);
    }

    /* ——— Helpers de rol ——— */

    /**
     * Comprueba si el usuario tiene el rol indicado.
     *
     * Los roles se derivan de la estructura existente de la BD:
     *   - admin      → columna es_admin = 1
     *   - organizador → existe fila en tabla organizadores (usuario_id = este usuario)
     *   - empresa    → existe fila en tabla empresas    (usuario_id = este usuario)
     *   - usuario    → ninguno de los anteriores
     *
     * Eloquent cachea las relaciones en el objeto, así que llamadas repetidas
     * a isOrganizador() / isEmpresa() no generan consultas adicionales.
     *
     * @param  string  $role  'admin' | 'organizador' | 'empresa' | 'usuario'
     */
    public function hasRole(string $role): bool
    {
        return match($role) {
            'admin'       => $this->isAdmin(),
            'organizador' => $this->isOrganizador(),
            'empresa'     => $this->isEmpresa(),
            'usuario'     => $this->isUsuario(),
            default       => false,
        };
    }

    /** Devuelve true si el usuario es administrador (es_admin = 1). */
    public function isAdmin(): bool
    {
        return (bool) $this->es_admin;
    }

    /**
     * Devuelve true si el usuario tiene un registro activo en la tabla organizadores.
     * Acceder a $this->organizador usa lazy loading con caché en el objeto.
     */
    public function isOrganizador(): bool
    {
        return $this->organizador !== null
            && (int) $this->organizador->estado === 1;
    }

    /**
     * Devuelve true si el usuario es propietario de una empresa en la tabla empresas.
     * Acceder a $this->empresa usa lazy loading con caché en el objeto.
     */
    public function isEmpresa(): bool
    {
        return $this->empresa !== null
            && (int) $this->empresa->estado === 1;
    }

    /**
     * Devuelve true si el usuario es un usuario regular
     * (sin admin, sin empresa, sin organizador).
     */
    public function isUsuario(): bool
    {
        return ! $this->isAdmin()
            && ! $this->isOrganizador()
            && ! $this->isEmpresa();
    }
}
