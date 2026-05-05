<?php

namespace App\Http\Controllers\Empresa;

use App\Http\Controllers\Controller;
use App\Models\BolsaOfertaTrabajo;
use App\Models\CategoriaTrabajo;
use App\Models\Empresa;
use App\Models\Organizador;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

/**
 * Controlador para la gestión de ofertas de trabajo de empresa.
 */
class OfertasController extends Controller
{
    private function obtenerOrganizador(): Organizador
    {
        /** @var \App\Models\Usuario $user */
        $user = Auth::user();

        if (!$user || !$user->isEmpresa()) {
            abort(403, 'Acceso restringido a empresas.');
        }

        $empresa = $user->empresa;

        if ($empresa) {
            $organizador = Organizador::where('empresa_id', $empresa->id)
                ->where('estado', 1)
                ->first();

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

        $organizador = Organizador::where('usuario_id', $user->id)
            ->where('estado', 1)
            ->first();

        if (!$organizador) {
            $empresa = Empresa::create([
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

            $user->unsetRelation('empresa');
        }

        return $organizador;
    }

    /**
     * Muestra el formulario de creación de oferta de trabajo.
     * GET /empresa/ofertas/crear
     */
    public function create(): View
    {
        $categorias = CategoriaTrabajo::where('estado', 1)
            ->orderBy('nombre')
            ->get();

        return view('empresa.ofertas.crear', compact('categorias'));
    }

    /**
     * Guarda la nueva oferta de trabajo.
     * POST /empresa/ofertas
     */
    public function store(Request $request)
    {
        $organizador = $this->obtenerOrganizador();

        $validated = $request->validate([
            'titulo'               => ['required', 'string', 'max:300'],
            'descripcion'          => ['nullable', 'string', 'max:5000'],
            'requisitos'           => ['nullable', 'string', 'max:3000'],
            'categoria_trabajo_id' => ['nullable', 'integer', 'exists:categorias_trabajo,id'],
            'ubicacion'            => ['nullable', 'string', 'max:300'],
            'salario_min'          => ['nullable', 'numeric', 'min:0'],
            'salario_max'          => ['nullable', 'numeric', 'min:0', 'gte:salario_min'],
            'vacantes'             => ['nullable', 'integer', 'min:1'],
            'fecha_inicio_trabajo' => ['nullable', 'date'],
            'fecha_fin_trabajo'    => ['nullable', 'date', 'after_or_equal:fecha_inicio_trabajo'],
        ], [
            'titulo.required'               => 'El título de la oferta es obligatorio.',
            'titulo.max'                    => 'El título no puede superar los 300 caracteres.',
            'categoria_trabajo_id.exists'   => 'La categoría seleccionada no es válida.',
            'salario_max.gte'               => 'El salario máximo debe ser mayor o igual al mínimo.',
            'fecha_fin_trabajo.after_or_equal' => 'La fecha de fin debe ser posterior a la de inicio.',
        ]);

        $oferta = BolsaOfertaTrabajo::create([
            'organizador_id'       => $organizador->id,
            'titulo'               => $validated['titulo'],
            'descripcion'          => $validated['descripcion'] ?? null,
            'requisitos'           => $validated['requisitos'] ?? null,
            'categoria_trabajo_id' => $validated['categoria_trabajo_id'] ?? null,
            'ubicacion'            => $validated['ubicacion'] ?? null,
            'salario_min'          => $validated['salario_min'] ?? null,
            'salario_max'          => $validated['salario_max'] ?? null,
            'vacantes'             => $validated['vacantes'] ?? 1,
            'fecha_inicio_trabajo' => $validated['fecha_inicio_trabajo'] ?? null,
            'fecha_fin_trabajo'    => $validated['fecha_fin_trabajo'] ?? null,
            'estado'               => 1,
            'fecha_creacion'       => now(),
        ]);

        return redirect()
            ->route('empresa.home')
            ->with('success', '¡Oferta "' . $oferta->titulo . '" publicada correctamente!');
    }
}
