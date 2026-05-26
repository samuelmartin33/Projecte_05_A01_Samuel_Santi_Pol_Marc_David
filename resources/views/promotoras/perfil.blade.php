@extends('layouts.app')

@section('titulo', $empresa->nombre_empresa . ' — VIBEZ')

@push('estilos')
<link rel="stylesheet" href="{{ asset('css/vibez-home.css') }}">
<style>
.promotora-hero {
    position: relative;
    overflow: hidden;
    min-height: 320px;
    background: #07060c;
    display: flex;
    align-items: flex-end;
}
.promotora-hero-bg {
    position: absolute;
    inset: 0;
    background: linear-gradient(135deg, rgba(124,58,237,0.15) 0%, rgba(7,6,12,0) 60%);
    pointer-events: none;
}
.promotora-hero-dots {
    position: absolute;
    inset: 0;
    background-image: radial-gradient(circle, rgba(168,85,247,0.07) 1.5px, transparent 1.5px);
    background-size: 28px 28px;
    pointer-events: none;
}
.promotora-hero-overlay {
    position: absolute;
    inset: 0;
    background: linear-gradient(to top, #07060c 0%, transparent 60%);
}
.promotora-grid-eventos {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(260px, 1fr));
    gap: 18px;
}
.promotora-evento-card {
    background: rgba(255,255,255,0.03);
    border: 1px solid rgba(245,241,234,0.08);
    border-radius: 10px;
    overflow: hidden;
    text-decoration: none;
    display: block;
    transition: border-color 0.18s, transform 0.18s;
}
.promotora-evento-card:hover {
    border-color: rgba(168,85,247,0.35);
    transform: translateY(-2px);
}
.promotora-evento-img {
    width: 100%;
    height: 155px;
    object-fit: cover;
    display: block;
    background: rgba(168,85,247,0.08);
}
.promotora-evento-body {
    padding: 14px 16px;
}
</style>
@endpush

@section('content')

@include('partials.home.nav')

{{-- ── HERO ── --}}
<div class="promotora-hero">
    <div class="promotora-hero-bg"></div>
    <div class="promotora-hero-dots"></div>
    <div class="promotora-hero-overlay"></div>

    <div style="max-width:1200px;margin:0 auto;padding:3rem 2rem 2.5rem;position:relative;z-index:1;width:100%;">

        {{-- Volver --}}
        <a href="{{ route('home') }}"
           class="mono"
           style="display:inline-flex;align-items:center;gap:8px;font-size:10px;color:rgba(245,241,234,0.4);text-decoration:none;margin-bottom:2rem;transition:color 0.15s;"
           onmouseover="this.style.color='rgba(245,241,234,0.8)'"
           onmouseout="this.style.color='rgba(245,241,234,0.4)'">
            <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
            </svg>
            Volver
        </a>

        <div style="display:flex;align-items:flex-end;gap:2rem;flex-wrap:wrap;">

            {{-- Logo --}}
            <div style="flex-shrink:0;">
                @if($empresa->logo_url)
                    <img src="{{ $empresa->logo_url }}"
                         alt="{{ $empresa->nombre_empresa }}"
                         style="width:88px;height:88px;border-radius:12px;object-fit:cover;border:2px solid rgba(168,85,247,0.3);">
                @else
                    <div style="width:88px;height:88px;border-radius:12px;background:linear-gradient(135deg,#4e3a96,#7c3aed);display:flex;align-items:center;justify-content:center;">
                        <span style="font-family:'Anton',sans-serif;font-size:2rem;color:#fff;text-transform:uppercase;">{{ strtoupper(substr($empresa->nombre_empresa, 0, 1)) }}</span>
                    </div>
                @endif
            </div>

            {{-- Info --}}
            <div style="flex:1;min-width:0;">
                <div class="mono" style="font-size:10px;color:var(--magenta);margin-bottom:8px;display:flex;align-items:center;gap:8px;">
                    <span style="width:20px;height:1px;background:var(--magenta);display:inline-block;"></span>
                    Promotora
                    @if($empresa->ciudad)
                        · {{ $empresa->ciudad }}
                    @endif
                </div>

                <h1 class="display" style="font-size:clamp(2rem,5vw,3.5rem);color:var(--ink);margin:0 0 0.75rem;line-height:1.05;">
                    {{ $empresa->nombre_empresa }}
                </h1>

                {{-- Media de valoraciones --}}
                @if($totalValoraciones > 0)
                    <div style="display:flex;align-items:center;gap:10px;margin-bottom:0.75rem;">
                        <div style="color:#f59e0b;font-size:1rem;" id="estrellas-media-empresa">
                            @for($i = 1; $i <= 5; $i++){{ $i <= round($mediaValoracion) ? '★' : '☆' }}@endfor
                        </div>
                        <span class="mono" id="media-empresa-texto" style="font-size:10px;color:rgba(245,241,234,0.5);">
                            {{ $mediaValoracion }} · {{ $totalValoraciones }} {{ $totalValoraciones === 1 ? 'reseña' : 'reseñas' }}
                        </span>
                    </div>
                @else
                    <div style="margin-bottom:0.75rem;">
                        <span class="mono" style="font-size:10px;color:rgba(245,241,234,0.3);">Sin valoraciones todavía</span>
                    </div>
                @endif

                {{-- Descripción --}}
                @if($empresa->descripcion)
                    <p style="font-family:'Archivo',sans-serif;font-size:14px;color:var(--ink-dim);max-width:600px;line-height:1.6;margin:0 0 1rem;">
                        {{ $empresa->descripcion }}
                    </p>
                @endif

                {{-- Botón seguir --}}
                @auth
                    @if(!Auth::user()->isAdmin() && !Auth::user()->isEmpresa())
                        <button class="btn-seguir-promotora {{ $siguePromotor ? 'siguiendo' : '' }}"
                                data-empresa-id="{{ $empresa->id }}"
                                onclick="toggleSeguirPromotora(this)">
                            <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                @if($siguePromotor)
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                                @else
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
                                @endif
                            </svg>
                            <span class="btn-seguir-texto">{{ $siguePromotor ? 'Siguiendo' : 'Seguir' }}</span>
                        </button>
                    @endif
                @endauth
            </div>

        </div>
    </div>
