<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Modelo Entrada — Representa un ticket individual de un evento en VIBEZ.
 *
 * Cada vez que un usuario compra entradas para un evento, el sistema genera
 * una o varias instancias de este modelo (una por ticket comprado). Cada
 * Entrada pertenece a un Pedido y está vinculada a un Evento concreto.
 *
 * Ciclo de vida de una Entrada:
 *   1. El usuario inicia la compra → se crea un Pedido.
 *   2. EntradaController::comprar() genera tantas Entradas como unidades compradas.
 *   3. Cada Entrada recibe un codigo_qr único (UUID) para su identificación.
 *   4. El estado_entrada evoluciona: válida → usada (al escanear en el evento).
 *
 * Arquitectura de tablas:
 *   pedidos (1) ←——HasMany——→ (N) entradas (N) ←——BelongsTo——→ (1) eventos
 *
 * @property int         $id
 * @property int         $pedido_id        FK → tabla pedidos. Agrupa las entradas de una compra.
 * @property int         $evento_id        FK → tabla eventos. Evento al que da acceso este ticket.
 * @property int         $estado_entrada   Estado del ticket (ver valores posibles abajo).
 * @property string      $codigo_qr        UUID único generado al crear la entrada (ver abajo).
 * @property float       $precio_unitario  Precio original de la entrada en el momento de compra.
 * @property float       $precio_pagado    Precio efectivamente pagado (puede incluir descuentos).
 * @property \Carbon\Carbon|null $fecha_uso  Fecha en que se escaneó/usó la entrada en el evento.
 * @property int         $estado           Estado general del registro (1=activo, 0=inactivo).
 * @property \Carbon\Carbon|null $fecha_creacion
 * @property \Carbon\Carbon|null $fecha_actualizacion
 *
 * Valores posibles de estado_entrada:
 *   1 = Válida    → la entrada es correcta y puede usarse para acceder al evento.
 *   2 = Usada     → ya fue escaneada; el asistente entró al evento.
 *   0 = Cancelada → la compra fue cancelada o reembolsada; no da acceso.
 */
class Entrada extends Model
{
    // Nombre de la tabla en la BD. Laravel asumiría "entradas" por convención,
    // pero lo declaramos explícitamente para mayor legibilidad del código.
    protected $table = 'entradas';

    // Desactivamos timestamps automáticos de Laravel (created_at / updated_at)
    // porque la tabla usa los campos personalizados fecha_creacion y fecha_actualizacion.
    public $timestamps = false;

    // Lista blanca de campos asignables masivamente con create() o fill().
    // Incluye todos los campos necesarios para registrar un ticket de compra.
    protected $fillable = [
        'pedido_id',       // agrupa este ticket dentro de su pedido
        'evento_id',       // a qué evento da acceso este ticket
        'estado_entrada',  // 0=cancelada, 1=válida, 2=usada
        'codigo_qr',       // UUID único, se convierte en imagen QR para el usuario
        'precio_unitario', // precio de catálogo en el momento de la compra
        'precio_pagado',   // precio real pagado (puede diferir por descuentos)
        'fecha_uso',       // se rellena al escanear el QR en el evento
        'estado',          // estado general del registro en BD
        'fecha_creacion',
        'fecha_actualizacion',
    ];

    /**
     * Casts: conversiones automáticas de tipos al leer datos de la BD.
     *
     * - 'float'    → precios almacenados como DECIMAL en BD; se convierten a
     *                float de PHP para poder hacer cálculos aritméticos.
     * - 'datetime' → fechas almacenadas como DATETIME en BD; se convierten a
     *                objetos Carbon para poder formatearlas y compararlas fácilmente.
     *                Ejemplo: $entrada->fecha_creacion->format('d/m/Y')
     *
     * @var array<string, string>
     */
    protected $casts = [
        'precio_unitario'     => 'float',    // DECIMAL → float
        'precio_pagado'       => 'float',    // DECIMAL → float
        'fecha_uso'           => 'datetime', // DATETIME → Carbon (null si no usada)
        'fecha_creacion'      => 'datetime', // DATETIME → Carbon
        'fecha_actualizacion' => 'datetime', // DATETIME → Carbon
    ];

    /* ——— Relaciones Eloquent ——— */

    /**
     * Relación BelongsTo: una entrada PERTENECE A un pedido.
     *
     * BelongsTo indica que la clave foránea (pedido_id) está en la tabla "entradas".
     * A través de esta relación podemos navegar del ticket a toda la información
     * del pedido: usuario que compró, total pagado, fecha de compra, etc.
     *
     * Uso en controladores (eager loading para evitar el problema N+1):
     *   $entradas = Entrada::with('pedido')->get();
     *
     * Acceso: $entrada->pedido  →  objeto Pedido.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function pedido()
    {
        return $this->belongsTo(Pedido::class, 'pedido_id');
    }

    /**
     * Relación BelongsTo: una entrada PERTENECE A un evento.
     *
     * La clave foránea evento_id en "entradas" apunta a la tabla "eventos".
     * Permite acceder a todos los datos del evento desde el ticket: título,
     * fecha, lugar, imagen de portada, etc. Muy útil para mostrar el ticket
     * al usuario con toda la información del evento.
     *
     * Uso habitual con eager loading (evita N consultas SQL para N entradas):
     *   $entradas = Entrada::with('evento')->where('pedido_id', $id)->get();
     *   // Ahora $entrada->evento->titulo no lanza una nueva query SQL
     *
     * Acceso: $entrada->evento  →  objeto Evento.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function evento()
    {
        return $this->belongsTo(Evento::class, 'evento_id');
    }
}
