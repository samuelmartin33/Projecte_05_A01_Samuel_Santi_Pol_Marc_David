<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Evento extends Model
{
    use HasFactory;

    protected $table = 'eventos';
    public $timestamps = false;

    protected $fillable = [
        'organizador_id', 'categoria_evento_id', 'tipo_evento',
        'titulo', 'descripcion', 'fecha_inicio', 'fecha_fin',
        'ubicacion_nombre', 'ubicacion_direccion',
        'latitud', 'longitud', 'precio_base', 'aforo_maximo',
        'aforo_actual', 'edad_minima', 'es_gratuito',
        'url_externa', 'estado', 'fecha_creacion', 'fecha_actualizacion',
    ];

    protected $casts = [
        'precio_base' => 'float',
        'latitud'     => 'float',
        'longitud'    => 'float',
        'es_gratuito' => 'boolean',
        'fecha_inicio' => 'datetime',
        'fecha_fin'    => 'datetime',
        'fecha_creacion'      => 'datetime',
        'fecha_actualizacion' => 'datetime',
    ];

    public function categoria()
    {
        return $this->belongsTo(CategoriaEvento::class, 'categoria_evento_id');
    }

    public function organizador()
    {
        return $this->belongsTo(Organizador::class, 'organizador_id');
    }

    public function imagenes()
    {
        return $this->hasMany(EventoImagen::class, 'evento_id');
    }

    public function portada()
    {
        return $this->hasOne(EventoImagen::class, 'evento_id')
                    ->where('es_portada', 1);
    }

    public function getUrlPortadaAttribute(): string
    {
        return $this->portada?->imagen_url
            ?? "https://picsum.photos/seed/evento-{$this->id}/600/400";
    }

    public function getPrecioFormateadoAttribute(): string
    {
        if ($this->es_gratuito) {
            return 'Gratis';
        }
        return '€ ' . number_format($this->precio_base, 2);
    }
}
