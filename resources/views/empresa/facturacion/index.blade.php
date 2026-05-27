@extends('layouts.app')

@section('titulo', 'Administración — ' . $empresa->nombre_empresa)

@push('estilos')
<link rel="stylesheet" href="{{ asset('css/empresa-home.css') }}">
<link rel="stylesheet" href="{{ asset('css/empresa-facturacion.css') }}">
{{-- CSS extraido a public/css/empresa-facturacion.css --}}
@endpush

@section('content')

@include('partials.home.nav')

{{-- HERO --}}
<section class="hero-home">
    <div class="hero-particula hero-particula-1"></div>
    <div class="hero-particula hero-particula-2"></div>
    <div class="hero-particula hero-particula-3"></div>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-14 text-center relative z-10">
        <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full text-xs font-bold mb-5"
             style="background:rgba(168,85,247,0.12);border:1px solid rgba(168,85,247,0.28);color:#c084fc;letter-spacing:0.06em;text-transform:uppercase;">
            <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 14l6-6m-5.5.5h.01m4.99 5h.01M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16l3.5-2 3.5 2 3.5-2 3.5 2z"/>
            </svg>
            Ventas y facturación
        </div>
        <h1 class="text-4xl sm:text-5xl font-black text-white tracking-tight leading-tight mb-3">Administración</h1>
        <p class="text-slate-400 text-lg max-w-xl mx-auto">Consulta las ventas de cada evento y genera la factura en PDF.</p>
    </div>
</section>

{{-- STATS --}}
<section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    <div class="empresa-stats-grid" style="grid-template-columns:repeat(4,1fr);">
        <div class="empresa-stat-card">
            <div class="empresa-stat-numero" style="color:#34d399;">{{ $totalIngresos > 0 ? number_format($totalIngresos,0,',','.') . '€' : '—' }}</div>
            <div class="empresa-stat-label">Ingresos totales</div>
        </div>
        <div class="empresa-stat-card">
            <div class="empresa-stat-numero" style="color:#c084fc;">{{ number_format($totalEntradas,0,',','.') }}</div>
            <div class="empresa-stat-label">Entradas vendidas</div>
        </div>
        <div class="empresa-stat-card">
            <div class="empresa-stat-numero">{{ $eventos->count() }}</div>
            <div class="empresa-stat-label">Eventos totales</div>
        </div>
        <div class="empresa-stat-card" style="border-right:none;">
            <div class="empresa-stat-numero">{{ $avgTicket > 0 ? number_format($avgTicket,2,',','.') . '€' : '—' }}</div>
            <div class="empresa-stat-label">Precio medio</div>
        </div>
    </div>
</section>

