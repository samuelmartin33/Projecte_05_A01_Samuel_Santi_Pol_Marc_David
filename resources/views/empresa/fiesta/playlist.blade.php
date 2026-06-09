@extends('layouts.app')

@section('titulo', 'Playlist — {{ $evento->titulo }} — VIBEZ')

@push('estilos')
<link rel="stylesheet" href="{{ asset('css/empresa-fiesta.css') }}">
@endpush

@section('content')

@include('partials.home.nav')

{{-- Hero --}}
<div class="fiesta-hero">
    <div style="max-width:1480px;margin:0 auto;">
        <a href="{{ route('empresa.fiesta.index') }}"
           style="display:inline-flex;align-items:center;gap:6px;color:rgba(245,241,234,0.45);font-family:'Archivo Narrow',sans-serif;font-size:0.75rem;text-transform:uppercase;letter-spacing:0.12em;text-decoration:none;margin-bottom:12px;">
            ← Volver a Eventos Fiesta
        </a>
        <span class="fiesta-hero-badge">🎵 Playlist</span>
        <h1>{{ $evento->titulo }}</h1>
        <p>{{ $evento->fecha_inicio->format('d/m/Y H:i') }} · {{ $evento->ubicacion_nombre }}</p>
    </div>
</div>

{{-- Contenido --}}
<div style="max-width:1480px;margin:0 auto;padding:32px;">

    @if($playlist->isEmpty())
        <div style="background:#0d0a18;border:1px solid rgba(245,241,234,0.08);padding:60px;text-align:center;">
            <p style="font-family:'Archivo Narrow',sans-serif;font-size:0.875rem;text-transform:uppercase;letter-spacing:0.1em;color:rgba(245,241,234,0.25);">
                La playlist está vacía — ningún cliente ha añadido canciones todavía.
            </p>
        </div>
    @else
        {{-- Resumen --}}
        <div style="display:flex;gap:16px;margin-bottom:24px;">
            <div style="background:#0d0a18;border:1px solid rgba(245,241,234,0.10);padding:18px 28px;text-align:center;">
                <p style="font-family:'Anton',sans-serif;font-size:2rem;color:#f5f1ea;margin:0;line-height:1;">{{ $playlist->count() }}</p>
                <p style="font-family:'Archivo Narrow',sans-serif;font-size:0.5625rem;font-weight:600;text-transform:uppercase;letter-spacing:0.16em;color:rgba(245,241,234,0.45);margin:4px 0 0;">Canciones</p>
            </div>
            <div style="background:#0d0a18;border:1px solid rgba(245,241,234,0.10);padding:18px 28px;text-align:center;">
                <p style="font-family:'Anton',sans-serif;font-size:2rem;color:#c084fc;margin:0;line-height:1;">{{ number_format($playlist->sum('precio_pagado'), 2) }} €</p>
                <p style="font-family:'Archivo Narrow',sans-serif;font-size:0.5625rem;font-weight:600;text-transform:uppercase;letter-spacing:0.16em;color:rgba(245,241,234,0.45);margin:4px 0 0;">Ingresos playlist</p>
            </div>
            <div style="background:#0d0a18;border:1px solid rgba(245,241,234,0.10);padding:18px 28px;text-align:center;">
                <p style="font-family:'Anton',sans-serif;font-size:2rem;color:#f5f1ea;margin:0;line-height:1;">{{ $playlist->pluck('usuario_id')->unique()->count() }}</p>
                <p style="font-family:'Archivo Narrow',sans-serif;font-size:0.5625rem;font-weight:600;text-transform:uppercase;letter-spacing:0.16em;color:rgba(245,241,234,0.45);margin:4px 0 0;">Clientes</p>
            </div>
        </div>

        {{-- Tabla de playlist --}}
        <div style="overflow-x:auto;">
            <table class="fiesta-tabla">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Portada</th>
                        <th>Canción</th>
                        <th>Artista</th>
                        <th>Géneros</th>
                        <th>Duración</th>
                        <th>Cliente</th>
                        <th>Precio pagado</th>
                        <th>Audio</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($playlist as $item)
                    <tr>
                        <td>
                            <span style="font-family:'Anton',sans-serif;font-size:1.1rem;color:#c084fc;">
                                {{ $item->orden }}
                            </span>
                        </td>
                        <td>
                            @if($item->cancion?->portada_url)
                                <img src="{{ $item->cancion->portada_url }}" alt="Portada"
                                     style="width:44px;height:44px;object-fit:cover;border-radius:4px;border:1px solid rgba(245,241,234,0.12);">
                            @else
                                <span style="display:flex;align-items:center;justify-content:center;width:44px;height:44px;border-radius:4px;background:rgba(124,58,237,0.15);border:1px solid rgba(124,58,237,0.25);font-size:1.1rem;">🎵</span>
                            @endif
                        </td>
                        <td>
                            <strong style="color:#f5f1ea;">{{ $item->cancion?->titulo ?? '—' }}</strong>
                        </td>
                        <td style="color:rgba(245,241,234,0.65);">
                            {{ $item->cancion?->artista ?? '—' }}
                        </td>
                        <td>
                            @if($item->cancion?->generos)
                                @foreach($item->cancion->generos as $g)
                                    <span class="badge-fiesta" style="margin-right:4px;font-size:0.65rem;">{{ $g }}</span>
                                @endforeach
                            @else
                                <span style="color:rgba(245,241,234,0.3);">—</span>
                            @endif
                        </td>
                        <td style="color:rgba(245,241,234,0.65);font-family:'Archivo Narrow',sans-serif;">
                            {{ $item->cancion?->duracion_fmt ?? '—' }}
                        </td>
                        <td>
                            <div style="display:flex;align-items:center;gap:8px;">
                                @if($item->usuario?->foto_url)
                                    <img src="{{ $item->usuario->foto_url }}" alt=""
                                         style="width:28px;height:28px;border-radius:50%;object-fit:cover;flex-shrink:0;">
                                @else
                                    <span style="width:28px;height:28px;border-radius:50%;background:linear-gradient(135deg,#7c3aed,#a855f7);display:flex;align-items:center;justify-content:center;font-family:'Anton',sans-serif;font-size:11px;color:#f5f1ea;flex-shrink:0;">
                                        {{ strtoupper(substr($item->usuario?->nombre ?? '?', 0, 1)) }}
                                    </span>
                                @endif
                                <span style="color:#f5f1ea;font-size:0.875rem;">
                                    {{ $item->usuario?->nombre }} {{ $item->usuario?->apellido1 }}
                                </span>
                            </div>
                        </td>
                        <td>
                            <span class="badge-fiesta">{{ number_format($item->precio_pagado, 2) }} €</span>
                        </td>
                        <td>
                            @if($item->cancion?->audio_url)
                                <audio controls style="height:28px;width:160px;">
                                    <source src="{{ $item->cancion->audio_url }}">
                                </audio>
                            @else
                                <span style="color:rgba(245,241,234,0.2);font-size:0.75rem;font-family:'Archivo Narrow',sans-serif;">Sin audio</span>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>

@endsection
