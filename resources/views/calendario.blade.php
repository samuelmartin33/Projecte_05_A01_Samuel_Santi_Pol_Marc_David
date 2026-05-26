@extends('layouts.app')

@section('titulo', 'Calendario — VIBEZ')

@section('content')

<style>
  :root {
    --bg: #07060c; --bg-2: #0d0820;
    --ink: #f5f1ea; --ink-dim: rgba(245,241,234,0.55); --ink-faint: rgba(245,241,234,0.18);
    --morado: #7c3aed; --magenta: #a855f7;
    --line: rgba(245,241,234,0.10);
  }

  /* Celda del calendario */
  .cal-celda { cursor: default; user-select: none; transition: all 0.18s ease; }
  .cal-celda.tiene-eventos { cursor: pointer; }
  .cal-celda.tiene-eventos:hover {
    background: rgba(168,85,247,0.12) !important;
    border-color: rgba(168,85,247,0.35) !important;
  }
  .cal-celda.seleccionada {
    background: rgba(168,85,247,0.2) !important;
    border-color: var(--magenta) !important;
    box-shadow: 0 0 16px rgba(168,85,247,0.25);
  }

  /* Punto indicador de evento */
  .evento-chip {
    width: 6px; height: 6px; border-radius: 50%;
    background: var(--magenta);
    display: inline-block;
    box-shadow: 0 0 5px rgba(168,85,247,0.65);
    flex-shrink: 0;
  }

  /* Tarjeta de evento en el panel lateral */
  .panel-evento-card {
    display: flex; gap: 14px; padding: 14px;
    background: rgba(245,241,234,0.04);
    border: 1px solid var(--line);
    border-radius: 10px;
    text-decoration: none;
    transition: background 0.2s, border-color 0.2s;
    margin-bottom: 10px;
  }
  .panel-evento-card:hover {
    background: rgba(168,85,247,0.1);
    border-color: rgba(168,85,247,0.4);
  }

  /* Layout responsive */
  #cal-layout {
    display: grid;
    grid-template-columns: 1fr;
    gap: 28px;
  }
  @media (min-width: 1024px) {
    #cal-layout { grid-template-columns: 1fr 360px; }
  }
</style>

{{-- Datos de eventos embebidos para JS --}}
<script>window.EVENTOS_MES = @json($eventosParaCalendario);</script>

{{-- Navegación VIBEZ --}}
@include('partials.home.nav')

