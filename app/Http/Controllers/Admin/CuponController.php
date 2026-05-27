<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Cupon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CuponController extends Controller
{
    public function index()
    {
        $cupones = Cupon::with(['empresa', 'eventos'])
            ->where('estado', 1)
            ->orderBy('fecha_fin', 'asc')
            ->get();

        return view('admin.cupones.index', compact('cupones'));
    }

    /** Búsqueda AJAX de cupones por código o empresa. */
    public function buscar(Request $request): JsonResponse
    {
        $q = $request->input('q', '');
        $cupones = Cupon::with(['empresa', 'eventos'])
            ->where('estado', 1)
            ->when($q, fn ($query) => $query->where(function ($q2) use ($q) {
                $q2->where('codigo', 'like', "%{$q}%")
                   ->orWhereHas('empresa', fn ($qe) => $qe->where('nombre_empresa', 'like', "%{$q}%"));
            }))
            ->orderBy('fecha_fin')->limit(30)->get();

        return response()->json($cupones->map(fn ($c) => [
            'id'              => $c->id,
            'codigo'          => $c->codigo,
            'empresa'         => $c->empresa?->nombre_empresa ?? '—',
            'valor_descuento' => $c->valor_descuento,
            'fecha_inicio'    => $c->fecha_inicio->format('d/m/Y'),
            'fecha_fin'       => $c->fecha_fin->format('d/m/Y'),
            'usos_actuales'   => $c->usos_actuales,
            'limite_usos'     => $c->limite_usos_total,
            'num_eventos'     => $c->eventos->count(),
        ]));
    }
}
