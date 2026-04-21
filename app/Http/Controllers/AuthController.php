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

class AuthController extends Controller
{
    /* ============================================================
       VISTAS
       ============================================================ */

    /**
     * Muestra la vista de login.
     * Si ya hay sesión activa redirige directo al dashboard.
     */
    public function showLogin(): View|RedirectResponse
    {
        if (Auth::check()) {
            return redirect()->route('index');
        }
        return view('login');
    }

    /**
     * Muestra la vista de registro.
     * Si ya hay sesión activa redirige directo al dashboard.
     */
    public function showRegister(): View|RedirectResponse
    {
        if (Auth::check()) {
            return redirect()->route('index');
        }
        return view('register');
    }

    /**
     * Muestra el dashboard (protegido por middleware 'auth').
     */
    public function showIndex(): View
    {
        return view('index');
    }

    /* ============================================================
       ENDPOINTS AJAX — respuestas JSON consistentes:
       { success: bool, data: mixed, message: string }
       ============================================================ */

    /**
     * Procesa el login por AJAX contra la tabla 'usuarios'.
     * Auth::attempt busca por 'email' y verifica con getAuthPasswordName()
     * que en el modelo Usuario devuelve 'password_hash'.
     * Rate limiting: máx 5 intentos/minuto (configurado en rutas).
     */
    public function login(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'email'    => ['required', 'email'],
            'password' => ['required', 'min:8'],
        ], [
            'email.required'    => 'El email es obligatorio',
            'email.email'       => 'El formato del email no es válido',
            'password.required' => 'La contraseña es obligatoria',
            'password.min'      => 'La contraseña debe tener al menos 8 caracteres',
        ]);

        // Auth::attempt usa el modelo configurado en auth.php (Usuario)
        // 'password' en el array siempre es el valor en texto plano a verificar;
        // Laravel internamente llama a getAuthPasswordName() para saber contra
        // qué columna comparar (en este caso, 'password_hash')
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

        // Bloquear acceso hasta que el admin verifique la cuenta
        if (! $usuario->email_verificado) {
            Auth::logout();
            return response()->json([
                'success'    => false,
                'unverified' => true,
                'message'    => 'Tu cuenta aún no ha sido verificada por el administrador. Revisa tu Gmail: recibirás un correo cuando tu cuenta esté activa y puedas iniciar sesión.',
                'data'       => null,
            ], 403);
        }

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
     * Procesa el registro por AJAX y guarda en la tabla 'usuarios'.
     * El cast 'hashed' en password_hash encripta automáticamente.
     * Hace auto-login tras el registro.
     * Rate limiting: máx 5 intentos/minuto (configurado en rutas).
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
        $esEmpresa = $validated['tipo_cuenta'] === 'empresa';

        $usuario = Usuario::create([
            'nombre'              => $validated['nombre'],
            'apellido1'           => $validated['apellido1'],
            'apellido2'           => $validated['apellido2'],
            'email'               => $validated['email'],
            'password_hash'       => $validated['password'],
            'fecha_nacimiento'    => $validated['fecha_nacimiento'],
            'telefono'            => $validated['telefono'],
            'tipo_cuenta'         => $validated['tipo_cuenta'],
            'email_verificado'    => $esEmpresa ? 0 : 1,
            'estado_registro'     => $esEmpresa ? 'pendiente' : 'aprobado',
            'es_admin'            => 0,
            'estado'              => 1,
            'fecha_creacion'      => $ahora,
            'fecha_actualizacion' => $ahora,
        ]);

        if (! $esEmpresa) {
            Auth::login($usuario);
            $request->session()->regenerate();

            return response()->json([
                'success' => true,
                'status'  => 'active',
                'message' => '¡Cuenta creada! Ya puedes acceder.',
                'data'    => ['user' => ['id' => $usuario->id, 'nombre' => $usuario->nombre]],
            ], 201);
        }

        return response()->json([
            'success' => true,
            'status'  => 'pending',
            'message' => 'Solicitud enviada. Tu cuenta está pendiente de aprobación por el administrador.',
            'data'    => null,
        ], 201);
    }

    /**
     * Autentica con Google Identity Services.
     * Verifica el JWT con la API de Google, luego busca o crea el usuario.
     */
    public function googleAuth(Request $request): JsonResponse
    {
        $request->validate([
            'credential' => ['required', 'string'],
        ]);

        // En local, WAMP puede no tener el bundle de CA configurado — desactivamos
        // la verificación SSL solo en entorno de desarrollo.
        $http = app()->environment('local')
            ? Http::withOptions(['verify' => false])
            : Http::new();

        $googleResponse = $http->get('https://oauth2.googleapis.com/tokeninfo', [
            'id_token' => $request->credential,
        ]);

        if (! $googleResponse->ok()) {
            return response()->json([
                'success' => false,
                'message' => 'Token de Google no válido. Inténtalo de nuevo.',
                'data'    => null,
            ], 401);
        }

        $payload  = $googleResponse->json();
        $clientId = config('services.google.client_id');

        if (! in_array($clientId, [$payload['aud'] ?? '', $payload['azp'] ?? ''])) {
            return response()->json([
                'success' => false,
                'message' => 'Token de Google no válido.',
                'data'    => null,
            ], 401);
        }

        $email = $payload['email'] ?? null;

        if (! $email) {
            return response()->json([
                'success' => false,
                'message' => 'No se pudo obtener el email de la cuenta de Google.',
                'data'    => null,
            ], 422);
        }

        $ahora   = now();
        $usuario = Usuario::where('email', $email)->first();

        if (! $usuario) {
            $usuario = Usuario::create([
                'nombre'              => $payload['given_name'] ?? $payload['name'] ?? 'Usuario',
                'apellido1'           => $payload['family_name'] ?? null,
                'email'               => $email,
                'password_hash'       => Str::uuid()->toString(),
                'email_verificado'    => 1,
                'es_admin'            => 0,
                'estado'              => 1,
                'ultimo_acceso'       => $ahora,
                'fecha_creacion'      => $ahora,
                'fecha_actualizacion' => $ahora,
            ]);
        } else {
            $usuario->update([
                'ultimo_acceso'       => $ahora,
                'fecha_actualizacion' => $ahora,
            ]);
        }

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
     * Cierra la sesión por AJAX.
     */
    public function logout(Request $request): JsonResponse
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return response()->json([
            'success' => true,
            'message' => 'Sesión cerrada correctamente',
            'data'    => null,
        ]);
    }
}
