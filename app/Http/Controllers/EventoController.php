<?php

namespace App\Http\Controllers;

use App\Models\Evento;
use App\Models\CategoriaEvento;
use App\Models\BolsaOfertaTrabajo;
use App\Models\CategoriaTrabajo;
use Illuminate\Http\Request;

class EventoController extends Controller
{
    /**
     * Página de inicio (home).
     * Carga todos los eventos activos y las ofertas de trabajo,
     * junto con los datos necesarios para los filtros.
     */
    public function index()
    {
        // Cargar eventos activos con sus relaciones (categoría, portada, empresa organizadora)
        $eventos = Evento::with(['categoria', 'portada', 'organizador.empresa'])
            ->where('estado', 1)
            ->orderBy('fecha_inicio', 'asc')
            ->get();

        // Obtener todas las categorías activas para el selector de filtro
        $categorias = CategoriaEvento::where('estado', 1)
            ->orderBy('nombre')
            ->get();

        // Cargar ofertas de trabajo activas con el nombre de la empresa
        $ofertas = BolsaOfertaTrabajo::with(['organizador.empresa'])
            ->where('estado', 1)
            ->orderBy('fecha_creacion', 'desc')
            ->get();

        // Obtener ubicaciones únicas de eventos para el selector de filtro
        $ubicaciones = Evento::where('estado', 1)
            ->whereNotNull('ubicacion_nombre')
            ->orderBy('ubicacion_nombre')
            ->distinct()
            ->pluck('ubicacion_nombre');

        return view('home', compact('eventos', 'categorias', 'ofertas', 'ubicaciones'));
    }

    /**
     * Endpoint AJAX para filtrar eventos y ofertas.
     * Recibe los parámetros ?categoria= y ?ubicacion= por GET.
     * Devuelve JSON con arrays 'eventos' y 'ofertas'.
     *
     * Casos especiales:
     *  - categoria = 'trabajo' → solo muestra ofertas de trabajo
     *  - categoria = ID numérico → filtra eventos por esa categoría
     *  - categoria vacío → muestra todo (eventos + ofertas)
     */
    public function filtrar(Request $request)
    {
        $categoriaId = $request->input('categoria', '');
        $ubicacion   = $request->input('ubicacion', '');

        // --- Determinar qué mostrar según el filtro de categoría ---
        if ($categoriaId === 'trabajo') {
            // Solo mostrar ofertas de trabajo
            $eventos = collect();
            $ofertasQuery = BolsaOfertaTrabajo::with(['organizador.empresa'])
                ->where('estado', 1);

            // Filtrar también por ubicación si se proporcionó
            if ($ubicacion) {
                $ofertasQuery->where('ubicacion', 'like', "%{$ubicacion}%");
            }

            $ofertas = $ofertasQuery->orderBy('fecha_creacion', 'desc')->get();

        } elseif ($categoriaId) {
            // Filtrar eventos por categoría específica, sin ofertas
            $eventosQuery = Evento::with(['categoria', 'portada', 'organizador.empresa'])
                ->where('estado', 1)
                ->where('categoria_evento_id', $categoriaId);

            if ($ubicacion) {
                $eventosQuery->where('ubicacion_nombre', 'like', "%{$ubicacion}%");
            }

            $eventos = $eventosQuery->orderBy('fecha_inicio', 'asc')->get();
            $ofertas = collect();

        } else {
            // Sin filtro de categoría → mostrar todo
            $eventosQuery = Evento::with(['categoria', 'portada', 'organizador.empresa'])
                ->where('estado', 1);

            if ($ubicacion) {
                $eventosQuery->where('ubicacion_nombre', 'like', "%{$ubicacion}%");
            }

            $eventos = $eventosQuery->orderBy('fecha_inicio', 'asc')->get();
            $ofertas = BolsaOfertaTrabajo::with(['organizador.empresa'])
                ->where('estado', 1)
                ->orderBy('fecha_creacion', 'desc')
                ->get();
        }

        // --- Formatear eventos para JSON ---
        $eventosData = $eventos->map(function ($evento) {
            return [
                'id'               => $evento->id,
                'tipo'             => 'evento',
                'titulo'           => $evento->titulo,
                'fecha_inicio'     => $evento->fecha_inicio,
                'ubicacion_nombre' => $evento->ubicacion_nombre,
                'precio_base'      => $evento->precio_base,
                'es_gratuito'      => $evento->es_gratuito,
                'precio_formateado'=> $evento->precio_formateado,
                'categoria'        => $evento->categoria?->nombre ?? 'Evento',
                'portada'          => $evento->portada?->imagen_url
                                       ?? "https://picsum.photos/seed/evento-{$evento->id}/600/400",
                'organizador'      => $evento->organizador?->empresa?->nombre_empresa ?? 'Organizador',
            ];
        });

        // --- Formatear ofertas para JSON ---
        $ofertasData = $ofertas->map(function ($oferta) {
            return [
                'id'                => $oferta->id,
                'tipo'              => 'oferta',
                'titulo'            => $oferta->titulo,
                'ubicacion_nombre'  => $oferta->ubicacion,
                'salario_formateado'=> $oferta->salario_formateado,
                'organizador'       => $oferta->organizador?->empresa?->nombre_empresa ?? 'Empresa',
                'vacantes'          => $oferta->vacantes,
            ];
        });

        return response()->json([
            'eventos' => $eventosData,
            'ofertas' => $ofertasData,
            'total'   => $eventosData->count() + $ofertasData->count(),
        ]);
    }