<div style="min-height:100vh;background:var(--bg);padding:48px 0 80px;">
  <div style="max-width:1260px;margin:0 auto;padding:0 24px;">

    {{-- ── CABECERA ──────────────────────────────────────────── --}}
    <div style="display:flex;align-items:flex-end;justify-content:space-between;flex-wrap:wrap;gap:16px;margin-bottom:40px;">
      <div>
        <p style="font-family:'Archivo Narrow',sans-serif;font-size:11px;text-transform:uppercase;letter-spacing:0.18em;color:var(--magenta);margin:0 0 6px;">Próximos eventos</p>
        <h1 style="font-family:'Bebas Neue',sans-serif;font-size:clamp(2.5rem,5vw,4rem);color:var(--ink);margin:0;line-height:1;letter-spacing:0.04em;">CALENDARIO</h1>
      </div>

      {{-- Navegación de mes --}}
      <div style="display:flex;align-items:center;gap:20px;">
        <a href="{{ route('calendario', ['mes' => $mesPrev->month, 'anio' => $mesPrev->year]) }}"
           style="display:flex;align-items:center;justify-content:center;width:42px;height:42px;border-radius:50%;background:rgba(245,241,234,0.05);border:1px solid var(--line);color:var(--ink);text-decoration:none;font-size:20px;transition:all 0.2s;"
           onmouseover="this.style.background='rgba(168,85,247,0.18)';this.style.borderColor='var(--magenta)'"
           onmouseout="this.style.background='rgba(245,241,234,0.05)';this.style.borderColor='var(--line)'">‹</a>

        <div style="text-align:center;min-width:190px;">
          <div style="font-family:'Bebas Neue',sans-serif;font-size:2.2rem;color:var(--ink);line-height:1;letter-spacing:0.06em;text-transform:uppercase;">
            {{ $inicio->locale('es')->isoFormat('MMMM') }}
          </div>
          <div style="font-family:'Archivo Narrow',sans-serif;font-size:12px;color:var(--ink-dim);letter-spacing:0.14em;text-transform:uppercase;margin-top:2px;">
            {{ $anio }}
          </div>
        </div>

        <a href="{{ route('calendario', ['mes' => $mesSig->month, 'anio' => $mesSig->year]) }}"
           style="display:flex;align-items:center;justify-content:center;width:42px;height:42px;border-radius:50%;background:rgba(245,241,234,0.05);border:1px solid var(--line);color:var(--ink);text-decoration:none;font-size:20px;transition:all 0.2s;"
           onmouseover="this.style.background='rgba(168,85,247,0.18)';this.style.borderColor='var(--magenta)'"
           onmouseout="this.style.background='rgba(245,241,234,0.05)';this.style.borderColor='var(--line)'">›</a>
      </div>
    </div>

    {{-- ── CUADRÍCULA + PANEL LATERAL ──────────────────────── --}}
    <div id="cal-layout">

      {{-- CUADRÍCULA DEL MES --}}
      <div>
        {{-- Cabecera días de la semana --}}
        <div style="display:grid;grid-template-columns:repeat(7,1fr);gap:4px;margin-bottom:8px;">
          @foreach(['Lun','Mar','Mié','Jue','Vie','Sáb','Dom'] as $nombreDia)
            <div style="text-align:center;font-family:'Archivo Narrow',sans-serif;font-size:11px;text-transform:uppercase;letter-spacing:0.12em;color:var(--ink-dim);padding:8px 4px;">{{ $nombreDia }}</div>
          @endforeach
        </div>

        {{-- Celdas del calendario --}}
        @php
          $hoy          = now()->day;
          $mesHoy       = now()->month;
          $anioHoy      = now()->year;
          /* Carbon: dayOfWeek 0=Dom,1=Lun,...,6=Sáb. Para cuadrícula Lun-Dom: offset = (dow+6)%7 */
          $offsetInicio = ($inicio->dayOfWeek + 6) % 7;
          $diasEnMes    = $inicio->daysInMonth;
        @endphp

        <div style="display:grid;grid-template-columns:repeat(7,1fr);gap:4px;">
          {{-- Celdas vacías al inicio del mes --}}
          @for($i = 0; $i < $offsetInicio; $i++)
            <div style="aspect-ratio:1;"></div>
          @endfor

          {{-- Días del mes --}}
          @for($dia = 1; $dia <= $diasEnMes; $dia++)
            @php
              $tieneEventos = isset($eventosPorDia[$dia]) && count($eventosPorDia[$dia]) > 0;
              $esHoy        = ($dia === $hoy && $mes === $mesHoy && $anio === $anioHoy);
              $numEventos   = $tieneEventos ? count($eventosPorDia[$dia]) : 0;
            @endphp
            <div
              id="cel-{{ $dia }}"
              class="cal-celda{{ $tieneEventos ? ' tiene-eventos' : '' }}{{ $esHoy ? ' hoy' : '' }}"
              onclick="{{ $tieneEventos ? 'seleccionarDia('.$dia.')' : '' }}"
              style="aspect-ratio:1;border:1px solid {{ $esHoy ? 'rgba(168,85,247,0.5)' : 'var(--line)' }};border-radius:10px;padding:8px 4px;display:flex;flex-direction:column;align-items:center;justify-content:space-between;background:{{ $esHoy ? 'rgba(124,58,237,0.08)' : 'rgba(245,241,234,0.02)' }};"
            >
              {{-- Número del día --}}
              <span style="font-family:'DM Sans',sans-serif;font-size:13px;font-weight:{{ $esHoy ? '700' : '400' }};color:{{ $tieneEventos || $esHoy ? 'var(--ink)' : 'var(--ink-dim)' }};width:28px;height:28px;display:flex;align-items:center;justify-content:center;border-radius:50%;background:{{ $esHoy ? 'linear-gradient(135deg,#7c3aed,#a855f7)' : 'none' }};">{{ $dia }}</span>

              {{-- Puntos indicadores de eventos --}}
              @if($tieneEventos)
                <div style="display:flex;gap:3px;flex-wrap:wrap;justify-content:center;padding:2px 0;">
                  @for($p = 0; $p < min($numEventos, 3); $p++)
                    <span class="evento-chip"></span>
                  @endfor
                  @if($numEventos > 3)
                    <span style="font-family:'Archivo Narrow',sans-serif;font-size:8px;color:var(--magenta);font-weight:700;line-height:6px;">+{{ $numEventos - 3 }}</span>
                  @endif
                </div>
              @else
                <div style="height:8px;"></div>
              @endif
            </div>
          @endfor
        </div>

        {{-- Leyenda --}}
        <div style="display:flex;align-items:center;flex-wrap:wrap;gap:20px;margin-top:16px;padding:14px 18px;background:rgba(245,241,234,0.025);border:1px solid var(--line);border-radius:10px;">
          <div style="display:flex;align-items:center;gap:8px;">
            <span class="evento-chip"></span>
            <span style="font-family:'Archivo Narrow',sans-serif;font-size:11px;color:var(--ink-dim);letter-spacing:0.06em;">Evento programado</span>
          </div>
          <div style="display:flex;align-items:center;gap:8px;">
            <span style="width:22px;height:22px;border-radius:50%;background:linear-gradient(135deg,#7c3aed,#a855f7);display:inline-flex;align-items:center;justify-content:center;font-size:10px;font-family:'DM Sans',sans-serif;color:#fff;font-weight:700;">{{ now()->day }}</span>
            <span style="font-family:'Archivo Narrow',sans-serif;font-size:11px;color:var(--ink-dim);letter-spacing:0.06em;">Hoy</span>
          </div>
          <div style="margin-left:auto;">
            <span style="font-family:'Archivo Narrow',sans-serif;font-size:11px;color:var(--ink-dim);">
              {{ $eventos->count() }} evento{{ $eventos->count() !== 1 ? 's' : '' }} este mes
            </span>
          </div>
        </div>
      </div>

      {{-- PANEL LATERAL: eventos del día seleccionado --}}
      <div id="cal-panel" style="background:rgba(245,241,234,0.025);border:1px solid var(--line);border-radius:14px;padding:24px;align-self:start;min-height:280px;">

        {{-- Estado vacío --}}
        <div id="panel-vacio" style="display:flex;flex-direction:column;align-items:center;justify-content:center;padding:40px 0;text-align:center;">
          <div style="width:54px;height:54px;border-radius:50%;background:rgba(168,85,247,0.1);border:1px solid rgba(168,85,247,0.25);display:flex;align-items:center;justify-content:center;margin-bottom:16px;">
            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="#a855f7" stroke-width="1.5">
              <rect x="3" y="4" width="18" height="18" rx="2"/>
              <line x1="16" y1="2" x2="16" y2="6"/>
              <line x1="8" y1="2" x2="8" y2="6"/>
              <line x1="3" y1="10" x2="21" y2="10"/>
            </svg>
          </div>
          <p style="font-family:'DM Sans',sans-serif;font-size:14px;color:var(--ink-dim);margin:0 0 6px;">Selecciona un día</p>
          <p style="font-family:'Archivo Narrow',sans-serif;font-size:12px;color:rgba(245,241,234,0.3);margin:0;">Los eventos aparecerán aquí</p>
        </div>

        {{-- Contenido con eventos del día --}}
        <div id="panel-contenido" style="display:none;">
          <div id="panel-titulo" style="font-family:'Bebas Neue',sans-serif;font-size:1.5rem;color:var(--ink);letter-spacing:0.06em;line-height:1;margin-bottom:4px;"></div>
          <p id="panel-subtitulo" style="font-family:'Archivo Narrow',sans-serif;font-size:11px;text-transform:uppercase;letter-spacing:0.12em;color:var(--magenta);margin:0 0 20px;"></p>
          <div id="panel-lista"></div>
        </div>
      </div>
    </div>

    {{-- ── LISTA COMPLETA DEL MES ─────────────────────────── --}}
    @if($eventos->count() > 0)
      <div style="margin-top:56px;">
        <h2 style="font-family:'Bebas Neue',sans-serif;font-size:1.8rem;color:var(--ink);margin:0 0 24px;letter-spacing:0.06em;">
          TODOS LOS EVENTOS — <span style="color:var(--magenta);">{{ strtoupper($inicio->locale('es')->isoFormat('MMMM YYYY')) }}</span>
        </h2>
        <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(270px,1fr));gap:18px;">
          @foreach($eventos as $evento)
            <a href="{{ route('eventos.detalle', $evento->id) }}"
               style="text-decoration:none;display:block;background:rgba(245,241,234,0.025);border:1px solid var(--line);border-radius:14px;overflow:hidden;transition:all 0.2s;"
               onmouseover="this.style.borderColor='rgba(168,85,247,0.4)';this.style.background='rgba(168,85,247,0.06)'"
               onmouseout="this.style.borderColor='var(--line)';this.style.background='rgba(245,241,234,0.025)'">

              {{-- Portada con fecha --}}
              <div style="position:relative;aspect-ratio:16/7;overflow:hidden;">
                <img src="{{ $evento->url_portada }}" alt="{{ $evento->titulo }}"
                     style="width:100%;height:100%;object-fit:cover;">
                <div style="position:absolute;inset:0;background:linear-gradient(to bottom,transparent 25%,rgba(7,6,12,0.8));"></div>
                <div style="position:absolute;top:10px;left:10px;background:rgba(168,85,247,0.85);backdrop-filter:blur(8px);border-radius:6px;padding:4px 10px;">
                  <span style="font-family:'Archivo Narrow',sans-serif;font-size:11px;text-transform:uppercase;letter-spacing:0.1em;color:#fff;font-weight:700;">
                    {{ \Carbon\Carbon::parse($evento->fecha_inicio)->locale('es')->isoFormat('D MMM') }}
                  </span>
                </div>
              </div>

              {{-- Info del evento --}}
              <div style="padding:14px 16px;">
                <p style="font-family:'Archivo Narrow',sans-serif;font-size:10px;text-transform:uppercase;letter-spacing:0.1em;color:var(--magenta);margin:0 0 5px;">
                  {{ $evento->categorias->pluck('nombre')->join(' · ') ?: 'Evento' }}
                </p>
                <h3 style="font-family:'DM Sans',sans-serif;font-size:15px;font-weight:700;color:var(--ink);margin:0 0 10px;line-height:1.3;display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden;">
                  {{ $evento->titulo }}
                </h3>
                <div style="display:flex;align-items:center;justify-content:space-between;gap:8px;">
                  <span style="font-family:'Archivo Narrow',sans-serif;font-size:11px;color:var(--ink-dim);white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">
                    {{ \Carbon\Carbon::parse($evento->fecha_inicio)->format('H:i') }}
                    @if($evento->ubicacion_nombre)
                      · {{ Str::limit($evento->ubicacion_nombre, 22) }}
                    @endif
                  </span>
                  <span style="font-family:'Anton',sans-serif;font-size:14px;color:var(--magenta);letter-spacing:0.04em;flex-shrink:0;">
                    {{ $evento->precio_formateado }}
                  </span>
                </div>
              </div>
            </a>
          @endforeach
        </div>
      </div>
    @else
      <div style="margin-top:56px;text-align:center;padding:60px 0;">
        <div style="width:68px;height:68px;border-radius:50%;background:rgba(168,85,247,0.08);border:1px solid rgba(168,85,247,0.2);display:flex;align-items:center;justify-content:center;margin:0 auto 20px;">
          <svg width="30" height="30" viewBox="0 0 24 24" fill="none" stroke="#a855f7" stroke-width="1.5">
            <rect x="3" y="4" width="18" height="18" rx="2"/>
            <line x1="16" y1="2" x2="16" y2="6"/>
            <line x1="8" y1="2" x2="8" y2="6"/>
            <line x1="3" y1="10" x2="21" y2="10"/>
          </svg>
        </div>
        <p style="font-family:'DM Sans',sans-serif;font-size:16px;color:var(--ink-dim);margin:0 0 10px;">No hay eventos este mes</p>
        <a href="{{ route('eventos.index') }}"
           style="font-family:'Archivo Narrow',sans-serif;font-size:12px;color:var(--magenta);text-decoration:none;text-transform:uppercase;letter-spacing:0.1em;">
          Ver todos los eventos →
        </a>
      </div>
    @endif

  </div>
