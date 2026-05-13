<?php

namespace App\Services;

use App\Models\Entrada;
use App\Models\Evento;
use App\Models\FacturaEvento;
use App\Models\LineaFacturaEvento;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class FacturacionEventoService
{
    private const COMISION_DEFAULT = 10.0;
    private const IVA_DEFAULT      = 21.0;

    /**
     * Calcula todos los importes del evento sin guardar nada en BD.
     * Usado tanto para previsualización como para la emisión final.
     */
    public function calcular(
        Evento $evento,
        float $porcentajeComision = self::COMISION_DEFAULT,
        float $tipoIva = self::IVA_DEFAULT
    ): array {
        $evento->loadMissing('organizador.empresa');
        $empresa = $evento->organizador?->empresa;

        // precio_pagado es lo realmente cobrado, más fiable que precio_base × cantidad
        $stats = Entrada::where('evento_id', $evento->id)
            ->where('estado_entrada', '!=', 0)
            ->selectRaw('COUNT(*) as total_vendidas, COALESCE(SUM(precio_pagado), 0) as importe_bruto')
            ->first();

        $totalVendidas = (int)   $stats->total_vendidas;
        $importeBruto  = round((float) $stats->importe_bruto, 2);

        $importeComision = round($importeBruto * $porcentajeComision / 100, 2);
        $cuotaIva        = round($importeComision * $tipoIva / 100, 2);
        $totalCargos     = round($importeComision + $cuotaIva, 2);
        $importeNeto     = round($importeBruto - $totalCargos, 2);

        return [
            'empresa'             => $empresa,
            'evento'              => $evento,
            'total_vendidas'      => $totalVendidas,
            'importe_bruto'       => $importeBruto,
            'porcentaje_comision' => $porcentajeComision,
            'importe_comision'    => $importeComision,
            'tipo_iva'            => $tipoIva,
            'cuota_iva'           => $cuotaIva,
            'total_cargos'        => $totalCargos,
            'importe_neto'        => $importeNeto,
            'lineas'              => $this->construirLineas(
                $evento, $totalVendidas, $importeBruto,
                $porcentajeComision, $importeComision,
                $tipoIva, $cuotaIva
            ),
        ];
    }

    /**
     * Emite la factura de forma atómica: crea cabecera + líneas en BD y genera el PDF.
     * Si cualquier paso falla, hace rollback completo.
     */
    public function emitir(
        Evento         $evento,
        float          $porcentajeComision,
        float          $tipoIva,
        ?string        $notas,
        ?int           $adminId,
        ?FacturaEvento $facturaAnterior = null,
    ): FacturaEvento {
        $calculo = $this->calcular($evento, $porcentajeComision, $tipoIva);
        $empresa = $calculo['empresa'];

        return DB::transaction(function () use (
            $evento, $empresa, $calculo, $notas, $adminId,
            $tipoIva, $porcentajeComision, $facturaAnterior
        ) {
            // Si existe una factura anulada previa, la eliminamos (junto a sus líneas por cascade)
            if ($facturaAnterior) {
                $facturaAnterior->delete();
            }

            $factura = FacturaEvento::create([
                'numero_factura'          => $this->generarNumero(),
                'evento_id'               => $evento->id,
                'empresa_id'              => $empresa?->id,
                'generada_por_usuario_id' => $adminId,
                'estado'                  => 'emitida',

                // Datos fiscales congelados
                'nombre_empresa_frozen'   => $empresa?->nombre_empresa ?? 'Sin empresa',
                'razon_social_frozen'     => $empresa?->razon_social,
                'nif_cif_frozen'          => $empresa?->nif_cif,
                'direccion_frozen'        => $empresa?->direccion,

                // Datos del evento congelados
                'nombre_evento_frozen'    => $evento->titulo,
                'fecha_evento_frozen'     => $evento->fecha_inicio,

                // Importes calculados y congelados
                'total_entradas_vendidas' => $calculo['total_vendidas'],
                'importe_bruto'           => $calculo['importe_bruto'],
                'porcentaje_comision'     => $porcentajeComision,
                'importe_comision'        => $calculo['importe_comision'],
                'tipo_iva'                => $tipoIva,
                'cuota_iva'               => $calculo['cuota_iva'],
                'total_cargos_plataforma' => $calculo['total_cargos'],
                'importe_neto_empresa'    => $calculo['importe_neto'],

                'notas'         => $notas,
                'fecha_emision' => now(),
            ]);

            foreach ($calculo['lineas'] as $linea) {
                LineaFacturaEvento::create(
                    array_merge(['factura_evento_id' => $factura->id], $linea)
                );
            }

            // Generar y almacenar el PDF en storage/app/facturas/
            $pdfPath = $this->generarYAlmacenarPdf($factura->load('lineas'));
            $factura->update(['pdf_path' => $pdfPath]);

            return $factura;
        });
    }

    /**
     * Devuelve una respuesta HTTP de descarga del PDF.
     * Si el archivo ya existe en disco lo sirve directamente; si no, lo regenera.
     */
    public function descargarPdf(FacturaEvento $factura): \Symfony\Component\HttpFoundation\Response
    {
        $factura->loadMissing('lineas');

        if ($factura->pdf_path && Storage::disk('local')->exists($factura->pdf_path)) {
            return response()->download(
                Storage::disk('local')->path($factura->pdf_path),
                $factura->numero_factura . '.pdf',
                ['Content-Type' => 'application/pdf']
            );
        }

        // Regeneración al vuelo si el archivo no existe en disco
        return Pdf::loadView('admin.facturacion.pdf', compact('factura'))
                  ->setPaper('A4', 'portrait')
                  ->download($factura->numero_factura . '.pdf');
    }

    /**
     * Genera el PDF con DomPDF y lo almacena en storage/app/facturas/.
     */
    private function generarYAlmacenarPdf(FacturaEvento $factura): string
    {
        $pdf = Pdf::loadView('admin.facturacion.pdf', compact('factura'))
                  ->setPaper('A4', 'portrait')
                  ->setOptions([
                      'defaultFont'        => 'sans-serif',
                      'isHtml5ParserEnabled' => true,
                      'isRemoteEnabled'    => false,
                  ]);

        $rutaRelativa = 'facturas/' . $factura->numero_factura . '.pdf';
        Storage::disk('local')->put($rutaRelativa, $pdf->output());

        return $rutaRelativa;
    }

    /**
     * Genera el número de factura correlativo por año: FACT-2026-00001
     * Usa lockForUpdate() para evitar duplicados en accesos concurrentes.
     */
    private function generarNumero(): string
    {
        $year   = now()->year;
        $ultimo = FacturaEvento::whereYear('fecha_emision', $year)
                               ->lockForUpdate()
                               ->count();

        return sprintf('FACT-%d-%05d', $year, $ultimo + 1);
    }

    /**
     * Construye el array de líneas a insertar en lineas_factura_evento.
     */
    private function construirLineas(
        Evento $evento,
        int    $cantidad,
        float  $importeBruto,
        float  $pctComision,
        float  $importeComision,
        float  $tipoIva,
        float  $cuotaIva
    ): array {
        $lineas = [];
        $orden  = 1;

        $lineas[] = [
            'orden'           => $orden++,
            'tipo'            => 'venta',
            'concepto'        => 'Venta de entradas — ' . $evento->titulo,
            'cantidad'        => $cantidad,
            'precio_unitario' => $cantidad > 0 ? round($importeBruto / $cantidad, 4) : 0,
            'subtotal'        => $importeBruto,
        ];

        if ($pctComision > 0) {
            $lineas[] = [
                'orden'           => $orden++,
                'tipo'            => 'comision',
                'concepto'        => 'Comisión plataforma VIBEZ (' . $pctComision . '%)',
                'cantidad'        => 1,
                'precio_unitario' => $importeComision,
                'subtotal'        => -$importeComision,
            ];

            if ($tipoIva > 0) {
                $lineas[] = [
                    'orden'           => $orden++,
                    'tipo'            => 'iva',
                    'concepto'        => 'IVA (' . $tipoIva . '%) sobre comisión VIBEZ',
                    'cantidad'        => 1,
                    'precio_unitario' => $cuotaIva,
                    'subtotal'        => -$cuotaIva,
                ];
            }
        }

        return $lineas;
    }
}
