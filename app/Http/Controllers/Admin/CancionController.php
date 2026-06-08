<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Cancion;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

/**
 * Controlador para la gestión administrativa del catálogo de canciones.
 * Todas las operaciones de escritura devuelven JSON para AJAX.
 */
class CancionController extends Controller
{
    /**
     * Lista paginada de canciones.
     * Si es AJAX devuelve JSON con el HTML del partial _tabla.
     */
    public function index(Request $request)
    {
        $canciones = Cancion::orderByDesc('id')->paginate(15);

        if ($request->ajax()) {
            return response()->json([
                'html' => view('admin.canciones._tabla', compact('canciones'))->render(),
            ]);
        }

        return view('admin.canciones.index', compact('canciones'));
    }

    /**
     * Crea una nueva canción.
     */
    public function store(Request $request): JsonResponse
    {
        $datos = $request->validate([
            'titulo'            => ['required', 'string', 'max:255'],
            'artista'           => ['required', 'string', 'max:255'],
            'duracion_segundos' => ['required', 'integer', 'min:1'],
            'precio'            => ['required', 'numeric', 'min:0'],
            'generos'           => ['required', 'array', 'min:1'],
            'generos.*'         => ['string'],
            'activa'            => ['nullable', 'boolean'],
        ]);

        $datos['activa'] = $request->boolean('activa');

        Cancion::create($datos);

        return response()->json([
            'ok'      => true,
            'mensaje' => 'Canción creada correctamente.',
        ]);
    }

    /**
     * Devuelve los datos de una canción para precargar el modal de edición.
     */
    public function show(int $id): JsonResponse
    {
        $cancion = Cancion::findOrFail($id);

        return response()->json([
            'id'                => $cancion->id,
            'titulo'            => $cancion->titulo,
            'artista'           => $cancion->artista,
            'duracion_segundos' => $cancion->duracion_segundos,
            'precio'            => $cancion->precio,
            'generos'           => $cancion->generos ?? [],
            'activa'            => (bool) $cancion->activa,
        ]);
    }

    /**
     * Actualiza una canción existente.
     */
    public function update(Request $request, int $id): JsonResponse
    {
        $cancion = Cancion::findOrFail($id);

        $datos = $request->validate([
            'titulo'            => ['required', 'string', 'max:255'],
            'artista'           => ['required', 'string', 'max:255'],
            'duracion_segundos' => ['required', 'integer', 'min:1'],
            'precio'            => ['required', 'numeric', 'min:0'],
            'generos'           => ['required', 'array', 'min:1'],
            'generos.*'         => ['string'],
            'activa'            => ['nullable', 'boolean'],
        ]);

        $datos['activa'] = $request->boolean('activa');

        $cancion->update($datos);

        return response()->json([
            'ok'      => true,
            'mensaje' => 'Canción actualizada correctamente.',
        ]);
    }

    /**
     * Soft-delete lógico: desactiva la canción si no está en eventos futuros.
     */
    public function destroy(int $id): JsonResponse
    {
        $cancion = Cancion::findOrFail($id);

        // Comprueba si la canción está asignada a algún evento futuro
        $tieneEventoFuturo = DB::table('playlist_evento_canciones as pec')
            ->join('eventos as e', 'e.id', '=', 'pec.evento_id')
            ->where('pec.cancion_id', $id)
            ->where('e.fecha_inicio', '>', now())
            ->exists();

        if ($tieneEventoFuturo) {
            return response()->json([
                'ok'      => false,
                'mensaje' => 'No se puede desactivar esta canción porque está incluida en la playlist de uno o más eventos futuros.',
            ], 422);
        }

        $cancion->update(['activa' => false]);

        return response()->json(['ok' => true]);
    }
}
