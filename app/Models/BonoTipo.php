<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Modelo BonoTipo — Define los tipos de bono disponibles (2, 5 o 10 bebidas).
 * Vibez gestiona estos tipos desde el panel de administrador.
 */
class BonoTipo extends Model
{
    protected $table = 'bonos_tipos';

    protected $fillable = [
        'cantidad_bebidas',
        'precio',
        'descripcion',
        'activo',
    ];

    protected $casts = [
        'precio' => 'float',
        'activo' => 'boolean',
    ];

    // Un tipo de bono puede tener muchas compras realizadas por clientes
    public function compras()
    {
        return $this->hasMany(BonoCompra::class, 'bono_tipo_id');
    }
}
