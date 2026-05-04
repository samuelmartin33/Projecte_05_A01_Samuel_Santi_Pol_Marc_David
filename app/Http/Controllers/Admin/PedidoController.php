<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pedido;
use App\Models\Usuario;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class PedidoController extends Controller
{
    public function index(): View
    {
        $pedidos = Pedido::with('usuario')->orderByDesc('id')->paginate(12);

        return view('admin.pedidos.index', compact('pedidos'));
    }

    public function create(): View
    {
        return view('admin.pedidos.create', [
            'pedido' => new Pedido(),
            'usuarios' => Usuario::orderBy('nombre')->get(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validatedData($request);
        $data['fecha_creacion'] = now();
        $data['fecha_actualizacion'] = null;

        Pedido::create($data);

        return redirect()->route('admin.pedidos.index')->with('success', 'Pedido creado correctamente.');
    }

    public function edit(Pedido $pedido): View
    {
        return view('admin.pedidos.edit', [
            'pedido' => $pedido,
            'usuarios' => Usuario::orderBy('nombre')->get(),
        ]);
    }

    public function update(Request $request, Pedido $pedido): RedirectResponse
    {
        $data = $this->validatedData($request, $pedido);
        $data['fecha_actualizacion'] = now();

        $pedido->update($data);

        return redirect()->route('admin.pedidos.index')->with('success', 'Pedido actualizado correctamente.');
    }

    public function destroy(Pedido $pedido): RedirectResponse
    {
        $pedido->delete();

        return redirect()->route('admin.pedidos.index')->with('success', 'Pedido eliminado correctamente.');
    }

    private function validatedData(Request $request, ?Pedido $pedido = null): array
    {
        $data = $request->validate([
            'usuario_id' => ['required', 'integer', 'exists:usuarios,id'],
            'total' => ['required', 'numeric', 'min:0'],
            'total_descuento' => ['required', 'numeric', 'min:0'],
            'total_final' => ['required', 'numeric', 'min:0'],
            'estado' => ['required', 'integer', 'in:0,1'],
        ]);

        $data['estado'] = (int) $request->input('estado', 1);

        return $data;
    }
}