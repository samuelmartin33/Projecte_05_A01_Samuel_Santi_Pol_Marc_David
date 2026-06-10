<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Modelo ProductoBarra — Producto del stock de la barra.
 *
 * El stock es POR EMPRESA (campo empresa_id), no por evento.
 * Antes usaba evento_id, pero se cambió para poder reutilizar el mismo
 * inventario en varios eventos de la misma empresa sin duplicar productos.
 *
 * Flujo de stock:
 *   - Sube: cuando el camarero paga un PedidoProveedor (confirmarPagoPedido)
 *   - Baja: cuando el camarero canjea un bono (CanjeBonoController@validar)
 *   - Alerta: stockBajo() devuelve true si stock ≤ 2 → badge en navbar
 *
 * El precio de reposición al proveedor es siempre 2.50 €/ud (PRECIO_PROVEEDOR).
 */
class ProductoBarra extends Model
{
    protected $table = 'productos_barra';

    protected $fillable = [
        'empresa_id',   // propietaria del stock (no el evento)
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

    // Precio fijo que se usa para calcular el total de cada PedidoProveedor
    const PRECIO_PROVEEDOR = 2.50;

    // La empresa propietaria del producto
    public function empresa()
    {
        return $this->belongsTo(Empresa::class, 'empresa_id');
    }

    // Pedidos de reposición generados para este producto
    public function pedidos()
    {
        return $this->hasMany(PedidoProveedor::class, 'producto_barra_id');
    }

    // true si stock ≤ 2 → se usa en la navbar para mostrar badge de alerta
    public function stockBajo(): bool
    {
        return $this->stock <= 2;
    }
}
