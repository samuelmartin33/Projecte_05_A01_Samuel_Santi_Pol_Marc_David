@extends('admin.layouts.dashboard')

@section('title', 'Dashboard — VIBEZ Admin')

@section('content')

{{-- ── Hero de bienvenida ── --}}
<div class="adm-hero">
    <div class="adm-hero-row">
        <div>
            <p class="adm-hero-kicker">
                ▸ Panel de control · {{ now()->locale('es')->isoFormat('dddd, D [de] MMMM') }}
            </p>
            <h1>Panel <em>Admin</em></h1>
            <p class="adm-hero-sub">
                {{ $eventosActivos }} evento{{ $eventosActivos !== 1 ? 's' : '' }} activo{{ $eventosActivos !== 1 ? 's' : '' }},
                {{ $usuariosActivos }} usuarios verificados
                @if($empresasPendientes > 0)
                    · <strong style="color:var(--adm-warn)">{{ $empresasPendientes }} empresa{{ $empresasPendientes !== 1 ? 's' : '' }} pendiente{{ $empresasPendientes !== 1 ? 's' : '' }}</strong>
                @endif
            </p>
        </div>
        <div class="adm-hero-actions">
            <a href="{{ route('admin.eventos.create') }}" class="adm-btn-pri">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                    <line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/>
                </svg>
                Nuevo evento
            </a>
            <a href="{{ route('admin.empresas.index') }}" class="adm-btn-ghost">
                Gestionar empresas
            </a>
        </div>
    </div>
</div>

{{-- ── KPI Grid ── --}}
<div class="adm-kpi-grid">

    <div class="adm-kpi">
        <div class="adm-kpi-head">
            <div class="adm-kpi-label">Eventos activos</div>
            <div class="adm-kpi-icon">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <rect x="3" y="4" width="18" height="18" rx="2"/>
                    <line x1="16" y1="2" x2="16" y2="6"/>
                    <line x1="8"  y1="2" x2="8"  y2="6"/>
                    <line x1="3"  y1="10" x2="21" y2="10"/>
                </svg>
            </div>
        </div>
        <div class="adm-kpi-value">{{ $eventosActivos }}</div>
        <div class="adm-kpi-foot">
            <span class="adm-kpi-lbl">eventos publicados</span>
        </div>
        <svg class="adm-spark" viewBox="0 0 80 24" preserveAspectRatio="none">
            <polyline points="0,18 14,15 28,17 42,12 56,10 70,7 80,4"
                      fill="none" stroke="#a855f7" stroke-width="1.5"/>
        </svg>
    </div>

    <div class="adm-kpi">
        <div class="adm-kpi-head">
            <div class="adm-kpi-label">Usuarios activos</div>
            <div class="adm-kpi-icon">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/>
                    <circle cx="9" cy="7" r="4"/>
                    <path d="M23 21v-2a4 4 0 0 0-3-3.87"/>
                    <path d="M16 3.13a4 4 0 0 1 0 7.75"/>
                </svg>
            </div>
        </div>
        <div class="adm-kpi-value">{{ $usuariosActivos }}</div>
        <div class="adm-kpi-foot">
            <span class="adm-kpi-lbl">de {{ $totalUsuarios }} registrados</span>
        </div>
        <svg class="adm-spark" viewBox="0 0 80 24" preserveAspectRatio="none">
            <polyline points="0,20 14,18 28,14 42,16 56,10 70,7 80,3"
                      fill="none" stroke="#a855f7" stroke-width="1.5"/>
        </svg>
    </div>

    <div class="adm-kpi">
        <div class="adm-kpi-head">
            <div class="adm-kpi-label">Empresas pendientes</div>
            <div class="adm-kpi-icon">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M19 21V5a2 2 0 0 0-2-2H7a2 2 0 0 0-2 2v16"/>
                    <path d="M3 21h18"/>
                    <path d="M9 9h1m-1 4h1m4-4h1m-1 4h1"/>
                </svg>
            </div>
        </div>
        <div class="adm-kpi-value {{ $empresasPendientes > 0 ? 'warn' : '' }}">{{ $empresasPendientes }}</div>
        <div class="adm-kpi-foot">
            <span class="adm-kpi-lbl">esperando aprobación</span>
        </div>
    </div>

    <div class="adm-kpi">
        <div class="adm-kpi-head">
            <div class="adm-kpi-label">Total pedidos</div>
            <div class="adm-kpi-icon">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M2 9V7a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v2a2 2 0 0 0 0 4v2a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2v-2a2 2 0 0 0 0-4z"/>
                    <line x1="13" y1="5" x2="13" y2="19"/>
                </svg>
            </div>
        </div>
        <div class="adm-kpi-value">{{ $totalPedidos }}</div>
        <div class="adm-kpi-foot">
            <span class="adm-kpi-lbl">pedidos registrados</span>
        </div>
        <svg class="adm-spark" viewBox="0 0 80 24" preserveAspectRatio="none">
            <polyline points="0,16 14,14 28,12 42,10 56,8 70,5 80,3"
                      fill="none" stroke="#a855f7" stroke-width="1.5"/>
        </svg>
    </div>

