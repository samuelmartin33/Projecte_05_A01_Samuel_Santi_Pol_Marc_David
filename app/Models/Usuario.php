<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

/**
 * Modelo Usuario — Modelo principal de autenticación de VIBEZ.
 *
 * IMPORTANTE: Este modelo extiende Authenticatable (y no el Model básico de Eloquent)
 * porque es el modelo que Laravel usa para gestionar sesiones, login y autenticación.
 * Laravel lo localiza a través de config/auth.php → 'providers' → 'users' → 'model'.
 * Al extender Authenticatable obtenemos gratis métodos como getAuthIdentifier(),
 * getAuthPassword(), etc., que el sistema de login necesita internamente.
 *
 * El trait Notifiable permite enviar notificaciones (email, SMS, etc.) al usuario.
 *
 * @property int         $id
 * @property string      $nombre
 * @property string      $apellido1
 * @property string|null $apellido2
 * @property string      $email
 * @property string      $password_hash       Hash bcrypt de la contraseña.
 * @property string|null $foto_url
 * @property string|null $biografia
 * @property string|null $mood
 * @property string|null $fecha_nacimiento
 * @property string|null $telefono
 * @property bool        $email_verificado
 * @property string|null $tipo_cuenta         Puede ser 'empresa', 'organizador', etc.
 * @property int         $estado_registro
 * @property bool        $es_admin            true si el usuario tiene permisos de administrador.
 * @property string|null $ultimo_acceso
 * @property int         $estado
 * @property string|null $fecha_creacion
 * @property string|null $fecha_actualizacion
 */
class Usuario extends Authenticatable
{
    use Notifiable;

    /* ——— Tabla y timestamps ——— */

    // Laravel asume por convención que la tabla se llama igual que el modelo en plural
    // y en inglés (p.ej. "users"). Como nuestra tabla se llama "usuarios", necesitamos
    // indicarlo explícitamente con $table.
    protected $table = 'usuarios';

    // Laravel espera columnas "created_at" y "updated_at" para gestionar timestamps
    // automáticamente. Nuestra tabla usa "fecha_creacion" y "fecha_actualizacion",
    // así que desactivamos el comportamiento automático de Eloquent con false.
    public $timestamps = false;

    /* ——— Campos asignables en masa ——— */

    // $fillable es la lista blanca de columnas que se pueden asignar con
    // métodos como create() o fill(). Protege contra ataques de Mass Assignment,
    // donde un usuario malicioso podría intentar asignar campos no deseados
    // (como es_admin) enviando datos extra en un formulario o petición HTTP.
    protected $fillable = [
        'nombre',
        'apellido1',
        'apellido2',
        'email',
        'password_hash',
        'foto_url',
        'biografia',
        'mood',
        'fecha_nacimiento',
        'telefono',
        'email_verificado',
        'tipo_cuenta',
        'estado_registro',
        'es_admin',
        'ultimo_acceso',
        'estado',
        'fecha_creacion',
        'fecha_actualizacion',
    ];

    /* ——— Campos ocultos en serialización ——— */

    // $hidden define los campos que se OCULTAN automáticamente cuando el modelo
    // se convierte a array o JSON (por ejemplo, al devolver un recurso API).
    // El hash de la contraseña nunca debe viajar al frontend: aunque es un hash
    // bcrypt (no reversible), exponerlo sería un riesgo de seguridad innecesario.
    protected $hidden = ['password_hash'];

    /* ——— Casts ——— */

    /**
     * Define cómo Eloquent convierte los valores al leer/escribir cada columna.
     *
     * - 'hashed': cuando asignamos $usuario->password_hash = 'texto',
     *   Laravel aplica automáticamente bcrypt (Hash::make) antes de guardar en BD.
     *   Así no necesitamos llamar a Hash::make() manualmente en el controlador.
     *
     * - 'boolean': la BD almacena 0/1 (TINYINT), pero el cast nos permite usar
     *   true/false en PHP. Ejemplo: if ($usuario->es_admin) { ... }
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'password_hash'    => 'hashed',    // bcrypt automático al asignar
            'email_verificado' => 'boolean',   // 0/1 en BD → true/false en PHP
            'es_admin'         => 'boolean',   // 0/1 en BD → true/false en PHP
        ];
    }


    /* ——— Métodos que Laravel Auth necesita sobreescribir ——— */

    /**
     * Indica a Laravel qué columna de la tabla contiene la contraseña hasheada.
     *
     * Por defecto Laravel busca la columna "password". Como nuestra columna se
     * llama "password_hash", sobreescribimos este método para que Auth::attempt()
     * y Hash::check() busquen en la columna correcta.
     *
     * @return string Nombre de la columna que contiene el hash de la contraseña.
     */
    public function getAuthPasswordName(): string
    {
        return 'password_hash';
    }

    /**
     * Desactiva la funcionalidad "Recuérdame" (Remember Me).
     *
     * La funcionalidad remember_token requiere una columna del mismo nombre en la tabla.
     * Como nuestra tabla no la tiene, devolvemos null para que Laravel no intente
     * leer ni escribir ese token, evitando errores de columna inexistente.
     *
     * @return string|null null desactiva la funcionalidad.
     */
    public function getRememberTokenName(): ?string
    {
        return null;
    }

    /* ——— Relaciones ——— */

    /**
     * Relación HasOne con la tabla organizadores.
     *
     * HasOne significa "este usuario TIENE UNO en la otra tabla".
     * Si existe una fila en "organizadores" con usuario_id = $this->id,
     * entonces este usuario tiene el rol de organizador.
     * Acceso: $usuario->organizador  (devuelve un objeto Organizador o null)
     *
     * @return HasOne
     */
    public function organizador(): HasOne
    {
        return $this->hasOne(Organizador::class, 'usuario_id');
    }

