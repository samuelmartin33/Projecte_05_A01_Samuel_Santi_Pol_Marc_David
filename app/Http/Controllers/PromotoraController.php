<?php

namespace App\Http\Controllers;

use App\Models\Empresa;
use App\Models\Evento;
use App\Models\ValoracionEmpresa;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;

class PromotoraController extends Controller
{
    /**
     * Perfil público de una empresa/promotora.
     * Muestra sus eventos, valoraciones y permite valorar si el usuario está autenticado.
     */
    public function show(int $id): View
    {
        // Cargar empresa activa
        $empresa = Empresa::where('estado', 1)->findOrFail($id);

        // Próximos eventos activos de la empresa (sin paginar para el perfil)
        $eventos = Evento::with(['portada', 'categorias'])
            ->whereHas('organizador', fn ($q) => $q->where('empresa_id', $id))
            ->where('estado', 1)
            ->whereRaw('COALESCE(fecha_fin, fecha_inicio) >= NOW()')
            ->orderBy('fecha_inicio')
            ->get();

        // Valoraciones visibles con datos del autor
        $valoraciones = ValoracionEmpresa::with('usuario')
            ->where('empresa_id', $id)
            ->where('estado', 1)
            ->orderBy('fecha_creacion', 'desc')
            ->get();

        $mediaValoracion   = $valoraciones->count() > 0 ? round($valoraciones->avg('puntuacion'), 1) : null;
        $totalValoraciones = $valoraciones->count();

        // Estado del usuario autenticado respecto a esta empresa
        $yaValorado    = false;
        $siguePromotor = false;
        $esPropiaEmpresa = false;

        if (Auth::check()) {
            /** @var \App\Models\Usuario $usuario */
            $usuario = Auth::user();

            $yaValorado = ValoracionEmpresa::where('usuario_id', $usuario->id)
                                           ->where('empresa_id', $id)
                                           ->exists();

            $siguePromotor = $usuario->seguimientos()->where('empresa_id', $id)->exists();

            // La empresa propietaria no puede valorarse a sí misma
            $esPropiaEmpresa = $usuario->isEmpresa() && $usuario->empresa?->id === $id;
        }

        return view('promotoras.perfil', compact(
            'empresa',
            'eventos',
            'valoraciones',
            'mediaValoracion',
            'totalValoraciones',
            'yaValorado',
            'siguePromotor',
            'esPropiaEmpresa'
        ));
    }
}
