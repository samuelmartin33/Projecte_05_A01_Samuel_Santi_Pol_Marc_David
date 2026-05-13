<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FacturaEvento extends Model
{
    protected $table = 'facturas_evento';

    protected $fillable = [
        'numero_factura',
        'evento_id',
        'empresa_id',
        'generada_por_usuario_id',
        'estado',
        'nombre_empresa_frozen',
        'razon_social_frozen',
        'nif_cif_frozen',
        'direccion_frozen',
        'nombre_evento_frozen',
        'fecha_evento_frozen',
        'total_entradas_vendidas',
        'importe_bruto',
        'porcentaje_comision',
        'importe_comision',
        'tipo_iva',
        'cuota_iva',
        'total_cargos_plataforma',
        'importe_neto_empresa',
        'notas',
        'pdf_path',
        'fecha_emision',
    ];

    protected $casts = [
        'fecha_evento_frozen'     => 'datetime',
        'fecha_emision'           => 'datetime',
        'importe_bruto'           => 'float',
        'porcentaje_comision'     => 'float',
        'importe_comision'        => 'float',
        'tipo_iva'                => 'float',
        'cuota_iva'               => 'float',
        'total_cargos_plataforma' => 'float',
        'importe_neto_empresa'    => 'float',
    ];

    public function evento()
    {
        return $this->belongsTo(Evento::class);
    }

    public function empresa()
    {
        return $this->belongsTo(Empresa::class);
    }

    public function generadaPor()
    {
        return $this->belongsTo(Usuario::class, 'generada_por_usuario_id');
    }

    public function lineas()
    {
        return $this->hasMany(LineaFacturaEvento::class, 'factura_evento_id')
                    ->orderBy('orden');
    }

    public function getEstaEmitidaAttribute(): bool
    {
        return $this->estado === 'emitida';
    }

    public function getEstaAnuladaAttribute(): bool
    {
        return $this->estado === 'anulada';
    }
}
