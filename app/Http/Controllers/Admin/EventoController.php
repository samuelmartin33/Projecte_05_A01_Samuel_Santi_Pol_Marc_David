<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CategoriaEvento;
use App\Models\Evento;
use App\Models\Organizador;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class EventoController extends Controller
{
    public function index(): View
    {
        $eventos = Evento::with(['categoriaEvento', 'organizador'])
            ->orderByDesc('id')
            ->paginate(10);

        return view('admin.eventos.index', compact('eventos'));
    }

    public function create(): View
    {
        return view('admin.eventos.create', [
            'evento' => new Evento(),
            'organizadores' => Organizador::orderBy('id')->get(),
            'categorias' => CategoriaEvento::orderBy('nombre')->get(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validatedData($request);
        $data['fecha_creacion'] = now();
        $data['fecha_actualizacion'] = null;

        Evento::create($data);

        return redirect()
            ->route('admin.eventos.index')
            ->with('success', 'Evento creado correctamente.');
    }

    public function edit(Evento $evento): View
    {
        return view('admin.eventos.edit', [
            'evento' => $evento,
            'organizadores' => Organizador::orderBy('id')->get(),
            'categorias' => CategoriaEvento::orderBy('nombre')->get(),
        ]);
    }

    public function update(Request $request, Evento $evento): RedirectResponse
    {
        $data = $this->validatedData($request);
        $data['fecha_actualizacion'] = now();

        $evento->update($data);

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
            'organizador_id' => ['required', 'integer', 'exists:organizadores,id'],
            'categoria_evento_id' => ['required', 'integer', 'exists:categorias_evento,id'],
            'tipo_evento' => ['required', 'integer', 'in:1,2'],
            'titulo' => ['required', 'string', 'max:300'],
            'descripcion' => ['nullable', 'string'],
            'fecha_inicio' => ['required', 'date'],
            'fecha_fin' => ['nullable', 'date', 'after_or_equal:fecha_inicio'],
            'ubicacion_nombre' => ['nullable', 'string', 'max:300'],
            'ubicacion_direccion' => ['nullable', 'string', 'max:500'],
            'latitud' => ['nullable', 'numeric', 'between:-90,90'],
            'longitud' => ['nullable', 'numeric', 'between:-180,180'],
            'precio_base' => ['required', 'numeric', 'min:0'],
            'aforo_maximo' => ['nullable', 'integer', 'min:1'],
            'aforo_actual' => ['required', 'integer', 'min:0'],
            'edad_minima' => ['nullable', 'integer', 'between:0,120'],
            'es_gratuito' => ['nullable', 'boolean'],
            'url_externa' => ['nullable', 'url', 'max:500'],
            'estado' => ['required', 'integer', 'in:0,1'],
        ]);

        $data['es_gratuito'] = $request->boolean('es_gratuito') ? 1 : 0;
        $data['precio_base'] = $data['es_gratuito'] ? 0 : $data['precio_base'];

        return $data;
    }
}
