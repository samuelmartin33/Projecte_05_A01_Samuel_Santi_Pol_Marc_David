<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pedido extends Model
{
    protected $table      = 'pedidos';
    public    $timestamps = false;

    protected $fillable = [
        'usuario_id',
        'total',
        'total_descuento',
        'total_final',
        'estado',
        'fecha_creacion',
        'fecha_actualizacion',
    ];

    protected $casts = [
        'total'               => 'float',
        'total_descuento'     => 'float',
        'total_final'         => 'float',
        'fecha_creacion'      => 'datetime',
        'fecha_actualizacion' => 'datetime',
    ];

    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'usuario_id');
    }

    public function entradas()
    {
        return $this->hasMany(Entrada::class, 'pedido_id');
    }
}
