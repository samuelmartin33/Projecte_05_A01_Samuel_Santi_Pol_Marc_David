<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Modelo PedidoProveedor — Pedido de reposición de stock generado por el camarero.
 * Al pagar el pedido (Stripe), el stock del producto se incrementa y se envía email de confirmación.
 */
class PedidoProveedor extends Model
{
    protected $table = 'pedidos_proveedor';

    protected $fillable = [
        'producto_barra_id',
        'cantidad',
        'precio_total',
        'estado',
    ];

    protected $casts = [
        'cantidad'    => 'integer',
        'precio_total' => 'float',
    ];

    // El producto de barra que se está reponiendo
    public function producto()
    {
        return $this->belongsTo(ProductoBarra::class, 'producto_barra_id');
    }

    // True si el pedido ya ha sido pagado
    public function estaPagado(): bool
    {
        return $this->estado === 'pagado';
    }
}