</div>

<div style="max-width:1200px;margin:0 auto;padding:3rem 2rem 5rem;">

    {{-- ── PRÓXIMOS EVENTOS ── --}}
    <div style="margin-bottom:3rem;">
        <div class="mono" style="font-size:10px;color:var(--magenta);margin-bottom:1.25rem;display:flex;align-items:center;gap:8px;">
            <span style="width:20px;height:1px;background:var(--magenta);display:inline-block;"></span>
            Próximos eventos
        </div>

        @if($eventos->isEmpty())
            <div style="background:rgba(255,255,255,0.02);border:1px solid var(--line);border-radius:10px;padding:2rem;text-align:center;">
                <p class="mono" style="font-size:11px;color:rgba(245,241,234,0.3);">No hay eventos próximos programados.</p>
            </div>
        @else
            <div class="promotora-grid-eventos">
                @foreach($eventos as $ev)
                    <a href="{{ route('eventos.detalle', $ev->id) }}" class="promotora-evento-card">
                        {{-- Imagen portada --}}
                        @if($ev->portada)
                            <img class="promotora-evento-img" src="{{ $ev->portada->url }}" alt="{{ $ev->titulo }}">
                        @else
                            <div class="promotora-evento-img" style="background:linear-gradient(135deg,rgba(124,58,237,0.15),rgba(168,85,247,0.08));display:flex;align-items:center;justify-content:center;">
                                <svg width="32" height="32" fill="none" stroke="rgba(168,85,247,0.4)" stroke-width="1.5" viewBox="0 0 24 24">
                                    <path d="M9 19V6l12-3v13M9 19c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zm12-3c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2z"/>
                                </svg>
                            </div>
                        @endif

                        <div class="promotora-evento-body">
                            {{-- Categorías --}}
                            @if($ev->categorias->isNotEmpty())
                                <div class="mono" style="font-size:9px;color:var(--magenta);margin-bottom:6px;overflow:hidden;white-space:nowrap;text-overflow:ellipsis;">
                                    {{ $ev->categorias->pluck('nombre')->join(' · ') }}
                                </div>
                            @endif

                            <p style="font-family:'Anton',sans-serif;font-size:1rem;color:var(--ink);margin:0 0 6px;text-transform:uppercase;letter-spacing:0.01em;line-height:1.2;overflow:hidden;display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;">
                                {{ $ev->titulo }}
                            </p>

                            <div style="display:flex;justify-content:space-between;align-items:center;margin-top:10px;">
                                <span class="mono" style="font-size:9px;color:rgba(245,241,234,0.45);">{{ $ev->fecha_fmt }}</span>
                                <span class="mono" style="font-size:9px;color:var(--magenta);">{{ $ev->precio_formateado }}</span>
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>
        @endif
    </div>

    {{-- ── VALORACIONES ── --}}
    <div class="vibe-card" style="padding:2rem;" id="seccion-valoraciones-empresa">

        <div class="mono" style="font-size:10px;color:var(--magenta);margin-bottom:1.25rem;padding-bottom:0.75rem;border-bottom:1px solid var(--line);display:flex;align-items:center;gap:8px;">
            <span style="width:20px;height:1px;background:var(--magenta);display:inline-block;"></span>
            Valoraciones de la promotora
        </div>

        {{-- Resumen --}}
        <div style="display:flex;align-items:center;gap:1.5rem;margin-bottom:1.5rem;">
            <div style="text-align:center;">
                <div class="display" id="media-numerica-empresa" style="font-size:2.8rem;color:var(--magenta);line-height:1;">
                    {{ $totalValoraciones > 0 ? $mediaValoracion : '—' }}
                </div>
                <div style="color:#f59e0b;font-size:1.1rem;margin-top:2px;">
                    @if($totalValoraciones > 0)
                        @for($i = 1; $i <= 5; $i++){{ $i <= round($mediaValoracion) ? '★' : '☆' }}@endfor
                    @else
                        ☆☆☆☆☆
                    @endif
                </div>
            </div>
            <div>
                <p class="mono" style="font-size:10px;color:rgba(245,241,234,0.45);" id="total-empresa-texto">
                    {{ $totalValoraciones }} {{ $totalValoraciones === 1 ? 'valoración' : 'valoraciones' }}
                </p>
            </div>
        </div>

        {{-- Formulario condicional --}}
        @auth
            @if(!Auth::user()->isAdmin() && !$esPropiaEmpresa)
                @if($yaValorado)
                    <div style="background:rgba(168,85,247,0.08);border:1px solid rgba(168,85,247,0.2);border-radius:10px;padding:1rem;margin-bottom:1.5rem;">
                        <p class="mono" style="font-size:10px;color:rgba(168,85,247,0.8);">Ya has valorado esta promotora. ¡Gracias por tu reseña!</p>
                    </div>
                @else
                    <div id="form-valoracion-empresa" style="background:rgba(255,255,255,0.03);border:1px solid var(--line);border-radius:10px;padding:1.25rem;margin-bottom:1.5rem;">
                        <p class="mono" style="font-size:10px;color:rgba(245,241,234,0.5);margin-bottom:0.75rem;">Tu valoración</p>

                        {{-- Selector de estrellas --}}
                        <div id="selector-estrellas-empresa" style="display:flex;gap:6px;margin-bottom:0.75rem;">
                            @for($i = 1; $i <= 5; $i++)
                                <span class="estrella-empresa"
                                      data-valor="{{ $i }}"
                                      onclick="seleccionarEstrellaEmpresa({{ $i }})"
                                      onmouseover="resaltarEstrellasEmpresa({{ $i }})"
                                      onmouseout="restaurarEstrellasEmpresa()"
                                      style="font-size:1.9rem;color:rgba(245,241,234,0.2);transition:color 0.1s;cursor:pointer;user-select:none;">★</span>
                            @endfor
                        </div>
                        <input type="hidden" id="puntuacion-empresa" value="0">

                        <textarea id="comentario-empresa"
                                  placeholder="Cuéntanos tu experiencia con esta promotora (opcional)..."
                                  maxlength="1000"
                                  style="width:100%;background:rgba(255,255,255,0.05);border:1px solid var(--line);border-radius:8px;padding:10px 12px;color:var(--ink);font-family:'Archivo',sans-serif;font-size:13px;resize:vertical;min-height:80px;box-sizing:border-box;outline:none;"></textarea>

                        <button onclick="enviarValoracionEmpresa({{ $empresa->id }})"
                                style="margin-top:0.75rem;padding:10px 24px;background:linear-gradient(135deg,#7c3aed,#a855f7);color:#fff;border:none;border-radius:999px;font-family:'Archivo Narrow',sans-serif;font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:0.1em;cursor:pointer;transition:opacity 0.15s;"
                                onmouseover="this.style.opacity='0.85'"
                                onmouseout="this.style.opacity='1'">
                            Enviar valoración
                        </button>
                    </div>
                @endif
            @endif
        @else
            <div style="background:rgba(255,255,255,0.03);border:1px solid var(--line);border-radius:10px;padding:1rem;margin-bottom:1.5rem;">
                <p class="mono" style="font-size:10px;color:rgba(245,241,234,0.35);">
                    <a href="{{ route('login') }}" style="color:var(--magenta);text-decoration:none;">Inicia sesión</a>
                    para valorar esta promotora.
                </p>
            </div>
        @endauth

        {{-- Lista de reseñas --}}
        @if($valoraciones->isNotEmpty())
            <div id="contenedor-resenyas-empresa" style="display:flex;flex-direction:column;gap:1.1rem;">
                @foreach($valoraciones as $val)
                    <div style="border-bottom:1px solid var(--line);padding-bottom:1.1rem;">
                        <div style="display:flex;align-items:center;gap:10px;margin-bottom:6px;">
                            <div style="width:32px;height:32px;border-radius:50%;overflow:hidden;background:linear-gradient(135deg,#4e3a96,#7c3aed);display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                                @if($val->usuario?->foto_url)
                                    <img src="{{ $val->usuario->foto_url }}" style="width:100%;height:100%;object-fit:cover;" alt="">
                                @else
                                    <span style="color:#fff;font-size:12px;font-weight:900;font-family:'Anton',sans-serif;">{{ strtoupper(substr($val->usuario?->nombre ?? '?', 0, 1)) }}</span>
                                @endif
                            </div>
                            <div style="flex:1;min-width:0;">
                                <p style="font-family:'Archivo',sans-serif;font-size:13px;font-weight:700;color:var(--ink);margin:0;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">
                                    {{ $val->usuario?->nombre }} {{ $val->usuario?->apellido1 }}
                                </p>
                                <div style="color:#f59e0b;font-size:0.8rem;line-height:1.2;">
                                    @for($i = 1; $i <= 5; $i++){{ $i <= $val->puntuacion ? '★' : '☆' }}@endfor
                                </div>
                            </div>
                            <span class="mono" style="font-size:9px;color:rgba(245,241,234,0.25);white-space:nowrap;">{{ $val->fecha_creacion->format('d/m/Y') }}</span>
                        </div>
                        @if($val->comentario)
                            <p style="font-family:'Archivo',sans-serif;font-size:13px;color:var(--ink-dim);line-height:1.6;margin:0;">{{ $val->comentario }}</p>
                        @endif
                    </div>
                @endforeach
            </div>
        @else
            <p class="mono" style="font-size:10px;color:rgba(245,241,234,0.3);text-align:center;padding:1rem 0;">
                Aún no hay valoraciones para esta promotora.
            </p>
        @endif

    </div>

