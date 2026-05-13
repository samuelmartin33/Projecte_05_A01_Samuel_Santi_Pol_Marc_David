<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Modelo Notificacion — Avisos internos para usuarios de VIBEZ.
 *
 * Tipos (campo tipo_notificacion — tinyint):
 *   1 → empresa_registro : Admin recibe aviso cuando una empresa se registra.
 *   2 → perfil_fiscal    : Empresa recibe aviso para completar datos fiscales.
 *   3 → empresa_aprobada : Empresa recibe aviso de aprobación por admin.
 *   4 → empresa_rechazada: Empresa recibe aviso de rechazo por admin.
 *   5 → general          : Avisos genéricos.
 */
class Notificacion extends Model
{
    protected $table    = 'notificaciones';
    public    $timestamps = false;

    // Tipos como constantes para no usar "magic numbers" en el código
    const EMPRESA_REGISTRO  = 1;
    const PERFIL_FISCAL     = 2;
    const EMPRESA_APROBADA  = 3;
    const EMPRESA_RECHAZADA = 4;
    const GENERAL           = 5;

    protected $fillable = [
        'usuario_id',
        'tipo_notificacion',
        'titulo',
        'mensaje',
        'url_accion',
        'leida',
        'estado',
        'fecha_creacion',
        'fecha_actualizacion',
    ];

    protected $casts = [
        'leida'  => 'boolean',
        'estado' => 'integer',
    ];

    /* ——— Relaciones ——— */

    public function usuario(): BelongsTo
    {
        return $this->belongsTo(Usuario::class, 'usuario_id');
    }

    /* ——— Helpers estáticos ——— */

    /**
     * Crea una notificación para un usuario concreto.
     *
     * @param int         $usuarioId  ID del destinatario
     * @param int         $tipo       Constante de tipo (self::EMPRESA_REGISTRO, etc.)
     * @param string      $titulo     Texto corto que aparece en el badge
     * @param string|null $mensaje    Detalle opcional
     * @param string|null $url        URL de acción al hacer clic
     */
    public static function crear(int $usuarioId, int $tipo, string $titulo, ?string $mensaje = null, ?string $url = null): self
    {
        $ahora = now()->toDateTimeString();
        return self::create([
            'usuario_id'          => $usuarioId,
            'tipo_notificacion'   => $tipo,
            'titulo'              => $titulo,
            'mensaje'             => $mensaje,
            'url_accion'          => $url,
            'leida'               => 0,
            'estado'              => 1,
            'fecha_creacion'      => $ahora,
            'fecha_actualizacion' => $ahora,
        ]);
    }

    /**
     * Crea la misma notificación para todos los usuarios con es_admin = 1.
     */
    public static function notificarAdmins(int $tipo, string $titulo, ?string $mensaje = null, ?string $url = null): void
    {
        $admins = Usuario::where('es_admin', 1)->where('estado', 1)->pluck('id');
        foreach ($admins as $adminId) {
            self::crear($adminId, $tipo, $titulo, $mensaje, $url);
        }
    }

    /**
     * Devuelve el icono SVG correspondiente al tipo de notificación.
     * Útil en vistas Blade para mostrar un icono distinto por tipo.
     */
    public function icono(): string
    {
        return match ($this->tipo_notificacion) {
            self::EMPRESA_REGISTRO  => '🏢',
            self::PERFIL_FISCAL     => '📋',
            self::EMPRESA_APROBADA  => '✅',
            self::EMPRESA_RECHAZADA => '❌',
            default                 => '🔔',
        };
    }
}
