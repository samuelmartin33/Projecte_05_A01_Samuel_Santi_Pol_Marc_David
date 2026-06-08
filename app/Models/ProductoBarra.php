<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Modelo ProductoBarra — Producto del stock de la barra de un evento Fiesta.
 * El camarero gestiona este stock. Cuando queda stock <= 2 se lanza una alerta.
 */
class ProductoBarra extends Model
{
    protected $table = 'productos_barra';

    protected $fillable = [
        'evento_id',
        'nombre',
        'tipo_producto',
        'proveedor',
        'stock',
        'precio_unitario',
    ];

    protected $casts = [
        'stock'           => 'integer',
        'precio_unitario' => 'float',
    ];

    // El evento Fiesta al que pertenece este producto
    public function evento()
    {
        return $this->belongsTo(Evento::class, 'evento_id');
    }

    // Pedidos de reposición generados para este producto
    public function pedidos()
    {
        return $this->hasMany(PedidoProveedor::class, 'producto_barra_id');
    }

    // True si el stock está bajo (≤ 2 unidades → disparar alerta)
    public function stockBajo(): bool
    {
        return $this->stock <= 2;
    }
}
