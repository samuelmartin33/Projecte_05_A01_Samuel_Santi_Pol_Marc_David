@extends('layouts.app')

@section('titulo', 'Playlist — ' . $evento->titulo . ' — VIBEZ')

@push('estilos')
<link rel="stylesheet" href="{{ asset('css/vibez-home.css') }}">
<style>
  .playlist-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
    gap: 1.25rem;
  }
  @media (max-width: 640px) {
    .playlist-grid { grid-template-columns: 1fr; }
  }
  .cancion-card {
    background: rgba(13,10,24,0.9);
    border: 1px solid rgba(245,241,234,0.08);
    border-radius: 12px;
    overflow: hidden;
    transition: border-color 0.2s, transform 0.2s;
  }
  .cancion-card:hover { border-color: rgba(124,58,237,0.4); transform: translateY(-2px); }
  .cancion-card.ya-añadida { border-color: rgba(74,222,128,0.3); }
  .btn-añadir {
    width: 100%;
    padding: 10px 0;
    border: none;
    border-radius: 999px;
    font-family: 'Archivo Narrow', sans-serif;
    font-size: 13px;
    font-weight: 700;
    cursor: pointer;
    transition: opacity 0.15s, transform 0.1s;
    background: linear-gradient(135deg, #7c3aed, #a855f7);
    color: #fff;
    letter-spacing: 0.03em;
  }
  .btn-añadir:hover { opacity: 0.88; transform: scale(0.98); }
  .btn-añadir:disabled { opacity: 0.4; cursor: not-allowed; transform: none; }
  .badge-genero-card {
    display: inline-block;
    padding: 2px 8px;
    background: rgba(124,58,237,0.15);
    border: 1px solid rgba(124,58,237,0.3);
    border-radius: 999px;
    font-size: 10px;
    font-family: 'Archivo Narrow', sans-serif;
    color: #c084fc;
    margin: 2px 2px 0 0;
  }
  /* Modal de pago */
  #modal-pago-overlay {
    display: none;
    position: fixed;
    inset: 0;
    background: rgba(0,0,0,0.75);
    backdrop-filter: blur(4px);
    z-index: 1000;
    align-items: center;
    justify-content: center;
  }
  #modal-pago-overlay.abierto { display: flex; }
  #modal-pago {
    background: #0d0a18;
    border: 1px solid rgba(124,58,237,0.35);
    border-radius: 16px;
    padding: 2rem;
    width: 100%;
    max-width: 420px;
    position: relative;
  }
  #stripe-card-el {
    background: rgba(255,255,255,0.04);
    border: 1px solid rgba(168,85,247,0.35);
    padding: 14px 16px;
    border-radius: 8px;
    margin-bottom: 1rem;
    transition: border-color 0.2s;
  }
</style>
@endpush

@section('content')

@include('partials.home.nav')

{{-- Hero --}}
<div style="background:rgba(7,6,12,0.95);border-bottom:1px solid rgba(245,241,234,0.06);padding:2rem 2rem 1.5rem;">
    <div style="max-width:1280px;margin:0 auto;">
        <a href="{{ route('eventos.detalle', $evento->id) }}"
           style="display:inline-flex;align-items:center;gap:8px;font-size:10px;color:rgba(245,241,234,0.4);text-decoration:none;margin-bottom:1rem;font-family:'Archivo Narrow',sans-serif;text-transform:uppercase;letter-spacing:0.1em;transition:color 0.15s;"
           onmouseover="this.style.color='rgba(245,241,234,0.8)'"
           onmouseout="this.style.color='rgba(245,241,234,0.4)'">
            ← Volver al evento
        </a>
        <div style="display:flex;align-items:center;gap:16px;flex-wrap:wrap;">
            @if($evento->url_portada)
                <img src="{{ $evento->url_portada }}" alt="{{ $evento->titulo }}"
                     style="width:56px;height:56px;object-fit:cover;border-radius:8px;border:1px solid rgba(124,58,237,0.3);flex-shrink:0;">
            @endif
            <div>
                <div style="font-family:'Archivo Narrow',sans-serif;font-size:10px;text-transform:uppercase;letter-spacing:0.12em;color:#a855f7;margin-bottom:4px;">🎵 Playlist · Añadir canción</div>
                <h1 style="font-family:'Anton',sans-serif;font-size:clamp(1.4rem,3vw,2rem);color:#f5f1ea;margin:0;line-height:1.1;">
                    {{ $evento->titulo }}
                </h1>
                <p style="font-family:'Archivo Narrow',sans-serif;font-size:12px;color:rgba(245,241,234,0.4);margin:4px 0 0;">
                    {{ $evento->fecha_inicio->format('d/m/Y H:i') }}
                    @if($evento->ubicacion_nombre) · {{ $evento->ubicacion_nombre }} @endif
                </p>
            </div>
        </div>
    </div>
