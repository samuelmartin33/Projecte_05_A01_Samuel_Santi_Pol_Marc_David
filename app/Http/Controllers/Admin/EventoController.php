<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CategoriaEvento;
use App\Models\Evento;
use App\Models\Organizador;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

/**
 * Controlador para la gestión administrativa de eventos.
 */
class EventoController extends Controller
{
    public function index(Request $request): View
    {
        $busqueda = $request->input('busqueda', '');

        $eventos = Evento::with(['categoriaEvento', 'organizador.empresa'])
            ->when($busqueda, function ($q) use ($busqueda) {
                $q->where('titulo', 'like', '%' . $busqueda . '%')
                  ->orWhereHas('organizador.empresa', function ($q2) use ($busqueda) {
                      $q2->where('nombre_empresa', 'like', '%' . $busqueda . '%');
                  });
            })
            ->orderByDesc('id')
            ->paginate(10)
            ->withQueryString();

        return view('admin.eventos.index', compact('eventos', 'busqueda'));
    }

    /** Búsqueda AJAX de eventos por título. */
    public function buscar(Request $request): JsonResponse
    {
        $q = $request->input('q', '');
        $eventos = Evento::with(['categoriaEvento'])
            ->when($q, fn ($query) => $query->where('titulo', 'like', "%{$q}%"))
            ->orderByDesc('id')->limit(30)->get();

        return response()->json($eventos->map(fn ($e) => [
            'id'          => $e->id,
            'titulo'      => $e->titulo,
            'categoria'   => $e->categoriaEvento?->nombre ?? 'Sin categoría',
            'estado'      => (int) $e->estado,
            'fecha_inicio'=> optional($e->fecha_inicio)->format('d/m/Y H:i') ?? '—',
            'edit_url'    => route('admin.eventos.edit', $e->id),
        ]));
    }

    public function create(): View
    {
        return view('admin.eventos.create', [
            'evento' => new Evento(),
            'organizadores' => Organizador::with('usuario')->orderBy('id')->get(),
            'categorias' => CategoriaEvento::orderBy('nombre')->get(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validatedData($request);
        $categorias = $data['categorias'];
        unset($data['categorias']);
        $data['fecha_creacion'] = now();
        $data['fecha_actualizacion'] = null;

        $evento = Evento::create($data);
        $evento->categorias()->sync($categorias);

        return redirect()
            ->route('admin.eventos.index')
            ->with('success', 'Evento creado correctamente.');
    }

    public function edit(Evento $evento): View
    {
        return view('admin.eventos.edit', [
            'evento' => $evento,
            'organizadores' => Organizador::with('usuario')->orderBy('id')->get(),
            'categorias' => CategoriaEvento::orderBy('nombre')->get(),
        ]);
    }

    public function update(Request $request, Evento $evento): RedirectResponse
    {
        $data = $this->validatedData($request);
        $categorias = $data['categorias'];
        unset($data['categorias']);
        $data['fecha_actualizacion'] = now();

        $evento->update($data);
        $evento->categorias()->sync($categorias);

        return redirect()
            ->route('admin.eventos.index')
            ->with('success', 'Evento actualizado correctamente.');
    }

    public function destroy(Evento $evento): RedirectResponse
    {
        $evento->delete();

        return redirect()
            ->route('admin.eventos.index')
            ->with('success', 'Evento eliminado correctamente.');
    }

    private function validatedData(Request $request): array
    {
        $data = $request->validate([
            'organizador_id'     => ['required', 'integer', 'exists:organizadores,id'],
            'categorias'         => ['required', 'array', 'min:1'],
            'categorias.*'       => ['integer', 'exists:categorias_evento,id'],
            'tipo_evento'        => ['required', 'integer', 'in:1,2'],
            'titulo'             => ['required', 'string', 'max:300'],
            'descripcion'        => ['nullable', 'string'],
            'fecha_inicio'       => ['required', 'date'],
            'fecha_fin'          => ['nullable', 'date', 'after_or_equal:fecha_inicio'],
            'ubicacion_nombre'   => ['nullable', 'string', 'max:300'],
            'ubicacion_direccion'=> ['nullable', 'string', 'max:500'],
            'latitud'            => ['nullable', 'numeric', 'between:-90,90'],
            'longitud'           => ['nullable', 'numeric', 'between:-180,180'],
            'precio_base'        => ['required', 'numeric', 'min:0'],
            'aforo_maximo'       => ['nullable', 'integer', 'min:1'],
            'aforo_actual'       => ['required', 'integer', 'min:0'],
            'edad_minima'        => ['nullable', 'integer', 'between:0,120'],
            'es_gratuito'        => ['nullable', 'boolean'],
            'url_externa'        => ['nullable', 'url', 'max:500'],
            'estado'             => ['required', 'integer', 'in:0,1'],
        ]);

        $data['es_gratuito']        = $request->boolean('es_gratuito') ? 1 : 0;
        $data['precio_base']        = $data['es_gratuito'] ? 0 : $data['precio_base'];
        $data['categoria_evento_id'] = $data['categorias'][0];

        return $data;
    }
}
