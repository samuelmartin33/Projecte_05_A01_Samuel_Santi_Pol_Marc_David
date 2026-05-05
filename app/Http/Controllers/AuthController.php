<?php

namespace App\Http\Controllers;

use App\Models\Usuario;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use Illuminate\View\View;
use App\Http\Controllers\EventoController;

/**
 * AuthController — Controlador de autenticación de VIBEZ.
 *
 * Responsabilidades:
 *  - Mostrar las vistas de login y registro.
 *  - Procesar el inicio de sesión con email/contraseña.
 *  - Procesar el registro de nuevos usuarios (clientes y empresas).
 *  - Autenticar con Google Identity Services (OAuth2 via JWT).
 *  - Cerrar la sesión del usuario.
 *  - Redirigir al dashboard correcto según el rol del usuario.
 *
 * Modelos y roles gestionados:
 *  - Usuario (model): tabla 'usuarios' con campo 'tipo_cuenta' (cliente/empresa).
 *  - Roles: admin, empresa, organizador, usuario (cliente normal).
 *
 * Conceptos clave para el alumno:
 *  - Auth::attempt(): comprueba credenciales contra la BD e inicia sesión si son correctas.
 *    Usa el modelo configurado en config/auth.php (aquí: Usuario, NO el User por defecto).
 *    La columna de contraseña la define getAuthPasswordName() en el modelo → 'password_hash'.
 *  - Auth::check(): devuelve true si hay un usuario con sesión activa.
 *  - Auth::login($usuario): inicia sesión directamente sin verificar contraseña
 *    (útil tras registro o autenticación OAuth).
 *  - $request->session()->regenerate(): cambia el ID de sesión tras el login
 *    para prevenir ataques de fijación de sesión (session fixation).
 *  - $request->expectsJson(): devuelve true si la petición incluye el header
 *    'Accept: application/json' (peticiones AJAX/fetch desde JavaScript).
 */
class AuthController extends Controller
{
    /* ============================================================
       VISTAS
       ============================================================ */

    /**
     * Muestra la vista de login.
     * Si ya hay sesión activa redirige directo al home de usuario.
     *
     * Auth::check() devuelve true si existe un usuario autenticado en la sesión.
     * Evita que un usuario que ya inició sesión vea de nuevo el formulario de login.
     *
     * @return View|RedirectResponse  Vista 'login' o redirección a 'home'.
     */
    public function showLogin(): View|RedirectResponse
    {
        if (Auth::check()) {
            return redirect()->route('home');
        }
        return view('login');
    }

    /**
     * Muestra la vista de registro.
     * Si ya hay sesión activa redirige directo al home de usuario.
     *
     * Mismo comportamiento que showLogin(): si el usuario ya está autenticado
     * no tiene sentido mostrarle el formulario de registro.
     *
     * @return View|RedirectResponse  Vista 'register' o redirección a 'home'.
     */
    public function showRegister(): View|RedirectResponse
    {
        if (Auth::check()) {
            return redirect()->route('home');
        }
        return view('register');
    }

    /**
     * Home del usuario autenticado (explorar eventos, bolsa de trabajo, cupones).
     * Delega en EventoController para cargar los datos necesarios.
     * Las empresas son redirigidas a su panel específico.
     *
     * Por qué delega en EventoController:
     *  La home de usuario muestra principalmente eventos, por lo que reutilizamos
     *  el método index() de EventoController en lugar de duplicar la lógica.
     *
     * @return mixed  Vista de home de usuario o redirección a empresa.home.
     */
    public function showHome(): mixed
    {
        /** @var \App\Models\Usuario $usuario */
        $usuario = Auth::user();

        // Las empresas no ven la home pública — redirigir a su panel
        if ($usuario && $usuario->isEmpresa()) {
            return redirect()->route('empresa.home');
        }

        return (new EventoController())->index();
    }

