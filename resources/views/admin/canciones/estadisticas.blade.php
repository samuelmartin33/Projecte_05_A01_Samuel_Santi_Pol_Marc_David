@extends('admin.layouts.dashboard')

@section('title', 'Estadísticas de Canciones — Admin VIBEZ')

@push('estilos')
    <link rel="stylesheet" href="{{ asset('css/admin-canciones-stats.css') }}">
@endpush

@section('content')

{{-- ── Cabecera ──────────────────────────────────────────────────── --}}
<header class="admin-header">
    <div>
        <h1>Estadísticas de canciones</h1>
        <p>Reproducciones y ganancias por canción · Filtra por evento o empresa</p>
    </div>
    <div class="stats-acciones">
        <a href="{{ route('admin.canciones.index') }}" class="btn btn-secondary">
            ← Volver al catálogo
        </a>
        <button class="btn btn-primary" onclick="descargarPdfMes()">
            Descargar PDF del mes
        </button>
    </div>
</header>

{{-- ── Filtros ───────────────────────────────────────────────────── --}}
<section class="card">
    <div class="stats-filtros">

        <div class="stats-filtro-grupo">
            <label for="filtro-evento">Evento</label>
            <select id="filtro-evento">
                <option value="">Todos los eventos</option>
                @foreach($eventos as $evento)
                    <option value="{{ $evento->id }}"
                        {{ request('evento_id') == $evento->id ? 'selected' : '' }}>
                        {{ $evento->titulo }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="stats-filtro-grupo">
            <label for="filtro-empresa">Empresa</label>
            <select id="filtro-empresa">
                <option value="">Todas las empresas</option>
                @foreach($empresas as $empresa)
                    <option value="{{ $empresa->id }}"
                        {{ request('empresa_id') == $empresa->id ? 'selected' : '' }}>
                        {{ $empresa->nombre_empresa }}
                    </option>
                @endforeach
            </select>
        </div>

    </div>
</section>

{{-- ── Tabla de resultados ──────────────────────────────────────── --}}
<section class="card">
    <table class="tabla-eventos">
        <thead>
            <tr>
                <th>Canción</th>
                <th>Artista</th>
                <th>Veces seleccionada</th>
                <th>Ganancia</th>
            </tr>
        </thead>
        <tbody id="tabla-stats-body">
            @forelse($filas as $fila)
                <tr>
                    <td data-label="Canción">{{ $fila->cancion?->titulo ?? '—' }}</td>
                    <td data-label="Artista">{{ $fila->cancion?->artista ?? '—' }}</td>
                    <td data-label="Veces seleccionada">
                        <span class="badge-veces">{{ $fila->veces }}</span>
                    </td>
                    <td data-label="Ganancia">
                        {{ number_format((float) $fila->ganancia, 2) }} €
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" class="stats-vacio">
                        Todavía no hay canciones seleccionadas en playlists.
                    </td>
                </tr>
            @endforelse
        </tbody>

        @if($filas->isNotEmpty())
            <tfoot>
                <tr class="stats-total-row">
                    <td colspan="3">Total global</td>
                    <td id="total-global">{{ number_format((float) $totalGlobal, 2) }} €</td>
                </tr>
            </tfoot>
        @endif
    </table>
</section>

@endsection

@push('scripts')
    <script src="{{ asset('js/admin-canciones-stats.js') }}"></script>
@endpush
