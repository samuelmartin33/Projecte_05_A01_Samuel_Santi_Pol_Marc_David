<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Modelo BonoCompra — Representa la compra de un bono de bebidas por un cliente.
 * Al crearse se genera un código QR único. El camarero lo escanea para descontar bebidas.
 */
class BonoCompra extends Model
{
    protected $table = 'bono_compras';

    protected $fillable = [
        'usuario_id',
        'bono_tipo_id',
        'evento_id',
        'codigo_qr',
        'bebidas_restantes',
    ];

    protected $casts = [
        'bebidas_restantes' => 'integer',
    ];

    // El cliente que compró el bono
    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'usuario_id');
    }

    // El tipo de bono comprado (2, 5 o 10 bebidas)
    public function tipo()
    {
        return $this->belongsTo(BonoTipo::class, 'bono_tipo_id');
    }

    // El evento Fiesta al que pertenece el bono
    public function evento()
    {
        return $this->belongsTo(Evento::class, 'evento_id');
    }

    // Historial de consumiciones realizadas con este bono
    public function consumos()
    {
        return $this->hasMany(BonoConsumo::class, 'bono_compra_id');
    }

    // True si el bono aún tiene bebidas disponibles
    public function tieneSaldo(): bool
    {
        return $this->bebidas_restantes > 0;
    }
}