    /**
     * Home de empresa autenticada.
     * Muestra información de la empresa: sus eventos, trabajadores y ofertas.
     * Las empresas no ven la bolsa de trabajo pública ni pueden comprar entradas.
     *
     * Usa lazy loading explícito (load) para cargar las relaciones necesarias
     * una vez que ya tenemos el usuario en memoria.
     * collect() devuelve una colección vacía cuando la empresa no existe, evitando
     * errores al iterar en la vista con un valor nulo.
     *
     * @return View  Vista 'empresa.home' con usuario, empresa, eventos, trabajadores y ofertas.
     */
    public function showEmpresaHome(): View
    {
        /** @var \App\Models\Usuario $usuario */
        $usuario = Auth::user();

        // Cargar la empresa con sus relaciones
        $usuario->load('empresa.organizadores.usuario');
        $empresa = $usuario->empresa;

        // Eventos de la empresa (a través de sus organizadores)
        $eventos = $empresa
            ? $empresa->eventos()
                ->with(['categoria', 'portada'])
                ->where('eventos.estado', 1)
                ->orderBy('eventos.fecha_inicio', 'desc')
                ->get()
            : collect();

        // Organizadores/trabajadores de la empresa
        $trabajadores = $empresa
            ? $empresa->organizadores()
                ->with('usuario')
                ->where('organizadores.estado', 1)
                ->get()
            : collect();

        // Ofertas de trabajo publicadas por la empresa
        $ofertas = $empresa
            ? $empresa->ofertas()
                ->with('categoria')
                ->where('bolsa_ofertas_trabajo.estado', 1)
                ->orderBy('bolsa_ofertas_trabajo.fecha_creacion', 'desc')
                ->get()
            : collect();

        return view('empresa.home', compact('usuario', 'empresa', 'eventos', 'trabajadores', 'ofertas'));
    }

    /* ============================================================
       AUTENTICACIÓN — Form POST con redirección basada en rol
       ============================================================ */

    /**
     * Procesa el login enviado desde el formulario HTML (form POST).
     *
     * En caso de éxito, redirige al dashboard según el rol:
     *   admin      → /admin/dashboard
     *   empresa    → /empresa/dashboard
     *   organizador → /organizador/dashboard
     *   usuario    → /index
     *
     * En caso de error, redirige de vuelta con los errores de validación
     * y el email anterior para no obligar al usuario a reescribirlo.
     *
     * Rate limiting: máx 5 intentos/minuto (configurado en routes/api.php).
     *
     * Por qué Auth::attempt() con modelo personalizado:
     *  Laravel por defecto usa el modelo App\Models\User, pero VIBEZ usa
     *  App\Models\Usuario con una columna de contraseña llamada 'password_hash'
     *  (en vez del estándar 'password'). El modelo sobreescribe getAuthPasswordName()
     *  para indicarle a Auth::attempt() qué columna comparar con bcrypt.
     *
     * Por qué se comprueba email_verificado DESPUÉS de Auth::attempt():
     *  Primero comprobamos que las credenciales son correctas (email + contraseña).
     *  Solo si son válidas verificamos si la cuenta está aprobada. Así evitamos
     *  revelar si una cuenta existe o no a alguien con credenciales incorrectas.
     *
     * @param  Request                    $request  Petición con 'email' y 'password'.
     * @return RedirectResponse|JsonResponse        JSON con resultado del login.
     */
    public function login(Request $request): RedirectResponse|JsonResponse
    {
        $validated = $request->validate([
            'email'    => ['required', 'email'],
            'password' => ['required', 'min:8'],
        ], [
            'email.required'    => 'El email es obligatorio.',
            'email.email'       => 'El formato del email no es válido.',
            'password.required' => 'La contraseña es obligatoria.',
            'password.min'      => 'La contraseña debe tener al menos 8 caracteres.',
        ]);

        // Auth::attempt() busca el usuario por email en la tabla 'usuarios' (modelo
        // configurado en config/auth.php) y compara la contraseña con el hash
        // almacenado en la columna 'password_hash' (definida en getAuthPasswordName()).
        // Devuelve true si las credenciales son correctas e inicia la sesión.
        if (! Auth::attempt([
            'email'    => $validated['email'],
            'password' => $validated['password'],
        ])) {
            return response()->json([
                'success' => false,
                'message' => 'Credenciales incorrectas. Revisa tu email y contraseña.',
                'data'    => null,
            ], 401);
        }

        /** @var Usuario $usuario */
        $usuario = Auth::user();

        // Comprobación de verificación de cuenta:
        // Las empresas y cuentas rechazadas tienen email_verificado = 0.
        // Si el usuario no está verificado, cerramos la sesión que acaba de abrirse
        // (Auth::attempt la inicia aunque la cuenta esté pendiente) y devolvemos
        // un error 403 con el motivo del bloqueo.
        if (! $usuario->email_verificado) {
            Auth::logout();

            if ($usuario->estado_registro === 'rechazado') {
                $mensaje = 'Tu cuenta está rechazada, si no estás de acuerdo habla con atención al cliente.';
            } else {
                $mensaje = 'Tu cuenta está pendiente de revisión. El administrador la aprobará pronto y recibirás un correo cuando esté activa.';
            }

            return response()->json([
                'success'         => false,
                'unverified'      => true,
                'estado_registro' => $usuario->estado_registro,
                'message'         => $mensaje,
                'data'            => null,
            ], 403);
        }

        // regenerate() cambia el ID de la sesión para evitar ataques de session fixation:
        // si alguien robó el ID de sesión antes del login, ese ID ya no servirá.
        $request->session()->regenerate();

        $ahora = now();
        $usuario->update([
            'ultimo_acceso'       => $ahora,
            'fecha_actualizacion' => $ahora,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Sesión iniciada correctamente',
            'data'    => [
                'user' => [
                    'id'     => $usuario->id,
                    'nombre' => $usuario->nombre,
                    'email'  => $usuario->email,
                ],
            ],
        ]);
    }

