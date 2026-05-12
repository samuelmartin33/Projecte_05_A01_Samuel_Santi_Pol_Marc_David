@extends('admin.layouts.dashboard')

@section('title', 'Admin | Emitir factura')

@section('content')

<header class="admin-header">
    <div>
        <h1>Emitir factura</h1>
        <p>{{ $evento->titulo }} · Revisa el cálculo y confirma para generar el PDF</p>
    </div>
    <a href="{{ route('admin.facturacion.index') }}" class="btn-action" style="font-size:.85rem;">
        ← Volver al listado
    </a>
</header>

@if(session('error'))
    <div class="alert error">{{ session('error') }}</div>
@endif

@if($facturaExistente)
    <div class="alert" style="background:rgba(245,158,11,.12);border:1px solid rgba(245,158,11,.35);color:#f59e0b;padding:12px 18px;margin-bottom:1.5rem;border-radius:6px;">
        ⚠ Existe una factura anterior <strong>{{ $facturaExistente->numero_factura }}</strong>
        con estado <strong>{{ $facturaExistente->estado }}</strong>.
        Al confirmar se eliminará y se creará una nueva.
    </div>
@endif

<form method="POST" action="{{ route('admin.facturacion.confirmar', $evento) }}">
@csrf

<div style="display:grid;grid-template-columns:1fr 1fr;gap:1.5rem;max-width:960px;">

    {{-- ── COLUMNA IZQUIERDA: Parámetros ── --}}
    <section class="card">
        <h2 style="font-size:1rem;font-weight:700;margin-bottom:1.25rem;padding-bottom:.75rem;border-bottom:1px solid rgba(255,255,255,.08);">
            Parámetros de facturación
        </h2>

        <div style="margin-bottom:1rem;">
            <label style="display:block;font-size:.75rem;text-transform:uppercase;letter-spacing:.06em;opacity:.6;margin-bottom:.4rem;">
                Comisión VIBEZ (%)
            </label>
            <input type="number" name="porcentaje_comision" id="inputComision"
                   value="10" min="0" max="100" step="0.01"
                   oninput="recalcular()"
                   style="width:100%;background:rgba(255,255,255,.06);border:1px solid rgba(255,255,255,.12);color:inherit;padding:8px 12px;border-radius:6px;font-size:.95rem;">
            <small style="opacity:.45;font-size:.75rem;">Porcentaje que VIBEZ retiene sobre las ventas brutas.</small>
        </div>

        <div style="margin-bottom:1rem;">
            <label style="display:block;font-size:.75rem;text-transform:uppercase;letter-spacing:.06em;opacity:.6;margin-bottom:.4rem;">
                IVA sobre comisión (%)
            </label>
            <input type="number" name="tipo_iva" id="inputIva"
                   value="21" min="0" max="100" step="0.01"
                   oninput="recalcular()"
                   style="width:100%;background:rgba(255,255,255,.06);border:1px solid rgba(255,255,255,.12);color:inherit;padding:8px 12px;border-radius:6px;font-size:.95rem;">
            <small style="opacity:.45;font-size:.75rem;">IVA aplicado por VIBEZ sobre su comisión de servicio.</small>
        </div>

        <div style="margin-bottom:1.5rem;">
            <label style="display:block;font-size:.75rem;text-transform:uppercase;letter-spacing:.06em;opacity:.6;margin-bottom:.4rem;">
                Notas internas (opcional)
            </label>
            <textarea name="notas" rows="3"
                      placeholder="Observaciones para esta factura…"
                      style="width:100%;background:rgba(255,255,255,.06);border:1px solid rgba(255,255,255,.12);color:inherit;padding:8px 12px;border-radius:6px;font-size:.9rem;resize:vertical;"></textarea>
        </div>

        {{-- Datos del evento --}}
        <div style="padding-top:1rem;border-top:1px solid rgba(255,255,255,.08);font-size:.85rem;opacity:.65;">
            <div style="margin-bottom:.4rem;"><strong>Evento:</strong> {{ $evento->titulo }}</div>
            <div style="margin-bottom:.4rem;"><strong>Fecha:</strong> {{ \Carbon\Carbon::parse($evento->fecha_inicio)->format('d/m/Y H:i') }}</div>
            <div><strong>Empresa:</strong> {{ $calculo['empresa']?->nombre_empresa ?? 'Sin empresa' }}</div>
            @if($calculo['empresa']?->nif_cif)
                <div><strong>NIF/CIF:</strong> {{ $calculo['empresa']->nif_cif }}</div>
            @endif
        </div>
    </section>

    {{-- ── COLUMNA DERECHA: Previsualización ── --}}
    <section class="card">
        <h2 style="font-size:1rem;font-weight:700;margin-bottom:1.25rem;padding-bottom:.75rem;border-bottom:1px solid rgba(255,255,255,.08);">
            Previsualización del cálculo
        </h2>

        <table style="width:100%;border-collapse:collapse;font-size:.9rem;">
            <tr style="border-bottom:1px dashed rgba(255,255,255,.08);">
                <td style="padding:10px 0;opacity:.6;">Entradas vendidas</td>
                <td style="padding:10px 0;text-align:right;font-family:monospace;font-weight:700;color:#c084fc;">
                    {{ number_format($calculo['total_vendidas']) }}
                </td>
            </tr>
            <tr style="border-bottom:1px dashed rgba(255,255,255,.08);">
                <td style="padding:10px 0;opacity:.6;">Importe bruto ventas</td>
                <td style="padding:10px 0;text-align:right;font-family:monospace;font-weight:700;color:#10b981;">
                    <span id="prev-bruto">{{ number_format($calculo['importe_bruto'], 2, ',', '.') }}</span> €
                </td>
            </tr>
            <tr style="border-bottom:1px dashed rgba(255,255,255,.08);">
                <td style="padding:10px 0;opacity:.6;">Comisión VIBEZ (<span id="prev-pct-com">10</span>%)</td>
                <td style="padding:10px 0;text-align:right;font-family:monospace;color:#f87171;">
                    −<span id="prev-comision">{{ number_format($calculo['importe_comision'], 2, ',', '.') }}</span> €
                </td>
            </tr>
            <tr style="border-bottom:1px dashed rgba(255,255,255,.08);">
                <td style="padding:10px 0;opacity:.6;">IVA comisión (<span id="prev-pct-iva">21</span>%)</td>
                <td style="padding:10px 0;text-align:right;font-family:monospace;color:#f87171;">
                    −<span id="prev-iva">{{ number_format($calculo['cuota_iva'], 2, ',', '.') }}</span> €
                </td>
            </tr>
            <tr>
                <td style="padding:16px 0 0;font-weight:800;font-size:1rem;">Neto a liquidar empresa</td>
                <td style="padding:16px 0 0;text-align:right;font-family:monospace;font-weight:900;font-size:1.5rem;color:#10b981;">
                    <span id="prev-neto">{{ number_format($calculo['importe_neto'], 2, ',', '.') }}</span> €
                </td>
            </tr>
        </table>

        <div style="margin-top:1.5rem;">
            <button type="submit" class="btn-action btn-primary" style="width:100%;padding:12px;font-size:.95rem;text-align:center;">
                ✓ Confirmar y emitir factura
            </button>
            <a href="{{ route('admin.facturacion.index') }}"
               style="display:block;text-align:center;margin-top:.75rem;font-size:.8rem;opacity:.45;">
                Cancelar y volver
            </a>
        </div>
    </section>

</div>
</form>

@endsection

@push('scripts')
<script>
var bruto = {{ $calculo['importe_bruto'] }};

function recalcular() {
    var pct = parseFloat(document.getElementById('inputComision').value) || 0;
    var iva = parseFloat(document.getElementById('inputIva').value) || 0;
    var com = Math.round(bruto * pct / 100 * 100) / 100;
    var cuota = Math.round(com * iva / 100 * 100) / 100;
    var neto  = Math.round((bruto - com - cuota) * 100) / 100;

    document.getElementById('prev-pct-com').textContent  = pct;
    document.getElementById('prev-pct-iva').textContent  = iva;
    document.getElementById('prev-comision').textContent = fmt(com);
    document.getElementById('prev-iva').textContent      = fmt(cuota);
    document.getElementById('prev-neto').textContent     = fmt(neto);
}

function fmt(n) {
    return n.toLocaleString('es-ES', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
}
</script>
@endpush