</div>

{{-- Info unique --}}
<div style="background:rgba(124,58,237,0.08);border-bottom:1px solid rgba(124,58,237,0.18);padding:10px 2rem;">
    <div style="max-width:1280px;margin:0 auto;font-family:'Archivo Narrow',sans-serif;font-size:12px;color:rgba(196,181,253,0.7);display:flex;align-items:center;gap:8px;">
        <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" style="flex-shrink:0;">
            <path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
        </svg>
        Puedes añadir cada canción una sola vez. Distintos asistentes pueden añadir la misma canción.
    </div>
</div>

{{-- Catálogo --}}
<div style="background:radial-gradient(circle,rgba(124,58,237,0.07) 1.5px,transparent 1.5px),linear-gradient(160deg,#0d0820 0%,#130228 45%,#0d0820 100%);background-size:28px 28px,100% 100%;min-height:calc(100vh - 220px);padding:2rem;">
    <div style="max-width:1280px;margin:0 auto;">

        @if($canciones->isEmpty())
            <div style="text-align:center;padding:80px 0;">
                <p style="font-family:'Archivo Narrow',sans-serif;font-size:14px;color:rgba(245,241,234,0.3);text-transform:uppercase;letter-spacing:0.1em;">
                    No hay canciones disponibles en el catálogo.
                </p>
            </div>
        @else
            <div style="margin-bottom:1.5rem;display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:12px;">
                <div>
                    <h2 style="font-family:'Anton',sans-serif;font-size:1.25rem;color:#f5f1ea;margin:0 0 4px;">
                        Catálogo de canciones
                    </h2>
                    <p style="font-family:'Archivo Narrow',sans-serif;font-size:12px;color:rgba(245,241,234,0.4);margin:0;">
                        {{ $canciones->count() }} canciones disponibles ·
                        {{ count($cancionesYaEnPlaylist) }} ya en tu playlist
                    </p>
                </div>
                {{-- Filtro rápido por género --}}
                <input type="text" id="filtro-titulo"
                       placeholder="Buscar canción o artista…"
                       oninput="filtrarCanciones(this.value)"
                       style="background:rgba(255,255,255,0.04);border:1px solid rgba(168,85,247,0.3);color:#f5f1ea;padding:9px 16px;border-radius:999px;font-family:'Archivo Narrow',sans-serif;font-size:13px;width:240px;outline:none;transition:border-color 0.2s;"
                       onfocus="this.style.borderColor='rgba(168,85,247,0.7)'"
                       onblur="this.style.borderColor='rgba(168,85,247,0.3)'">
            </div>

            <div class="playlist-grid" id="grid-canciones">
                @foreach($canciones as $cancion)
                @php
                    $yaEnPlaylist = in_array($cancion->id, $cancionesYaEnPlaylist);
                    $esGratis     = $cancion->precio <= 0;
                @endphp
                <div class="cancion-card {{ $yaEnPlaylist ? 'ya-añadida' : '' }}"
                     data-titulo="{{ strtolower($cancion->titulo) }}"
                     data-artista="{{ strtolower($cancion->artista) }}"
                     id="card-cancion-{{ $cancion->id }}">

                    {{-- Portada --}}
                    <div style="position:relative;aspect-ratio:1/1;overflow:hidden;max-height:200px;">
                        @if($cancion->portada_url)
                            <img src="{{ $cancion->portada_url }}" alt="{{ $cancion->titulo }}"
                                 style="width:100%;height:100%;object-fit:cover;">
                        @else
                            <div style="width:100%;height:100%;background:linear-gradient(135deg,rgba(124,58,237,0.2),rgba(168,85,247,0.1));display:flex;align-items:center;justify-content:center;font-size:3rem;">
                                🎵
                            </div>
                        @endif

                        {{-- Badge precio superpuesto --}}
                        <div style="position:absolute;top:10px;right:10px;background:{{ $esGratis ? 'rgba(74,222,128,0.85)' : 'rgba(13,10,24,0.85)' }};border:1px solid {{ $esGratis ? 'rgba(74,222,128,0.4)' : 'rgba(124,58,237,0.5)' }};border-radius:999px;padding:4px 12px;font-family:'Anton',sans-serif;font-size:0.95rem;color:{{ $esGratis ? '#0d0a18' : '#c084fc' }};backdrop-filter:blur(4px);">
                            {{ $esGratis ? 'Gratis' : number_format($cancion->precio, 2) . ' €' }}
                        </div>

                        {{-- Badge ya añadida --}}
                        @if($yaEnPlaylist)
                            <div style="position:absolute;inset:0;background:rgba(13,10,24,0.65);display:flex;align-items:center;justify-content:center;">
                                <div style="background:rgba(74,222,128,0.2);border:1px solid rgba(74,222,128,0.5);border-radius:999px;padding:6px 18px;font-family:'Archivo Narrow',sans-serif;font-size:13px;font-weight:700;color:#4ade80;">
                                    ✓ En tu playlist
                                </div>
                            </div>
                        @endif
                    </div>

                    {{-- Info --}}
                    <div style="padding:14px;">
                        <div style="font-family:'Anton',sans-serif;font-size:1.05rem;color:#f5f1ea;margin-bottom:2px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">
                            {{ $cancion->titulo }}
                        </div>
                        <div style="font-family:'Archivo Narrow',sans-serif;font-size:12px;color:rgba(245,241,234,0.55);margin-bottom:8px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">
                            {{ $cancion->artista }}
                        </div>

                        {{-- Géneros --}}
                        <div style="margin-bottom:10px;min-height:20px;">
                            @foreach($cancion->generos ?? [] as $genero)
                                <span class="badge-genero-card">{{ $genero }}</span>
                            @endforeach
                        </div>

                        {{-- Duración + audio preview --}}
                        <div style="display:flex;align-items:center;gap:8px;margin-bottom:12px;">
                            <span style="font-family:'Archivo Narrow',sans-serif;font-size:11px;color:rgba(245,241,234,0.4);">
                                ⏱ {{ $cancion->duracion_fmt }}
                            </span>
                            @if($cancion->audio_url)
                                <audio controls style="height:24px;flex:1;min-width:0;max-width:160px;">
                                    <source src="{{ $cancion->audio_url }}">
                                </audio>
                            @endif
                        </div>

                        {{-- Botón acción --}}
                        @if($yaEnPlaylist)
                            <button class="btn-añadir" disabled
                                    style="background:rgba(74,222,128,0.15);border:1px solid rgba(74,222,128,0.3);color:#4ade80;font-weight:700;">
                                ✓ Ya está en tu playlist
                            </button>
                        @else
                            <button class="btn-añadir"
                                    id="btn-añadir-{{ $cancion->id }}"
                                    onclick="iniciarAñadir({{ $cancion->id }}, '{{ addslashes($cancion->titulo) }}', '{{ addslashes($cancion->artista) }}', {{ $cancion->precio }}, '{{ $cancion->portada_url ?? '' }}')">
                                @if($esGratis)
                                    + Añadir gratis
                                @else
                                    + Añadir · {{ number_format($cancion->precio, 2) }} €
                                @endif
                            </button>
                        @endif
                    </div>
                </div>
                @endforeach
            </div>
        @endif

    </div>
