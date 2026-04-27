<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EventoFavorito extends Model
{
    use HasFactory;

    protected $table = 'eventos_favoritos';
    public $timestamps = false;

    protected $fillable = [
        'usuario_id',
        'evento_id',
        'estado',
        'fecha_creacion',
        'fecha_actualizacion',
    ];

    protected $casts = [
        'estado' => 'boolean',
        'fecha_creacion' => 'datetime',
        'fecha_actualizacion' => 'datetime',
    ];

    public function usuario(): BelongsTo
    {
        return $this->belongsTo(Usuario::class, 'usuario_id');
    }

    public function evento(): BelongsTo
    {
        return $this->belongsTo(Evento::class, 'evento_id');
    }
}
