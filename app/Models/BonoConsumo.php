<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Modelo BonoConsumo — Registra cada vez que el camarero canjea una bebida de un bono.
 * Cada registro = 1 bebida consumida. El tipo de producto se elige en el momento del canje.
 */
class BonoConsumo extends Model
{
    protected $table = 'bono_consumos';

    protected $fillable = [
        'bono_compra_id',
        'tipo_producto',
        'camarero_id',
    ];

    // El bono del que se descontó esta consumición
    public function bonoCompra()
    {
        return $this->belongsTo(BonoCompra::class, 'bono_compra_id');
    }

    // El camarero (organizador) que validó el canje
    public function camarero()
    {
        return $this->belongsTo(Organizador::class, 'camarero_id');
    }
}
