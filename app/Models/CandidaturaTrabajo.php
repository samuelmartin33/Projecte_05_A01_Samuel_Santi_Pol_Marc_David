<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CandidaturaTrabajo extends Model
{
    protected $table = 'candidaturas_trabajo';
    public $timestamps = false;

    // estado_candidatura values
    const ESTADO_NUEVO           = 1;
    const ESTADO_REVISADO        = 2;
    const ESTADO_PRESELECCIONADO = 3;
    const ESTADO_RECHAZADO       = 4;

    protected $fillable = [
        'oferta_id', 'trabajador_id', 'estado_candidatura',
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
            default                      => 'Desconocido',
        };
    }

    public function estadoClases(): string
    {
        return match ((int) $this->estado_candidatura) {
            self::ESTADO_NUEVO           => 'bg-blue-100 text-blue-700',
            self::ESTADO_REVISADO        => 'bg-amber-100 text-amber-700',
            self::ESTADO_PRESELECCIONADO => 'bg-green-100 text-green-700',
            self::ESTADO_RECHAZADO       => 'bg-red-100 text-red-700',
            default                      => 'bg-gray-100 text-gray-600',
        };
    }

    public function tieneArchivo(): bool
    {
        return !empty($this->cv_url);
    }
}