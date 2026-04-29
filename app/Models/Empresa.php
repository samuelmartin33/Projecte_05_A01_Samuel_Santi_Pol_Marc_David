<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

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

    /** Todos los eventos creados por los organizadores de esta empresa. */
    public function eventos(): HasManyThrough
    {
        return $this->hasManyThrough(
            \App\Models\Evento::class,
            Organizador::class,
            'empresa_id',     // FK en organizadores → empresas.id
            'organizador_id', // FK en eventos → organizadores.id
            'id',
            'id'
        );
    }

    /** Todas las ofertas publicadas por los organizadores de esta empresa. */
    public function ofertas(): HasManyThrough
    {
        return $this->hasManyThrough(
            BolsaOfertaTrabajo::class,
            Organizador::class,
            'empresa_id',     // FK en organizadores → empresas.id
            'organizador_id', // FK en bolsa_ofertas_trabajo → organizadores.id
            'id',
            'id'
        );
    }
}
