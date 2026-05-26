<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Modelo PagoPremium — Registra cada compra de suscripción Premium en VIBEZ.
 *
 * Cada fila representa un pago único de 5€ realizado por un usuario.
 * La clave stripe_session_id tiene UNIQUE en BD para garantizar idempotencia:
 * aunque el webhook de Stripe llegue dos veces, solo se crea un registro.
 *
 * @property int         $id
 * @property int         $usuario_id
 * @property string      $stripe_session_id
 * @property string|null $stripe_payment_intent_id
 * @property float       $importe
 * @property string      $moneda
 * @property int         $estado                    1=completado, 0=reembolsado
 * @property \Carbon\Carbon|null $fecha_pago
 * @property \Carbon\Carbon|null $fecha_creacion
 * @property \Carbon\Carbon|null $fecha_actualizacion
 */
class PagoPremium extends Model
{
    protected $table = 'pagos_premium';
    public $timestamps = false;

    protected $fillable = [
        'usuario_id',
        'stripe_session_id',
        'stripe_payment_intent_id',
        'importe',
        'moneda',
        'estado',
        'fecha_pago',
        'fecha_creacion',
        'fecha_actualizacion',
    ];

    protected $casts = [
        'importe'             => 'float',
        'fecha_pago'          => 'datetime',
        'fecha_creacion'      => 'datetime',
        'fecha_actualizacion' => 'datetime',
    ];

    /**
     * El usuario que compró el premium.
     */
    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'usuario_id');
    }
}