{{-- TABLA --}}
<section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-20">

    <div class="seccion-empresa-titulo">
        <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
        </svg>
        Tus eventos
    </div>
    <p class="seccion-empresa-sub">Filtra, ordena y genera la factura de ventas en PDF</p>

    @if($eventos->isEmpty())
        <div class="empty-state">
            <svg fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
            </svg>
            <p class="empty-titulo">Aún no tienes eventos</p>
            <p class="empty-desc">Crea tu primer evento para ver las ventas aquí.</p>
        </div>
    @else

    {{-- FILTROS --}}
    <div class="filtros-bar">

        <div class="filtro-grupo" style="flex:1;min-width:200px;">
            <label class="filtro-label">Buscar evento</label>
            <input id="f-nombre" class="filtro-input filtro-input-search" type="text"
                   placeholder="Nombre del evento…" oninput="aplicarFiltros()">
        </div>

        <div class="filtro-grupo">
            <label class="filtro-label">Fecha desde</label>
            <input id="f-desde" class="filtro-input filtro-input-date" type="date" oninput="aplicarFiltros()">
        </div>

        <div class="filtro-grupo">
            <label class="filtro-label">Fecha hasta</label>
            <input id="f-hasta" class="filtro-input filtro-input-date" type="date" oninput="aplicarFiltros()">
        </div>

        {{-- Estado --}}
        <div class="filtro-grupo">
            <label class="filtro-label">Estado</label>
            <div class="cselect" id="cs-estado">
                <div class="cselect-trigger" onclick="toggleSelect('cs-estado')">
                    <span class="cselect-val">Todos</span>
                    <svg class="cselect-arrow" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
                    </svg>
                </div>
                <div class="cselect-menu">
                    <div class="cselect-option selected" data-val="">Todos</div>
                    <div class="cselect-option" data-val="activo">Activo</div>
                    <div class="cselect-option" data-val="inactivo">Inactivo</div>
                </div>
            </div>
        </div>

        {{-- Ventas --}}
        <div class="filtro-grupo">
            <label class="filtro-label">Ventas</label>
            <div class="cselect" id="cs-ventas">
                <div class="cselect-trigger" onclick="toggleSelect('cs-ventas')">
                    <span class="cselect-val">Todas</span>
                    <svg class="cselect-arrow" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
                    </svg>
                </div>
                <div class="cselect-menu">
                    <div class="cselect-option selected" data-val="">Todas</div>
                    <div class="cselect-option" data-val="con">Con ventas</div>
                    <div class="cselect-option" data-val="sin">Sin ventas</div>
                </div>
            </div>
        </div>

        {{-- Ordenar --}}
        <div class="filtro-grupo">
            <label class="filtro-label">Ordenar por</label>
            <div class="cselect" id="cs-orden" style="min-width:175px;">
                <div class="cselect-trigger" onclick="toggleSelect('cs-orden')">
                    <span class="cselect-val">Más reciente</span>
                    <svg class="cselect-arrow" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
                    </svg>
                </div>
                <div class="cselect-menu">
                    <div class="cselect-option selected" data-val="fecha-desc">Más reciente</div>
                    <div class="cselect-option" data-val="fecha-asc">Más antiguo</div>
                    <div class="cselect-option" data-val="ventas-desc">Más ventas</div>
                    <div class="cselect-option" data-val="ventas-asc">Menos ventas</div>
                    <div class="cselect-option" data-val="importe-desc">Mayor importe</div>
                    <div class="cselect-option" data-val="importe-asc">Menor importe</div>
                    <div class="cselect-option" data-val="nombre-asc">Nombre A → Z</div>
                    <div class="cselect-option" data-val="nombre-desc">Nombre Z → A</div>
                </div>
            </div>
        </div>

        <div class="filtro-grupo" style="justify-content:flex-end;">
            <label class="filtro-label" style="opacity:0;">·</label>
            <button class="filtro-reset" onclick="resetFiltros()">
                <svg width="11" height="11" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                </svg>
                Limpiar
            </button>
        </div>

    </div>

    <div class="adm-count" id="adm-count">
        Mostrando <span id="adm-count-num">{{ $eventos->count() }}</span> de {{ $eventos->count() }} eventos
    </div>

    <div class="adm-table-wrap">
        <table class="adm-table" id="adm-table">
            <thead>
                <tr>
                    <th data-col="nombre" onclick="sortByCol(this)"><span class="sort-icon">Evento</span></th>
                    <th data-col="estado" onclick="sortByCol(this)"><span class="sort-icon">Estado</span></th>
                    <th class="r" data-col="vendidas" onclick="sortByCol(this)"><span class="sort-icon">Entradas vendidas</span></th>
                    <th class="r" data-col="bruto" onclick="sortByCol(this)"><span class="sort-icon">Importe bruto</span></th>
                    <th class="r">Factura PDF</th>
                </tr>
            </thead>
            <tbody id="adm-tbody">
            @foreach($eventos as $ev)
            @php
                $vendidas = (int)   ($ev->entradas_vendidas ?? 0);
                $bruto    = (float) ($ev->ingresos_brutos   ?? 0);
                $estado   = $ev->estado == 1 ? 'activo' : 'inactivo';
            @endphp
            <tr data-nombre="{{ strtolower($ev->titulo) }}"
                data-fecha="{{ \Carbon\Carbon::parse($ev->fecha_inicio)->format('Y-m-d') }}"
                data-estado="{{ $estado }}"
                data-vendidas="{{ $vendidas }}"
                data-bruto="{{ $bruto }}">
                <td>
                    <div class="adm-evento-nombre">{{ $ev->titulo }}</div>
                    <div class="adm-evento-fecha">
                        {{ \Carbon\Carbon::parse($ev->fecha_inicio)->locale('es')->isoFormat('D MMM YYYY · H:mm') }}
                        @if($ev->ubicacion_nombre) &nbsp;·&nbsp;{{ $ev->ubicacion_nombre }} @endif
                    </div>
                </td>
                <td>
                    @if($estado === 'activo')
                        <span class="adm-badge-activo">Activo</span>
                    @else
                        <span class="adm-badge-inactivo">Inactivo</span>
                    @endif
                </td>
                <td class="r">
                    <span style="font-family:'Anton',sans-serif;font-size:1.1rem;color:#c084fc;">{{ number_format($vendidas) }}</span>
                    @if($ev->aforo_maximo)
                        <span style="font-family:'Archivo Narrow',sans-serif;font-size:0.7rem;color:rgba(245,241,234,0.25);"> / {{ $ev->aforo_maximo }}</span>
                    @endif
                </td>
                <td class="r">
                    @if($ev->es_gratuito && $bruto == 0)
                        <span style="font-family:'Archivo Narrow',sans-serif;font-size:0.65rem;font-weight:600;text-transform:uppercase;letter-spacing:.1em;color:rgba(245,241,234,0.25);">Gratuito</span>
                    @else
                        <span style="font-family:'Anton',sans-serif;font-size:1.1rem;color:#34d399;">{{ number_format($bruto,2,',','.') }} €</span>
                    @endif
                </td>
                <td class="r">
                    @if($vendidas > 0)
                        <a href="{{ route('empresa.facturacion.generar-pdf', $ev) }}" class="btn-pdf" target="_blank">
                            <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                            </svg>
                            Generar factura
                        </a>
                    @else
                        <span class="btn-pdf-dis">Sin ventas</span>
                    @endif
                </td>
            </tr>
            @endforeach
            </tbody>
        </table>
        <div id="adm-empty-filter">No hay eventos que coincidan con los filtros aplicados.</div>
    </div>

    @endif
</section>

@endsection

@push('scripts')
<script src="{{ asset('js/empresa-facturacion.js') }}"></script>
{{-- JS en public/js/empresa-facturacion.js --}}
@endpush
