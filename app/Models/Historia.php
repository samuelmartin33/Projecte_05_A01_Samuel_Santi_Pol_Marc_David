<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Historia extends Model
{
    protected $table   = 'historias';
    public $timestamps = false;

    protected $fillable = [
        'usuario_id',
        'media_url',
        'texto',
        'evento_id',
        'expira_en',
        'vistas',
        'estado',
        'fecha_creacion',
        'fecha_actualizacion',
    ];

    protected $casts = [
        'expira_en'           => 'datetime',
        'fecha_creacion'      => 'datetime',
        'fecha_actualizacion' => 'datetime',
    ];

    // ── Relaciones ──────────────────────────────────────────────

    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'usuario_id');
    }

    public function evento()
    {
        return $this->belongsTo(Evento::class, 'evento_id');
    }

    public function vistasRelacion()
    {
        return $this->hasMany(HistoriaVista::class, 'historia_id');
    }

    // ── Scopes ──────────────────────────────────────────────────

    /**
     * Solo historias activas: estado 1 y no expiradas.
     */
    public function scopeActivas(Builder $query): Builder
    {
        return $query->where('estado', 1)->where('expira_en', '>', now());
    }
}
