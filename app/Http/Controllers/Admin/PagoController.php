<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pago;
use App\Models\Pedido;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PagoController extends Controller
{
    public function index(): View
    {
        $pagos = Pago::with('pedido.usuario')->orderByDesc('id')->paginate(12);

        return view('admin.pagos.index', compact('pagos'));
    }

    public function create(): View
    {
        return view('admin.pagos.create', [
            'pago' => new Pago(),
            'pedidos' => Pedido::with('usuario')->orderByDesc('id')->get(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validatedData($request);
        $data['fecha_creacion'] = now();
        $data['fecha_actualizacion'] = null;

        Pago::create($data);

        return redirect()->route('admin.pagos.index')->with('success', 'Pago creado correctamente.');
    }

    public function edit(Pago $pago): View
    {
        return view('admin.pagos.edit', [
            'pago' => $pago,
            'pedidos' => Pedido::with('usuario')->orderByDesc('id')->get(),
        ]);
    }

    public function update(Request $request, Pago $pago): RedirectResponse
    {
        $data = $this->validatedData($request, $pago);
        $data['fecha_actualizacion'] = now();

        $pago->update($data);

        return redirect()->route('admin.pagos.index')->with('success', 'Pago actualizado correctamente.');
    }

    public function destroy(Pago $pago): RedirectResponse
    {
        $pago->delete();

        return redirect()->route('admin.pagos.index')->with('success', 'Pago eliminado correctamente.');
    }

    private function validatedData(Request $request, ?Pago $pago = null): array
    {
        $data = $request->validate([
            'pedido_id' => ['required', 'integer', 'exists:pedidos,id'],
            'metodo_pago' => ['required', 'integer', 'in:1,2,3,4'],
            'estado_pago' => ['required', 'integer', 'in:1,2,3'],
            'importe' => ['required', 'numeric', 'min:0'],
            'moneda' => ['required', 'string', 'size:3'],
            'fecha_pago' => ['nullable', 'date'],
            'fecha_reembolso' => ['nullable', 'date'],
            'importe_reembolso' => ['nullable', 'numeric', 'min:0'],
            'motivo_reembolso' => ['nullable', 'string', 'max:500'],
            'estado' => ['required', 'integer', 'in:0,1'],
        ]);

        $data['estado'] = (int) $request->input('estado', 1);

        return $data;
    }
}