</div>

{{-- ── JAVASCRIPT ─────────────────────────────────────────── --}}
<script>
/* Día actualmente seleccionado */
var _diaSeleccionado = null;

/* Nombres de los meses en español */
var _meses = ['enero','febrero','marzo','abril','mayo','junio','julio','agosto','septiembre','octubre','noviembre','diciembre'];

/**
 * Selecciona un día del calendario y muestra sus eventos en el panel lateral.
 * Se llama via onclick desde cada celda con eventos.
 */
function seleccionarDia(dia) {
  /* Quitar estilo seleccionado del día anterior */
  if (_diaSeleccionado) {
    var anterior = document.getElementById('cel-' + _diaSeleccionado);
    if (anterior) anterior.classList.remove('seleccionada');
  }

  /* Pulsar el mismo día lo deselecciona */
  if (_diaSeleccionado === dia) {
    _diaSeleccionado = null;
    document.getElementById('panel-vacio').style.display = 'flex';
    document.getElementById('panel-contenido').style.display = 'none';
    return;
  }

  _diaSeleccionado = dia;
  var celda = document.getElementById('cel-' + dia);
  if (celda) celda.classList.add('seleccionada');

  var eventos = window.EVENTOS_MES[dia] || [];
  if (!eventos.length) return;

  /* Título del panel */
  var nombreMes = _meses[{{ $mes }} - 1];
  document.getElementById('panel-titulo').textContent =
    dia + ' de ' + nombreMes.charAt(0).toUpperCase() + nombreMes.slice(1);
  document.getElementById('panel-subtitulo').textContent =
    eventos.length + ' evento' + (eventos.length !== 1 ? 's' : '');

  /* Construir tarjetas de eventos */
  var html = '';
  eventos.forEach(function(ev) {
    var horaStr = ev.horaFin ? ev.hora + ' — ' + ev.horaFin : ev.hora;
    html += '<a href="' + ev.url + '" class="panel-evento-card">';
    html += '<img src="' + ev.img + '" alt="portada" style="width:68px;height:68px;object-fit:cover;border-radius:8px;flex-shrink:0;">';
    html += '<div style="flex:1;min-width:0;">';
    html += '<p style="font-family:\'Archivo Narrow\',sans-serif;font-size:10px;text-transform:uppercase;letter-spacing:0.1em;color:var(--magenta);margin:0 0 4px;">' + ev.categoria + '</p>';
    html += '<p style="font-family:\'DM Sans\',sans-serif;font-size:13px;font-weight:700;color:var(--ink);margin:0 0 6px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">' + ev.titulo + '</p>';
    html += '<div style="display:flex;align-items:center;justify-content:space-between;gap:6px;">';
    html += '<span style="font-family:\'Archivo Narrow\',sans-serif;font-size:11px;color:var(--ink-dim);">' + horaStr + '</span>';
    html += '<span style="font-family:\'Anton\',sans-serif;font-size:13px;color:var(--magenta);flex-shrink:0;">' + ev.precio + '</span>';
    html += '</div>';
    if (ev.lugar) {
      html += '<p style="font-family:\'Archivo Narrow\',sans-serif;font-size:10px;color:rgba(245,241,234,0.35);margin:4px 0 0;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">' + ev.lugar + '</p>';
    }
    html += '</div></a>';
  });

  document.getElementById('panel-lista').innerHTML = html;
  document.getElementById('panel-vacio').style.display = 'none';
  document.getElementById('panel-contenido').style.display = 'block';

  /* En móvil, hacer scroll suave al panel */
  if (window.innerWidth < 1024) {
    document.getElementById('cal-panel').scrollIntoView({ behavior: 'smooth', block: 'start' });
  }
}
</script>

@endsection
