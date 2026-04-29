<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pago extends Model
{
    protected $table = 'pagos';
    public $timestamps = false;

    protected $fillable = [
        'pedido_id',
        'metodo_pago',
        'estado_pago',
        'importe',
        'moneda',
        'fecha_pago',
        'fecha_reembolso',
        'importe_reembolso',
        'motivo_reembolso',
        'estado',
        'fecha_creacion',
        'fecha_actualizacion',
    ];

    protected $casts = [
        'importe'             => 'float',
        'importe_reembolso'   => 'float',
        'fecha_pago'          => 'datetime',
        'fecha_reembolso'     => 'datetime',
        'fecha_creacion'      => 'datetime',
        'fecha_actualizacion' => 'datetime',
    ];

    public function pedido()
    {
        return $this->belongsTo(Pedido::class, 'pedido_id');
    }
}