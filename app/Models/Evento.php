<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Modelo Evento — Representa un evento publicado en la plataforma VIBEZ.
 *
 * Un evento es el núcleo de la aplicación: lo crea un Organizador, pertenece
 * a una CategoriaEvento, puede tener varias imágenes (EventoImagen) y genera
 * Entradas (tickets) cuando los usuarios realizan Pedidos.
 *
 * Arquitectura MVC:
 *   - Model (este archivo): define la estructura de datos y las relaciones.
 *   - View (Blade/Vue): muestra los datos del evento al usuario.
 *   - Controller (EventoController): gestiona la lógica de negocio (crear, editar, listar).
 *
 * @property int         $id
 * @property int         $organizador_id        FK → tabla organizadores.
 * @property int         $categoria_evento_id   FK → tabla categorias_evento.
 * @property string      $tipo_evento           Tipo de evento (presencial, online, híbrido).
 * @property string      $titulo                Nombre del evento.
 * @property string|null $descripcion           Descripción larga del evento.
 * @property \Carbon\Carbon $fecha_inicio       Fecha y hora de inicio.
 * @property \Carbon\Carbon|null $fecha_fin     Fecha y hora de finalización.
 * @property string|null $ubicacion_nombre      Nombre del lugar (ej: "Palau de la Música").
 * @property string|null $ubicacion_direccion   Dirección postal del evento.
 * @property float|null  $latitud               Coordenada geográfica para el mapa.
 * @property float|null  $longitud              Coordenada geográfica para el mapa.
 * @property float       $precio_base           Precio de una entrada (0 si es gratuito).
 * @property int|null    $aforo_maximo          Número máximo de asistentes permitidos.
 * @property int         $aforo_actual          Entradas vendidas hasta el momento.
 * @property int|null    $edad_minima           Edad mínima para asistir (ej: 18).
 * @property bool        $es_gratuito           true si el evento no tiene coste de entrada.
 * @property string|null $url_externa           Enlace externo (web del evento, Eventbrite, etc.).
 * @property int         $estado                Estado del evento (1=activo, 0=inactivo, etc.).
 * @property \Carbon\Carbon|null $fecha_creacion
 * @property \Carbon\Carbon|null $fecha_actualizacion
 *
 * Propiedades virtuales (accessors):
 * @property-read string $url_portada           URL de la imagen de portada o imagen por defecto.
 * @property-read string $precio_formateado     Precio listo para mostrar ("€ 12,00" o "Gratis").
 */
class Evento extends Model
{
    use HasFactory;

    // Nombre de la tabla en la BD. Laravel asumiría "eventos" en este caso (plural
    // del modelo en minúsculas), pero lo declaramos explícitamente para mayor claridad.
    protected $table = 'eventos';

    // Desactivamos timestamps automáticos de Laravel (created_at / updated_at)
    // porque la tabla usa los campos personalizados fecha_creacion y fecha_actualizacion.
    public $timestamps = false;

    // Lista blanca de campos que se pueden asignar de forma masiva con create() o fill().
    // Protege contra Mass Assignment: solo se pueden asignar los campos listados aquí.
    protected $fillable = [
        'organizador_id', 'categoria_evento_id', 'tipo_evento',
        'titulo', 'descripcion', 'fecha_inicio', 'fecha_fin',
        'ubicacion_nombre', 'ubicacion_direccion',
        'latitud', 'longitud', 'precio_base', 'aforo_maximo',
        'aforo_actual', 'edad_minima', 'es_gratuito',
        'url_externa', 'estado', 'fecha_creacion', 'fecha_actualizacion',
    ];

    /**
     * Casts: Eloquent convierte automáticamente los valores de BD al tipo PHP indicado.
     *
     * - 'float'    → los precios y coordenadas llegan de BD como string; el cast
     *                los convierte a número decimal para poder operar con ellos.
     * - 'boolean'  → la BD guarda es_gratuito como TINYINT (0 o 1); el cast lo
     *                convierte a true/false de PHP, así podemos escribir:
     *                if ($evento->es_gratuito) { ... }
     * - 'datetime' → convierte las fechas a objetos Carbon, lo que permite formatearlas
     *                fácilmente: $evento->fecha_inicio->format('d/m/Y H:i')
     *
     * @var array<string, string>
     */
    protected $casts = [
        'precio_base'  => 'float',
        'latitud'      => 'float',
        'longitud'     => 'float',
        'es_gratuito'  => 'boolean',  // 0/1 en BD → true/false en PHP
        'fecha_inicio' => 'datetime',
        'fecha_fin'    => 'datetime',
        'fecha_creacion'      => 'datetime',
        'fecha_actualizacion' => 'datetime',
    ];

