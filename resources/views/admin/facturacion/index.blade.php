@extends('admin.layouts.dashboard')

@section('title', 'Admin | Facturación por evento')

@section('content')

<header class="admin-header">
    <div>
        <h1>Facturación por evento</h1>
        <p>Emite liquidaciones y genera PDFs de cada evento de la plataforma.</p>
    </div>
</header>

@if(session('success'))
    <div class="alert success">{{ session('success') }}</div>
@endif
@if(session('error'))
    <div class="alert error">{{ session('error') }}</div>
@endif

<section class="card" style="overflow-x:auto;">
    <table class="tabla-eventos">
        <thead>
            <tr>
                <th>ID</th>
                <th>Evento</th>
                <th>Fecha</th>
                <th>Empresa</th>
                <th style="text-align:right;">Tickets vendidos</th>
                <th style="text-align:right;">Importe bruto</th>
                <th>Estado facturación</th>
                <th>Acción</th>
            </tr>
        </thead>
        <tbody>
        @forelse($eventos as $evento)
            @php
                $factura      = $evento->facturaEvento;
                $vendidas     = (int) $evento->entradas_vendidas;
                $importeBruto = (float) $evento->importe_bruto;
                $empresa      = $evento->organizador?->empresa;
            @endphp
            <tr>
                <td data-label="ID" style="font-family:monospace;opacity:.7;">#{{ $evento->id }}</td>

                <td data-label="Evento">
                    <strong style="display:block;">{{ $evento->titulo }}</strong>
                    @if($evento->es_gratuito)
                        <span class="estado inactivo" style="font-size:.7rem;">Gratuito</span>
                    @else
                        <span style="font-size:.75rem;opacity:.6;">{{ number_format($evento->precio_base, 2, ',', '.') }} €/entrada</span>
                    @endif
                </td>

                <td data-label="Fecha" style="font-family:monospace;white-space:nowrap;font-size:.85rem;">
                    {{ \Carbon\Carbon::parse($evento->fecha_inicio)->format('d/m/Y') }}<br>
                    <span style="opacity:.5;font-size:.75rem;">{{ \Carbon\Carbon::parse($evento->fecha_inicio)->format('H:i') }}</span>
                </td>

                <td data-label="Empresa">
                    <strong>{{ $empresa?->nombre_empresa ?? '—' }}</strong>
                    @if($empresa?->nif_cif)
                        <small style="display:block;opacity:.5;font-size:.7rem;">{{ $empresa->nif_cif }}</small>
                    @endif
                </td>

                <td data-label="Tickets" style="text-align:right;font-family:monospace;font-size:1.05rem;font-weight:700;color:#c084fc;">
                    {{ number_format($vendidas) }}
                </td>

                <td data-label="Importe bruto" style="text-align:right;font-family:monospace;font-size:1.05rem;font-weight:700;color:#10b981;">
                    @if($importeBruto > 0)
                        {{ number_format($importeBruto, 2, ',', '.') }} €
                    @else
                        <span style="opacity:.3;">—</span>
                    @endif
                </td>

                <td data-label="Estado">
                    @if(!$factura)
                        <span class="estado inactivo">Sin factura</span>
                    @elseif($factura->estado === 'emitida')
                        <span class="estado activo">Facturado</span>
                        <small style="display:block;font-family:monospace;font-size:.7rem;opacity:.55;margin-top:2px;">
                            {{ $factura->numero_factura }}<br>
                            {{ $factura->fecha_emision->format('d/m/Y H:i') }}
                        </small>
                    @elseif($factura->estado === 'anulada')
                        <span class="estado" style="background:rgba(245,158,11,.15);color:#f59e0b;border:1px solid rgba(245,158,11,.3);">Anulada</span>
                    @elseif($factura->estado === 'error')
                        <span class="estado inactivo">Error</span>
                    @endif
                </td>

                <td data-label="Acción" style="white-space:nowrap;">
                    @if(!$factura || in_array($factura->estado, ['anulada', 'error']))
                        <a href="{{ route('admin.facturacion.empezar', $evento) }}"
                           class="btn-action btn-primary" style="font-size:.8rem;padding:6px 14px;">
                            Empezar facturación
                        </a>
                    @elseif($factura->estado === 'emitida')
                        <a href="{{ route('admin.facturacion.descargar', $factura) }}"
                           class="btn-action" style="font-size:.8rem;padding:6px 14px;background:rgba(16,185,129,.15);color:#10b981;border:1px solid rgba(16,185,129,.3);"
                           target="_blank">
                            ↓ Descargar PDF
                        </a>
                        <form method="POST"
                              action="{{ route('admin.facturacion.anular', $factura) }}"
                              style="display:inline-block;margin-left:6px;"
                              onsubmit="return confirm('¿Anular la factura {{ $factura->numero_factura }}?\nEl evento quedará disponible para re-facturar.')">
                            @csrf @method('PATCH')
                            <button type="submit"
                                    class="btn-action"
                                    style="font-size:.8rem;padding:6px 14px;background:rgba(239,68,68,.1);color:#f87171;border:1px solid rgba(239,68,68,.25);cursor:pointer;">
                                Anular
                            </button>
                        </form>
                    @endif
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="8" class="empty">No hay eventos registrados en la plataforma.</td>
            </tr>
        @endforelse
        </tbody>
    </table>
</section>

@if($eventos->hasPages())
<nav class="paginacion" aria-label="Paginación">
    <div class="pagination-summary">
        Mostrando <strong>{{ $eventos->firstItem() }}</strong>
        a <strong>{{ $eventos->lastItem() }}</strong>
        de <strong>{{ $eventos->total() }}</strong> eventos
    </div>
    <div class="pagination-controls">
        @if($eventos->onFirstPage())
            <span class="pagination-arrow disabled">‹</span>
        @else
            <a class="pagination-arrow" href="{{ $eventos->previousPageUrl() }}">‹</a>
        @endif
        @for($p = 1; $p <= $eventos->lastPage(); $p++)
            @if($p === $eventos->currentPage())
                <span class="pagination-page active">{{ $p }}</span>
            @else
                <a class="pagination-page" href="{{ $eventos->url($p) }}">{{ $p }}</a>
            @endif
        @endfor
        @if($eventos->hasMorePages())
            <a class="pagination-arrow" href="{{ $eventos->nextPageUrl() }}">›</a>
        @else
            <span class="pagination-arrow disabled">›</span>
        @endif
    </div>
</nav>
@endif

@endsection
