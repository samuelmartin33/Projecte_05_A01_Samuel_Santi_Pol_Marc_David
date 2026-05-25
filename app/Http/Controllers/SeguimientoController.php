<?php

namespace App\Http\Controllers;

use App\Models\Empresa;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

/**
 * Gestiona que un usuario siga o deje de seguir a una empresa/promotora.
 */
class SeguimientoController extends Controller
{
    /**
     * Alterna el seguimiento del usuario autenticado sobre una empresa.
     * Si ya la sigue → la deja de seguir.  Si no → empieza a seguirla.
     *
     * POST /api/seguimientos/{empresa}/toggle
     *
     * @return JsonResponse  { siguiendo: bool, total_seguidores: int }
     */
    public function toggle(int $empresaId): JsonResponse
    {
        $empresa = Empresa::where('estado', 1)->findOrFail($empresaId);

        /** @var \App\Models\Usuario $usuario */
        $usuario = Auth::user();

        $yaSigue = $usuario->seguimientos()->where('empresa_id', $empresa->id)->exists();

        if ($yaSigue) {
            $usuario->seguimientos()->detach($empresa->id);
            $siguiendo = false;
        } else {
            $usuario->seguimientos()->attach($empresa->id, [
                'fecha_creacion' => now(),
            ]);
            $siguiendo = true;
        }

        return response()->json([
            'success'          => true,
            'siguiendo'        => $siguiendo,
            'total_seguidores' => $empresa->seguidores()->count(),
        ]);
    }

    /**
     * Devuelve los IDs de empresas que sigue el usuario autenticado.
     * Usado por el home para saber qué botones pintar como "Siguiendo".
     *
     * GET /api/seguimientos/ids
     *
     * @return JsonResponse  { ids: int[] }
     */
    public function misSeguidosIds(): JsonResponse
    {
        /** @var \App\Models\Usuario $usuario */
        $usuario = Auth::user();

        $ids = $usuario->seguimientos()->pluck('empresas.id')->map(fn ($id) => (int) $id)->all();

        return response()->json(['ids' => $ids]);
    }

    /**
     * Devuelve las empresas que sigue el usuario con sus próximos eventos.
     * Usado por el perfil/social para mostrar la lista de promotoras seguidas.
     *
     * GET /api/seguimientos/promotoras
     *
     * @return JsonResponse  { promotoras: [...] }
     */
    public function misPromotoras(): JsonResponse
    {
        /** @var \App\Models\Usuario $usuario */
        $usuario = Auth::user();

        $promotoras = $usuario->seguimientos()
            ->with(['eventos' => function ($q) {
                $q->where('eventos.estado', 1)
                  ->where('eventos.fecha_inicio', '>=', now())
                  ->orderBy('fecha_inicio')
                  ->limit(3);
            }])
            ->get()
            ->map(fn ($empresa) => [
                'id'              => $empresa->id,
                'nombre'          => $empresa->nombre_empresa,
                'logo_url'        => $empresa->logo_url,
                'descripcion'     => $empresa->descripcion,
                'proximos_eventos' => $empresa->eventos->map(fn ($e) => [
                    'id'       => $e->id,
                    'titulo'   => $e->titulo,
                    'fechaFmt' => $e->fecha_fmt,
                    'img'      => $e->url_portada,
                ])->values(),
            ]);

        return response()->json(['promotoras' => $promotoras]);
    }
}
