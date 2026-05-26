@extends('admin.layouts.dashboard')

@section('title', 'Admin | Pagos Premium')

@section('content')
    <header class="admin-header">
        <div>
            <h1>Suscripciones Premium</h1>
            <p>Historial de compras de Premium — facturación recibida por VIBEZ.</p>
        </div>
    </header>

    @if (session('success'))
        <div class="alert success">{{ session('success') }}</div>
    @endif

    {{-- KPIs de cabecera --}}
    <div class="adm-kpi-grid" style="grid-template-columns: repeat(3,1fr); margin-bottom:1.5rem;">

        <div class="adm-kpi" style="background:linear-gradient(135deg,#1e1b4b 0%,#2d1b69 100%);border-color:#4c1d95;">
            <div class="adm-kpi-head">
                <div class="adm-kpi-label" style="color:#c4b5fd;">Total recaudado (Premium)</div>
                <div class="adm-kpi-icon" style="color:#a855f7;">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/>
                    </svg>
                </div>
            </div>
            <div class="adm-kpi-value" style="color:#f5f3ff;">
                {{ number_format($totalRecaudado, 2) }} €
            </div>
            <div class="adm-kpi-foot">
                <span class="adm-kpi-lbl" style="color:#a78bfa;">ingresos directos a VIBEZ</span>
            </div>
        </div>

        <div class="adm-kpi">
            <div class="adm-kpi-head">
                <div class="adm-kpi-label">Suscripciones activas</div>
                <div class="adm-kpi-icon">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/>
                        <circle cx="9" cy="7" r="4"/>
                        <polyline points="16 11 18 13 22 9"/>
                    </svg>
                </div>
            </div>
            <div class="adm-kpi-value">{{ $totalSuscripciones }}</div>
            <div class="adm-kpi-foot">
                <span class="adm-kpi-lbl">compras completadas</span>
            </div>
        </div>

        <div class="adm-kpi">
            <div class="adm-kpi-head">
                <div class="adm-kpi-label">Precio por suscripción</div>
                <div class="adm-kpi-icon">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <line x1="12" y1="1" x2="12" y2="23"/>
                        <path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/>
                    </svg>
                </div>
            </div>
            <div class="adm-kpi-value">5,00 €</div>
            <div class="adm-kpi-foot">
                <span class="adm-kpi-lbl">pago único · Stripe Checkout</span>
            </div>
        </div>

    </div>

    {{-- Tabla de pagos --}}
    <section class="card">
        <div class="table-wrap">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Fecha</th>
                        <th>Usuario</th>
                        <th>Email</th>
                        <th>Importe</th>
                        <th>Estado</th>
                        <th>Session Stripe</th>
                        <th>Payment Intent</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($pagos as $pago)
                        <tr>
                            <td data-label="#">
                                <span style="color:#a855f7;">#{{ $pago->id }}</span>
                            </td>
                            <td data-label="Fecha">
                                <span style="font-size:.82rem;color:#94a3b8;">
                                    {{ optional($pago->fecha_pago ?? $pago->fecha_creacion)->format('d/m/Y') }}
                                    <br>{{ optional($pago->fecha_pago ?? $pago->fecha_creacion)->format('H:i') }}
                                </span>
                            </td>
                            <td data-label="Usuario">
                                @if($pago->usuario)
                                    <strong>{{ $pago->usuario->nombre }} {{ $pago->usuario->apellido1 }}</strong>
                                    @if($pago->usuario->apellido2)
                                        {{ $pago->usuario->apellido2 }}
                                    @endif
                                @else
                                    <span style="color:#475569;">Usuario eliminado</span>
                                @endif
                            </td>
                            <td data-label="Email">
                                <span style="font-size:.85rem;color:#94a3b8;">
                                    {{ $pago->usuario?->email ?? '—' }}
                                </span>
                            </td>
                            <td data-label="Importe">
                                <strong style="color:#a855f7;">
                                    {{ number_format($pago->importe, 2) }} {{ $pago->moneda }}
                                </strong>
                            </td>
                            <td data-label="Estado">
                                @if ((int) $pago->estado === 1)
                                    <span class="estado activo">Completado</span>
                                @else
                                    <span class="estado inactivo">Reembolsado</span>
                                @endif
                            </td>
                            <td data-label="Session Stripe">
                                @if($pago->stripe_session_id)
                                    <span style="font-family:monospace;font-size:.78rem;color:#7c3aed;"
                                          title="{{ $pago->stripe_session_id }}">
                                        {{ substr($pago->stripe_session_id, 0, 24) }}…
                                    </span>
                                @else
                                    <span style="color:#475569;">—</span>
                                @endif
                            </td>
                            <td data-label="Payment Intent">
                                @if($pago->stripe_payment_intent_id)
                                    <span style="font-family:monospace;font-size:.78rem;color:#64748b;"
                                          title="{{ $pago->stripe_payment_intent_id }}">
                                        {{ substr($pago->stripe_payment_intent_id, 0, 24) }}…
                                    </span>
                                @else
                                    <span style="color:#475569;">—</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="empty">
                                No hay suscripciones Premium registradas todavía.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>

    @if ($pagos->hasPages())
        <nav class="paginacion" aria-label="Paginación de pagos premium">
            <div class="pagination-summary">
                Mostrando <strong>{{ $pagos->firstItem() }}</strong> a <strong>{{ $pagos->lastItem() }}</strong>
                de <strong>{{ $pagos->total() }}</strong> resultados
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

@endsection
