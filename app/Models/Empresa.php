<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
<<<<<<< HEAD
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Modelo Empresa
 *
 * Representa una empresa o entidad colaboradora propietaria de un usuario.
 * Tabla: empresas
=======

/**
 * Modelo para la tabla empresas.
 * Almacena la información de las organizaciones que crean eventos.
>>>>>>> 94a132a4f2ad280543f6421d5139cf3709db3dfc
 */
class Empresa extends Model
{
    protected $table = 'empresas';
<<<<<<< HEAD

    /** La tabla no usa timestamps estándar de Laravel */
    public $timestamps = false;

    protected $fillable = [
        'usuario_id',
        'nombre_empresa',
        'razon_social',
        'nif_cif',
        'descripcion',
        'logo_url',
        'sitio_web',
        'telefono_contacto',
        'direccion',
        'estado',
        'fecha_creacion',
        'fecha_actualizacion',
    ];

    /** Relación: la empresa pertenece a un usuario (propietario) */
    public function usuario(): BelongsTo
    {
        return $this->belongsTo(Usuario::class, 'usuario_id');
    }

    /** Relación: la empresa tiene muchos organizadores */
    public function organizadores(): HasMany
    {
        return $this->hasMany(Organizador::class, 'empresa_id');
    }
=======
    public $timestamps = false;

    protected $fillable = [
        'usuario_id', 'nombre_empresa', 'razon_social', 'nif_cif',
        'descripcion', 'logo_url', 'sitio_web', 'telefono_contacto',
        'direccion', 'estado'
    ];
>>>>>>> 94a132a4f2ad280543f6421d5139cf3709db3dfc
}
