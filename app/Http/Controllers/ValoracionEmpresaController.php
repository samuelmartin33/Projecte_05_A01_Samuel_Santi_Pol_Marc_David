<?php

namespace App\Http\Controllers;

use App\Models\Empresa;
use App\Models\ValoracionEmpresa;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ValoracionEmpresaController extends Controller
{
    /**
     * Guarda una valoración de empresa/promotora.
     * Reglas:
     *   - Cualquier usuario autenticado puede valorar (sin requisito de compra).
     *   - Una empresa no puede valorarse a sí misma.
     *   - Admins no pueden valorar.
     *   - Un usuario solo puede valorar cada empresa una vez.
     */
    public function store(Request $request, int $empresaId): JsonResponse
    {
        /** @var \App\Models\Usuario $usuario */
        $usuario = Auth::user();

        // Bloquear admins
        if ($usuario->isAdmin()) {
            return response()->json([
                'success' => false,
                'message' => 'No tienes permiso para valorar empresas.',
            ], 403);
        }

        // Bloquear que una empresa se autovalore
        if ($usuario->isEmpresa() && $usuario->empresa?->id === $empresaId) {
            return response()->json([
                'success' => false,
                'message' => 'No puedes valorar tu propia empresa.',
            ], 403);
        }

        // Verificar que la empresa existe y está activa
        $empresa = Empresa::where('estado', 1)->findOrFail($empresaId);

        // Verificar que no ha valorado ya esta empresa
        $yaValorado = ValoracionEmpresa::where('usuario_id', $usuario->id)
                                       ->where('empresa_id', $empresaId)
                                       ->exists();

        if ($yaValorado) {
            return response()->json([
                'success' => false,
                'message' => 'Ya has valorado esta empresa.',
            ], 422);
        }

        // Validar los datos del formulario
        $validated = $request->validate([
            'puntuacion' => ['required', 'integer', 'min:1', 'max:5'],
            'comentario' => ['nullable', 'string', 'max:1000'],
        ]);

        // Crear la valoración
        $valoracion = ValoracionEmpresa::create([
            'usuario_id'          => $usuario->id,
            'empresa_id'          => $empresaId,
            'puntuacion'          => $validated['puntuacion'],
            'comentario'          => $validated['comentario'] ?? null,
            'estado'              => 1,
            'fecha_creacion'      => now(),
            'fecha_actualizacion' => now(),
        ]);

        // Calcular la nueva media y total para actualizar la UI sin recargar
        $media = ValoracionEmpresa::where('empresa_id', $empresaId)
                                  ->where('estado', 1)
                                  ->avg('puntuacion');
        $total = ValoracionEmpresa::where('empresa_id', $empresaId)
                                  ->where('estado', 1)
                                  ->count();

        return response()->json([
            'success'    => true,
            'message'    => '¡Gracias por tu valoración!',
            'media'      => round($media, 1),
            'total'      => $total,
            'valoracion' => [
                'puntuacion' => $valoracion->puntuacion,
                'comentario' => $valoracion->comentario,
                'autor'      => trim($usuario->nombre . ' ' . ($usuario->apellido1 ?? '')),
                'foto'       => $usuario->foto_url,
                'fecha'      => $valoracion->fecha_creacion->format('d/m/Y'),
            ],
        ]);
    }
}
