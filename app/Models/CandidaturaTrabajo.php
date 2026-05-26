<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\DB;
use App\Models\CategoriaTrabajo;

/**
 * Modelo para la tabla `candidaturas_trabajo`.
 */
class CandidaturaTrabajo extends Model
{
    protected $table = 'candidaturas_trabajo';
    public $timestamps = false;

    // estado_candidatura values
    const ESTADO_NUEVO           = 1;
    const ESTADO_REVISADO        = 2;
    const ESTADO_PRESELECCIONADO = 3;
    const ESTADO_RECHAZADO       = 4;
    const ESTADO_SELECCIONADO    = 5;

    protected $fillable = [
        'oferta_id', 'trabajo_id', 'trabajador_id', 'estado_candidatura',
        'carta_presentacion', 'cv_url', 'estado',
        'fecha_creacion', 'fecha_actualizacion',
        'nombre_candidato', 'apellidos_candidato', 'email_candidato',
        'telefono_candidato', 'ciudad_candidato', 'linkedin_candidato',
        'perfil_profesional', 'habilidades', 'idiomas',
    ];

    public function oferta(): BelongsTo
    {
        return $this->belongsTo(BolsaOfertaTrabajo::class, 'oferta_id');
    }

    /**
     * Relación con la categoría de trabajo a la que se postula el candidato.
     * Usa la misma tabla que las categorías de las ofertas (categorias_trabajo).
     */
    public function trabajo(): BelongsTo
    {
        return $this->belongsTo(CategoriaTrabajo::class, 'trabajo_id');
    }

    // ── Helpers ──────────────────────────────────────────────

    public function nombreCompleto(): string
    {
        $nombre = trim($this->nombre_candidato . ' ' . $this->apellidos_candidato);
        return $nombre ?: 'Candidato';
    }

    public function iniciales(): string
    {
        $nombre    = mb_substr($this->nombre_candidato    ?? 'C', 0, 1);
        $apellidos = mb_substr($this->apellidos_candidato ?? '',  0, 1);
        return strtoupper($nombre . $apellidos) ?: 'C';
    }

    public function estadoLabel(): string
    {
        return match ((int) $this->estado_candidatura) {
            self::ESTADO_NUEVO           => 'Nuevo',
            self::ESTADO_REVISADO        => 'Revisado',
            self::ESTADO_PRESELECCIONADO => 'Preseleccionado',
            self::ESTADO_RECHAZADO       => 'Rechazado',
            self::ESTADO_SELECCIONADO    => 'Seleccionado',
            default                      => 'Desconocido',
        };
    }

    public function estadoClases(): string
    {
        return match ((int) $this->estado_candidatura) {
            self::ESTADO_NUEVO           => 'estado-1',
            self::ESTADO_REVISADO        => 'estado-2',
            self::ESTADO_PRESELECCIONADO => 'estado-3',
            self::ESTADO_RECHAZADO       => 'estado-4',
            self::ESTADO_SELECCIONADO    => 'estado-5',
            default                      => 'estado-0',
        };
    }

    public function tieneArchivo(): bool
    {
        return !empty($this->cv_url);
    }

    /**
     * Devuelve el email del candidato resolviendo por todas las vías disponibles:
     * 1. email_candidato (candidaturas con formulario completo)
     * 2. email del Usuario vinculado a través de trabajador_id (candidaturas solo con PDF)
     */
    public function emailResuelto(): ?string
    {
        if ($this->email_candidato) {
            return $this->email_candidato;
        }

        if ($this->trabajador_id) {
            $usuarioId = DB::table('trabajadores')->where('id', $this->trabajador_id)->value('usuario_id');
            if ($usuarioId) {
                return DB::table('usuarios')->where('id', $usuarioId)->value('email');
            }
        }

        return null;
    }
}