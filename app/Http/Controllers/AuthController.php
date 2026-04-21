<?php

namespace App\Http\Controllers;

use App\Models\Usuario;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use App\Http\Controllers\EventoController;

class AuthController extends Controller
{
    /* ============================================================
       VISTAS
       ============================================================ */

    /**
     * Muestra la vista de login.
     * Si ya hay sesión activa redirige directo al home de usuario.
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
     */
    public function showHome(): mixed
    {
        return (new EventoController())->index();
    }

    /**
     * Home de empresa autenticada (crear eventos, subir ofertas, etc.).
     */
    public function showEmpresaHome(): View
    {
        return view('empresa.home');
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

        $request->session()->regenerate();

        /** @var Usuario $usuario */
        $usuario = Auth::user();

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
        ]);

        $usuario = Usuario::create([
            'nombre'           => $validated['nombre'],
            'apellido1'        => $validated['apellido1'],
            'apellido2'        => $validated['apellido2'],
            'email'            => $validated['email'],
            'password_hash'    => $validated['password'],
            'fecha_creacion'   => now(),
            'estado'           => 1,
            'email_verificado' => 0,
        ]);

        Auth::login($usuario);
        $request->session()->regenerate();

        return response()->json([
            'success' => true,
            'message' => 'Cuenta creada correctamente. ¡Bienvenido!',
            'data'    => [
                'user' => [
                    'id'     => $usuario->id,
                    'nombre' => $usuario->nombre,
                    'email'  => $usuario->email,
                ],
            ],
        ], 201);
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