    /**
     * Relación HasOne con la tabla empresas.
     *
     * Si existe una fila en "empresas" con usuario_id = $this->id,
     * el usuario es el propietario/administrador de esa empresa.
     * Acceso: $usuario->empresa  (devuelve un objeto Empresa o null)
     *
     * @return HasOne
     */
    public function empresa(): HasOne
    {
        return $this->hasOne(Empresa::class, 'usuario_id');
    }

    /**
     * Relación BelongsToMany: eventos marcados como favoritos por este usuario.
     *
     * BelongsToMany implica una tabla intermedia (pivot): "eventos_favoritos".
     * La estructura es: usuarios ←→ eventos_favoritos ←→ eventos.
     * wherePivot('estado', 1) filtra solo los favoritos activos (no eliminados).
     * withPivot() expone columnas extra de la tabla pivot como propiedades accesibles.
     * Acceso: $usuario->favoritos  (devuelve una colección de objetos Evento)
     *
     * @return BelongsToMany
     */
    public function favoritos(): BelongsToMany
    {
        return $this->belongsToMany(Evento::class, 'eventos_favoritos', 'usuario_id', 'evento_id')
            ->wherePivot('estado', 1)
            ->withPivot(['estado', 'fecha_creacion', 'fecha_actualizacion']);
    }

    /**
     * Empresas/promotoras que este usuario sigue.
     * Acceso: $usuario->seguimientos  →  colección de objetos Empresa.
     */
    public function seguimientos(): BelongsToMany
    {
        return $this->belongsToMany(Empresa::class, 'seguimientos_empresa', 'usuario_id', 'empresa_id')
            ->withPivot('fecha_creacion');
    }

    /* ——— Helpers de rol ——— */

    /**
     * Comprueba si el usuario tiene el rol indicado usando un único método.
     *
     * Centraliza la lógica de roles para poder hacer: $usuario->hasRole('admin').
     * Internamente delega en los métodos isAdmin(), isOrganizador(), etc.
     *
     * Los roles se derivan de la estructura existente de la BD:
     *   - admin       → columna es_admin = 1 en tabla usuarios
     *   - organizador → existe fila activa en tabla organizadores (usuario_id = este usuario)
     *   - empresa     → existe fila activa en tabla empresas (usuario_id = este usuario)
     *   - usuario     → ninguno de los anteriores (cuenta estándar)
     *
     * Eloquent cachea las relaciones en memoria del objeto, así que llamadas
     * repetidas a isOrganizador() / isEmpresa() no generan consultas SQL extra.
     *
     * @param  string  $role  Uno de: 'admin' | 'organizador' | 'empresa' | 'usuario'
     * @return bool    true si el usuario tiene ese rol, false en caso contrario.
     */
    public function hasRole(string $role): bool
    {
        return match($role) {
            'admin'       => $this->isAdmin(),
            'organizador' => $this->isOrganizador(),
            'empresa'     => $this->isEmpresa(),
            'usuario'     => $this->isUsuario(),
            default       => false,
        };
    }

    /**
     * Devuelve true si el usuario es administrador del sistema.
     *
     * Comprueba la columna booleana es_admin. El cast 'boolean' definido en
     * casts() garantiza que el valor de BD (0 o 1) se convierta a bool.
     *
     * @return bool
     */
    public function isAdmin(): bool
    {
        return (bool) $this->es_admin;
    }

    /**
     * Devuelve true si el usuario tiene un registro activo en la tabla organizadores.
     *
     * Comprueba dos condiciones:
     *   1. Que exista una fila relacionada en "organizadores" ($this->organizador !== null).
     *   2. Que esa fila tenga estado = 1 (cuenta activa, no suspendida).
     *
     * El acceso a $this->organizador usa lazy loading: la primera vez lanza
     * un SELECT a la BD, pero Eloquent almacena el resultado en caché del objeto.
     *
     * @return bool
     */
    public function isOrganizador(): bool
    {
        return $this->organizador !== null
            && (int) $this->organizador->estado === 1;
    }

    public function isPortero(): bool
    {
        return $this->isOrganizador()
            && $this->organizador->rol === 'portero';
    }

    /**
     * Devuelve true si el usuario representa a una empresa.
     *
     * Usa dos estrategias de detección para mayor robustez:
     *   1. Comprueba si existe fila activa en la tabla "empresas" (fuente principal).
     *   2. Como fallback, verifica el campo tipo_cuenta = 'empresa' (fuente secundaria).
     *
     * El operador || (OR) garantiza que cualquiera de las dos condiciones es suficiente.
     *
     * @return bool
     */
    public function isEmpresa(): bool
    {
        // Opción 1: tiene registro activo en tabla empresas
        if ($this->empresa !== null && (int) $this->empresa->estado === 1) {
            return true;
        }

        // Opción 2: tipo_cuenta marcado como empresa
        return $this->tipo_cuenta === 'empresa';
    }

    /**
     * Devuelve true si el usuario es un usuario regular (cuenta estándar).
     *
     * Un usuario "regular" es aquel que no tiene ningún rol especial asignado.
     * Se comprueba por descarte: si no es admin, ni organizador, ni empresa,
     * entonces es un usuario normal.
     *
     * @return bool
     */
    public function isUsuario(): bool
    {
        return ! $this->isAdmin()
            && ! $this->isOrganizador()
            && ! $this->isEmpresa();
    }
}