</div>

@include('partials.home.footer')

@endsection

@push('estilos')
<style>
.btn-seguir-promotora {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 8px 18px;
    border: 1.5px solid rgba(168,85,247,0.6);
    background: transparent;
    color: #a855f7;
    font-family: 'Syne', sans-serif;
    font-size: 0.8rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.06em;
    cursor: pointer;
    transition: background 0.18s, color 0.18s, border-color 0.18s;
    border-radius: 999px;
}
.btn-seguir-promotora:hover { background: rgba(168,85,247,0.15); }
.btn-seguir-promotora.siguiendo { background: rgba(168,85,247,0.2); border-color: #a855f7; color: #e9d5ff; }
.btn-seguir-promotora.cargando { opacity: 0.6; pointer-events: none; }
</style>
@endpush

@push('scripts')
<script>
/* ── Seguir promotora ── */
async function toggleSeguirPromotora(btn) {
    var empresaId = btn.dataset.empresaId;
    btn.classList.add('cargando');
    try {
        var res = await fetch('/api/seguimientos/' + empresaId + '/toggle', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json',
            },
        });
        var data = await res.json();
        if (data.success) {
            var texto = btn.querySelector('.btn-seguir-texto');
            if (data.siguiendo) {
                btn.classList.add('siguiendo');
                texto.textContent = 'Siguiendo';
            } else {
                btn.classList.remove('siguiendo');
                texto.textContent = 'Seguir';
            }
        }
    } catch (e) {
        console.error('Error al seguir promotora:', e);
    } finally {
        btn.classList.remove('cargando');
    }
}