    /* ——— Relaciones Eloquent ——— */

    /**
     * Relación BelongsTo: un evento PERTENECE A una categoría.
     *
     * BelongsTo indica que la clave foránea (categoria_evento_id) está en la
     * tabla "eventos" (la tabla de ESTE modelo). Es la relación inversa de HasMany.
     * Acceso: $evento->categoria  →  objeto CategoriaEvento o null.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function categoria()
    {
        return $this->belongsTo(CategoriaEvento::class, 'categoria_evento_id');
    }

    /**
     * Alias del método categoria() para mayor legibilidad en el código.
     *
     * Permite acceder a la misma relación con dos nombres:
     *   $evento->categoria        (nombre corto)
     *   $evento->categoriaEvento  (nombre descriptivo)
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function categoriaEvento()
    {
        return $this->categoria();
    }

    /**
     * Relación BelongsTo: un evento PERTENECE A un organizador.
     *
     * La clave foránea organizador_id en "eventos" apunta a la tabla "organizadores".
     * El organizador es quien ha creado y gestiona el evento.
     * Acceso: $evento->organizador  →  objeto Organizador.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function organizador()
    {
        return $this->belongsTo(Organizador::class, 'organizador_id');
    }

    /**
     * Relación HasMany: un evento TIENE MUCHAS imágenes.
     *
     * HasMany indica que la clave foránea (evento_id) está en la tabla relacionada
     * ("evento_imagenes"), no en "eventos". Es la relación inversa de BelongsTo.
     * Devuelve todas las imágenes asociadas al evento (portada + galería).
     * Acceso: $evento->imagenes  →  colección de objetos EventoImagen.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function imagenes()
    {
        return $this->hasMany(EventoImagen::class, 'evento_id');
    }

    /**
     * Relación HasOne filtrada: la imagen de portada del evento.
     *
     * HasOne devuelve solo UN registro relacionado en lugar de una colección.
     * El método ->where('es_portada', 1) añade una condición SQL para obtener
     * únicamente la imagen marcada como portada, ya que solo puede haber una.
     * Si el evento no tiene portada asignada, devuelve null.
     * Acceso: $evento->portada  →  objeto EventoImagen o null.
     *
     * Nota: este método se usa internamente en el accessor getUrlPortadaAttribute().
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function portada()
    {
        return $this->hasOne(EventoImagen::class, 'evento_id')
                    ->where('es_portada', 1); // filtra: solo la imagen marcada como portada
    }

    /* ——— Accessors (propiedades virtuales) ——— */

    /**
     * Accessor: devuelve la URL de la imagen de portada del evento.
     *
     * CONVENCIÓN DE LARAVEL: cualquier método con el patrón get{Campo}Attribute()
     * se convierte automáticamente en una propiedad virtual accesible como:
     *   $evento->url_portada   (camelCase → snake_case automáticamente)
     *
     * Lógica:
     *   1. Si el evento tiene portada asignada, usa su URL.
     *   2. Si no tiene portada, genera una imagen aleatoria de picsum.photos
     *      usando el ID del evento como semilla (seed) para que sea siempre la misma.
     *
     * El operador ?-> (nullsafe) evita un error si $this->portada es null.
     * El operador ?? (null coalescing) devuelve el valor de la derecha si la izquierda es null.
     *
     * @return string URL absoluta de la imagen de portada.
     */
    public function getUrlPortadaAttribute(): string
    {
        return $this->portada?->imagen_url
            ?? "https://picsum.photos/seed/evento-{$this->id}/600/400";
    }

    /**
     * Accessor: devuelve el precio del evento formateado para mostrar al usuario.
     *
     * CONVENCIÓN DE LARAVEL: el método getPrecioFormateadoAttribute() se accede como:
     *   $evento->precio_formateado
     *
     * Lógica:
     *   - Si es_gratuito es true → devuelve la cadena "Gratis".
     *   - Si es de pago → devuelve el precio con 2 decimales y símbolo €.
     *     Ejemplo: 12.5 → "€ 12,50"
     *
     * number_format($valor, 2) formatea el número con exactamente 2 decimales.
     *
     * @return string Precio listo para mostrar en la vista.
     */
    public function getPrecioFormateadoAttribute(): string
    {
        if ($this->es_gratuito) {
            return 'Gratis';
        }
        return '€ ' . number_format($this->precio_base, 2);
    }
}
