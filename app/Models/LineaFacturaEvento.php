<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LineaFacturaEvento extends Model
{
    protected $table = 'lineas_factura_evento';

    protected $fillable = [
        'factura_evento_id',
        'orden',
        'tipo',
        'concepto',
        'cantidad',
        'precio_unitario',
        'subtotal',
    ];

    protected $casts = [
        'precio_unitario' => 'float',
        'subtotal'        => 'float',
    ];

    public function factura()
    {
        return $this->belongsTo(FacturaEvento::class, 'factura_evento_id');
    }
}
