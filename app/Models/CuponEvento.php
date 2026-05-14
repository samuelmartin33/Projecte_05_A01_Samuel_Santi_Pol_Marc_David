<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Modelo CuponEvento — Pivot entre cupones y eventos.
 *
 * Cada fila indica que un cupón concreto puede canjearse en un evento concreto.
 * Si un cupón no tiene filas en esta tabla, aplica a TODOS los eventos (global).
 */
class CuponEvento extends Model
{
    protected $table      = 'cupones_evento';
    public    $timestamps = false;

    protected $fillable = [
        'cupon_id', 'evento_id', 'estado',
        'fecha_creacion', 'fecha_actualizacion',
    ];

    protected $casts = [
        'fecha_creacion'      => 'datetime',
        'fecha_actualizacion' => 'datetime',
    ];

    public function cupon()
    {
        return $this->belongsTo(Cupon::class, 'cupon_id');
    }

    public function evento()
    {
        return $this->belongsTo(Evento::class, 'evento_id');
    }
}
