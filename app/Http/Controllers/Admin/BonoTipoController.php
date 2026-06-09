<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BonoTipo;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * CRUD de tipos de bono (panel admin VIBEZ).
 * Permite gestionar los tipos disponibles (2, 5, 10 bebidas).
 * Todas las operaciones devuelven JSON para AJAX.
 */
class BonoTipoController extends Controller
{
    /** Lista todos los tipos de bono. */
    public function index(Request $request)
    {
        $tipos = BonoTipo::orderBy('cantidad_bebidas')->get();

        if ($request->ajax()) {
            return response()->json([
                'html' => view('admin.bonos-tipos._tabla', compact('tipos'))->render(),
            ]);
        }

        return view('admin.bonos-tipos.index', compact('tipos'));
    }

    /** Crea un nuevo tipo de bono. */
    public function store(Request $request): JsonResponse
    {
        $datos = $request->validate([
            'cantidad_bebidas' => ['required', 'integer', 'min:1'],
            'precio'           => ['required', 'numeric', 'min:0'],
            'descripcion'      => ['nullable', 'string', 'max:500'],
            'activo'           => ['nullable', 'boolean'],
        ]);

        $datos['activo'] = $request->boolean('activo', true);

        BonoTipo::create($datos);

        return response()->json([
            'ok'      => true,
            'mensaje' => 'Tipo de bono creado correctamente.',
        ]);
    }

    /** Devuelve los datos de un tipo para precargar el modal de edición. */
    public function show(int $id): JsonResponse
    {
        $tipo = BonoTipo::findOrFail($id);

        return response()->json([
            'id'               => $tipo->id,
            'cantidad_bebidas' => $tipo->cantidad_bebidas,
            'precio'           => $tipo->precio,
            'descripcion'      => $tipo->descripcion,
            'activo'           => (bool) $tipo->activo,
        ]);
    }

    /** Actualiza un tipo de bono existente. */
    public function update(Request $request, int $id): JsonResponse
    {
        $tipo = BonoTipo::findOrFail($id);

        $datos = $request->validate([
            'cantidad_bebidas' => ['required', 'integer', 'min:1'],
            'precio'           => ['required', 'numeric', 'min:0'],
            'descripcion'      => ['nullable', 'string', 'max:500'],
            'activo'           => ['nullable', 'boolean'],
        ]);

        $datos['activo'] = $request->boolean('activo');

        $tipo->update($datos);

        return response()->json([
            'ok'      => true,
            'mensaje' => 'Tipo de bono actualizado correctamente.',
        ]);
    }

    /** Activa o desactiva un tipo de bono (toggle). */
    public function toggleActivo(int $id): JsonResponse
    {
        $tipo = BonoTipo::findOrFail($id);
        $tipo->update(['activo' => ! $tipo->activo]);

        return response()->json([
            'ok'     => true,
            'activo' => $tipo->activo,
        ]);
    }

    /** Elimina un tipo de bono si no tiene compras asociadas. */
    public function destroy(int $id): JsonResponse
    {
        $tipo = BonoTipo::findOrFail($id);

        if ($tipo->compras()->exists()) {
            return response()->json([
                'ok'      => false,
                'mensaje' => 'No se puede eliminar: este tipo tiene compras de bonos asociadas.',
            ], 422);
        }

        $tipo->delete();

        return response()->json(['ok' => true]);
    }
}