</div>

{{-- ── Acciones + Resumen ── --}}
<div class="adm-two-col">

    <div class="adm-card">
        <div class="adm-card-head">
            <div>
                <h3 class="adm-card-title">Acciones rápidas</h3>
                <div class="adm-card-sub">Gestión del panel</div>
            </div>
        </div>
        <div class="adm-actions-grid">
            <a href="{{ route('admin.eventos.create') }}" class="adm-action-item">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <rect x="3" y="4" width="18" height="18" rx="2"/>
                    <line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/>
                    <line x1="3" y1="10" x2="21" y2="10"/>
                    <line x1="12" y1="14" x2="12" y2="18"/><line x1="10" y1="16" x2="14" y2="16"/>
                </svg>
                Crear evento
            </a>
            <a href="{{ route('admin.usuarios.create') }}" class="adm-action-item">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/>
                    <circle cx="8.5" cy="7" r="4"/>
                    <line x1="20" y1="8" x2="20" y2="14"/><line x1="23" y1="11" x2="17" y2="11"/>
                </svg>
                Crear usuario
            </a>
            <a href="{{ route('admin.empresas.index') }}" class="adm-action-item">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M19 21V5a2 2 0 0 0-2-2H7a2 2 0 0 0-2 2v16"/>
                    <path d="M3 21h18"/>
                    <path d="M9 9h1m-1 4h1m4-4h1m-1 4h1"/>
                </svg>
                Gestionar empresas
            </a>
            <a href="{{ route('admin.categorias.index') }}" class="adm-action-item">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M20.59 13.41l-7.17 7.17a2 2 0 0 1-2.83 0L2 12V2h10l8.59 8.59a2 2 0 0 1 0 2.82z"/>
                    <line x1="7" y1="7" x2="7.01" y2="7"/>
                </svg>
                Categorías
            </a>
            <a href="{{ route('admin.trabajos.index') }}" class="adm-action-item">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <rect x="2" y="7" width="20" height="14" rx="2"/>
                    <path d="M16 7V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v2"/>
                    <line x1="12" y1="12" x2="12" y2="16"/><line x1="10" y1="14" x2="14" y2="14"/>
                </svg>
                Tipos de trabajo
            </a>
            <a href="{{ route('admin.pedidos.index') }}" class="adm-action-item">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M2 9V7a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v2a2 2 0 0 0 0 4v2a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2v-2a2 2 0 0 0 0-4z"/>
                </svg>
                Ver pedidos
            </a>
            <a href="{{ route('admin.pagos.index') }}" class="adm-action-item">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <line x1="12" y1="1" x2="12" y2="23"/>
                    <path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/>
                </svg>
                Ver pagos
            </a>
        </div>
    </div>

    <div class="adm-card">
        <div class="adm-card-head">
            <div>
                <h3 class="adm-card-title">Resumen</h3>
                <div class="adm-card-sub">Estadísticas generales</div>
            </div>
        </div>
        <div>
            <div class="adm-stat-row">
                <span class="adm-stat-label">Total usuarios</span>
                <span class="adm-stat-value">{{ $totalUsuarios }}</span>
            </div>
            <div class="adm-stat-row">
                <span class="adm-stat-label">Usuarios Premium</span>
                <span class="adm-stat-value" style="color:#a855f7;">{{ $usuariosPremium }}</span>
            </div>
            <div class="adm-stat-row">
                <span class="adm-stat-label">Pagos registrados</span>
                <span class="adm-stat-value">{{ $totalPagos }}</span>
            </div>
            <div class="adm-stat-row">
                <span class="adm-stat-label">Ingresos VIBEZ</span>
                <span class="adm-stat-value" style="color:#a855f7;">{{ number_format($ingresoTotal, 2) }} €</span>
            </div>
            <div class="adm-stat-row">
                <span class="adm-stat-label">Categorías</span>
                <span class="adm-stat-value">{{ $totalCategorias }}</span>
            </div>
            <div class="adm-stat-row">
                <span class="adm-stat-label">Empresas pendientes</span>
                <span class="adm-stat-value" style="{{ $empresasPendientes > 0 ? 'color:var(--adm-warn)' : '' }}">
                    {{ $empresasPendientes }}
                </span>
            </div>
        </div>
    </div>