    /**
     * Procesa el registro enviado desde el formulario HTML (form POST).
     *
     * Diferencia de flujo según tipo de cuenta:
     *  - CLIENTE (tipo_cuenta = 'cliente'):
     *      email_verificado = 1, estado_registro = 'aprobado'.
     *      El usuario puede iniciar sesión inmediatamente.
     *      Tras crear la cuenta se hace auto-login (Auth::login) y se devuelve
     *      un JSON con status 'active'.
     *
     *  - EMPRESA (tipo_cuenta = 'empresa'):
     *      email_verificado = 0, estado_registro = 'pendiente'.
     *      La cuenta queda bloqueada hasta que el administrador la apruebe.
     *      No se hace auto-login. Se devuelve un JSON con status 'pending'.
     *      El motivo: las empresas publican eventos y ofertas, por lo que
     *      necesitan verificación manual para evitar abusos.
     *
     * Por qué 'password_hash' recibe la contraseña en texto plano:
     *  El modelo Usuario tiene un mutator (setPasswordHashAttribute o cast) que
     *  aplica bcrypt automáticamente antes de guardar en la BD, igual que el
     *  campo 'password' en el modelo User por defecto de Laravel.
     *
     * @param  Request      $request  Petición con todos los campos del formulario de registro.
     * @return JsonResponse           JSON con 'success', 'status' ('active'/'pending') y 'message'.
     */
    public function register(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'nombre'                => ['required', 'string', 'min:2', 'max:100'],
            'apellido1'             => ['required', 'string', 'min:2', 'max:150'],
            'apellido2'             => ['required', 'string', 'min:2', 'max:150'],
            'email'                 => ['required', 'email', 'unique:usuarios,email'],
            'password'              => ['required', 'min:8', 'confirmed'],
            'password_confirmation' => ['required'],
            'fecha_nacimiento'      => ['required', 'date', 'before:-14 years'],
            'telefono'              => ['required', 'string', 'max:20', 'regex:/^\+?[\d\s\-]{7,20}$/'],
            'tipo_cuenta'           => ['required', 'in:cliente,empresa'],
        ], [
            'nombre.required'                => 'El nombre es obligatorio',
            'nombre.min'                     => 'El nombre debe tener al menos 2 caracteres',
            'apellido1.required'             => 'El primer apellido es obligatorio',
            'apellido1.min'                  => 'El primer apellido debe tener al menos 2 caracteres',
            'apellido2.required'             => 'El segundo apellido es obligatorio',
            'apellido2.min'                  => 'El segundo apellido debe tener al menos 2 caracteres',
            'email.required'                 => 'El email es obligatorio',
            'email.email'                    => 'El formato del email no es válido',
            'email.unique'                   => 'Este email ya está registrado',
            'password.required'              => 'La contraseña es obligatoria',
            'password.min'                   => 'La contraseña debe tener al menos 8 caracteres',
            'password.confirmed'             => 'Las contraseñas no coinciden',
            'password_confirmation.required' => 'Confirma tu contraseña',
            'fecha_nacimiento.required'      => 'La fecha de nacimiento es obligatoria',
            'fecha_nacimiento.date'          => 'La fecha no es válida',
            'fecha_nacimiento.before'        => 'Debes tener al menos 14 años',
            'telefono.required'              => 'El teléfono es obligatorio',
            'telefono.regex'                 => 'Introduce un teléfono válido',
            'tipo_cuenta.required'           => 'Selecciona el tipo de cuenta',
            'tipo_cuenta.in'                 => 'Tipo de cuenta no válido',
        ]);

        $ahora     = now();
        // Variable booleana que determina si el usuario se registra como empresa.
        // Controla el flujo bifurcado de verificación más abajo.
        $esEmpresa = $validated['tipo_cuenta'] === 'empresa';

        // Creamos el usuario. La columna email_verificado y estado_registro
        // se asignan de forma diferente según si es empresa o cliente.
        $usuario = Usuario::create([
            'nombre'              => $validated['nombre'],
            'apellido1'           => $validated['apellido1'],
            'apellido2'           => $validated['apellido2'],
            'email'               => $validated['email'],
            'password_hash'       => $validated['password'],  // El modelo aplica bcrypt automáticamente
            'fecha_nacimiento'    => $validated['fecha_nacimiento'],
            'telefono'            => $validated['telefono'],
            'tipo_cuenta'         => $validated['tipo_cuenta'],
            // Las empresas empiezan sin verificar (0) y pendientes; los clientes quedan activos (1).
            'email_verificado'    => $esEmpresa ? 0 : 1,
            'estado_registro'     => $esEmpresa ? 'pendiente' : 'aprobado',
            'es_admin'            => 0,
            'estado'              => 1,
            'fecha_creacion'      => $ahora,
            'fecha_actualizacion' => $ahora,
        ]);

        // Flujo CLIENTE: auto-login inmediato y respuesta 'active'.
        if (! $esEmpresa) {
            // Auth::login() inicia la sesión sin pasar por el formulario de login.
            // Es el equivalente a un "login silencioso" tras el registro.
            Auth::login($usuario);
            $request->session()->regenerate();

            return response()->json([
                'success' => true,
                'status'  => 'active',
                'message' => '¡Cuenta creada! Ya puedes acceder.',
                'data'    => ['user' => ['id' => $usuario->id, 'nombre' => $usuario->nombre]],
            ], 201);
        }

        // Flujo EMPRESA: sin auto-login, la cuenta queda en espera de aprobación.
        return response()->json([
            'success' => true,
            'status'  => 'pending',
            'message' => 'Solicitud enviada. Tu cuenta está pendiente de aprobación por el administrador.',
            'data'    => null,
        ], 201);
    }

    /**
     * Autentica con Google Identity Services (OAuth2 mediante JWT).
     *
     * Flujo completo:
     *  1. El navegador obtiene un JWT (credential) de los servidores de Google.
     *  2. Este método envía ese JWT a la API de Google para verificarlo.
     *  3. Si es válido, Google devuelve el payload con los datos del usuario
     *     (email, nombre, apellido, foto...).
     *  4. Verificamos que el 'aud' del token coincide con nuestro client_id de Google
     *     para evitar que se use un token de otra aplicación en VIBEZ.
     *  5. Si el usuario ya existe en la BD (mismo email), lo actualizamos.
     *     Si no existe, lo creamos con email_verificado = 1 (Google ya verificó el email).
     *  6. Iniciamos sesión con Auth::login().
     *
     * Por qué se desactiva la verificación SSL en entorno local:
     *  WAMP en Windows no incluye por defecto el bundle de certificados CA de Mozilla,
     *  por lo que las peticiones HTTPS con cURL fallan al no poder validar el
     *  certificado de los servidores de Google. En producción (servidor real con
     *  certificados configurados) la verificación SSL está siempre activa.
     *  NUNCA desactivar SSL en producción — es solo un workaround de desarrollo.
     *
     * Por qué se usa Str::uuid() como password para usuarios de Google:
     *  Los usuarios de Google no tienen contraseña propia en VIBEZ. Se guarda un
     *  UUID aleatorio como placeholder para cumplir con el campo NOT NULL de la BD.
     *  No se puede usar para iniciar sesión porque nadie lo conoce.
     *
     * @param  Request      $request  Petición con el campo 'credential' (JWT de Google).
     * @return JsonResponse           JSON con 'success', datos del usuario o mensaje de error.
     */
    public function googleAuth(Request $request): JsonResponse
    {
        $request->validate([
            'credential' => ['required', 'string'],
        ]);

        // En local, WAMP puede no tener el bundle de CA configurado — desactivamos
        // la verificación SSL solo en entorno de desarrollo.
        // app()->environment('local') lee la variable APP_ENV del archivo .env.
        $http = app()->environment('local')
            ? Http::withOptions(['verify' => false])
            : Http::new();

        // Enviamos el JWT a la API pública de Google para que lo verifique y
        // nos devuelva el payload con los datos del usuario.
        $googleResponse = $http->get('https://oauth2.googleapis.com/tokeninfo', [
            'id_token' => $request->credential,
        ]);

        // Si Google no responde con 200 OK, el token no es válido.
        if (! $googleResponse->ok()) {
            return response()->json([
                'success' => false,
                'message' => 'Token de Google no válido. Inténtalo de nuevo.',
                'data'    => null,
            ], 401);
        }

        $payload  = $googleResponse->json();
        // Nuestro client_id de Google configurado en config/services.php.
        $clientId = config('services.google.client_id');

        // Verificamos que el token fue emitido para NUESTRA aplicación.
        // 'aud' (audience) y 'azp' (authorized party) deben coincidir con nuestro client_id.
        // Sin esta comprobación, un atacante podría usar un token válido de otra app.
        if (! in_array($clientId, [$payload['aud'] ?? '', $payload['azp'] ?? ''])) {
            return response()->json([
                'success' => false,
                'message' => 'Token de Google no válido.',
                'data'    => null,
            ], 401);
        }

        $email = $payload['email'] ?? null;

        // El email es imprescindible para identificar al usuario en nuestra BD.
        if (! $email) {
            return response()->json([
                'success' => false,
                'message' => 'No se pudo obtener el email de la cuenta de Google.',
                'data'    => null,
            ], 422);
        }

        $ahora   = now();
        // Buscamos si ya existe un usuario con ese email en nuestra BD.
        $usuario = Usuario::where('email', $email)->first();

        if (! $usuario) {
            // El usuario no existe: lo creamos con los datos que Google nos proporciona.
            // 'given_name' → nombre, 'family_name' → apellido (según el estándar OpenID).
            // email_verificado = 1 porque Google ya verificó que el email pertenece al usuario.
            $usuario = Usuario::create([
                'nombre'              => $payload['given_name'] ?? $payload['name'] ?? 'Usuario',
                'apellido1'           => $payload['family_name'] ?? null,
                'email'               => $email,
                // UUID aleatorio como contraseña placeholder (el usuario de Google no tiene contraseña propia).
                'password_hash'       => Str::uuid()->toString(),
                'email_verificado'    => 1,
                'es_admin'            => 0,
                'estado'              => 1,
                'ultimo_acceso'       => $ahora,
                'fecha_creacion'      => $ahora,
                'fecha_actualizacion' => $ahora,
            ]);
        } else {
            // El usuario ya existe: solo actualizamos la fecha de último acceso.
            $usuario->update([
                'ultimo_acceso'       => $ahora,
                'fecha_actualizacion' => $ahora,
            ]);
        }

        // Iniciamos la sesión de Laravel con el usuario (existente o recién creado).
        Auth::login($usuario);
        $request->session()->regenerate();

        return response()->json([
            'success' => true,
            'message' => 'Sesión iniciada con Google correctamente',
            'data'    => [
                'user' => [
                    'id'     => $usuario->id,
                    'nombre' => $usuario->nombre,
                    'email'  => $usuario->email,
                ],
            ],
        ]);
    }

    /**
     * Cierra la sesión del usuario autenticado.
     *
     * Pasos del cierre de sesión:
     *  1. Auth::logout() elimina los datos de autenticación de la sesión.
     *  2. invalidate() destruye completamente la sesión actual en el servidor.
     *  3. regenerateToken() genera un nuevo token CSRF para la próxima petición,
     *     evitando que el token anterior (ya inválido) pueda usarse en ataques CSRF.
     *
     * Doble respuesta según el tipo de petición:
     *  - Petición AJAX (fetch/axios con header 'Accept: application/json'):
     *    $request->expectsJson() devuelve true → respondemos con JSON.
     *    El JavaScript recibe la respuesta y redirige manualmente.
     *  - Petición de formulario HTML tradicional (form POST):
     *    $request->expectsJson() devuelve false → respondemos con redirect.
     *
     * @param  Request                    $request  Petición de cierre de sesión.
     * @return JsonResponse|RedirectResponse        JSON de confirmación o redirección a 'welcome'.
     */
    public function logout(Request $request): JsonResponse|RedirectResponse
    {
        // Paso 1: eliminar los datos de autenticación de la sesión activa.
        Auth::logout();

        // Paso 2: destruir la sesión para que no pueda reutilizarse.
        $request->session()->invalidate();

        // Paso 3: generar un token CSRF nuevo para la siguiente petición.
        $request->session()->regenerateToken();

        // Si el cliente espera JSON (petición AJAX), devolvemos JSON.
        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Sesión cerrada correctamente.',
                'data'    => null,
            ]);
        }

        // Si es una petición de formulario normal, redirigimos a la página de bienvenida.
        return redirect()->route('welcome');
    }

    /* ============================================================
       MÉTODOS PRIVADOS
       ============================================================ */

    /**
     * Devuelve la URL del dashboard según el rol del usuario autenticado.
     * Prioridad: admin > empresa > organizador > usuario
     *
     * Este método centraliza la lógica de redirección por rol para no repetirla
     * en login(), googleAuth() u otros métodos que necesiten redirigir tras
     * autenticar. Al ser privado, solo se puede usar dentro de este controlador.
     *
     * NOTA: actualmente no se invoca desde ningún método del controlador.
     * Está preparado para cuando el login deje de devolver JSON y necesite
     * hacer una redirección directa según el rol (RedirectResponse).
     *
     * @return string  URL completa del dashboard correspondiente al rol del usuario.
     */
    private function redirectByRole(): string
    {
        /** @var \App\Models\Usuario $usuario */
        $usuario = Auth::user();

        // Comprobamos los roles de mayor a menor privilegio.
        // isAdmin(), isEmpresa(), isOrganizador() son métodos definidos en el modelo Usuario.
        if ($usuario->isAdmin()) {
            return route('admin.dashboard');
        }

        if ($usuario->isEmpresa()) {
            return route('empresa.dashboard');
        }

        if ($usuario->isOrganizador()) {
            return route('organizador.dashboard');
        }

        // Si no tiene ningún rol especial, es un cliente normal.
        return route('index');
    }
}
