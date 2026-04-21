<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Evento extends Model
{
    use HasFactory;

    protected $table = 'eventos';

    public $timestamps = false;

    protected $fillable = [
        'organizador_id',
        'categoria_evento_id',
        'tipo_evento',
        'titulo',
        'descripcion',
        'fecha_inicio',
        'fecha_fin',
        'ubicacion_nombre',
        'ubicacion_direccion',
        'latitud',
        'longitud',
        'precio_base',
        'aforo_maximo',
        'aforo_actual',
        'edad_minima',
        'es_gratuito',
        'url_externa',
        'estado',
        'fecha_creacion',
        'fecha_actualizacion',
    ];

    protected $casts = [
        'fecha_inicio' => 'datetime',
        'fecha_fin' => 'datetime',
        'fecha_creacion' => 'datetime',
        'fecha_actualizacion' => 'datetime',
    ];

    public function categoriaEvento(): BelongsTo
    {
        return $this->belongsTo(CategoriaEvento::class, 'categoria_evento_id');
    }

    public function organizador(): BelongsTo
    {
        return $this->belongsTo(Organizador::class, 'organizador_id');
    }
}