</div>

{{-- Modal de pago --}}
<div id="modal-pago-overlay" onclick="cerrarModalSiOverlay(event)">
    <div id="modal-pago" role="dialog" aria-modal="true">

        <button onclick="cerrarModalPago()"
                style="position:absolute;top:14px;right:16px;background:none;border:none;color:rgba(245,241,234,0.4);font-size:1.3rem;cursor:pointer;line-height:1;padding:4px;">✕</button>

        <div style="display:flex;align-items:center;gap:14px;margin-bottom:1.5rem;">
            <img id="modal-portada" src="" alt=""
                 style="width:56px;height:56px;object-fit:cover;border-radius:8px;border:1px solid rgba(124,58,237,0.3);display:none;">
            <div id="modal-portada-placeholder"
                 style="width:56px;height:56px;border-radius:8px;background:rgba(124,58,237,0.15);border:1px solid rgba(124,58,237,0.25);display:flex;align-items:center;justify-content:center;font-size:1.5rem;flex-shrink:0;">
                🎵
            </div>
            <div>
                <div style="font-family:'Archivo Narrow',sans-serif;font-size:10px;text-transform:uppercase;letter-spacing:0.1em;color:rgba(245,241,234,0.4);margin-bottom:2px;">Añadir a playlist</div>
                <div id="modal-titulo-cancion" style="font-family:'Anton',sans-serif;font-size:1.2rem;color:#f5f1ea;line-height:1.1;"></div>
                <div id="modal-artista-cancion" style="font-family:'Archivo Narrow',sans-serif;font-size:12px;color:rgba(245,241,234,0.5);margin-top:2px;"></div>
            </div>
        </div>

        {{-- Precio --}}
        <div style="display:flex;justify-content:space-between;align-items:center;border-top:1px solid rgba(245,241,234,0.06);border-bottom:1px solid rgba(245,241,234,0.06);padding:12px 0;margin-bottom:1.25rem;">
            <span style="font-family:'Archivo Narrow',sans-serif;font-size:12px;color:rgba(245,241,234,0.4);">Importe</span>
            <span id="modal-precio" style="font-family:'Anton',sans-serif;font-size:1.6rem;color:#c084fc;"></span>
        </div>

        {{-- Stripe card element --}}
        <div id="stripe-card-el"></div>
        <div id="stripe-error-msg" style="display:none;color:#f87171;font-family:'Archivo Narrow',sans-serif;font-size:12px;margin-bottom:10px;"></div>

        <button id="btn-pagar-modal" type="button" onclick="procesarPagoCancion()"
                style="width:100%;padding:12px;border:none;border-radius:999px;background:linear-gradient(135deg,#7c3aed,#a855f7);color:#fff;font-family:'Archivo Narrow',sans-serif;font-size:14px;font-weight:700;cursor:pointer;transition:opacity 0.15s;"
                onmouseover="this.style.opacity='0.88'"
                onmouseout="this.style.opacity='1'">
            Pagar y añadir
        </button>

        @if(app()->environment('local'))
        <div style="margin-top:12px;padding:10px;background:rgba(168,85,247,0.06);border:1px solid rgba(168,85,247,0.15);border-radius:8px;font-family:'Archivo Narrow',sans-serif;font-size:11px;color:rgba(245,241,234,0.35);text-align:center;">
            Prueba: 4242 4242 4242 4242 · Cualquier fecha · Cualquier CVC
        </div>
        @endif

    </div>
</div>

@include('partials.home.footer')

@endsection

@push('scripts')
<script src="https://js.stripe.com/v3/"></script>
<script>
window.playlistData = {
    eventoId:  {{ $evento->id }},
    stripeKey: '{{ config('services.stripe.key') }}',
};
</script>
<script src="{{ asset('js/fiesta-playlist.js') }}"></script>
@endpush
