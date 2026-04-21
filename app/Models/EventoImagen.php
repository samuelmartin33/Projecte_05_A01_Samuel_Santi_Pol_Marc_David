<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Modelo para la tabla eventos_imagenes.
 * Cada registro es una imagen asociada a un evento.
 * El campo es_portada = 1 indica la imagen principal.
 */
class EventoImagen extends Model
{
    protected $table = 'eventos_imagenes';
    public $timestamps = false;

    protected $fillable = ['evento_id', 'imagen_url', 'descripcion', 'es_portada', 'estado'];
}
