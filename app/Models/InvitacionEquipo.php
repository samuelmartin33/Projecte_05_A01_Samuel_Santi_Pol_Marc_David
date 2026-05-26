<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * Token de invitación que permite a un candidato seleccionado unirse al equipo de la empresa.
 * Caduca a los 5 días y solo puede usarse una vez.
 */
class InvitacionEquipo extends Model
{
    protected $table = 'invitaciones_equipo';
    public $timestamps = false;

    protected $fillable = [
        'token', 'candidatura_id', 'empresa_id',
        'email', 'rol', 'expira_en', 'usado_en', 'fecha_creacion',
    ];

    protected $casts = [
        'expira_en'      => 'datetime',
        'usado_en'       => 'datetime',
        'fecha_creacion' => 'datetime',
    ];

    public function candidatura(): BelongsTo
    {
        return $this->belongsTo(CandidaturaTrabajo::class, 'candidatura_id');
    }

    public function empresa(): BelongsTo
    {
        return $this->belongsTo(Empresa::class, 'empresa_id');
    }

    /** Devuelve true si el token está vigente (no caducado y no usado). */
    public function estaVigente(): bool
    {
        return is_null($this->usado_en) && Carbon::now()->lt($this->expira_en);
    }
}