<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

/**
 * Modelo Empresa — Representa una empresa registrada en la plataforma VIBEZ.
 *
 * Una empresa es una entidad jurídica (autónomo, S.L., S.A., etc.) que puede
 * organizar eventos a través de la plataforma. La arquitectura es:
 *
 *   Usuario (cuenta) → Empresa (perfil empresa) → Organizador → Evento
 *
 * Por qué existe el modelo Organizador como intermediario:
 *   Una empresa puede tener VARIOS organizadores (personas físicas dentro de la
 *   empresa que gestionan eventos). El modelo Organizador actúa como perfil
 *   público del gestor de eventos, con su propio nombre artístico, descripción,
 *   etc. Esto permite que una empresa tenga múltiples "marcas" o "promotores"
 *   bajo su paraguas, cada uno con sus propios eventos.
 *
 * Campos identificativos de la empresa:
 *   - nif_cif       → Número de Identificación Fiscal o Código de Identificación
 *                     Fiscal. Es el identificador legal único de la empresa en España.
 *                     Ejemplos: B12345678 (sociedad), 12345678A (autónomo).
 *   - razon_social  → Nombre legal oficial de la empresa tal como aparece en el
 *                     Registro Mercantil. Ejemplo: "Vibez Events, S.L."
 *   - nombre_empresa → Nombre comercial o de marca con el que la empresa se
 *                      presenta al público. Puede diferir de la razón social.
 *                      Ejemplo: "VIBEZ" (mientras la razón social es "Vibez Events, S.L.")
 *
 * @property int         $id
 * @property int         $usuario_id         FK → tabla usuarios. El usuario propietario/admin.
 * @property string      $nombre_empresa     Nombre comercial o de marca.
 * @property string      $razon_social       Nombre legal oficial (Registro Mercantil).
 * @property string      $nif_cif            Identificador fiscal único (NIF/CIF).
 * @property string|null $descripcion        Descripción pública de la empresa.
 * @property string|null $logo_url           URL del logotipo de la empresa.
 * @property string|null $sitio_web          URL del sitio web corporativo.
 * @property string|null $telefono_contacto  Teléfono de contacto empresarial.
 * @property string|null $direccion          Dirección fiscal o sede de la empresa.
 * @property int         $estado             Estado (1=activa, 0=inactiva/suspendida).
 * @property string|null $fecha_creacion
 * @property string|null $fecha_actualizacion
 */
class Empresa extends Model
{
    // Nombre de la tabla en BD. Se declara explícitamente para mayor claridad,
    // aunque Laravel ya asumiría "empresas" por convención (plural en minúsculas).
    protected $table = 'empresas';

    // Desactivamos timestamps automáticos de Laravel (created_at / updated_at)
    // porque la tabla usa los campos personalizados fecha_creacion y fecha_actualizacion.
    public $timestamps = false;

    // Lista blanca de campos asignables masivamente con create() o fill().
    // Protege contra Mass Assignment: solo se asignan los campos listados.
    protected $fillable = [
        'usuario_id',        // vincula la empresa a su usuario propietario
        'nombre_empresa',    // nombre comercial/marca
        'razon_social',      // nombre legal (Registro Mercantil)
        'nif_cif',           // identificador fiscal único
        'descripcion',       // descripción pública de la empresa
        'logo_url',          // URL del logotipo
        'sitio_web',         // web corporativa
        'telefono_contacto', // teléfono de contacto
        'direccion',         // dirección física o fiscal
        'estado',            // 1=activa, 0=inactiva
        'fecha_creacion',
        'fecha_actualizacion',
    ];

    /* ——— Relaciones Eloquent ——— */

    /**
     * Relación BelongsTo: una empresa PERTENECE A un usuario.
     *
     * BelongsTo indica que la clave foránea (usuario_id) está en la tabla "empresas".
     * A través de esta relación accedemos al usuario administrador/propietario
     * de la empresa (email, nombre, foto de perfil, etc.).
     *
     * Acceso: $empresa->usuario  →  objeto Usuario.
     *
     * @return BelongsTo
     */
    public function usuario(): BelongsTo
    {
        return $this->belongsTo(Usuario::class, 'usuario_id');
    }

