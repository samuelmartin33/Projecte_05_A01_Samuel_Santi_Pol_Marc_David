<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Entrada extends Model
{
    protected $table      = 'entradas';
    public    $timestamps = false;

    protected $fillable = [
        'pedido_id',
        'evento_id',
        'estado_entrada',
        'codigo_qr',
        'precio_unitario',
        'precio_pagado',
        'fecha_uso',
        'estado',
        'fecha_creacion',
        'fecha_actualizacion',
    ];

    protected $casts = [
        'precio_unitario'     => 'float',
        'precio_pagado'       => 'float',
        'fecha_uso'           => 'datetime',
        'fecha_creacion'      => 'datetime',
        'fecha_actualizacion' => 'datetime',
    ];

    public function pedido()
    {
        return $this->belongsTo(Pedido::class, 'pedido_id');
    }

    public function evento()
    {
        return $this->belongsTo(Evento::class, 'evento_id');
    }
}
