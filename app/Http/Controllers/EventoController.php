<?php

namespace App\Http\Controllers;

use App\Models\Entrada;
use App\Models\Evento;
use App\Models\CategoriaEvento;
use App\Models\BolsaOfertaTrabajo;
use App\Models\CategoriaTrabajo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

/**
 * Controlador público de eventos.
 */
class EventoController extends Controller
{
    /**
     * Página de inicio (home). Solo muestra eventos activos.
     */
    public function index()
    {
        /* Evento destacado: el primero con featured=1, o el más próximo */
        $eventoFeatured = Evento::with(['categoria', 'portada'])
            ->where('estado', 1)
            ->where('featured', 1)
            ->orderBy('fecha_inicio')
            ->first()
            ?? Evento::with(['categoria', 'portada'])
                ->where('estado', 1)
                ->orderBy('fecha_inicio')
                ->first();

        /* Resto de eventos (sin el featured) */
        $eventos = Evento::with(['categoria', 'portada'])
            ->where('estado', 1)
            ->when($eventoFeatured, fn ($q) => $q->where('id', '!=', $eventoFeatured->id))
            ->orderBy('fecha_inicio')
            ->get();

        /* Categorías de BD (para el chip bar) */
        $categorias = CategoriaEvento::where('estado', 1)->orderBy('nombre')->get();

        /* Ubicaciones únicas (compatibilidad con código existente) */
        $ubicaciones = Evento::where('estado', 1)
            ->whereNotNull('ubicacion_nombre')
            ->orderBy('ubicacion_nombre')
            ->distinct()
            ->pluck('ubicacion_nombre');

        /* Favoritos del usuario autenticado */
        $favoritosIds = [];
        /** @var \App\Models\Usuario|null $usuario */
        $usuario = Auth::user();
        if ($usuario) {
            $favoritosIds = $usuario->favoritos()
                ->pluck('eventos.id')
                ->map(fn ($id) => (int) $id)
                ->all();
        }

        /* Entradas activas del usuario (para MisTickets) */
        $entradas = collect();
        if ($usuario) {
            $entradas = Entrada::with(['evento.portada', 'evento.categoria'])
                ->whereHas('pedido', fn ($q) => $q->where('usuario_id', $usuario->id))
                ->where('estado_entrada', 1)
                ->orderBy('fecha_creacion', 'desc')
                ->take(6)
                ->get();
        }

        /* Moods estáticos (los mismos que el prototipo) */
        $moods = [
            ['id' => 'rave',      'emoji' => '🌀', 'label' => 'Rave hasta el amanecer'],
            ['id' => 'indie',     'emoji' => '🎸', 'label' => 'Indie & cervezas'],
            ['id' => 'perreo',    'emoji' => '🔥', 'label' => 'Perreo y reggaeton'],
            ['id' => 'chill',     'emoji' => '🌅', 'label' => 'Chill, terraza, sunset'],
            ['id' => 'discoteca', 'emoji' => '💋', 'label' => 'Discoteca pura'],
            ['id' => 'concierto', 'emoji' => '🎤', 'label' => 'Concierto en pie'],
        ];

        /* Transformar eventos a array JS-friendly */
        $todosEventos = $eventoFeatured
            ? collect([$eventoFeatured])->merge($eventos)
            : $eventos;

        $ahora = now();
        $eventosParaJs = $todosEventos->map(fn ($e) => [
            'id'             => $e->id,
            'titulo'         => $e->titulo,
            'artista'        => $e->organizador?->empresa?->nombre ?? $e->organizador?->nombre_empresa ?? '',
            'tagline'        => $e->tagline,
            'fechaFmt'       => $e->fecha_fmt,
            'hora'           => $e->hora,
            'lugar'          => $e->ubicacion_nombre,
            'ciudad'         => $e->ubicacion_nombre,
            'coords'         => $e->coords,
            'categoria'      => $e->categoria?->nombre ?? 'Evento',
            'precio'         => $e->precio_formateado,
            'cupos'          => $e->cupos_disponibles,
            'img'            => $e->url_portada,
            'featured'       => (bool) $e->featured,
            'soldOut'        => $e->sell_out,
            'estaOcurriendo' => $e->fecha_inicio <= $ahora && ($e->fecha_fin === null || $e->fecha_fin >= $ahora),
            'haTerminado'    => $e->fecha_fin !== null && $e->fecha_fin < $ahora,
            'color'          => '#a855f7',
        ]);

        return view('home', compact(
            'eventoFeatured', 'eventos', 'entradas',
            'categorias', 'ubicaciones', 'favoritosIds',
            'moods', 'eventosParaJs'
        ));
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
        $soloFavoritos = $request->boolean('favoritos');

        $favoritosIds = [];
        if (Auth::check()) {
            /** @var \App\Models\Usuario $usuario */
            $usuario = Auth::user();

            $favoritosIds = $usuario
                ->favoritos()
                ->pluck('eventos.id')
                ->map(fn ($id) => (int) $id)
                ->all();
        }

        // --- Determinar qué mostrar según filtros activos ---
        if ($soloFavoritos) {
            // Solo eventos marcados como favorito (sin ofertas)
            if ($categoriaId === 'trabajo') {
                $eventos = collect();
            } else {
                $eventosQuery = Evento::with(['categoria', 'portada', 'organizador.empresa'])
                    ->where('estado', 1);

                if (count($favoritosIds) > 0) {
                    $eventosQuery->whereIn('id', $favoritosIds);
                } else {
                    $eventosQuery->whereRaw('1 = 0');
                }

                if ($categoriaId) {
                    $eventosQuery->where('categoria_evento_id', $categoriaId);
                }

                if ($ubicacion) {
                    $eventosQuery->where('ubicacion_nombre', 'like', "%{$ubicacion}%");
                }

                $eventos = $eventosQuery->orderBy('fecha_inicio', 'asc')->get();
            }

            $ofertas = collect();
        } elseif ($categoriaId === 'trabajo') {
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
            // Filtrar eventos por categoría — acepta ID numérico o nombre de categoría
            $eventosQuery = Evento::with(['categoria', 'portada', 'organizador.empresa'])
                ->where('estado', 1);

            if (is_numeric($categoriaId)) {
                $eventosQuery->where('categoria_evento_id', $categoriaId);
            } else {
                $eventosQuery->whereHas('categoria', fn ($q) => $q->where('nombre', $categoriaId));
            }

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
        $ahora = now();
        $eventosData = $eventos->map(function ($evento) use ($favoritosIds, $ahora) {
            return [
                'id'               => $evento->id,
                'tipo'             => 'evento',
                'titulo'           => $evento->titulo,
                /* Campos compatibles con vibez-home.js */
                'img'              => $evento->url_portada,
                'fechaFmt'         => $evento->fecha_fmt,
                'hora'             => $evento->hora,
                'lugar'            => $evento->ubicacion_nombre,
                'precio'           => $evento->precio_formateado,
                'categoria'        => $evento->categoria?->nombre ?? 'Evento',
                'estaOcurriendo'   => $evento->fecha_inicio <= $ahora && ($evento->fecha_fin === null || $evento->fecha_fin >= $ahora),
                'haTerminado'      => $evento->fecha_fin !== null && $evento->fecha_fin < $ahora,
                /* Campos legacy (para compatibilidad con home.js antiguo) */
                'fecha_inicio'     => $evento->fecha_inicio,
                'ubicacion_nombre' => $evento->ubicacion_nombre,
                'precio_formateado'=> $evento->precio_formateado,
                'url_portada'      => $evento->url_portada,
                'is_favorito'      => in_array((int) $evento->id, $favoritosIds, true),
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

        $esFavorito = false;
        if (Auth::check()) {
            /** @var \App\Models\Usuario $usuario */
            $usuario = Auth::user();

            $esFavorito = $usuario
                ->favoritos()
                ->where('eventos.id', $evento->id)
                ->exists();
        }

        $empresa     = $evento->organizador?->empresa;
        $stripeActivo = !$evento->es_gratuito
            && $empresa
            && $empresa->stripe_account_id
            && $empresa->stripe_charges_enabled;

        return view('eventos.detalle', compact('evento', 'esFavorito', 'stripeActivo'));
    }

    /**
     * Página de compra de entradas para un evento.
     * GET /eventos/{id}/comprar
     */
    public function compra(int $id)
    {
        /** @var \App\Models\Usuario $usuario */
        $usuario = Auth::user();

        if ($usuario->isAdmin()) {
            abort(403, 'Los administradores no pueden comprar entradas.');
        }

        $evento = Evento::with(['categoria', 'imagenes', 'organizador.empresa'])
            ->where('estado', 1)
            ->findOrFail($id);

        $empresa      = $evento->organizador?->empresa;
        $stripeActivo = !$evento->es_gratuito
            && $empresa
            && $empresa->stripe_account_id
            && $empresa->stripe_charges_enabled;

        $aforoLibre = $evento->aforo_maximo !== null
            ? max(0, $evento->aforo_maximo - $evento->aforo_actual)
            : 9999;

        return view('eventos.comprar', compact('evento', 'stripeActivo', 'aforoLibre'));
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

    /**
     * Recibe el formulario de CV y guarda la candidatura.
     */
    public function postular(Request $request, int $id)
    {
        $request->validate([
            'nombre'               => 'required|string|max:100',
            'apellidos'            => 'required|string|max:100',
            'email'                => 'required|email|max:200',
            'telefono'             => 'required|string|max:30',
            'ciudad'               => 'required|string|max:100',
            'perfil_profesional'   => 'required|string|max:2000',
            'carta_presentacion'   => 'required|string|max:5000',
            'linkedin'             => 'nullable|string|max:500',
            'habilidades'          => 'nullable|string|max:1000',
            'idiomas'              => 'nullable|string|max:500',
        ]);

        BolsaOfertaTrabajo::where('estado', 1)->findOrFail($id);

        $trabajadorId = $this->resolverTrabajadorActual();

        $expResumen = $this->construirExpFormacion($request);

        DB::table('candidaturas_trabajo')->insert([
            'oferta_id'              => $id,
            'trabajador_id'          => $trabajadorId,
            'estado_candidatura'     => 1,
            // Structured columns (visible to empresa)
            'nombre_candidato'       => $request->nombre,
            'apellidos_candidato'    => $request->apellidos,
            'email_candidato'        => $request->email,
            'telefono_candidato'     => $request->telefono,
            'ciudad_candidato'       => $request->ciudad,
            'linkedin_candidato'     => $request->linkedin,
            'perfil_profesional'     => $request->perfil_profesional,
            'habilidades'            => $request->habilidades,
            'idiomas'                => $request->idiomas,
            // Full carta + experience/education serialized
            'carta_presentacion'     => $request->carta_presentacion . ($expResumen ? "\n\n" . $expResumen : ''),
            'cv_url'                 => null,
            'estado'                 => 1,
            'fecha_creacion'         => now(),
        ]);

        return response()->json(['success' => true]);
    }

    /**
     * Recibe un archivo CV y guarda la candidatura.
     */
    public function postularArchivo(Request $request, int $id)
    {
        $request->validate([
            'cv_file'                    => 'required|file|mimes:pdf,doc,docx|max:5120',
            'carta_presentacion_archivo' => 'nullable|string|max:3000',
        ]);

        BolsaOfertaTrabajo::where('estado', 1)->findOrFail($id);

        $trabajadorId = $this->resolverTrabajadorActual();

        $path = $request->file('cv_file')->store('cvs', 'public');

        DB::table('candidaturas_trabajo')->insert([
            'oferta_id'          => $id,
            'trabajador_id'      => $trabajadorId,
            'estado_candidatura' => 1,
            'carta_presentacion' => $request->input('carta_presentacion_archivo'),
            'cv_url'             => $path,
            'estado'             => 1,
            'fecha_creacion'     => now(),
        ]);

        return response()->json(['success' => true]);
    }

    /**
     * Devuelve el ID del perfil de trabajador del usuario autenticado si existe.
     * Si el usuario todavía no tiene perfil, devuelve null para permitir la candidatura.
     */
    private function resolverTrabajadorActual(): ?int
    {
        if (!Auth::check()) {
            return null;
        }

        $usuario = Auth::user();

        return $usuario
            ? DB::table('trabajadores')->where('usuario_id', $usuario->id)->value('id')
            : null;
    }

    /** Serializa experiencia laboral y formación académica del formulario. */
    private function construirExpFormacion(Request $request): string
    {
        $lineas = [];

        $empresas = $request->input('exp_empresa', []);
        if (array_filter($empresas)) {
            $lineas[] = '';
            $lineas[] = '=== EXPERIENCIA LABORAL ===';
            foreach ($empresas as $i => $empresa) {
                if (empty($empresa)) continue;
                $cargo  = $request->input('exp_cargo',       [])[$i] ?? '';
                $desde  = $request->input('exp_desde',       [])[$i] ?? '';
                $hasta  = $request->input('exp_hasta',       [])[$i] ?? 'Actualidad';
                $desc   = $request->input('exp_descripcion', [])[$i] ?? '';
                $lineas[] = "— {$empresa} | {$cargo} ({$desde} – {$hasta})";
                if ($desc) $lineas[] = "  {$desc}";
            }
        }

        $instituciones = $request->input('edu_institucion', []);
        if (array_filter($instituciones)) {
            $lineas[] = '';
            $lineas[] = '=== FORMACIÓN ACADÉMICA ===';
            foreach ($instituciones as $i => $inst) {
                if (empty($inst)) continue;
                $titulo = $request->input('edu_titulo', [])[$i] ?? '';
                $inicio = $request->input('edu_inicio', [])[$i] ?? '';
                $fin    = $request->input('edu_fin',    [])[$i] ?? '';
                $lineas[] = "— {$inst} | {$titulo} ({$inicio}–{$fin})";
            }
        }

        return implode("\n", $lineas);
    }

    /**
     * Página dedicada del mapa de eventos.
     * Muestra un mapa Leaflet a pantalla completa con todos los eventos activos y no finalizados.
     */
    public function mapa()
    {
        $ahora = now();

        $todosEventos = Evento::with(['categoria', 'portada'])
            ->where('estado', 1)
            ->where(function ($q) use ($ahora) {
                /* Solo eventos que no han terminado aún */
                $q->whereNull('fecha_fin')
                  ->orWhere('fecha_fin', '>=', $ahora);
            })
            ->orderBy('fecha_inicio')
            ->get();

        $categorias = CategoriaEvento::where('estado', 1)->orderBy('nombre')->get();

        $favoritosIds = [];
        /** @var \App\Models\Usuario|null $usuario */
        $usuario = Auth::user();
        if ($usuario) {
            $favoritosIds = $usuario->favoritos()
                ->pluck('eventos.id')
                ->map(fn ($id) => (int) $id)
                ->all();
        }

        $eventosParaJs = $todosEventos->map(fn ($e) => [
            'id'             => $e->id,
            'titulo'         => $e->titulo,
            'artista'        => $e->organizador?->empresa?->nombre ?? $e->organizador?->nombre_empresa ?? '',
            'tagline'        => $e->tagline,
            'fechaFmt'       => $e->fecha_fmt,
            'hora'           => $e->hora,
            'lugar'          => $e->ubicacion_nombre,
            'ciudad'         => $e->ubicacion_nombre,
            'coords'         => $e->coords,
            'categoria'      => $e->categoria?->nombre ?? 'Evento',
            'precio'         => $e->precio_formateado,
            'cupos'          => $e->cupos_disponibles,
            'img'            => $e->url_portada,
            'featured'       => (bool) $e->featured,
            'soldOut'        => $e->sell_out,
            'estaOcurriendo' => $e->fecha_inicio <= $ahora && ($e->fecha_fin === null || $e->fecha_fin >= $ahora),
            'haTerminado'    => $e->fecha_fin !== null && $e->fecha_fin < $ahora,
            'color'          => '#a855f7',
        ]);

        return view('mapa', compact('eventosParaJs', 'categorias', 'favoritosIds'));
    }
}
