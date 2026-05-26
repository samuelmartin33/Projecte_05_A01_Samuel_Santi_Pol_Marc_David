<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CategoriaTrabajo;
use App\Models\CategoriaEvento;
use App\Models\Evento;
use App\Models\Pago;
use App\Models\PagoPremium;
use App\Models\Pedido;
use App\Models\Usuario;
use Illuminate\View\View;

/**
 * Controlador del panel de administración.
 */
class DashboardController extends Controller
{
    /** Porcentaje de comisión que retiene VIBEZ de cada venta de entradas. */
    const COMISION_VIBEZ = 0.10;

    /** Precio fijo de la suscripción Premium. */
    const PRECIO_PREMIUM = 5.00;

    public function index(): View
    {
        // Suma de todos los pagos completados y no reembolsados
        $totalVentasEntradas = Pago::where('estado_pago', 2)->sum('importe');

        // Comisión del 10% que retiene VIBEZ de cada venta de entradas
        $ingresoComisiones = round($totalVentasEntradas * self::COMISION_VIBEZ, 2);

        // Ingresos premium desde la tabla real de pagos (dato exacto, no estimado)
        $usuariosPremium = Usuario::where('es_premium', true)->count();
        $ingresoPremium  = round(PagoPremium::where('estado', 1)->sum('importe'), 2);

        // Últimos 6 pagos completados para el resumen del dashboard
        $ultimosPagos = Pago::with('pedido.usuario', 'pedido.entradas.evento')
            ->where('estado_pago', 2)
            ->orderByDesc('fecha_creacion')
            ->limit(6)
            ->get();

        return view('admin.dashboard', [
            'eventosActivos'      => Evento::where('estado', 1)->count(),
            'totalUsuarios'       => Usuario::count(),
            'usuariosActivos'     => Usuario::where('estado', 1)->count(),
            'totalPedidos'        => Pedido::count(),
            'totalCategorias'     => CategoriaTrabajo::count() + CategoriaEvento::count(),
            'totalPagos'          => Pago::count(),
            'empresasPendientes'  => Usuario::where('tipo_cuenta', 'empresa')
                ->where('estado_registro', 'pendiente')
                ->count(),
            // Ingresos de VIBEZ
            'ingresoComisiones'   => $ingresoComisiones,
            'ingresoPremium'      => $ingresoPremium,
            'ingresoTotal'        => round($ingresoComisiones + $ingresoPremium, 2),
            'usuariosPremium'     => $usuariosPremium,
            'totalVentasEntradas' => $totalVentasEntradas,
            'ultimosPagos'        => $ultimosPagos,
        ]);
    }
}
