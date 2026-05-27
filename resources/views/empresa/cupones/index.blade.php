@extends('layouts.app')

@section('titulo', 'Mis cupones — VIBEZ')

@push('estilos')
<link rel="stylesheet" href="{{ asset('css/empresa-home.css') }}">
<link rel="stylesheet" href="{{ asset('css/empresa-cupones-index.css') }}">
{{-- CSS extraido a public/css/empresa-cupones-index.css --}}
@endpush

@section('content')

@include('partials.home.nav')

{{-- Hero --}}
<section class="hero-home">
    <div class="hero-particula hero-particula-1"></div>
    <div class="hero-particula hero-particula-2"></div>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 relative z-10">
        <div class="flex flex-wrap items-end justify-between gap-4">
            <div>
                <div class="inline-flex items-center gap-2 px-4 py-1.5 mb-4"
                     style="background:rgba(168,85,247,0.15);border:1px solid rgba(168,85,247,0.3);color:#c084fc;font-family:'Archivo Narrow',sans-serif;font-size:0.625rem;font-weight:600;text-transform:uppercase;letter-spacing:0.16em;">
                    <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                    </svg>
                    Panel de empresa
                </div>
                <h1 class="text-4xl sm:text-5xl font-black text-white tracking-tight"
                    style="font-family:'Anton',sans-serif;text-transform:uppercase;letter-spacing:-0.005em;line-height:0.9;">
                    Mis cupones
                </h1>
                <p style="font-family:'Archivo Narrow',sans-serif;font-size:0.75rem;font-weight:600;text-transform:uppercase;letter-spacing:0.16em;color:rgba(245,241,234,0.40);margin-top:0.6rem;">
                    Gestiona los descuentos de tu empresa
                </p>
            </div>
            <a href="{{ route('empresa.cupones.create') }}" class="btn-crear-evento">
                <svg fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
                </svg>
                Nuevo cupón
            </a>
        </div>
    </div>
</section>

<section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">

    @if(session('success'))
        <div class="alert-success">{{ session('success') }}</div>
    @endif

    <div class="tabla-wrap">
        <table class="cupones-tabla">
            <thead>
                <tr>
                    <th>Código</th>
                    <th>Descuento</th>
                    <th>Descripción</th>
                    <th>Eventos</th>
                    <th>Usos</th>
                    <th>Validez</th>
                    <th>Estado</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse($cupones as $cupon)
                    <tr>
                        <td><span class="badge-codigo">{{ $cupon->codigo }}</span></td>
                        <td>
                            <span class="badge-descuento {{ $cupon->valor_descuento == 0 ? 'gratis' : '' }}">
                                @if($cupon->valor_descuento == 0)
                                    Gratis
                                @else
                                    {{ number_format($cupon->valor_descuento, 0) }}%
                                @endif
                            </span>
                        </td>
                        <td class="td-desc">{{ $cupon->descripcion ?? '—' }}</td>
                        <td>
                            @if($cupon->eventos->isEmpty())
                                <span class="badge-global">Global</span>
                            @else
                                <span class="td-usos">{{ $cupon->eventos->count() }} evento(s)</span>
                            @endif
                        </td>
                        <td class="td-usos">
                            {{ $cupon->usos_actuales }} /
                            {{ $cupon->limite_usos_total ?? '∞' }}
                        </td>
                        <td class="td-fecha">
                            {{ optional($cupon->fecha_inicio)->format('d/m/Y') }}
                            →
                            {{ optional($cupon->fecha_fin)->format('d/m/Y') }}
                        </td>
                        <td>
                            @if(!$cupon->estado)
                                <span class="badge-estado inactivo">Inactivo</span>
                            @elseif($cupon->expirado)
                                <span class="badge-estado inactivo">Expirado</span>
                            @elseif($cupon->agotado)
                                <span class="badge-estado agotado">Agotado</span>
                            @else
                                <span class="badge-estado activo">Activo</span>
                            @endif
                        </td>
                        <td>
                            <div style="display:flex;align-items:center;gap:8px;">
                                <a href="{{ route('empresa.cupones.edit', $cupon->id) }}"
                                   class="btn-accion btn-editar">Editar</a>
                                <form method="POST" action="{{ route('empresa.cupones.destroy', $cupon->id) }}"
                                      onsubmit="return confirm('¿Eliminar el cupón {{ $cupon->codigo }}? Esta acción no se puede deshacer.')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn-accion btn-borrar">Eliminar</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8">
                            <div class="empty-cupones">
                                <svg fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                                </svg>
                                <p>No tienes cupones creados todavía.</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Paginación --}}
    @if($cupones->hasPages())
        <nav class="paginacion">
            @if($cupones->onFirstPage())
                <span class="pag-btn disabled">‹</span>
            @else
                <a class="pag-btn" href="{{ $cupones->previousPageUrl() }}">‹</a>
            @endif

            @for($page = 1; $page <= $cupones->lastPage(); $page++)
                @if($page === $cupones->currentPage())
                    <span class="pag-btn activo">{{ $page }}</span>
                @else
                    <a class="pag-btn" href="{{ $cupones->url($page) }}">{{ $page }}</a>
                @endif
            @endfor

            @if($cupones->hasMorePages())
                <a class="pag-btn" href="{{ $cupones->nextPageUrl() }}">›</a>
            @else
                <span class="pag-btn disabled">›</span>
            @endif
        </nav>
    @endif

    <div style="margin-top:2rem;">
        <a href="{{ route('empresa.home') }}"
           style="font-family:'Archivo Narrow',sans-serif;font-size:0.625rem;font-weight:600;text-transform:uppercase;letter-spacing:0.16em;color:rgba(245,241,234,0.40);text-decoration:none;transition:color 0.15s;"
           onmouseover="this.style.color='#f5f1ea'"
           onmouseout="this.style.color='rgba(245,241,234,0.40)'">
            ← Volver al panel
        </a>
    </div>

</section>

@endsection
