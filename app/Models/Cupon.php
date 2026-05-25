<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use App\Models\Evento;

/**
 * Modelo Cupon — Representa un cupón de descuento en VIBEZ.
 *
 * Un cupón tiene un código único, un porcentaje de descuento y puede estar
 * vinculado a uno o varios eventos a través de la tabla pivot cupones_evento.
 *
 * @property int         $id
 * @property int|null    $empresa_id
 * @property int|null    $organizador_id
 * @property string      $codigo                  Código que el usuario introduce.
 * @property string|null $descripcion
 * @property float       $valor_descuento         Porcentaje de descuento (ej: 10 = 10%).
 * @property Carbon      $fecha_inicio
 * @property Carbon      $fecha_fin
 * @property int|null    $limite_usos_total
 * @property int|null    $limite_usos_por_usuario
 * @property int         $usos_actuales
 * @property int         $estado                  1 = activo, 0 = inactivo.
 * @property Carbon      $fecha_creacion
 * @property Carbon|null $fecha_actualizacion
 *
 * Accessors:
 * @property-read bool   $is_valido               true si el cupón está activo y dentro de validez.
 * @property-read int    $usos_restantes          Usos disponibles (-1 si ilimitado).
 */
class Cupon extends Model
{
    protected $table      = 'cupones';
    public    $timestamps = false;

    protected $fillable = [
        'empresa_id', 'organizador_id', 'codigo', 'descripcion',
        'valor_descuento', 'fecha_inicio', 'fecha_fin',
        'limite_usos_total', 'limite_usos_por_usuario',
        'usos_actuales', 'estado',
        'fecha_creacion', 'fecha_actualizacion',
    ];

    protected $casts = [
        'valor_descuento'         => 'float',
        'limite_usos_total'       => 'integer',
        'limite_usos_por_usuario' => 'integer',
        'usos_actuales'           => 'integer',
        'fecha_inicio'            => 'datetime',
        'fecha_fin'               => 'datetime',
        'fecha_creacion'          => 'datetime',
        'fecha_actualizacion'     => 'datetime',
    ];

    /* ——— Relaciones ——— */

    /** Los eventos donde aplica este cupón (pivot cupones_evento). */
    public function eventos()
    {
        return $this->belongsToMany(Evento::class, 'cupones_evento', 'cupon_id', 'evento_id')
                    ->withPivot('estado')
                    ->wherePivot('estado', 1);
    }

    /** Registros de uso de este cupón. */
    public function usos()
    {
        return $this->hasMany(CuponUso::class, 'cupon_id');
    }

    /** Empresa propietaria (si aplica). */
    public function empresa()
    {
        return $this->belongsTo(Empresa::class, 'empresa_id');
    }

    /* ——— Accessors ——— */

    /**
     * ¿El cupón es válido ahora mismo?
     * Comprueba: estado activo, dentro del rango de fechas y sin exceder el límite total.
     */
    public function getIsValidoAttribute(): bool
    {
        $ahora = now();

        if ($this->estado !== 1) return false;
        if ($ahora->lt($this->fecha_inicio)) return false;
        if ($ahora->gt($this->fecha_fin)) return false;
        if ($this->limite_usos_total !== null && $this->usos_actuales >= $this->limite_usos_total) return false;

        return true;
    }

    /**
     * Usos restantes. Devuelve -1 si el límite es ilimitado.
     */
    public function getUsosRestantesAttribute(): int
    {
        if ($this->limite_usos_total === null) return -1;
        return max(0, $this->limite_usos_total - $this->usos_actuales);
    }

    /**
     * Indica si el cupón ya ha caducado (fecha_fin pasada).
     */
    public function getExpiradoAttribute(): bool
    {
        return now()->gt($this->fecha_fin);
    }

    /**
     * Indica si el cupón está agotado (usos >= límite).
     */
    public function getAgotadoAttribute(): bool
    {
        return $this->limite_usos_total !== null
            && $this->usos_actuales >= $this->limite_usos_total;
    }

    /* ——— Scopes ——— */

    /** Solo cupones activos y dentro de fechas. */
    public function scopeVigentes($query)
    {
        return $query->where('estado', 1)
                     ->where('fecha_inicio', '<=', now())
                     ->where('fecha_fin', '>=', now());
    }

    /**
     * Comprueba si el cupón es válido para un evento concreto.
     *
     * @param int $eventoId
     * @return bool
     */
    public function aplicaAEvento(int $eventoId): bool
    {
        $tieneEventos = $this->eventos()->count() > 0;

        if ($tieneEventos) {
            return $this->eventos()->where('eventos.id', $eventoId)->exists();
        }

        // Sin eventos asignados: si el cupón pertenece a una empresa,
        // solo aplica a los eventos de esa empresa.
        if ($this->empresa_id) {
            return Evento::where('id', $eventoId)
                ->whereHas('organizador', fn ($q) => $q->where('empresa_id', $this->empresa_id))
                ->exists();
        }

        return true;
    }

    /**
     * Comprueba cuántas veces ha usado este cupón un usuario concreto.
     *
     * @param int $usuarioId
     * @return int
     */
    public function usosDeUsuario(int $usuarioId): int
    {
        return $this->usos()
                    ->whereHas('pedido', fn ($q) => $q->where('usuario_id', $usuarioId))
                    ->count();
    }
}
