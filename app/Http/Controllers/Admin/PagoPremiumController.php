<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PagoPremium;
use Illuminate\View\View;

/**
 * Controlador para la gestión administrativa de pagos Premium.
 *
 * Solo lectura: los pagos premium los genera Stripe automáticamente.
 * El admin puede consultar el historial y exportar datos para facturación.
 */
class PagoPremiumController extends Controller
{
    /**
     * Lista todos los pagos premium ordenados por más recientes primero.
     * Incluye datos del usuario para poder identificar al comprador.
     */
    public function index(): View
    {
        $pagos = PagoPremium::with('usuario')
            ->orderByDesc('fecha_creacion')
            ->paginate(15);

        // KPIs para el encabezado de la sección
        $totalRecaudado  = PagoPremium::where('estado', 1)->sum('importe');
        $totalSuscripciones = PagoPremium::where('estado', 1)->count();

        return view('admin.pagos-premium.index', compact(
            'pagos',
            'totalRecaudado',
            'totalSuscripciones'
        ));
    }
}
