<?php

namespace App\Http\Controllers;

use App\Models\Usuario;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthController extends Controller
{
    /* ============================================================
       VISTAS
       ============================================================ */

    /**
     * Muestra la vista de login.
     * Si ya hay sesión activa redirige al dashboard del rol correspondiente.
     */
    public function showLogin(): View|RedirectResponse
    {
        if (Auth::check()) {
            return redirect($this->redirectByRole());
        }
        return view('login');
    }

    /**
     * Muestra la vista de registro.
     * Si ya hay sesión activa redirige al dashboard del rol correspondiente.
     */
    public function showRegister(): View|RedirectResponse
    {
        if (Auth::check()) {
            return redirect($this->redirectByRole());
        }
        return view('register');
    }

    /**
     * Muestra el dashboard de usuario (protegido por middleware 'auth').
     */
    public function showIndex(): View
    {
        return view('index');
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
     */
    public function login(Request $request): RedirectResponse
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

        // Auth::attempt usa el modelo configurado en auth.php (Usuario)
        // y verifica contra la columna que devuelve getAuthPasswordName() → 'password_hash'
        if (! Auth::attempt([
            'email'    => $validated['email'],
            'password' => $validated['password'],
        ])) {
            return back()
                ->withInput($request->only('email'))
                ->with('error', 'Credenciales incorrectas. Revisa tu email y contraseña.');
        }

        $request->session()->regenerate();

        // redirect()->intended() respeta la URL a la que el usuario intentaba acceder
        // antes de ser redirigido al login; si no existe, usa el dashboard del rol
        return redirect()->intended($this->redirectByRole());
    }

    /**
     * Procesa el registro enviado desde el formulario HTML (form POST).
     * Los nuevos usuarios siempre son 'usuario' (sin empresa ni organizador).
     * Hace auto-login tras el registro y redirige al dashboard de usuario.
     */
    public function register(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'nombre'                => ['required', 'string', 'min:2', 'max:100'],
            'apellido1'             => ['required', 'string', 'min:2', 'max:150'],
            'apellido2'             => ['required', 'string', 'min:2', 'max:150'],
            'email'                 => ['required', 'email', 'unique:usuarios,email'],
            'password'              => ['required', 'min:8', 'confirmed'],
            'password_confirmation' => ['required'],
        ], [
            'nombre.required'                => 'El nombre es obligatorio.',
            'nombre.min'                     => 'El nombre debe tener al menos 2 caracteres.',
            'apellido1.required'             => 'El primer apellido es obligatorio.',
            'apellido1.min'                  => 'El primer apellido debe tener al menos 2 caracteres.',
            'apellido2.required'             => 'El segundo apellido es obligatorio.',
            'apellido2.min'                  => 'El segundo apellido debe tener al menos 2 caracteres.',
            'email.required'                 => 'El email es obligatorio.',
            'email.email'                    => 'El formato del email no es válido.',
            'email.unique'                   => 'Este email ya está registrado.',
            'password.required'              => 'La contraseña es obligatoria.',
            'password.min'                   => 'La contraseña debe tener al menos 8 caracteres.',
            'password.confirmed'             => 'Las contraseñas no coinciden.',
            'password_confirmation.required' => 'Confirma tu contraseña.',
        ]);

        $usuario = Usuario::create([
            'nombre'           => $validated['nombre'],
            'apellido1'        => $validated['apellido1'],
            'apellido2'        => $validated['apellido2'],
            'email'            => $validated['email'],
            'password_hash'    => $validated['password'],  // cast 'hashed' lo encripta
            'fecha_creacion'   => now(),
            'estado'           => 1,
            'email_verificado' => 0,
            'es_admin'         => 0,
        ]);

        Auth::login($usuario);
        $request->session()->regenerate();

        return redirect()->route('index')
            ->with('success', '¡Cuenta creada correctamente! Bienvenido a VIBEZ.');
    }

    /**
     * Cierra la sesión por AJAX (usada desde index.js y dashboards).
     * Devuelve JSON para mantener compatibilidad con el JS existente.
     */
    public function logout(Request $request): JsonResponse
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return response()->json([
            'success' => true,
            'message' => 'Sesión cerrada correctamente.',
            'data'    => null,
        ]);
    }

    /* ============================================================
       MÉTODOS PRIVADOS
       ============================================================ */

    /**
     * Devuelve la URL del dashboard según el rol del usuario autenticado.
     * Prioridad: admin > empresa > organizador > usuario
     */
    private function redirectByRole(): string
    {
        /** @var \App\Models\Usuario $usuario */
        $usuario = Auth::user();

        if ($usuario->isAdmin()) {
            return route('admin.dashboard');
        }

        if ($usuario->isEmpresa()) {
            return route('empresa.dashboard');
        }

        if ($usuario->isOrganizador()) {
            return route('organizador.dashboard');
        }

        return route('index');
    }
}
