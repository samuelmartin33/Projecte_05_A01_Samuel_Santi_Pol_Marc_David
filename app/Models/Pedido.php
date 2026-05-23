<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Modelo Pedido — Representa una orden de compra en VIBEZ.
 *
 * Un Pedido agrupa uno o varios tickets (Entradas) adquiridos por un usuario
 * en una misma transacción. Es el equivalente al "carrito de compra completado"
 * o "recibo de compra" de la plataforma.
 *
 * Flujo de compra:
 *   1. El usuario selecciona un evento y elige la cantidad de entradas.
 *   2. El sistema crea un Pedido con el total calculado.
 *   3. Se generan tantos registros Entrada como tickets comprados, todos con
 *      el mismo pedido_id, cada uno con su propio codigo_qr único (UUID).
 *   4. El usuario recibe sus tickets (QR) por pantalla y/o email.
 *
 * Estructura de importes — diseñada para soportar cupones de descuento futuros:
 *   total            → suma de todos los precios unitarios sin descuentos aplicados.
 *   total_descuento  → importe total ahorrado por cupones/promociones (0 si no hay).
 *   total_final      → lo que el usuario pagó realmente: total - total_descuento.
 *
 * Actualmente total_descuento siempre es 0 (la funcionalidad de cupones está
 * planificada para futuras versiones), pero la estructura de la BD ya lo contempla.
 *
 * Relaciones:
 *   Pedido → BelongsTo → Usuario  (el usuario que realizó la compra)
 *   Pedido → HasMany   → Entrada  (los tickets generados en esta compra)
 *
 * @property int         $id
 * @property int         $usuario_id       FK → tabla usuarios. Quién realizó la compra.
 * @property float       $total            Precio total antes de descuentos.
 * @property float       $total_descuento  Descuento total aplicado (0 si no hay cupón).
 * @property float       $total_final      Precio final pagado por el usuario.
 * @property int         $estado           Estado del pedido (1=completado, 0=cancelado, etc.).
 * @property \Carbon\Carbon|null $fecha_creacion      Fecha en que se realizó la compra.
 * @property \Carbon\Carbon|null $fecha_actualizacion Última modificación del pedido.
 */
class Pedido extends Model
{
    // Nombre de la tabla en BD. Laravel asumiría "pedidos" por convención (plural
    // del modelo en minúsculas), pero lo declaramos explícitamente por claridad.
    protected $table = 'pedidos';

    // Desactivamos timestamps automáticos de Laravel (created_at / updated_at)
    // porque la tabla usa los campos personalizados fecha_creacion y fecha_actualizacion.
    public $timestamps = false;

    // Lista blanca de campos asignables masivamente con create() o fill().
    // Incluye todos los campos necesarios para registrar una orden de compra.
    protected $fillable = [
        'usuario_id',
        'total',
        'total_descuento',
        'total_final',
        'estado',
        'stripe_payment_intent_id',
        'fecha_creacion',
        'fecha_actualizacion',
    ];

    /**
     * Casts: conversiones automáticas de tipos al leer datos de la BD.
     *
     * - 'float'    → los importes se almacenan como DECIMAL en BD y se convierten
     *                a float de PHP para poder hacer cálculos aritméticos con ellos.
     * - 'datetime' → las fechas se convierten a objetos Carbon que permiten
     *                formatear, comparar y manipular fechas fácilmente.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'total'               => 'float',    // DECIMAL → float
        'total_descuento'     => 'float',    // DECIMAL → float
        'total_final'         => 'float',    // DECIMAL → float
        'fecha_creacion'      => 'datetime', // DATETIME → Carbon
        'fecha_actualizacion' => 'datetime', // DATETIME → Carbon
    ];

    /* ——— Relaciones Eloquent ——— */

    /**
     * Relación BelongsTo: un pedido PERTENECE A un usuario.
     *
     * BelongsTo indica que la clave foránea (usuario_id) está en la tabla "pedidos".
     * Permite navegar del pedido al usuario que lo realizó para obtener su nombre,
     * email, etc. (por ejemplo, para enviarle la confirmación de compra).
     *
     * Acceso: $pedido->usuario  →  objeto Usuario.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'usuario_id');
    }

    /**
     * Relación HasMany: un pedido TIENE MUCHAS entradas (tickets).
     *
     * HasMany indica que la clave foránea (pedido_id) está en la tabla relacionada
     * ("entradas"). Un usuario puede comprar varias entradas en una misma compra,
     * por lo que un pedido puede tener de 1 a N entradas asociadas.
     *
     * Acceso: $pedido->entradas  →  colección de objetos Entrada.
     *
     * ---- POR QUÉ SE USA EAGER LOADING EN LOS CONTROLADORES ----
     * En los controladores se usa frecuentemente:
     *   $pedidos = Pedido::with('entradas.evento')->where('usuario_id', $id)->get();
     *
     * Esto se llama "eager loading" (carga ansiosa). Sin él, Laravel lanzaría:
     *   - 1 query para obtener los pedidos.
     *   - 1 query por cada pedido para obtener sus entradas (problema N+1).
     *   - 1 query por cada entrada para obtener su evento (problema N+1 anidado).
     *
     * Con with('entradas.evento'), Laravel lo resuelve en solo 3 queries totales,
     * independientemente de cuántos pedidos, entradas o eventos haya.
     * Esto mejora enormemente el rendimiento en páginas con muchos registros.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function entradas()
    {
        return $this->hasMany(Entrada::class, 'pedido_id');
    }
}
