<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Modelo Empresa
 *
 * Representa una empresa o entidad colaboradora propietaria de un usuario.
 * Tabla: empresas
 */
class Empresa extends Model
{
    protected $table = 'empresas';

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
}
