<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Modelo CuponUso — Registro de cada vez que se canjea un cupón.
 *
 * Cuando un usuario aplica un cupón en la compra, se crea un CuponUso
 * que vincula el cupón con el pedido resultante y guarda el descuento aplicado.
 *
 * @property int    $id
 * @property int    $cupon_id
 * @property int    $pedido_id
 * @property float  $descuento_aplicado   Importe en € descontado en ese pedido.
 * @property int    $estado
 */
class CuponUso extends Model
{
    protected $table      = 'cupones_uso';
    public    $timestamps = false;

    protected $fillable = [
        'cupon_id', 'pedido_id', 'descuento_aplicado',
        'estado', 'fecha_creacion', 'fecha_actualizacion',
    ];

    protected $casts = [
        'descuento_aplicado'  => 'float',
        'fecha_creacion'      => 'datetime',
        'fecha_actualizacion' => 'datetime',
    ];

    public function cupon()
    {
        return $this->belongsTo(Cupon::class, 'cupon_id');
    }

    public function pedido()
    {
        return $this->belongsTo(Pedido::class, 'pedido_id');
    }
}
