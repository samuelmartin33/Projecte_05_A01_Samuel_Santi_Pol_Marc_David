<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Cancion;
use App\Models\Empresa;
use App\Models\Evento;
use App\Models\PlaylistEventoCancion;
use Barryvdh\DomPDF\Facade\Pdf;
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

    /* ══════════════════════════════════════════════════════════════
       ESTADÍSTICAS
    ══════════════════════════════════════════════════════════════ */

    /**
     * Estadísticas de reproducciones y ganancias por canción.
     * Filtros opcionales GET: evento_id, empresa_id.
     * AJAX → JSON {datos, total_global}
     * Normal → vista estadisticas.blade.php
     */
    public function estadisticas(Request $request)
    {
        $consulta = PlaylistEventoCancion::selectRaw(
                'cancion_id,
                 COUNT(*)            AS veces,
                 SUM(precio_pagado)  AS ganancia'
            )
            ->with('cancion')
            ->join('eventos', 'eventos.id', '=', 'playlist_evento_canciones.evento_id');

        // Filtro por evento concreto
        if ($request->filled('evento_id')) {
            $consulta->where('playlist_evento_canciones.evento_id', $request->evento_id);
        }

        // Filtro por empresa: evento → organizador → empresa
        if ($request->filled('empresa_id')) {
            $consulta->join(
                'organizadores',
                'organizadores.id', '=', 'eventos.organizador_id'
            )->where('organizadores.empresa_id', $request->empresa_id);
        }

        $filas = $consulta
            ->groupBy('cancion_id')
            ->orderByDesc('veces')
            ->get();

        $totalGlobal = $filas->sum('ganancia');

        if ($request->ajax()) {
            return response()->json([
                'datos'        => $filas->map(fn ($f) => [
                    'titulo'   => $f->cancion?->titulo   ?? '—',
                    'artista'  => $f->cancion?->artista  ?? '—',
                    'veces'    => (int) $f->veces,
                    'ganancia' => number_format((float) $f->ganancia, 2),
                ]),
                'total_global' => number_format((float) $totalGlobal, 2),
            ]);
        }

        // Para los selects de la vista
        $eventos  = Evento::orderBy('titulo')->get(['id', 'titulo']);
        $empresas = Empresa::orderBy('nombre_empresa')->get(['id', 'nombre_empresa']);

        return view('admin.canciones.estadisticas', compact('filas', 'totalGlobal', 'eventos', 'empresas'));
    }

    /**
     * Descarga el PDF del mes actual con las estadísticas de canciones.
     * Aplica los mismos filtros opcionales que estadisticas().
     */
    public function descargarPdf(Request $request)
    {
        $mes  = now()->month;
        $anyo = now()->year;

        $consulta = PlaylistEventoCancion::selectRaw(
                'cancion_id,
                 COUNT(*)            AS veces,
                 SUM(precio_pagado)  AS ganancia'
            )
            ->with('cancion')
            ->join('eventos', 'eventos.id', '=', 'playlist_evento_canciones.evento_id')
            ->whereMonth('playlist_evento_canciones.created_at', $mes)
            ->whereYear('playlist_evento_canciones.created_at', $anyo);

        if ($request->filled('evento_id')) {
            $consulta->where('playlist_evento_canciones.evento_id', $request->evento_id);
        }

        if ($request->filled('empresa_id')) {
            $consulta->join(
                'organizadores',
                'organizadores.id', '=', 'eventos.organizador_id'
            )->where('organizadores.empresa_id', $request->empresa_id);
        }

        $datos = $consulta
            ->groupBy('cancion_id')
            ->orderByDesc('veces')
            ->get();

        $pdf = Pdf::loadView('admin.canciones.pdf-mes', compact('datos', 'mes', 'anyo'))
                  ->setPaper('A4', 'portrait');

        $nombreFichero = 'vibez-canciones-' . now()->format('m-Y') . '.pdf';

        return response($pdf->output(), 200, [
            'Content-Type'        => 'application/pdf',
            'Content-Disposition' => 'attachment; filename="' . $nombreFichero . '"',
        ]);
    }
}
