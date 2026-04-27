<?php

namespace App\Http\Controllers;

use App\Models\Amigo;
use App\Models\Chat;
use App\Models\Mensaje;
use App\Models\Usuario;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class SocialController extends Controller
{
    /* ============================================================
       VISTA PRINCIPAL
       ============================================================ */

    /**
     * Carga la página social con el recuento inicial de solicitudes pendientes
     * para mostrar el badge sin esperar a la primera petición AJAX.
     */
    public function index(): View
    {
        /** @var Usuario $usuario */
        $usuario = Auth::user();

        // Solicitudes pendientes recibidas para el badge inicial
        $solicitudesPendientes = Amigo::where('receptor_id', $usuario->id)
            ->where('estado', 0)
            ->count();

        return view('social.index', compact('usuario', 'solicitudesPendientes'));
    }

    /* ============================================================
       API — AMIGOS
       ============================================================ */

    /**
     * Devuelve la lista de amigos aceptados del usuario autenticado.
     * Mapea siempre al "otro" usuario de la relación (solicitante o receptor).
     */
    public function misAmigos(): JsonResponse
    {
        /** @var Usuario $usuario */
        $usuario = Auth::user();

        $relaciones = Amigo::with(['solicitante', 'receptor'])
            ->where(function ($q) use ($usuario) {
                $q->where('solicitante_id', $usuario->id)
                  ->orWhere('receptor_id', $usuario->id);
            })
            ->where('estado', 1)
            ->get();

        $amigos = $relaciones->map(function ($rel) use ($usuario) {
            // Determinar cuál de los dos es el amigo (no el usuario actual)
            $amigo = $rel->solicitante_id === $usuario->id
                ? $rel->receptor
                : $rel->solicitante;

            return [
                'id'          => $amigo->id,
                'nombre'      => $amigo->nombre,
                'apellido1'   => $amigo->apellido1,
                'foto_url'    => $amigo->foto_url,
                'mood'        => $amigo->mood,
                'relacion_id' => $rel->id,
            ];
        });

        return response()->json(['exito' => true, 'datos' => $amigos]);
    }

    /**
     * Devuelve las solicitudes de amistad pendientes que ha recibido el usuario.
     */
    public function solicitudesPendientes(): JsonResponse
    {
        /** @var Usuario $usuario */
        $usuario = Auth::user();

        $solicitudes = Amigo::with('solicitante')
            ->where('receptor_id', $usuario->id)
            ->where('estado', 0)
            ->orderByDesc('fecha_creacion')
            ->get()
            ->map(fn($sol) => [
                'id'             => $sol->id,
                'solicitante_id' => $sol->solicitante_id,
                'nombre'         => $sol->solicitante->nombre,
                'apellido1'      => $sol->solicitante->apellido1,
                'foto_url'       => $sol->solicitante->foto_url,
                'fecha'          => $sol->fecha_creacion,
            ]);

        return response()->json(['exito' => true, 'datos' => $solicitudes]);
    }

    /**
     * Acepta una solicitud de amistad recibida (versión AJAX).
     * Solo el receptor puede aceptarla.
     */
    public function aceptarSolicitud(int $id): JsonResponse
    {
        /** @var Usuario $usuario */
        $usuario = Auth::user();

        $solicitud = Amigo::where('id', $id)
            ->where('receptor_id', $usuario->id)
            ->where('estado', 0)
            ->firstOrFail();

        $solicitud->update([
            'estado'              => 1,   // 1 = aceptado
            'fecha_actualizacion' => now(),
        ]);

        return response()->json(['exito' => true, 'mensaje' => '¡Ahora sois amigos!']);
    }

    /**
     * Rechaza una solicitud de amistad recibida (versión AJAX).
     */
    public function rechazarSolicitud(int $id): JsonResponse
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

        return response()->json(['exito' => true, 'mensaje' => 'Solicitud rechazada.']);
    }

    /**
     * Busca usuarios por nombre o email para añadirlos como amigos.
     * Excluye al propio usuario y a los que ya tienen relación (pendiente, aceptada o rechazada).
     */
    public function buscarUsuarios(Request $request): JsonResponse
    {
        $busqueda = trim($request->get('q', ''));

        if (strlen($busqueda) < 2) {
            return response()->json(['exito' => true, 'datos' => []]);
        }

        /** @var Usuario $usuario */
        $usuario = Auth::user();

        // Recoger todos los IDs con los que ya existe alguna relación
        $idsRelacionados = Amigo::where('solicitante_id', $usuario->id)
            ->orWhere('receptor_id', $usuario->id)
            ->get()
            ->flatMap(fn($rel) => [$rel->solicitante_id, $rel->receptor_id])
            ->unique()
            ->filter(fn($idRel) => $idRel !== $usuario->id)
            ->values();

        $resultados = Usuario::where('id', '!=', $usuario->id)
            ->where('estado', 1)
            ->whereNotIn('id', $idsRelacionados)
            ->where(function ($q) use ($busqueda) {
                $q->where('nombre', 'like', "%{$busqueda}%")
                  ->orWhere('apellido1', 'like', "%{$busqueda}%")
                  ->orWhere('email', 'like', "%{$busqueda}%");
            })
            ->limit(8)
            ->get(['id', 'nombre', 'apellido1', 'foto_url']);

        return response()->json(['exito' => true, 'datos' => $resultados]);
    }

    /**
     * Envía una solicitud de amistad (versión AJAX).
     * Comprueba que no exista ya una relación entre los dos usuarios.
     */
    public function enviarSolicitud(Request $request): JsonResponse
    {
        $request->validate([
            'receptor_id' => ['required', 'integer', 'exists:usuarios,id'],
        ]);

        /** @var Usuario $usuario */
        $usuario = Auth::user();

        if ($usuario->id === (int) $request->receptor_id) {
            return response()->json([
                'exito'   => false,
                'mensaje' => 'No puedes enviarte una solicitud a ti mismo.',
            ], 422);
        }

        // Buscar relación existente en ambas direcciones
        $existe = Amigo::where(function ($q) use ($usuario, $request) {
            $q->where('solicitante_id', $usuario->id)
              ->where('receptor_id', $request->receptor_id);
        })->orWhere(function ($q) use ($usuario, $request) {
            $q->where('solicitante_id', $request->receptor_id)
              ->where('receptor_id', $usuario->id);
        })->first();

        if ($existe) {
            $mensajeError = match ((int) $existe->estado) {
                0       => 'Ya hay una solicitud pendiente.',
                1       => 'Ya sois amigos.',
                default => 'Solicitud rechazada anteriormente.',
            };
            return response()->json(['exito' => false, 'mensaje' => $mensajeError], 422);
        }

        Amigo::create([
            'solicitante_id' => $usuario->id,
            'receptor_id'    => $request->receptor_id,
            'estado'         => 0,
            'fecha_creacion' => now(),
        ]);

        return response()->json(['exito' => true, 'mensaje' => 'Solicitud enviada correctamente.']);
    }

    /* ============================================================
       API — CHATS
       ============================================================ */

    /**
     * Devuelve la lista de conversaciones activas del usuario.
     * Cada chat incluye datos del otro participante y el último mensaje.
     *
     * Identificamos los chats del usuario por el campo "nombre",
     * que sigue la convención: dm_{idMenor}_{idMayor}
     */
    public function misChats(): JsonResponse
    {
        /** @var Usuario $usuario */
        $usuario = Auth::user();
        $miId    = $usuario->id;

        // Buscar todos los chats directos donde este usuario es participante
        $chats = Chat::where('tipo_chat', 1)
            ->where('estado', 1)
            ->where(function ($q) use ($miId) {
                $q->where('nombre', 'like', "dm_{$miId}_%")
                  ->orWhere('nombre', 'like', "dm_%_{$miId}");
            })
            ->with(['ultimoMensaje.usuario'])
            ->get()
            ->map(function ($chat) use ($miId) {
                // Extraer el ID del otro usuario del nombre (formato: dm_5_12)
                $partes  = explode('_', $chat->nombre);
                $idOtro  = (int) $partes[1] === $miId ? (int) $partes[2] : (int) $partes[1];
                $amigo   = Usuario::find($idOtro, ['id', 'nombre', 'apellido1', 'foto_url', 'mood']);

                // Contar mensajes no leídos del otro usuario en este chat
                $noLeidos = Mensaje::where('chat_id', $chat->id)
                    ->where('usuario_id', '!=', $miId)
                    ->where('leido', 0)
                    ->where('estado', 1)
                    ->count();

                return [
                    'chat_id'        => $chat->id,
                    'amigo'          => $amigo,
                    'ultimo_mensaje' => $chat->ultimoMensaje ? [
                        'contenido' => $chat->ultimoMensaje->contenido,
                        'es_mio'    => $chat->ultimoMensaje->usuario_id === $miId,
                        'fecha'     => $chat->ultimoMensaje->fecha_creacion,
                    ] : null,
                    'no_leidos'      => $noLeidos,
                ];
            })
            ->sortByDesc(fn($c) => $c['ultimo_mensaje']['fecha'] ?? '1970-01-01')
            ->values();

        return response()->json(['exito' => true, 'datos' => $chats]);
    }

    /**
     * Abre (o crea si no existe) un chat directo con un amigo.
     * Solo se permite si los dos usuarios son amigos con estado=1.
     *
     * El nombre del chat sigue el patrón: dm_{idMenor}_{idMayor}
     */
    public function abrirChat(Request $request): JsonResponse
    {
        $request->validate([
            'amigo_id' => ['required', 'integer', 'exists:usuarios,id'],
        ]);

        /** @var Usuario $usuario */
        $usuario = Auth::user();
        $amigoId = (int) $request->amigo_id;

        // Verificar que existe amistad aceptada
        $sonAmigos = Amigo::where('estado', 1)
            ->where(function ($q) use ($usuario, $amigoId) {
                $q->where('solicitante_id', $usuario->id)->where('receptor_id', $amigoId);
            })->orWhere(function ($q) use ($usuario, $amigoId) {
                $q->where('solicitante_id', $amigoId)->where('receptor_id', $usuario->id);
            })->exists();

        if (!$sonAmigos) {
            return response()->json([
                'exito'   => false,
                'mensaje' => 'Solo puedes chatear con tus amigos.',
            ], 403);
        }

        // Nombre único del chat para este par de usuarios
        $idMenor    = min($usuario->id, $amigoId);
        $idMayor    = max($usuario->id, $amigoId);
        $nombreChat = "dm_{$idMenor}_{$idMayor}";

        // Obtener el chat existente o crear uno nuevo
        $chat = Chat::firstOrCreate(
            ['nombre' => $nombreChat],
            [
                'tipo_chat'      => 1,
                'nombre'         => $nombreChat,
                'estado'         => 1,
                'fecha_creacion' => now(),
            ]
        );

        $amigo = Usuario::find($amigoId, ['id', 'nombre', 'apellido1', 'foto_url', 'mood']);

        return response()->json([
            'exito' => true,
            'datos' => [
                'chat_id' => $chat->id,
                'amigo'   => $amigo,
            ],
        ]);
    }

    /**
     * Devuelve todos los mensajes activos de un chat.
     * Marca como leídos los mensajes del otro usuario.
     */
    public function mensajesChat(int $chatId): JsonResponse
    {
        /** @var Usuario $usuario */
        $usuario = Auth::user();

        $this->verificarAccesoChat($chatId, $usuario->id);

        $mensajes = Mensaje::with('usuario:id,nombre,apellido1,foto_url')
            ->where('chat_id', $chatId)
            ->where('estado', 1)
            ->orderBy('fecha_creacion')
            ->orderBy('id')
            ->get()
            ->map(fn($m) => [
                'id'        => $m->id,
                'contenido' => $m->contenido,
                'es_mio'    => $m->usuario_id === $usuario->id,
                'autor'     => $m->usuario->nombre,
                'foto'      => $m->usuario->foto_url,
                'fecha'     => $m->fecha_creacion,
            ]);

        // Marcar como leídos los mensajes recibidos (no propios)
        Mensaje::where('chat_id', $chatId)
            ->where('usuario_id', '!=', $usuario->id)
            ->where('leido', 0)
            ->update(['leido' => 1, 'fecha_actualizacion' => now()]);

        return response()->json(['exito' => true, 'datos' => $mensajes]);
    }

    /**
     * Envía un nuevo mensaje en un chat.
     * Actualiza el timestamp del chat para que aparezca primero en la lista.
     */
    public function enviarMensaje(Request $request, int $chatId): JsonResponse
    {
        $request->validate([
            'contenido' => ['required', 'string', 'max:2000'],
        ]);

        /** @var Usuario $usuario */
        $usuario = Auth::user();

        $this->verificarAccesoChat($chatId, $usuario->id);

        $mensaje = Mensaje::create([
            'chat_id'        => $chatId,
            'usuario_id'     => $usuario->id,
            'contenido'      => trim($request->contenido),
            'leido'          => 0,
            'estado'         => 1,
            'fecha_creacion' => now(),
        ]);

        // Actualizar fecha del chat para que suba en la lista de conversaciones
        Chat::where('id', $chatId)->update(['fecha_actualizacion' => now()]);

        return response()->json([
            'exito' => true,
            'datos' => [
                'id'        => $mensaje->id,
                'contenido' => $mensaje->contenido,
                'es_mio'    => true,
                'fecha'     => $mensaje->fecha_creacion,
            ],
        ]);
    }

    /**
     * Polling de mensajes nuevos desde un ID dado.
     * El cliente envía el ID del último mensaje que tiene cargado.
     * Solo devuelve los mensajes posteriores a ese ID.
     */
    public function mensajesNuevos(Request $request, int $chatId): JsonResponse
    {
        $desdeId = (int) $request->get('desde', 0);

        /** @var Usuario $usuario */
        $usuario = Auth::user();

        $this->verificarAccesoChat($chatId, $usuario->id);

        $mensajesNuevos = Mensaje::with('usuario:id,nombre,apellido1,foto_url')
            ->where('chat_id', $chatId)
            ->where('id', '>', $desdeId)
            ->where('estado', 1)
            ->orderBy('id')
            ->get()
            ->map(fn($m) => [
                'id'        => $m->id,
                'contenido' => $m->contenido,
                'es_mio'    => $m->usuario_id === $usuario->id,
                'autor'     => $m->usuario->nombre,
                'foto'      => $m->usuario->foto_url,
                'fecha'     => $m->fecha_creacion,
            ]);

        // Marcar como leídos si hay mensajes nuevos del otro usuario
        if ($mensajesNuevos->isNotEmpty()) {
            Mensaje::where('chat_id', $chatId)
                ->where('usuario_id', '!=', $usuario->id)
                ->where('leido', 0)
                ->update(['leido' => 1, 'fecha_actualizacion' => now()]);
        }

        return response()->json(['exito' => true, 'datos' => $mensajesNuevos]);
    }

    /**
     * Devuelve el total de mensajes no leídos y solicitudes pendientes.
     * Lo usa el navbar para mostrar el badge de notificaciones.
     */
    public function contadorNoLeidos(): JsonResponse
    {
        /** @var Usuario $usuario */
        $usuario = Auth::user();
        $miId    = $usuario->id;

        // IDs de los chats del usuario
        $idsChats = Chat::where('tipo_chat', 1)
            ->where('estado', 1)
            ->where(function ($q) use ($miId) {
                $q->where('nombre', 'like', "dm_{$miId}_%")
                  ->orWhere('nombre', 'like', "dm_%_{$miId}");
            })
            ->pluck('id');

        $mensajesNoLeidos = Mensaje::whereIn('chat_id', $idsChats)
            ->where('usuario_id', '!=', $miId)
            ->where('leido', 0)
            ->where('estado', 1)
            ->count();

        $solicitudesPendientes = Amigo::where('receptor_id', $miId)
            ->where('estado', 0)
            ->count();

        return response()->json([
            'exito' => true,
            'datos' => [
                'mensajes'    => $mensajesNoLeidos,
                'solicitudes' => $solicitudesPendientes,
                'total'       => $mensajesNoLeidos + $solicitudesPendientes,
            ],
        ]);
    }

    /* ============================================================
       HELPERS PRIVADOS
       ============================================================ */

    /**
     * Verifica que el usuario autenticado tiene acceso al chat indicado.
     * Los chats directos se identifican por el campo nombre: dm_minId_maxId.
     * Devuelve 403 si el usuario no pertenece al chat.
     */
    private function verificarAccesoChat(int $chatId, int $usuarioId): void
    {
        $tieneAcceso = Chat::where('id', $chatId)
            ->where('tipo_chat', 1)
            ->where('estado', 1)
            ->where(function ($q) use ($usuarioId) {
                $q->where('nombre', 'like', "dm_{$usuarioId}_%")
                  ->orWhere('nombre', 'like', "dm_%_{$usuarioId}");
            })
            ->exists();

        abort_unless($tieneAcceso, 403, 'No tienes acceso a este chat.');
    }
}
