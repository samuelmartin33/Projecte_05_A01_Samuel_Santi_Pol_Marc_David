<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Evento;
use App\Models\FacturaEvento;
use App\Services\FacturacionEventoService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class FacturacionEventoController extends Controller
{
    public function __construct(private FacturacionEventoService $servicio) {}

    /**
     * Listado de todos los eventos con su estado de facturación.
     */
    public function index()
    {
        $eventos = Evento::with(['organizador.empresa', 'facturaEvento'])
            ->withCount([
                'entradas as entradas_vendidas' => fn($q) => $q->where('estado_entrada', '!=', 0),
            ])
            ->withSum(
                ['entradas as importe_bruto' => fn($q) => $q->where('estado_entrada', '!=', 0)],
                'precio_pagado'
            )
            ->orderByDesc('fecha_inicio')
            ->paginate(20);

        return view('admin.facturacion.index', compact('eventos'));
    }

    /**
     * Muestra el resumen calculado del evento antes de confirmar la emisión.
     */
    public function empezar(Evento $evento)
    {
        $facturaExistente = $evento->facturaEvento;

        if ($facturaExistente && $facturaExistente->estado === 'emitida') {
            return redirect()->route('admin.facturacion.index')
                ->with('error', "El evento «{$evento->titulo}» ya tiene la factura {$facturaExistente->numero_factura} emitida. Anúlala primero para re-facturar.");
        }

        $calculo = $this->servicio->calcular($evento);

        return view('admin.facturacion.empezar', compact('evento', 'calculo', 'facturaExistente'));
    }

    /**
     * Confirma los parámetros, emite la factura y devuelve el PDF para descarga.
     */
    public function confirmar(Request $request, Evento $evento)
    {
        $request->validate([
            'porcentaje_comision' => ['required', 'numeric', 'min:0', 'max:100'],
            'tipo_iva'            => ['required', 'numeric', 'min:0', 'max:100'],
            'notas'               => ['nullable', 'string', 'max:1000'],
        ]);

        $facturaExistente = $evento->facturaEvento;

        if ($facturaExistente && $facturaExistente->estado === 'emitida') {
            return back()->with('error', 'Factura ya emitida. Anúlala primero para poder re-facturar.');
        }

        try {
            $factura = $this->servicio->emitir(
                evento:             $evento,
                porcentajeComision: (float) $request->porcentaje_comision,
                tipoIva:            (float) $request->tipo_iva,
                notas:              $request->notas,
                adminId:            Auth::id(),
                facturaAnterior:    $facturaExistente,
            );

            return redirect()
                ->route('admin.facturacion.index')
                ->with('success', "Factura {$factura->numero_factura} emitida correctamente.")
                ->with('factura_descarga', $factura->id);

        } catch (\Throwable $e) {
            Log::error('Error emitiendo factura del evento ' . $evento->id . ': ' . $e->getMessage());
            return back()->with('error', 'Error al generar la factura: ' . $e->getMessage());
        }
    }

    /**
     * Descarga el PDF de una factura emitida.
     */
    public function descargar(FacturaEvento $factura)
    {
        abort_if($factura->estado !== 'emitida', 404, 'Factura no disponible para descarga.');

        return $this->servicio->descargarPdf($factura);
    }

    /**
     * Anula una factura emitida, dejando el evento disponible para re-facturar.
     */
    public function anular(FacturaEvento $factura)
    {
        abort_if($factura->estado !== 'emitida', 422, 'Solo se pueden anular facturas emitidas.');

        $factura->update(['estado' => 'anulada']);

        return back()->with('success', "Factura {$factura->numero_factura} anulada. El evento puede re-facturarse.");
    }
}
