<?php

namespace App\Http\Controllers\Empresa;

use App\Http\Controllers\Controller;
use App\Models\CategoriaEvento;
use App\Models\Evento;
use App\Models\EventoImagen;
use App\Models\Organizador;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * FiestaController — CRUD AJAX de eventos de categoría Fiesta para empresas.
 *
 * Todos los eventos gestionados aquí pertenecen a la empresa del usuario autenticado
 * y tienen categoría Fiesta. Las respuestas son JSON excepto index() que devuelve la vista.
 */
class FiestaController extends Controller
{
    /** Obtiene la empresa del usuario logueado o aborta si no tiene. */
    private function getEmpresa()
    {
        $empresa = Auth::user()->empresa;
        abort_if(!$empresa, 403, 'No tienes empresa asociada.');
        return $empresa;
    }

    /** Obtiene el organizador principal de la empresa logueada. */
    private function getOrganizador()
    {
        $empresa = $this->getEmpresa();
        return Organizador::where('empresa_id', $empresa->id)
            ->where('rol', 'organizador')
            ->where('estado', 1)
            ->firstOrFail();
    }

    /** Obtiene el ID de la categoría Fiesta. */
    private function getCategoriaFiestaId(): int
    {
        return CategoriaEvento::where('slug', 'fiesta')->value('id')
            ?? abort(500, 'Categoría Fiesta no encontrada en la BD.');
    }

    /**
     * Vista principal del CRUD de eventos Fiesta.
     * GET /empresa/fiesta
     */
    public function index()
    {
        $empresa = $this->getEmpresa();

        // Camareros disponibles de esta empresa para asignar a eventos
        $camareros = Organizador::where('empresa_id', $empresa->id)
            ->where('rol', 'camarero')
            ->where('estado', 1)
            ->with('usuario')
            ->get();

        return view('empresa.fiesta.index', compact('camareros'));
    }

    /**
     * Devuelve los eventos Fiesta de la empresa aplicando filtros. (AJAX)
     * GET /empresa/fiesta/listar
     */
    public function listar(Request $request)
    {
        $empresa = $this->getEmpresa();
        $categoriaId = $this->getCategoriaFiestaId();

        $query = Evento::whereHas('organizador', fn($q) => $q->where('empresa_id', $empresa->id))
            ->whereHas('categorias', fn($q) => $q->where('categorias_evento.id', $categoriaId))
            ->with(['portada', 'camarero.usuario']);

        // Filtros sumativos: se aplican solo si se envían en la petición
        if ($request->filled('nombre')) {
            $query->where('titulo', 'like', '%' . $request->nombre . '%');
        }
        if ($request->filled('fecha')) {
            $query->whereDate('fecha_inicio', $request->fecha);
        }
        if ($request->filled('ubicacion')) {
            $query->where('ubicacion_nombre', 'like', '%' . $request->ubicacion . '%');
        }

        $eventos = $query->orderBy('fecha_inicio', 'desc')->get()->map(function ($e) {
            return [
                'id'               => $e->id,
                'titulo'           => $e->titulo,
                'fecha_inicio'     => $e->fecha_inicio->format('d/m/Y H:i'),
                'ubicacion_nombre' => $e->ubicacion_nombre,
                'precio_base'      => number_format($e->precio_base, 2) . ' €',
                'aforo_maximo'     => $e->aforo_maximo,
                'aforo_actual'     => $e->aforo_actual,
                'camarero'         => $e->camarero?->usuario?->nombre . ' ' . $e->camarero?->usuario?->apellido1,
                'portada'          => $e->url_portada,
                'estado'           => $e->estado,
            ];
        });

        return response()->json($eventos);
    }

