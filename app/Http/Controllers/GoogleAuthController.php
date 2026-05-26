<?php

namespace App\Http\Controllers;

use App\Models\Usuario;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Laravel\Socialite\Facades\Socialite;

/**
 * GoogleAuthController — Gestiona el flujo OAuth2 con Google via Socialite.
 *
 * Flujo completo:
 *  1. redirectToGoogle() redirige al navegador a la pantalla de consentimiento de Google.
 *  2. El usuario aprueba → Google redirige a la URI de callback con un código de autorización.
 *  3. handleGoogleCallback() intercambia ese código por el perfil del usuario.
 *  4. Se busca o crea el usuario en nuestra BD, priorizando no crear duplicados.
 *  5. Se inicia la sesión de Laravel con Auth::login().
 */
class GoogleAuthController extends Controller
{
    /**
     * Redirige al usuario a la pantalla de consentimiento de Google.
     *
     * Socialite genera la URL de OAuth con los parámetros correctos:
     * client_id, redirect_uri, scope y state (token anti-CSRF).
     * Los scopes 'email' y 'profile' son los predeterminados de Socialite para Google.
     *
     * SSL en WAMP local: Socialite usa Guzzle/cURL internamente. WAMP no incluye
     * el bundle de certificados CA por defecto, por lo que las peticiones HTTPS
     * fallarían. Solo en entorno local se crea un cliente Guzzle con verify=false.
     * NUNCA desactivar SSL en producción.
     *
     * @return RedirectResponse Redirección a la pantalla de consentimiento de Google.
     */
    public function redirectToGoogle(): RedirectResponse
    {
        // Solo en local: deshabilitar verificación SSL (WAMP sin cacert.pem configurado).
        if (app()->environment('local')) {
            $guzzle = new \GuzzleHttp\Client(['verify' => false]);
            return Socialite::driver('google')->setHttpClient($guzzle)->redirect();
        }

        return Socialite::driver('google')->redirect();
    }

    /**
     * Gestiona la respuesta de Google tras el consentimiento del usuario.
     *
     * Lógica de control de duplicados (en orden de prioridad):
     *  1. Buscar por social_id + social_provider='google' → usuario ya vinculado, login directo.
     *  2. Buscar por email (normalizado a minúsculas):
     *     - Si existe → vincular google_id a la cuenta existente y hacer login.
     *     - Si no existe → crear usuario nuevo con los datos de Google.
     *
     * Google ya verifica el email del usuario, por lo que siempre marcamos
     * email_verificado = 1 al crear o vincular cuentas.
     *
     * @return RedirectResponse Home del usuario o login con mensaje flash de error.
     */
    public function handleGoogleCallback(): RedirectResponse
    {
        try {
            // SSL en WAMP local (misma razón que en redirectToGoogle).
            if (app()->environment('local')) {
                $guzzle        = new \GuzzleHttp\Client(['verify' => false]);
                $googleUsuario = Socialite::driver('google')->setHttpClient($guzzle)->user();
            } else {
                $googleUsuario = Socialite::driver('google')->user();
            }
        } catch (\Throwable $e) {
            // El usuario canceló el login o el token fue inválido/caducado.
            Log::error('Google OAuth — error Socialite->user(): ' . $e->getMessage(), [
                'tipo' => get_class($e),
                'en'   => $e->getFile() . ':' . $e->getLine(),
            ]);
            return redirect()->route('login')
                ->with('error', 'No se pudo iniciar sesión con Google. Inténtalo de nuevo.');
        }

        // Normalizar email a minúsculas para evitar duplicados por capitalización diferente.
        $email    = strtolower(trim($googleUsuario->getEmail()));
        $googleId = $googleUsuario->getId();
        $ahora    = now();

        try {
            // Marca si acabamos de crear el usuario en este flujo.
            // Lo usamos después del login para redirigir al perfil a completar datos.
            $esNuevo = false;

            // PASO 1: Buscar por Google ID — identificador único e inmutable de Google.
            // Es más fiable que el email porque el usuario podría cambiar su email en Google.
            $usuario = Usuario::where('social_provider', 'google')
                              ->where('social_id', $googleId)
                              ->first();

            if (! $usuario) {
                // PASO 2: Buscar por email para vincular con cuentas existentes.
                $usuario = Usuario::where('email', $email)->first();

                if ($usuario) {
                    // Cuenta existente (registrada con formulario clásico):
                    // Vinculamos el Google ID y actualizamos email_verificado.
                    // Si el usuario ya tenía foto, respetamos la suya; si no, usamos la de Google.
                    $usuario->update([
                        'social_provider'     => 'google',
                        'social_id'           => $googleId,
                        'email_verificado'    => 1,
                        'foto_url'            => $usuario->foto_url ?? $googleUsuario->getAvatar(),
                        'ultimo_acceso'       => $ahora,
                        'fecha_actualizacion' => $ahora,
                    ]);
                } else {
                    // PASO 3: Usuario nuevo — creamos la cuenta con datos de Google.
                    // Google proporciona: given_name (nombre), family_name (apellido), picture (foto).
                    // getRaw() devuelve el array completo sin lanzar excepción si falta una clave,
                    // a diferencia de offsetGet() que lanza ErrorException si el índice no existe.
                    $raw = $googleUsuario->getRaw();
                    $usuario = Usuario::create([
                        'nombre'              => $raw['given_name'] ?? $googleUsuario->getName() ?? 'Usuario',
                        'apellido1'           => $raw['family_name'] ?? 'Google',
                        'email'               => $email,
                        'password_hash'       => null,          // Sin contraseña — campo nullable desde migración
                        'social_provider'     => 'google',
                        'social_id'           => $googleId,
                        'foto_url'            => $googleUsuario->getAvatar(),
                        'email_verificado'    => 1,             // Google ya verificó que el email existe
                        'es_admin'            => 0,
                        'estado'              => 1,
                        'estado_registro'     => 'aprobado',    // Acceso inmediato como cliente
                        'ultimo_acceso'       => $ahora,
                        'fecha_creacion'      => $ahora,
                        'fecha_actualizacion' => $ahora,
                    ]);
                    $esNuevo = true;
                }
            } else {
                // Usuario ya vinculado con Google: solo actualizamos el último acceso.
                $usuario->update([
                    'ultimo_acceso'       => $ahora,
                    'fecha_actualizacion' => $ahora,
                ]);
            }

            // Iniciar sesión con Laravel.
            // regenerate() cambia el ID de sesión para prevenir ataques de session fixation.
            Auth::login($usuario);
            request()->session()->regenerate();

            // Usuario nuevo: redirigir al perfil para que complete fecha de nacimiento y otros datos.
            // Google no proporciona esa información, el usuario debe rellenarla manualmente.
            if ($esNuevo) {
                return redirect()->route('perfil')
                    ->with('info', '¡Bienvenido a VIBEZ! Tu cuenta ha sido creada con Google. Completa tu fecha de nacimiento y demás datos de perfil.');
            }

            // Redirigir al dashboard según el rol del usuario (mismo patrón que AuthController).
            if ($usuario->isAdmin()) {
                return redirect()->route('admin.dashboard');
            }

            if ($usuario->isEmpresa()) {
                return redirect()->route('empresa.home');
            }

            return redirect()->route('home');

        } catch (\Throwable $e) {
            Log::error('Google OAuth — error en operación de BD: ' . $e->getMessage(), [
                'tipo' => get_class($e),
                'en'   => $e->getFile() . ':' . $e->getLine(),
            ]);
            return redirect()->route('login')
                ->with('error', 'Error al procesar el inicio de sesión con Google. Inténtalo de nuevo.');
        }
    }
}
