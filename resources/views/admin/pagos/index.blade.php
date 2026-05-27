@extends('admin.layouts.dashboard')

@section('title', 'Admin | Pagos')

@section('content')
    <header class="admin-header">
        <div>
            <h1>Pagos</h1>
            <p>Consulta y gestiona los pagos registrados en la plataforma.</p>
        </div>
    </header>

    @if (session('success'))
        <div class="alert success">{{ session('success') }}</div>
    @endif
    @if (session('error'))
        <div class="alert error">{{ session('error') }}</div>
    @endif

    <section class="card">
        <div class="table-wrap">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Fecha</th>
                        <th>Usuario</th>
                        <th>Evento</th>
                        <th>Método</th>
                        <th>Estado pago</th>
                        <th>Importe</th>
                        <th>Comisión VIBEZ</th>
                        <th>Reembolso</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($pagos as $pago)
                        @php
                            $usuarioPago = $pago->pedido?->usuario;
                            $eventoPago  = $pago->pedido?->entradas?->first()?->evento;
                        @endphp
                        <tr>
                            <td data-label="#">
                                <span style="color:#a855f7;">#{{ $pago->id }}</span>
                                <br><small style="color:#475569;">Pedido #{{ $pago->pedido_id }}</small>
                            </td>
                            <td data-label="Fecha">
                                <span style="font-size:.82rem;color:#94a3b8;">
                                    {{ optional($pago->fecha_creacion)->format('d/m/Y') ?? '—' }}
                                    <br>{{ optional($pago->fecha_creacion)->format('H:i') }}
                                </span>
                            </td>
                            <td data-label="Usuario">
                                @if($usuarioPago)
                                    {{ $usuarioPago->nombre }} {{ $usuarioPago->apellido1 }}
                                    <br><small style="color:#64748b;">{{ $usuarioPago->email }}</small>
                                @else
                                    <span style="color:#475569;">—</span>
                                @endif
                            </td>
                            <td data-label="Evento">
                                <span style="font-size:.85rem;">{{ $eventoPago?->nombre ?? '—' }}</span>
                            </td>
                            <td data-label="Método">{{ $pago->metodo_pago }}</td>
                            <td data-label="Estado pago">
                                @if ((int) $pago->estado_pago === 3)
                                    <span class="estado inactivo">Reembolsado</span>
                                @elseif ((int) $pago->estado_pago === 2)
                                    <span class="estado activo">Completado</span>
                                @else
                                    <span class="estado" style="background:#334155;color:#94a3b8;">Pendiente</span>
                                @endif
                            </td>
                            <td data-label="Importe">
                                <strong>{{ number_format($pago->importe, 2) }} {{ $pago->moneda }}</strong>
                            </td>
                            <td data-label="Comisión VIBEZ">
                                @if ((int) $pago->estado_pago === 2)
                                    <span style="color:#a855f7;font-weight:600;">
                                        + {{ number_format($pago->importe * 0.10, 2) }} €
                                    </span>
                                @elseif ((int) $pago->estado_pago === 3)
                                    <span style="color:#ef4444;font-size:.8rem;">Reembolsado</span>
                                @else
                                    <span style="color:#475569;">—</span>
                                @endif
                            </td>
                            <td data-label="Reembolso">
                                @if ($pago->stripe_refund_id)
                                    <small style="display:block;color:#a855f7;font-family:monospace;">
                                        {{ substr($pago->stripe_refund_id, 0, 22) }}…
                                    </small>
                                    <small style="color:#94a3b8;">
                                        {{ optional($pago->fecha_reembolso)->format('d/m/Y H:i') }}
                                    </small>
                                @else
                                    <span style="color:#475569;">—</span>
                                @endif
                            </td>
                            <td data-label="Acciones">
                                @php
                                    $eventoEntrada = $pago->pedido->entradas->first()?->evento;
                                    $eventoFuturo  = $eventoEntrada && $eventoEntrada->fecha_inicio->isFuture();
                                    $tieneUsadas   = $pago->pedido->entradas->where('estado_entrada', 2)->isNotEmpty();
                                @endphp

                                @if ((int) $pago->estado_pago !== 3
                                    && !empty($pago->pedido->stripe_payment_intent_id)
                                    && !$tieneUsadas
                                    && $eventoFuturo)
                                    <button
                                        type="button"
                                        class="btn btn-danger btn-sm"
                                        onclick="abrirModalReembolso(
                                            '{{ route('admin.pagos.reembolsar', $pago) }}',
                                            {{ number_format($pago->importe, 2, '.', '') }}
                                        )"
                                    >
                                        Reembolsar
                                    </button>
                                @elseif ((int) $pago->estado_pago !== 3 && $eventoEntrada && !$eventoFuturo)
                                    <span style="color:#f59e0b;font-size:.8rem;" title="El evento ya ha tenido lugar">
                                        Evento pasado
                                    </span>
                                @elseif ((int) $pago->estado_pago !== 3 && $tieneUsadas)
                                    <span style="color:#f59e0b;font-size:.8rem;" title="Hay entradas ya escaneadas en este pedido">
                                        Entradas usadas
                                    </span>
                                @elseif ((int) $pago->estado_pago === 3)
                                    <span style="color:#475569;font-size:.85rem;">Reembolsado</span>
                                @else
                                    <span style="color:#475569;font-size:.85rem;">Sin PI Stripe</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="8" class="empty">No hay pagos registrados.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>

    @if ($pagos->hasPages())
        <nav class="paginacion" aria-label="Paginacion de pagos">
            <div class="pagination-summary">
                Mostrando <strong>{{ $pagos->firstItem() }}</strong> a <strong>{{ $pagos->lastItem() }}</strong> de <strong>{{ $pagos->total() }}</strong> resultados
            </div>
            <div class="pagination-controls">
                @if ($pagos->onFirstPage())
                    <span class="pagination-arrow disabled" aria-hidden="true">‹</span>
                @else
                    <a class="pagination-arrow" href="{{ $pagos->previousPageUrl() }}" rel="prev">‹</a>
                @endif
                @if ($pagos->hasMorePages())
                    <a class="pagination-arrow" href="{{ $pagos->nextPageUrl() }}" rel="next">›</a>
                @else
                    <span class="pagination-arrow disabled" aria-hidden="true">›</span>
                @endif
            </div>
        </nav>
    @endif

    {{-- Modal de confirmación de reembolso --}}
    <div
        id="modal-reembolso"
        style="display:none; position:fixed; inset:0; background:rgba(0,0,0,.65); z-index:9999; align-items:center; justify-content:center;"
        onclick="if(event.target===this) cerrarModalReembolso()"
    >
        <div style="background:#1e293b; border:1px solid #334155; border-radius:14px; padding:2rem; max-width:500px; width:90%;">
            <h3 style="margin:0 0 .75rem; font-size:1.1rem;">Confirmar reembolso</h3>
            <p style="color:#94a3b8; margin:0 0 1rem; font-size:.9rem;">
                Vas a reembolsar <strong id="modal-importe" style="color:#f1f5f9;"></strong> € al usuario. Esta acción:
            </p>
            <ul style="color:#94a3b8; font-size:.85rem; padding-left:1.25rem; margin:0 0 1.25rem; line-height:1.8;">
                <li>Devuelve el 100% al cliente</li>
                <li>Revierte el 90% transferido a la empresa (Stripe Connect)</li>
                <li>Devuelve el 10% de comisión de VIBEZ</li>
                <li>Cancela todas las entradas válidas del pedido</li>
            </ul>
            <form id="form-reembolso" method="POST">
                @csrf
                <label style="display:block; margin-bottom:1.25rem;">
                    <span style="font-size:.85rem; color:#94a3b8; display:block; margin-bottom:.4rem;">
                        Motivo del reembolso <span style="color:#ef4444;">*</span>
                    </span>
                    <textarea
                        name="motivo_reembolso"
                        rows="3"
                        required
                        minlength="5"
                        maxlength="500"
                        placeholder="Ej: Evento cancelado por el organizador..."
                        style="width:100%; padding:.6rem .75rem; border-radius:8px; background:#0f172a; border:1px solid #334155; color:#f1f5f9; font-size:.9rem; resize:vertical; box-sizing:border-box;"
                    ></textarea>
                </label>
                <div style="display:flex; gap:.75rem; justify-content:flex-end;">
                    <button
                        type="button"
                        onclick="cerrarModalReembolso()"
                        class="btn btn-secondary"
                        style="padding:.5rem 1.25rem;"
                    >
                        Cancelar
                    </button>
                    <button
                        type="submit"
                        class="btn btn-danger"
                        style="padding:.5rem 1.25rem;"
                    >
                        Confirmar reembolso
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
<script src="{{ asset('js/admin-pagos.js') }}"></script>
@endpush
