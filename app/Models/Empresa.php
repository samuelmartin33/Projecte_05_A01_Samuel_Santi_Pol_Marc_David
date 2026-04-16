<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Modelo para la tabla empresas.
 * Almacena la información de las organizaciones que crean eventos.
 */
class Empresa extends Model
{
    protected $table = 'empresas';
    public $timestamps = false;

    protected $fillable = [
        'usuario_id', 'nombre_empresa', 'razon_social', 'nif_cif',
        'descripcion', 'logo_url', 'sitio_web', 'telefono_contacto',
        'direccion', 'estado'
    ];
}