    /**
     * Devuelve los datos completos de un evento para rellenar el modal de edición. (AJAX)
     * GET /empresa/fiesta/{id}
     */
    public function show(int $id)
    {
        $empresa   = $this->getEmpresa();
        $categoriaId = $this->getCategoriaFiestaId();

        $evento = Evento::whereHas('organizador', fn($q) => $q->where('empresa_id', $empresa->id))
            ->whereHas('categorias', fn($q) => $q->where('categorias_evento.id', $categoriaId))
            ->with('portada')
            ->findOrFail($id);

        return response()->json([
            'id'                  => $evento->id,
            'titulo'              => $evento->titulo,
            'descripcion'         => $evento->descripcion,
            'fecha_inicio'        => $evento->fecha_inicio->format('Y-m-d\TH:i'),
            'fecha_fin'           => $evento->fecha_fin?->format('Y-m-d\TH:i'),
            'ubicacion_nombre'    => $evento->ubicacion_nombre,
            'ubicacion_direccion' => $evento->ubicacion_direccion,
            'latitud'             => $evento->latitud,
            'longitud'            => $evento->longitud,
            'precio_base'         => $evento->precio_base,
            'aforo_maximo'        => $evento->aforo_maximo,
            'camarero_id'         => $evento->camarero_id,
            'portada'             => $evento->url_portada,
        ]);
    }

    /**
     * Crea un nuevo evento de categoría Fiesta. (AJAX)
     * POST /empresa/fiesta
     */
    public function store(Request $request)
    {
        $empresa     = $this->getEmpresa();
        $organizador = $this->getOrganizador();
        $categoriaId = $this->getCategoriaFiestaId();

        $validated = $request->validate([
            'titulo'              => ['required', 'string', 'max:300'],
            'descripcion'         => ['nullable', 'string', 'max:5000'],
            'fecha_inicio'        => ['required', 'date', 'after_or_equal:today'],
            'fecha_fin'           => ['nullable', 'date', 'after_or_equal:fecha_inicio'],
            'ubicacion_nombre'    => ['required', 'string', 'max:300'],
            'ubicacion_direccion' => ['nullable', 'string', 'max:500'],
            'latitud'             => ['nullable', 'numeric', 'between:-90,90'],
            'longitud'            => ['nullable', 'numeric', 'between:-180,180'],
            'precio_base'         => ['required', 'numeric', 'min:0.01'],  // Fiesta es SIEMPRE de pago
            'aforo_maximo'        => ['nullable', 'integer', 'min:1'],
            'camarero_id'         => ['required', 'integer', 'exists:organizadores,id'],
            'imagen_portada'      => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
        ], [
            'precio_base.min'      => 'Los eventos de Fiesta son de pago obligatorio.',
            'camarero_id.required' => 'Debes asignar un camarero al evento de Fiesta.',
        ]);

        // Verificar que el camarero pertenece a esta empresa
        $camarero = Organizador::where('id', $validated['camarero_id'])
            ->where('empresa_id', $empresa->id)
            ->where('rol', 'camarero')
            ->firstOrFail();

        $evento = Evento::create([
            'organizador_id'      => $organizador->id,
            'categoria_evento_id' => $categoriaId,
            'tipo_evento'         => 1,
            'titulo'              => $validated['titulo'],
            'descripcion'         => $validated['descripcion'] ?? null,
            'fecha_inicio'        => $validated['fecha_inicio'],
            'fecha_fin'           => $validated['fecha_fin'] ?? null,
            'ubicacion_nombre'    => $validated['ubicacion_nombre'],
            'ubicacion_direccion' => $validated['ubicacion_direccion'] ?? null,
            'latitud'             => $validated['latitud'] ?? null,
            'longitud'            => $validated['longitud'] ?? null,
            'precio_base'         => $validated['precio_base'],
            'aforo_maximo'        => $validated['aforo_maximo'] ?? null,
            'aforo_actual'        => 0,
            'edad_minima'         => 18,   // Fiesta: siempre mayores de 18
            'es_gratuito'         => 0,    // Fiesta: siempre de pago
            'camarero_id'         => $camarero->id,
            'estado'              => 1,
            'fecha_creacion'      => now(),
            'fecha_actualizacion' => null,
        ]);

        // Asociar la categoría Fiesta en la tabla pivot
        $evento->categorias()->sync([$categoriaId]);

        // Guardar imagen de portada si se adjuntó
        if ($request->hasFile('imagen_portada')) {
            $path = $request->file('imagen_portada')->store('eventos', 'public');
            EventoImagen::create([
                'evento_id'      => $evento->id,
                'imagen_url'     => '/storage/' . $path,
                'descripcion'    => 'Portada del evento',
                'es_portada'     => 1,
                'estado'         => 1,
                'fecha_creacion' => now(),
            ]);
        }

        return response()->json(['ok' => true, 'mensaje' => 'Evento de Fiesta creado correctamente.', 'id' => $evento->id]);
    }

