<?php

namespace App\Http\Controllers\Empresa;

use App\Http\Controllers\Controller;
use App\Models\CategoriaEvento;
use App\Models\Evento;
use App\Models\EventoImagen;
use App\Models\Organizador;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class EventosController extends Controller
{
    /**
     * Obtiene el organizador vinculado a la empresa del usuario autenticado.
     * Si no existe un organizador, lo crea automáticamente.
     */
    private function obtenerOrganizador(): Organizador
    {
        /** @var \App\Models\Usuario $user */
        $user = Auth::user();

        if (!$user || !$user->isEmpresa()) {
            abort(403, 'Acceso restringido a empresas.');
        }

        $empresa = $user->empresa;

        // Si el usuario tiene empresa con registro en la tabla
        if ($empresa) {
            // Buscar organizador existente de esta empresa
            $organizador = Organizador::where('empresa_id', $empresa->id)
                ->where('estado', 1)
                ->first();

            // Si no hay organizador, crear uno con el usuario actual
            if (!$organizador) {
                $organizador = Organizador::create([
                    'usuario_id'          => $user->id,
                    'empresa_id'          => $empresa->id,
                    'estado'              => 1,
                    'fecha_creacion'      => now(),
                    'fecha_actualizacion' => now(),
                ]);
            }

            return $organizador;
        }

        // Si no tiene registro en tabla empresas, buscar organizador por usuario_id
        $organizador = Organizador::where('usuario_id', $user->id)
            ->where('estado', 1)
            ->first();

        if (!$organizador) {
            // Crear una empresa y un organizador para este usuario
            $empresa = \App\Models\Empresa::create([
                'usuario_id'          => $user->id,
                'nombre_empresa'      => $user->nombre . ' ' . ($user->apellido1 ?? ''),
                'estado'              => 1,
                'fecha_creacion'      => now(),
                'fecha_actualizacion' => now(),
            ]);

            $organizador = Organizador::create([
                'usuario_id'          => $user->id,
                'empresa_id'          => $empresa->id,
                'estado'              => 1,
                'fecha_creacion'      => now(),
                'fecha_actualizacion' => now(),
            ]);

            // Limpiar relación cacheada
            $user->unsetRelation('empresa');
        }

        return $organizador;
    }

    /**
     * Muestra el formulario de creación de evento.
     * GET /empresa/eventos/crear
     */
    public function create(): View
    {
        $categorias = CategoriaEvento::where('estado', 1)
            ->orderBy('nombre')
            ->get();

        return view('empresa.eventos.crear', compact('categorias'));
    }

    /**
     * Almacena un nuevo evento en la base de datos.
     * POST /empresa/eventos
     */
    public function store(Request $request)
    {
        $organizador = $this->obtenerOrganizador();

        $validated = $request->validate([
            'titulo'              => ['required', 'string', 'max:300'],
            'descripcion'         => ['nullable', 'string', 'max:5000'],
            'categoria_evento_id' => ['required', 'integer', 'exists:categorias_evento,id'],
            'tipo_evento'         => ['required', 'integer', 'in:1,2'],
            'fecha_inicio'        => ['required', 'date', 'after_or_equal:today'],
            'fecha_fin'           => ['nullable', 'date', 'after_or_equal:fecha_inicio'],
            'ubicacion_nombre'    => ['required', 'string', 'max:300'],
            'ubicacion_direccion' => ['nullable', 'string', 'max:500'],
            'latitud'             => ['nullable', 'numeric', 'between:-90,90'],
            'longitud'            => ['nullable', 'numeric', 'between:-180,180'],
            'precio_base'         => ['required', 'numeric', 'min:0'],
            'aforo_maximo'        => ['nullable', 'integer', 'min:1'],
            'edad_minima'         => ['nullable', 'integer', 'between:0,120'],
            'es_gratuito'         => ['nullable'],
            'url_externa'         => ['nullable', 'url', 'max:500'],
            'imagen_portada'      => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp,gif', 'max:5120'],
        ], [
            'titulo.required'              => 'El título del evento es obligatorio.',
            'titulo.max'                   => 'El título no puede superar los 300 caracteres.',
            'categoria_evento_id.required' => 'Selecciona una categoría.',
            'categoria_evento_id.exists'   => 'La categoría seleccionada no es válida.',
            'tipo_evento.required'         => 'Selecciona el tipo de evento.',
            'fecha_inicio.required'        => 'La fecha de inicio es obligatoria.',
            'fecha_inicio.after_or_equal'  => 'La fecha de inicio no puede ser anterior a hoy.',
            'fecha_fin.after_or_equal'     => 'La fecha de fin debe ser posterior a la de inicio.',
            'ubicacion_nombre.required'    => 'El nombre del lugar es obligatorio.',
            'precio_base.required'         => 'Indica el precio (0 si es gratuito).',
            'imagen_portada.image'         => 'El archivo debe ser una imagen.',
            'imagen_portada.mimes'         => 'Formatos permitidos: JPG, PNG, WebP, GIF.',
            'imagen_portada.max'           => 'La imagen no puede superar los 5 MB.',
            'precio_base.min'              => 'El precio no puede ser negativo.',
        ]);

        $esGratuito = $request->boolean('es_gratuito');

        $evento = Evento::create([
            'organizador_id'      => $organizador->id,
            'categoria_evento_id' => $validated['categoria_evento_id'],
            'tipo_evento'         => $validated['tipo_evento'],
            'titulo'              => $validated['titulo'],
            'descripcion'         => $validated['descripcion'] ?? null,
            'fecha_inicio'        => $validated['fecha_inicio'],
            'fecha_fin'           => $validated['fecha_fin'] ?? null,
            'ubicacion_nombre'    => $validated['ubicacion_nombre'] ?? null,
            'ubicacion_direccion' => $validated['ubicacion_direccion'] ?? null,
            'latitud'             => $validated['latitud'] ?? null,
            'longitud'            => $validated['longitud'] ?? null,
            'precio_base'         => $esGratuito ? 0 : $validated['precio_base'],
            'aforo_maximo'        => $validated['aforo_maximo'] ?? null,
            'aforo_actual'        => 0,
            'edad_minima'         => $validated['edad_minima'] ?? null,
            'es_gratuito'         => $esGratuito ? 1 : 0,
            'url_externa'         => $validated['url_externa'] ?? null,
            'estado'              => 1,
            'fecha_creacion'      => now(),
            'fecha_actualizacion' => null,
        ]);

        // Guardar imagen de portada si se subió un archivo
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

        return redirect()
            ->route('empresa.home')
            ->with('success', '¡Evento "' . $evento->titulo . '" creado correctamente!');
    }

    /**
     * Elimina un evento propio de la empresa.
     * DELETE /empresa/eventos/{id}
     */
    public function destroy(int $id)
    {
        $organizador = $this->obtenerOrganizador();

        // Verificar que el evento pertenece a este organizador
        $evento = Evento::where('id', $id)
            ->where('organizador_id', $organizador->id)
            ->firstOrFail();

        // Eliminar imágenes asociadas
        $evento->imagenes()->delete();

        // Eliminar el evento
        $titulo = $evento->titulo;
        $evento->delete();

        return redirect()
            ->route('empresa.home')
            ->with('success', 'Evento "' . $titulo . '" eliminado correctamente.');
    }
}
