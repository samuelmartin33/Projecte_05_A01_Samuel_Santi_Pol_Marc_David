<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Modelo principal para la tabla eventos.
 * Contiene toda la información de un evento: fecha, ubicación,
 * precio, aforo, etc. Las relaciones permiten acceder al organizador,
 * categoría e imágenes en una sola consulta (eager loading con with()).
 */
class Evento extends Model
{
    protected $table = 'eventos';
    public $timestamps = false;

    protected $fillable = [
        'organizador_id', 'categoria_evento_id', 'tipo_evento',
        'titulo', 'descripcion', 'fecha_inicio', 'fecha_fin',
        'ubicacion_nombre', 'ubicacion_direccion',
        'latitud', 'longitud', 'precio_base', 'aforo_maximo',
        'aforo_actual', 'edad_minima', 'es_gratuito',
        'url_externa', 'estado'
    ];

    // Castear estos campos como tipos nativos de PHP
    protected $casts = [
        'precio_base' => 'float',
        'latitud'     => 'float',
        'longitud'    => 'float',
        'es_gratuito' => 'boolean',
    ];

    /**
     * Relación: el evento pertenece a una categoría.
     */
    public function categoria()
    {
        return $this->belongsTo(CategoriaEvento::class, 'categoria_evento_id');
    }

    /**
     * Relación: el evento pertenece a un organizador.
     */
    public function organizador()
    {
        return $this->belongsTo(Organizador::class, 'organizador_id');
    }

    /**
     * Relación: un evento puede tener muchas imágenes.
     */
    public function imagenes()
    {
        return $this->hasMany(EventoImagen::class, 'evento_id');
    }

    /**
     * Relación: imagen principal del evento (es_portada = 1).
     * Devuelve solo una imagen — la portada.
     */
    public function portada()
    {
        return $this->hasOne(EventoImagen::class, 'evento_id')
                    ->where('es_portada', 1);
    }

    /**
     * Accessor: devuelve la URL de la imagen de portada,
     * o un placeholder de picsum.photos si no hay imagen.
     * El seed usa el ID del evento para que siempre sea la misma imagen.
     */
    public function getUrlPortadaAttribute(): string
    {
        return $this->portada?->imagen_url
            ?? "https://picsum.photos/seed/evento-{$this->id}/600/400";
    }

    /**
     * Accessor: formatea el precio para mostrar en la UI.
     * Si es gratuito devuelve 'Gratis', si no '€ X.XX'.
     */
    public function getPrecioFormateadoAttribute(): string
    {
        if ($this->es_gratuito) {
            return 'Gratis';
        }
        return '€ ' . number_format($this->precio_base, 2);
    }
}
