<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pedido;
use Illuminate\View\View;

class PedidoController extends Controller
{
    public function index(): View
    {
        $pedidos = Pedido::with('usuario')
            ->orderByDesc('id')
            ->paginate(12);

        return view('admin.pedidos.index', compact('pedidos'));
    }
}
