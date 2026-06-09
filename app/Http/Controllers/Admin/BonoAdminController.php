<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BonoCompra;
use App\Models\BonoTipo;
use App\Models\Empresa;
use App\Models\Evento;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * Controlador para el panel de control de bonos de bebidas (Admin VIBEZ).
 */
class BonoAdminController extends Controller
{
    /* ══════════════════════════════════════════════════════════════
       INDEX — Vista principal con resumen y bonos activos
    ══════════════════════════════════════════════════════════════ */

    public function index(Request $request)
    {
        // ── Consulta base con relaciones ─────────────────────────
        $consulta = BonoCompra::with(['tipo', 'usuario', 'evento.organizador.empresa'])
            ->join('eventos', 'eventos.id', '=', 'bono_compras.evento_id');

        if ($request->filled('evento_id')) {
            $consulta->where('bono_compras.evento_id', $request->evento_id);
        }

        if ($request->filled('empresa_id')) {
            $consulta->join('organizadores', 'organizadores.id', '=', 'eventos.organizador_id')
                     ->where('organizadores.empresa_id', $request->empresa_id);
        }

        $todosLosBonos = $consulta->select('bono_compras.*')->get();

        // ── Resumen por tipo de bono ──────────────────────────────
        $resumen = $todosLosBonos
            ->groupBy('bono_tipo_id')
            ->map(function ($grupo) {
                $primero = $grupo->first();
                return [
                    'cantidad_bebidas' => $primero->tipo?->cantidad_bebidas ?? '?',
                    'precio'           => $primero->tipo?->precio ?? 0,
                    'descripcion'      => $primero->tipo?->descripcion ?? '',
                    'total_vendidos'   => $grupo->count(),
                    'ingresos'         => round($grupo->count() * ($primero->tipo?->precio ?? 0), 2),
                ];
            })
            ->values();

        $totalVendidos = $todosLosBonos->count();
        $totalIngresos = $resumen->sum('ingresos');

        // ── Bonos activos (con saldo) ─────────────────────────────
        $bonosActivos = $todosLosBonos->filter(fn ($b) => $b->bebidas_restantes > 0);

        // ── Datos para los selects de filtro ─────────────────────
        $eventos  = Evento::orderBy('titulo')->get(['id', 'titulo']);
        $empresas = Empresa::orderBy('nombre_empresa')->get(['id', 'nombre_empresa']);

        if ($request->ajax()) {
            return response()->json([
                'resumen'           => $resumen,
                'total_vendidos'    => $totalVendidos,
                'total_ingresos'    => number_format($totalIngresos, 2),
                'html_bonos_activos' => view(
                    'admin.bonos._tabla_activos',
                    ['bonosActivos' => $bonosActivos]
                )->render(),
            ]);
        }

        return view('admin.bonos.index', compact(
            'resumen', 'totalVendidos', 'totalIngresos',
            'bonosActivos', 'eventos', 'empresas'
        ));
    }

    /* ══════════════════════════════════════════════════════════════
       PDF — Informe por evento con desglose por tipo de bono
    ══════════════════════════════════════════════════════════════ */

    public function descargarPdf(Request $request)
    {
        $consulta = BonoCompra::with(['tipo', 'evento'])
            ->join('eventos', 'eventos.id', '=', 'bono_compras.evento_id');

        if ($request->filled('evento_id')) {
            $consulta->where('bono_compras.evento_id', $request->evento_id);
        }

        if ($request->filled('empresa_id')) {
            $consulta->join('organizadores', 'organizadores.id', '=', 'eventos.organizador_id')
                     ->where('organizadores.empresa_id', $request->empresa_id);
        }

        $bonos = $consulta->select('bono_compras.*')->get();

        // Agrupar por evento
        $datos = $bonos->groupBy('evento_id')->map(function ($grupo) {
            $evento = $grupo->first()->evento;
            $porTipo = $grupo->groupBy('bono_tipo_id')->map(function ($tipoGrupo) {
                $tipo = $tipoGrupo->first()->tipo;
                return [
                    'cantidad_bebidas' => $tipo?->cantidad_bebidas ?? '?',
                    'precio'           => $tipo?->precio ?? 0,
                    'cantidad'         => $tipoGrupo->count(),
                    'subtotal'         => round($tipoGrupo->count() * ($tipo?->precio ?? 0), 2),
                ];
            })->values();

            return [
                'evento'         => $evento?->titulo ?? 'Evento eliminado',
                'fecha_evento'   => $evento?->fecha_inicio?->format('d/m/Y') ?? '—',
                'por_tipo'       => $porTipo,
                'total_bonos'    => $grupo->count(),
                'total_ingresos' => round($porTipo->sum('subtotal'), 2),
            ];
        })->values();

        $pdf = Pdf::loadView('admin.bonos.pdf', compact('datos'))
                  ->setPaper('A4', 'landscape');

        $nombreFichero = 'vibez-bonos-' . now()->format('d-m-Y') . '.pdf';

        return response($pdf->output(), 200, [
            'Content-Type'        => 'application/pdf',
            'Content-Disposition' => 'attachment; filename="' . $nombreFichero . '"',
        ]);
    }
}
