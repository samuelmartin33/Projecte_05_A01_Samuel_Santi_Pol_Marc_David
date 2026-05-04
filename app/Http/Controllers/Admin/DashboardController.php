<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Evento;
use App\Models\Pago;
use App\Models\Pedido;
use App\Models\Usuario;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        return view('admin.dashboard', [
            'eventosActivos'     => Evento::where('estado', 1)->count(),
            'totalUsuarios'      => Usuario::count(),
            'usuariosActivos'    => Usuario::where('estado', 1)->count(),
            'totalPedidos'       => Pedido::count(),
            'totalPagos'         => Pago::count(),
            'empresasPendientes' => Usuario::where('tipo_cuenta', 'empresa')
                ->where('estado_registro', 'pendiente')
                ->count(),
        ]);
    }
}
