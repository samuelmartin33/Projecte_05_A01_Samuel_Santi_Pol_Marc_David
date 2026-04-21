<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Modelo para la tabla usuarios (tabla personalizada de VIBEZ).
 * Se llama UsuarioVibez para no colisionar con el User de Laravel
 * que apunta a la tabla 'users' (tabla por defecto del framework).
 */
class UsuarioVibez extends Model
{
    protected $table = 'usuarios';
    public $timestamps = false;

    protected $fillable = [
        'nombre', 'apellido1', 'apellido2', 'email',
        'foto_url', 'biografia', 'fecha_nacimiento',
        'telefono', 'estado'
    ];

    // Nunca exponer la contraseña en resultados JSON
    protected $hidden = ['password_hash'];
}
