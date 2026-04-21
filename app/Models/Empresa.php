<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Empresa extends Model
{
    protected $table = 'empresas';

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

    public function usuario(): BelongsTo
    {
        return $this->belongsTo(Usuario::class, 'usuario_id');
    }

    public function organizadores(): HasMany
    {
        return $this->hasMany(Organizador::class, 'empresa_id');
    }
}
