<?php

namespace App\Http\Controllers\Empresa;

use App\Http\Controllers\Controller;
use App\Models\Entrada;
use App\Models\Evento;
use App\Models\FacturaEvento;
use App\Services\FacturacionEventoService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class FacturacionController extends Controller
{
    private function empresa()
    {
        $user = Auth::user();
        if (!$user || !$user->isEmpresa()) abort(403);
        $empresa = $user->empresa;
        if (!$empresa) abort(403);
        return $empresa;
    }

    public function index()
    {
        $empresa = $this->empresa();

        $eventos = $empresa->eventos()
            ->withCount(['entradas as entradas_vendidas' => fn($q) => $q->where('estado_entrada', '!=', 0)])
            ->withSum(['entradas as ingresos_brutos' => fn($q) => $q->where('estado_entrada', '!=', 0)], 'precio_pagado')
            ->orderByDesc('fecha_inicio')
            ->get();

        $totalIngresos = (float) ($eventos->sum('ingresos_brutos') ?? 0);
        $totalEntradas = (int)   $eventos->sum('entradas_vendidas');
        $avgTicket     = $totalEntradas > 0 ? $totalIngresos / $totalEntradas : 0;

        return view('empresa.facturacion.index', compact(
            'empresa', 'eventos', 'totalIngresos', 'totalEntradas', 'avgTicket'
        ));
    }

    public function generarPdf(Evento $evento)
    {
        $empresa = $this->empresa();

        // Verificar que el evento pertenece a esta empresa
        $eventoIds = $empresa->eventos()->pluck('eventos.id');
        abort_if(!$eventoIds->contains($evento->id), 403);

        $entradas = Entrada::where('evento_id', $evento->id)
            ->where('estado_entrada', '!=', 0)
            ->with('pedido.usuario')
            ->orderBy('fecha_creacion')
            ->get();

        $totalEntradas   = $entradas->count();
        $importeBruto    = round((float) $entradas->sum('precio_pagado'), 2);
        $entradasGratis  = $entradas->where('precio_pagado', 0)->count();
        $entradasPagadas = $totalEntradas - $entradasGratis;

        $pdf = Pdf::loadView('empresa.facturacion.pdf', compact(
            'empresa', 'evento', 'entradas',
            'totalEntradas', 'importeBruto', 'entradasGratis', 'entradasPagadas'
        ))->setPaper('A4', 'portrait')
          ->setOptions(['defaultFont' => 'sans-serif', 'isHtml5ParserEnabled' => true]);

        $filename = 'ventas_' . Str::slug($evento->titulo) . '_' . now()->format('Ymd') . '.pdf';

        return $pdf->download($filename);
    }

    public function descargar(FacturaEvento $factura)
    {
        $empresa = $this->empresa();

        abort_if($factura->empresa_id !== $empresa->id, 403);
        abort_if($factura->estado !== 'emitida', 404);

        if ($factura->pdf_path && Storage::disk('local')->exists($factura->pdf_path)) {
            return response()->download(
                Storage::disk('local')->path($factura->pdf_path),
                $factura->numero_factura . '.pdf',
                ['Content-Type' => 'application/pdf']
            );
        }

        // Regenerar al vuelo si el PDF no está en disco
        $factura->loadMissing('lineas');
        return app(FacturacionEventoService::class)->descargarPdf($factura);
    }
}