    /**
     * Actualiza un evento Fiesta existente. (AJAX)
     * POST /empresa/fiesta/{id}/actualizar
     */
    public function update(Request $request, int $id)
    {
        $empresa     = $this->getEmpresa();
        $categoriaId = $this->getCategoriaFiestaId();

        $evento = Evento::whereHas('organizador', fn($q) => $q->where('empresa_id', $empresa->id))
            ->whereHas('categorias', fn($q) => $q->where('categorias_evento.id', $categoriaId))
            ->with('portada')
            ->findOrFail($id);

        $validated = $request->validate([
            'titulo'              => ['required', 'string', 'max:300'],
            'descripcion'         => ['nullable', 'string', 'max:5000'],
            'fecha_inicio'        => ['required', 'date'],
            'fecha_fin'           => ['nullable', 'date', 'after_or_equal:fecha_inicio'],
            'ubicacion_nombre'    => ['required', 'string', 'max:300'],
            'ubicacion_direccion' => ['nullable', 'string', 'max:500'],
            'latitud'             => ['nullable', 'numeric', 'between:-90,90'],
            'longitud'            => ['nullable', 'numeric', 'between:-180,180'],
            'precio_base'         => ['required', 'numeric', 'min:0.01'],
            'aforo_maximo'        => ['nullable', 'integer', 'min:1'],
            'camarero_id'         => ['required', 'integer', 'exists:organizadores,id'],
            'imagen_portada'      => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
        ], [
            'precio_base.min'      => 'Los eventos de Fiesta son de pago obligatorio.',
            'camarero_id.required' => 'Debes asignar un camarero al evento de Fiesta.',
        ]);

        $camarero = Organizador::where('id', $validated['camarero_id'])
            ->where('empresa_id', $empresa->id)
            ->where('rol', 'camarero')
            ->firstOrFail();

        $evento->update([
            'titulo'              => $validated['titulo'],
            'descripcion'         => $validated['descripcion'] ?? null,
            'fecha_inicio'        => $validated['fecha_inicio'],
            'fecha_fin'           => $validated['fecha_fin'] ?? null,
            'ubicacion_nombre'    => $validated['ubicacion_nombre'],
            'ubicacion_direccion' => $validated['ubicacion_direccion'] ?? null,
            'latitud'             => $validated['latitud'] ?? null,
            'longitud'            => $validated['longitud'] ?? null,
            'precio_base'         => $validated['precio_base'],
            'aforo_maximo'        => $validated['aforo_maximo'] ?? null,
            'camarero_id'         => $camarero->id,
            'fecha_actualizacion' => now(),
        ]);

        if ($request->hasFile('imagen_portada')) {
            if ($evento->portada) {
                $evento->portada->delete();
            }
            $path = $request->file('imagen_portada')->store('eventos', 'public');
            EventoImagen::create([
                'evento_id'      => $evento->id,
                'imagen_url'     => '/storage/' . $path,
                'descripcion'    => 'Portada del evento',
                'es_portada'     => 1,
                'estado'         => 1,
                'fecha_creacion' => now(),
            ]);
        }

        return response()->json(['ok' => true, 'mensaje' => 'Evento actualizado correctamente.']);
    }

    /**
     * Elimina un evento Fiesta. (AJAX)
     * DELETE /empresa/fiesta/{id}
     */
    public function destroy(int $id)
    {
        $empresa     = $this->getEmpresa();
        $categoriaId = $this->getCategoriaFiestaId();

        $evento = Evento::whereHas('organizador', fn($q) => $q->where('empresa_id', $empresa->id))
            ->whereHas('categorias', fn($q) => $q->where('categorias_evento.id', $categoriaId))
            ->findOrFail($id);

        $evento->imagenes()->delete();
        $evento->delete();

        return response()->json(['ok' => true, 'mensaje' => 'Evento eliminado correctamente.']);
    }
}