    /**
     * Relación HasMany: una empresa TIENE MUCHOS organizadores.
     *
     * HasMany indica que la clave foránea (empresa_id) está en la tabla "organizadores".
     * Una empresa puede registrar múltiples organizadores (promotores, gestores de
     * eventos) bajo su marca. Cada organizador puede crear y gestionar sus propios eventos.
     *
     * Acceso: $empresa->organizadores  →  colección de objetos Organizador.
     *
     * @return HasMany
     */
    public function organizadores(): HasMany
    {
        return $this->hasMany(Organizador::class, 'empresa_id');
    }

    /**
     * Relación HasManyThrough: todos los eventos creados por los organizadores de esta empresa.
     *
     * ---- QUÉ ES hasManyThrough ----
     * HasManyThrough permite acceder a registros de una tabla lejana (Evento)
     * PASANDO ATRAVÉS de una tabla intermedia (Organizador), sin necesidad de
     * hacer dos consultas separadas ni unir manualmente los resultados.
     *
     * ---- ESTRUCTURA DE LAS 3 TABLAS ----
     *   empresas          organizadores          eventos
     *   --------          -------------          -------
     *   id ←————————— empresa_id             organizador_id ————→ id
     *                     id ←——————————————— organizador_id
     *
     * ---- PARÁMETROS DEL MÉTODO ----
     * hasManyThrough(
     *   Evento::class,      // Modelo final que queremos obtener
     *   Organizador::class, // Modelo intermedio que hace de puente
     *   'empresa_id',       // FK en la tabla intermedia (organizadores.empresa_id → empresas.id)
     *   'organizador_id',   // FK en la tabla final (eventos.organizador_id → organizadores.id)
     *   'id',               // PK de la tabla actual (empresas.id)
     *   'id'                // PK de la tabla intermedia (organizadores.id)
     * )
     *
     * ---- EQUIVALENCIA EN SQL ----
     * SELECT eventos.*
     * FROM eventos
     * INNER JOIN organizadores ON organizadores.id = eventos.organizador_id
     * WHERE organizadores.empresa_id = {$this->id}
     *
     * ---- VENTAJA PRÁCTICA ----
     * En lugar de hacer:
     *   $organizadores = $empresa->organizadores; // query 1
     *   $eventos = $organizadores->flatMap->eventos; // N queries (problema N+1)
     *
     * Podemos hacer en una sola query:
     *   $eventos = $empresa->eventos; // 1 sola query SQL eficiente
     *
     * Acceso: $empresa->eventos  →  colección de objetos Evento.
     *
     * @return HasManyThrough
     */
    public function eventos(): HasManyThrough
    {
        return $this->hasManyThrough(
            \App\Models\Evento::class, // modelo final: lo que queremos obtener
            Organizador::class,        // modelo intermedio: el puente entre empresa y evento
            'empresa_id',              // FK en tabla "organizadores" → apunta a "empresas.id"
            'organizador_id',          // FK en tabla "eventos" → apunta a "organizadores.id"
            'id',                      // PK de la tabla actual "empresas"
            'id'                       // PK de la tabla intermedia "organizadores"
        );
    }

    /**
     * Relación HasManyThrough: todas las ofertas de trabajo publicadas por los organizadores.
     *
     * Mismo patrón que eventos(): accede a BolsaOfertaTrabajo pasando por Organizador.
     * Permite a una empresa ver todas las ofertas de empleo publicadas por sus
     * organizadores sin necesidad de consultas SQL adicionales.
     *
     * Estructura:
     *   Empresa (1) → HasManyThrough → (N) BolsaOfertaTrabajo
     *   pasando por → Organizador (tabla intermedia)
     *
     * Acceso: $empresa->ofertas  →  colección de objetos BolsaOfertaTrabajo.
     *
     * @return HasManyThrough
     */
    public function ofertas(): HasManyThrough
    {
        return $this->hasManyThrough(
            BolsaOfertaTrabajo::class, // modelo final: oferta de trabajo
            Organizador::class,        // modelo intermedio: organizador de la empresa
            'empresa_id',              // FK en "organizadores" → apunta a "empresas.id"
            'organizador_id',          // FK en "bolsa_ofertas_trabajo" → apunta a "organizadores.id"
            'id',                      // PK de "empresas"
            'id'                       // PK de "organizadores"
        );
    }
}