/* ── Valoraciones empresa ── */
var estrellaPuntuacionEmpresa = 0;

function seleccionarEstrellaEmpresa(valor) {
    estrellaPuntuacionEmpresa = valor;
    document.getElementById('puntuacion-empresa').value = valor;
    pintarEstrellasEmpresa(valor, true);
}

function resaltarEstrellasEmpresa(valor) {
    pintarEstrellasEmpresa(valor, false);
}

function restaurarEstrellasEmpresa() {
    pintarEstrellasEmpresa(estrellaPuntuacionEmpresa, true);
}

function pintarEstrellasEmpresa(valor, esSeleccion) {
    var estrellas = document.getElementsByClassName('estrella-empresa');
    for (var i = 0; i < estrellas.length; i++) {
        estrellas[i].style.color = i < valor
            ? '#f59e0b'
            : (esSeleccion ? 'rgba(245,241,234,0.2)' : 'rgba(245,241,234,0.1)');
    }
}

function enviarValoracionEmpresa(empresaId) {
    var puntuacion = parseInt(document.getElementById('puntuacion-empresa').value, 10);
    if (!puntuacion || puntuacion < 1 || puntuacion > 5) {
        vibezAlerta('Selecciona una puntuación', 'Elige entre 1 y 5 estrellas antes de enviar.', 'warning');
        return;
    }
    var comentario = document.getElementById('comentario-empresa').value.trim();

    fetch('/api/valoraciones/empresas/' + empresaId, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
        },
        body: JSON.stringify({ puntuacion: puntuacion, comentario: comentario }),
    })
    .then(function (response) { return response.json(); })
    .then(function (data) {
        if (data.success) {
            // Ocultar formulario
            var form = document.getElementById('form-valoracion-empresa');
            if (form) { form.style.display = 'none'; }

            // Actualizar media y total en el resumen
            document.getElementById('media-numerica-empresa').textContent = data.media;
            document.getElementById('total-empresa-texto').textContent =
                data.total + ' ' + (data.total === 1 ? 'valoración' : 'valoraciones');

            // Actualizar también en el hero
            var heroTexto = document.getElementById('media-empresa-texto');
            if (heroTexto) {
                heroTexto.textContent = data.media + ' · ' + data.total + ' ' + (data.total === 1 ? 'reseña' : 'reseñas');
            }

            // Insertar la nueva reseña al inicio (si tiene comentario)
            var contenedor = document.getElementById('contenedor-resenyas-empresa');
            if (data.valoracion.comentario) {
                if (!contenedor) {
                    contenedor = document.createElement('div');
                    contenedor.id = 'contenedor-resenyas-empresa';
                    contenedor.style.cssText = 'display:flex;flex-direction:column;gap:1.1rem;';
                    document.getElementById('seccion-valoraciones-empresa').appendChild(contenedor);
                }
                var div = document.createElement('div');
                div.style.cssText = 'border-bottom:1px solid var(--line);padding-bottom:1.1rem;';
                var inicial = data.valoracion.autor ? data.valoracion.autor.charAt(0).toUpperCase() : '?';
                var estrellas = '';
                for (var i = 1; i <= 5; i++) { estrellas += i <= data.valoracion.puntuacion ? '★' : '☆'; }
                div.innerHTML =
                    '<div style="display:flex;align-items:center;gap:10px;margin-bottom:6px;">' +
                        '<div style="width:32px;height:32px;border-radius:50%;overflow:hidden;background:linear-gradient(135deg,#4e3a96,#7c3aed);display:flex;align-items:center;justify-content:center;flex-shrink:0;">' +
                            (data.valoracion.foto
                                ? '<img src="' + data.valoracion.foto + '" style="width:100%;height:100%;object-fit:cover;" alt="">'
                                : '<span style="color:#fff;font-size:12px;font-weight:900;">' + inicial + '</span>'
                            ) +
                        '</div>' +
                        '<div style="flex:1;min-width:0;">' +
                            '<p style="font-family:Archivo,sans-serif;font-size:13px;font-weight:700;color:var(--ink);margin:0;">' + data.valoracion.autor + '</p>' +
                            '<div style="color:#f59e0b;font-size:0.8rem;">' + estrellas + '</div>' +
                        '</div>' +
                        '<span style="font-size:9px;color:rgba(245,241,234,0.25);">' + data.valoracion.fecha + '</span>' +
                    '</div>' +
                    '<p style="font-family:Archivo,sans-serif;font-size:13px;color:var(--ink-dim);line-height:1.6;margin:0;">' +
                        data.valoracion.comentario +
                    '</p>';
                contenedor.insertBefore(div, contenedor.firstChild);
            }

            showSuccessAlert('¡Gracias por tu valoración!', data.message);
        } else {
            vibezAlerta('No se pudo enviar', data.message, 'error');
        }
    })
    .catch(function (e) {
        console.error('Error al valorar empresa:', e);
        vibezAlerta('Error', 'No se pudo enviar la valoración. Inténtalo de nuevo.', 'error');
    });
}
</script>
@endpush