    /**
     * Vista de detalle de un evento.
     * Carga el evento con todas sus relaciones y lo pasa a la vista.
     * Si el evento no existe o está inactivo, devuelve 404.
     */
    public function detalle(int $id)
    {
        // findOrFail lanza 404 automáticamente si no se encuentra
        $evento = Evento::with([
            'categoria',
            'imagenes',                    // todas las imágenes (para galería)
            'organizador.empresa',         // empresa organizadora
            'organizador.usuario',         // usuario del organizador
        ])
        ->where('estado', 1)
        ->findOrFail($id);

        return view('eventos.detalle', compact('evento'));
    }

    /**
     * Página completa de Bolsa de Trabajo.
     * Muestra todas las ofertas activas con filtros por ciudad y categoría.
     */
    public function bolsaTrabajo()
    {
        // Todas las ofertas activas con empresa y categoría
        $ofertas = BolsaOfertaTrabajo::with(['organizador.empresa', 'categoria'])
            ->where('estado', 1)
            ->orderBy('fecha_creacion', 'desc')
            ->get();

        // Categorías de trabajo para el filtro
        $categoriasTrabajo = CategoriaTrabajo::where('estado', 1)
            ->orderBy('nombre')
            ->get();

        // Ciudades únicas para el filtro
        $ciudades = BolsaOfertaTrabajo::where('estado', 1)
            ->whereNotNull('ubicacion')
            ->distinct()
            ->orderBy('ubicacion')
            ->pluck('ubicacion');

        return view('trabajos.index', compact('ofertas', 'categoriasTrabajo', 'ciudades'));
    }

    /**
     * Endpoint AJAX para filtrar ofertas de trabajo.
     * Acepta ?categoria= (ID de categoría_trabajo) y ?ciudad= (nombre ciudad).
     * Devuelve JSON con el array 'ofertas' y el 'total'.
     */
    public function filtrarTrabajos(Request $request)
    {
        $categoriaId = $request->input('categoria', '');
        $ciudad      = $request->input('ciudad', '');

        $query = BolsaOfertaTrabajo::with(['organizador.empresa', 'categoria'])
            ->where('estado', 1);

        if ($categoriaId) {
            $query->where('categoria_trabajo_id', $categoriaId);
        }
        if ($ciudad) {
            $query->where('ubicacion', 'like', "%{$ciudad}%");
        }

        $ofertas = $query->orderBy('fecha_creacion', 'desc')->get();

        $ofertasData = $ofertas->map(function ($oferta) {
            return [
                'id'                => $oferta->id,
                'titulo'            => $oferta->titulo,
                'descripcion'       => $oferta->descripcion
                    ? mb_substr($oferta->descripcion, 0, 120) . '…'
                    : '',
                'ubicacion'         => $oferta->ubicacion ?? '–',
                'salario_formateado'=> $oferta->salario_formateado,
                'vacantes'          => $oferta->vacantes,
                'categoria'         => $oferta->categoria?->nombre ?? 'General',
                'organizador'       => $oferta->organizador?->empresa?->nombre_empresa ?? 'Empresa',
                'fecha_inicio'      => $oferta->fecha_inicio_trabajo
                    ? \Carbon\Carbon::parse($oferta->fecha_inicio_trabajo)->format('d/m/Y')
                    : null,
            ];
        });

        return response()->json([
            'ofertas' => $ofertasData,
            'total'   => $ofertasData->count(),
        ]);
    }

    /**
     * Vista de detalle de una oferta de trabajo.
     */
    public function detalleOferta(int $id)
    {
        $oferta = BolsaOfertaTrabajo::with(['organizador.empresa'])
            ->where('estado', 1)
            ->findOrFail($id);

        return view('trabajos.detalle', compact('oferta'));
    }
}
