@extends('admin.layouts.dashboard')

@section('title', 'Control de Bonos — Admin VIBEZ')

@push('estilos')
    <link rel="stylesheet" href="{{ asset('css/admin-bonos.css') }}">
@endpush

@section('content')

{{-- ── Cabecera ──────────────────────────────────────────────────── --}}
<header class="admin-header">
    <div>
        <h1>Control de Bonos</h1>
        <p>Resumen de bonos de bebidas vendidos en eventos Fiesta</p>
    </div>
    <div class="bonos-acciones">
        <a href="{{ route('admin.bonos-tipos.index') }}" class="btn btn-secondary">
            Tipos de bono
        </a>
        <button class="btn-pink" onclick="descargarPdfBonos()">
            Descargar PDF
        </button>
    </div>
</header>

{{-- ── Filtros ───────────────────────────────────────────────────── --}}
<section class="card">
    <div class="bonos-filtros">
        <div class="bonos-filtro-grupo">
            <label for="filtro-evento">Evento</label>
            <select id="filtro-evento">
                <option value="">Todos los eventos</option>
                @foreach($eventos as $evento)
                    <option value="{{ $evento->id }}">{{ $evento->titulo }}</option>
                @endforeach
            </select>
        </div>
        <div class="bonos-filtro-grupo">
            <label for="filtro-empresa">Empresa</label>
            <select id="filtro-empresa">
                <option value="">Todas las empresas</option>
                @foreach($empresas as $empresa)
                    <option value="{{ $empresa->id }}">{{ $empresa->nombre_empresa }}</option>
                @endforeach
            </select>
        </div>
    </div>
</section>

{{-- ── Metric Cards ──────────────────────────────────────────────── --}}
<div class="bonos-cards">

    {{-- Cards dinámicas por tipo --}}
    <div id="cards-tipos" style="display:contents;">
        @foreach($resumen as $item)
            <div class="bono-card">
                <div class="bono-card-label">Bono {{ $item['cantidad_bebidas'] }} bebidas</div>
                <div class="bono-card-valor">{{ $item['total_vendidos'] }}</div>
                <div class="bono-card-sub">
                    {{ number_format($item['ingresos'], 2) }} €
                    · {{ number_format($item['precio'], 2) }} € / bono
                </div>
            </div>
        @endforeach
    </div>

    {{-- Card de totales globales --}}
    <div class="bono-card card-total">
        <div class="bono-card-label">Total global</div>
        <div class="bono-card-valor" id="total-vendidos">{{ $totalVendidos }}</div>
        <div class="bono-card-sub">
            <span id="total-ingresos">{{ number_format($totalIngresos, 2) }} €</span>
            en ingresos
        </div>
    </div>

</div>

{{-- ── Tabla de bonos activos ────────────────────────────────────── --}}
<section class="card">
    <div class="bonos-seccion-titulo">Bonos activos (con saldo restante)</div>
    <table class="tabla-eventos">
        <thead>
            <tr>
                <th>Usuario</th>
                <th>Evento</th>
                <th>Tipo</th>
                <th>Saldo restante</th>
                <th>Fecha compra</th>
            </tr>
        </thead>
        <tbody id="tbody-bonos-activos">
            @include('admin.bonos._tabla_activos', ['bonosActivos' => $bonosActivos])
        </tbody>
    </table>
</section>

@endsection

@push('scripts')
    <script src="{{ asset('js/admin-bonos.js') }}"></script>
@endpush