</div>

{{-- ── Aviso de empresas pendientes ── --}}
@if($empresasPendientes > 0)
<div class="adm-card adm-section">
    <div class="adm-card-head">
        <div>
            <h3 class="adm-card-title">Pendientes de aprobación</h3>
            <div class="adm-card-sub">
                {{ $empresasPendientes }} empresa{{ $empresasPendientes !== 1 ? 's' : '' }}
                esperando revisión
            </div>
        </div>
        <a href="{{ route('admin.empresas.index') }}" class="adm-btn-ghost">Ver todas →</a>
    </div>
    <p style="color:var(--adm-ink-dim);font-size:13px;margin:0;font-family:'Archivo Narrow',sans-serif;text-transform:uppercase;letter-spacing:0.08em;line-height:1.6">
        Revisa y aprueba o rechaza las empresas pendientes desde el gestor de empresas.
    </p>
</div>
@endif

{{-- ── Ingresos de VIBEZ ── --}}
<div class="adm-section">
    <div class="adm-card-head" style="margin-bottom:1rem;">
        <div>
            <h2 style="font-size:1.15rem;font-weight:700;margin:0;color:var(--adm-ink);">Ingresos de VIBEZ</h2>
            <div style="font-size:12px;color:var(--adm-ink-dim);margin-top:2px;">Facturación acumulada recibida por la plataforma</div>
        </div>
        <a href="{{ route('admin.pagos.index') }}" class="adm-btn-ghost">Ver todos los pagos →</a>
    </div>

    {{-- KPIs financieros --}}
    <div class="adm-kpi-grid" style="grid-template-columns: repeat(3,1fr); margin-bottom:1.5rem;">

        {{-- Total ingresos VIBEZ --}}
        <div class="adm-kpi" style="background:linear-gradient(135deg,#1e1b4b 0%,#2d1b69 100%);border-color:#4c1d95;">
            <div class="adm-kpi-head">
                <div class="adm-kpi-label" style="color:#c4b5fd;">Total recibido por VIBEZ</div>
                <div class="adm-kpi-icon" style="color:#a855f7;">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <circle cx="12" cy="12" r="10"/>
                        <line x1="12" y1="8" x2="12" y2="16"/>
                        <line x1="8" y1="12" x2="16" y2="12"/>
                        <path d="M15 9a3 3 0 0 0-6 0v1h6V9z"/>
                        <path d="M9 14a3 3 0 0 0 6 0v-1H9v1z"/>
                    </svg>
                </div>
            </div>
            <div class="adm-kpi-value" style="font-size:1.75rem;color:#f5f3ff;">
                {{ number_format($ingresoTotal, 2) }} €
            </div>
            <div class="adm-kpi-foot">
                <span class="adm-kpi-lbl" style="color:#a78bfa;">comisiones + premium</span>
            </div>
        </div>

        {{-- Comisiones de entradas --}}
        <div class="adm-kpi">
            <div class="adm-kpi-head">
                <div class="adm-kpi-label">Comisiones de entradas</div>
                <div class="adm-kpi-icon">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M2 9V7a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v2a2 2 0 0 0 0 4v2a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2v-2a2 2 0 0 0 0-4z"/>
                        <line x1="13" y1="5" x2="13" y2="19"/>
                    </svg>
                </div>
            </div>
            <div class="adm-kpi-value">{{ number_format($ingresoComisiones, 2) }} €</div>
            <div class="adm-kpi-foot">
                <span class="adm-kpi-lbl">
                    10 % de {{ number_format($totalVentasEntradas, 2) }} € vendidos
                </span>
            </div>
            <svg class="adm-spark" viewBox="0 0 80 24" preserveAspectRatio="none">
                <polyline points="0,18 20,14 40,10 60,7 80,4"
                          fill="none" stroke="#a855f7" stroke-width="1.5"/>
            </svg>
        </div>

        {{-- Premium --}}
        <div class="adm-kpi">
            <div class="adm-kpi-head">
                <div class="adm-kpi-label">Suscripciones Premium</div>
                <div class="adm-kpi-icon">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/>
                    </svg>
                </div>
            </div>
            <div class="adm-kpi-value">{{ number_format($ingresoPremium, 2) }} €</div>
            <div class="adm-kpi-foot">
                <a href="{{ route('admin.pagos-premium.index') }}"
                   style="color:#a855f7;font-size:.78rem;text-decoration:underline;">
                    {{ $usuariosPremium }} usuario{{ $usuariosPremium !== 1 ? 's' : '' }} premium →
                </a>
            </div>
            <svg class="adm-spark" viewBox="0 0 80 24" preserveAspectRatio="none">
                <polyline points="0,20 20,16 40,11 60,8 80,4"
                          fill="none" stroke="#a855f7" stroke-width="1.5"/>
            </svg>
        </div>

    </div>

    {{-- Últimos pagos completados --}}
    @if($ultimosPagos->isNotEmpty())
    <div class="adm-card">
        <div class="adm-card-head" style="margin-bottom:1rem;">
            <div>
                <h3 class="adm-card-title">Últimas transacciones</h3>
                <div class="adm-card-sub">Pagos completados más recientes</div>
            </div>
        </div>
        <div class="table-wrap">
            <table class="admin-table" style="font-size:.85rem;">
                <thead>
                    <tr>
                        <th>#Pago</th>
                        <th>Usuario</th>
                        <th>Evento</th>
                        <th>Total cliente</th>
                        <th>Comisión VIBEZ (10%)</th>
                        <th>Fecha</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($ultimosPagos as $p)
                        @php
                            $usuario  = $p->pedido?->usuario;
                            $evento   = $p->pedido?->entradas?->first()?->evento;
                        @endphp
                        <tr>
                            <td data-label="#Pago"><span style="color:#a855f7;">#{{ $p->id }}</span></td>
                            <td data-label="Usuario">
                                @if($usuario)
                                    {{ $usuario->nombre }} {{ $usuario->apellido1 }}
                                    <br><small style="color:#64748b;">{{ $usuario->email }}</small>
                                @else
                                    <span style="color:#475569;">—</span>
                                @endif
                            </td>
                            <td data-label="Evento">
                                {{ $evento?->nombre ?? '—' }}
                            </td>
                            <td data-label="Total cliente">
                                <strong>{{ number_format($p->importe, 2) }} {{ $p->moneda }}</strong>
                            </td>
                            <td data-label="Comisión VIBEZ (10%)">
                                <span style="color:#a855f7;font-weight:600;">
                                    + {{ number_format($p->importe * 0.10, 2) }} €
                                </span>
                            </td>
                            <td data-label="Fecha">
                                <span style="color:#94a3b8;font-size:.8rem;">
                                    {{ optional($p->fecha_creacion)->format('d/m/Y H:i') ?? '—' }}
                                </span>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @endif
</div>

@endsection
