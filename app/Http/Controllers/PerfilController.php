<?php

namespace App\Http\Controllers;

use App\Models\Amigo;
use App\Models\Usuario;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class PerfilController extends Controller
{
    /* ============================================================
       VISTA PRINCIPAL
       ============================================================ */

    /**
     * Muestra la página de perfil del usuario autenticado.
     * Carga solicitudes pendientes recibidas y lista de amigos.
     */
    public function show(): View
    {
        /** @var Usuario $usuario */
        $usuario = Auth::user();

        // Solicitudes de amistad pendientes que este usuario ha recibido
        $solicitudesPendientes = Amigo::with('solicitante')
            ->where('receptor_id', $usuario->id)
            ->where('estado', 0)
            ->get();

        // Amigos aceptados (en ambas direcciones)
        $amigos = Amigo::with(['solicitante', 'receptor'])
            ->where(function ($q) use ($usuario) {
                $q->where('solicitante_id', $usuario->id)
                  ->orWhere('receptor_id', $usuario->id);
            })
            ->where('estado', 1)
            ->get()
            ->map(function ($rel) use ($usuario) {
                // Devolver siempre el "otro" usuario de la relación
                return $rel->solicitante_id === $usuario->id
                    ? $rel->receptor
                    : $rel->solicitante;
            });

        return view('perfil.index', compact('usuario', 'solicitudesPendientes', 'amigos'));
    }

    /* ============================================================
       FORMULARIOS — devuelven redirect (no JSON)
       ============================================================ */

    /**
     * Guarda los datos personales enviados por el formulario HTML.
     * Redirige de vuelta al perfil con mensaje de éxito o error.
     */
    public function actualizar(Request $request): RedirectResponse
    {
        /** @var Usuario $usuario */
        $usuario = Auth::user();

        // validate() redirige automáticamente con errores si la validación falla
        $validated = $request->validate([
            'nombre'          => ['required', 'string', 'min:2', 'max:100'],
            'apellido1'       => ['required', 'string', 'min:2', 'max:150'],
            'apellido2'       => ['nullable', 'string', 'max:150'],
            'biografia'       => ['nullable', 'string', 'max:500'],
            'telefono'        => ['nullable', 'string', 'max:20'],
            'fecha_nacimiento'=> ['nullable', 'date'],
        ], [
            'nombre.required'   => 'El nombre es obligatorio.',
            'apellido1.required'=> 'El primer apellido es obligatorio.',
        ]);

        $usuario->update(array_merge($validated, [
            'fecha_actualizacion' => now(),
        ]));

        // with() guarda el mensaje en la sesión flash (dura solo la siguiente petición)
        return redirect()->route('perfil')->with('exito', 'Perfil actualizado correctamente.');
    }

    /**
     * Guarda la foto de perfil subida por el formulario.
     * Almacena el archivo en public/fotos/ y guarda la URL en la BD.
     */
    public function actualizarFoto(Request $request): RedirectResponse
    {
        $request->validate([
            'foto' => ['required', 'image', 'mimes:jpeg,png,jpg,gif,webp', 'max:5120'],
        ], [
            'foto.required' => 'Selecciona una imagen.',
            'foto.image'    => 'El archivo debe ser una imagen.',
            'foto.max'      => 'La imagen no puede superar 5 MB.',
        ]);

        /** @var Usuario $usuario */
        $usuario = Auth::user();

        // Borrar foto anterior si está almacenada localmente (no es URL externa)
        if ($usuario->foto_url && str_starts_with($usuario->foto_url, '/fotos/')) {
            $rutaAnterior = public_path(ltrim($usuario->foto_url, '/'));
            if (file_exists($rutaAnterior)) {
                unlink($rutaAnterior);
            }
        }

        // Mover el archivo al directorio público con nombre único
        $archivo    = $request->file('foto');
        $nombre     = 'u' . $usuario->id . '_' . time() . '.' . $archivo->extension();
        $directorio = public_path('fotos');

        if (!is_dir($directorio)) {
            mkdir($directorio, 0755, true);
        }

        $archivo->move($directorio, $nombre);
        $url = '/fotos/' . $nombre;

        $usuario->update([
            'foto_url'            => $url,
            'fecha_actualizacion' => now(),
        ]);

        return redirect()->route('perfil')->with('exito', 'Foto actualizada correctamente.');
    }

    /**
     * Guarda el mood (estado de ánimo) del usuario.
     * El mood es público: lo puede ver cualquier persona.
     */
    public function actualizarMood(Request $request): RedirectResponse
    {
        $request->validate([
            // nullable permite enviar cadena vacía para borrar el mood
            'mood' => ['nullable', 'string', 'max:100'],
        ]);

        /** @var Usuario $usuario */
        $usuario = Auth::user();

        // Si mood es vacío ('') lo guardamos como null para que no aparezca
        $usuario->update([
            'mood'                => $request->mood ?: null,
            'fecha_actualizacion' => now(),
        ]);

        $mensaje = $request->mood
            ? 'Estado de ánimo actualizado.'
            : 'Estado de ánimo eliminado.';

        return redirect()->route('perfil')->with('exito', $mensaje);
    }

    /**
     * Acepta una solicitud de amistad.
     * El formulario envía un POST desde el perfil.
     */
    public function aceptarSolicitud(Request $request, int $id): RedirectResponse
    {
        /** @var Usuario $usuario */
        $usuario = Auth::user();

        // firstOrFail lanza 404 si no existe o no pertenece al usuario
        $solicitud = Amigo::where('id', $id)
            ->where('receptor_id', $usuario->id)
            ->where('estado', 0)
            ->firstOrFail();

        $solicitud->update([
            'estado'              => 1,   // 1 = aceptado
            'fecha_actualizacion' => now(),
        ]);

        return redirect()->route('perfil')->with('exito', '¡Ahora sois amigos!');
    }

    /**
     * Rechaza una solicitud de amistad.
     */
    public function rechazarSolicitud(Request $request, int $id): RedirectResponse
    {
        /** @var Usuario $usuario */
        $usuario = Auth::user();

        $solicitud = Amigo::where('id', $id)
            ->where('receptor_id', $usuario->id)
            ->where('estado', 0)
            ->firstOrFail();

        $solicitud->update([
            'estado'              => 2,   // 2 = rechazado
            'fecha_actualizacion' => now(),
        ]);

        return redirect()->route('perfil')->with('exito', 'Solicitud rechazada.');
    }

    /* ============================================================
       ENDPOINTS AJAX — solo para búsqueda dinámica de amigos
       ============================================================ */

    /**
     * Busca usuarios por nombre o email.
     * Devuelve JSON porque se usa desde el buscador dinámico (AJAX).
     */
    public function buscarUsuarios(Request $request): JsonResponse
    {
        $query = trim($request->get('q', ''));

        if (strlen($query) < 2) {
            return response()->json(['success' => true, 'data' => []]);
        }

        /** @var Usuario $usuario */
        $usuario = Auth::user();

        $resultados = Usuario::where('id', '!=', $usuario->id)
            ->where('estado', 1)
            ->where(function ($q) use ($query) {
                $q->where('nombre', 'like', "%{$query}%")
                  ->orWhere('apellido1', 'like', "%{$query}%")
                  ->orWhere('email', 'like', "%{$query}%");
            })
            ->limit(10)
            ->get(['id', 'nombre', 'apellido1', 'foto_url']);

        return response()->json(['success' => true, 'data' => $resultados]);
    }

    /**
     * Envía una solicitud de amistad.
     * Devuelve JSON porque el botón de "Añadir" está en los resultados AJAX.
     */
    public function enviarSolicitud(Request $request): JsonResponse
    {
        $request->validate(['receptor_id' => ['required', 'integer', 'exists:usuarios,id']]);

        /** @var Usuario $usuario */
        $usuario = Auth::user();

        if ($usuario->id === (int) $request->receptor_id) {
            return response()->json(['success' => false, 'message' => 'No puedes enviarte una solicitud a ti mismo.'], 422);
        }

        // Verificar si ya existe alguna relación entre los dos usuarios
        $existe = Amigo::where(function ($q) use ($usuario, $request) {
            $q->where('solicitante_id', $usuario->id)->where('receptor_id', $request->receptor_id);
        })->orWhere(function ($q) use ($usuario, $request) {
            $q->where('solicitante_id', $request->receptor_id)->where('receptor_id', $usuario->id);
        })->first();

        if ($existe) {
            $msg = match ($existe->estado) {
                0 => 'Ya hay una solicitud pendiente.',
                1 => 'Ya sois amigos.',
                default => 'Solicitud rechazada anteriormente.',
            };
            return response()->json(['success' => false, 'message' => $msg], 422);
        }

        Amigo::create([
            'solicitante_id' => $usuario->id,
            'receptor_id'    => $request->receptor_id,
            'estado'         => 0,
            'fecha_creacion' => now(),
        ]);

        return response()->json(['success' => true, 'message' => 'Solicitud enviada correctamente.']);
    }
}
