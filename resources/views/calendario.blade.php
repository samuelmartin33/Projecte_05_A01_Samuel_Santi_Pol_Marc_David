@extends('layouts.app')

@section('titulo', 'Calendario — VIBEZ')

@section('content')

{{-- CSS externo del calendario --}}
<link rel="stylesheet" href="{{ asset('css/calendario.css') }}">

{{-- Datos de eventos embebidos para JS --}}
<script>window.EVENTOS_MES = @json($eventosParaCalendario);</script>

{{-- Navegación VIBEZ --}}
@include('partials.home.nav')

<div class="cal-wrapper">
  <div class="cal-container">

    {{-- ── CABECERA ──────────────────────────────────────────── --}}
    <div class="cal-cabecera">
      <div>
        <p class="cal-kicker">Próximos eventos</p>
        <h1 class="cal-titulo">CALENDARIO</h1>
      </div>

      {{-- Navegación de mes --}}
      <div class="cal-nav">
        <a href="{{ route('calendario', ['mes' => $mesPrev->month, 'anio' => $mesPrev->year]) }}"
           class="cal-nav-btn" aria-label="Mes anterior">‹</a>

        <div class="cal-mes-display">
          <div class="cal-mes-nombre">
            {{ $inicio->locale('es')->isoFormat('MMMM') }}
          </div>
          <div class="cal-mes-anio">{{ $anio }}</div>
        </div>

        <a href="{{ route('calendario', ['mes' => $mesSig->month, 'anio' => $mesSig->year]) }}"
           class="cal-nav-btn" aria-label="Mes siguiente">›</a>
      </div>
    </div>

    {{-- ── CUADRÍCULA + PANEL LATERAL ──────────────────────── --}}
    <div id="cal-layout">

      {{-- CUADRÍCULA DEL MES --}}
      <div>
        {{-- Cabecera días de la semana --}}
        <div class="cal-semana-header">
          @foreach(['Lun','Mar','Mié','Jue','Vie','Sáb','Dom'] as $nombreDia)
            <div class="cal-dia-nombre">{{ $nombreDia }}</div>
          @endforeach
        </div>

        {{-- Celdas del calendario --}}
        @php
          $hoy          = now()->day;
          $mesHoy       = now()->month;
          $anioHoy      = now()->year;
          $offsetInicio = ($inicio->dayOfWeek + 6) % 7;
          $diasEnMes    = $inicio->daysInMonth;
        @endphp

        <div class="cal-dias-grid">
          {{-- Celdas vacías al inicio del mes --}}
          @for($i = 0; $i < $offsetInicio; $i++)
            <div class="cal-celda-vacia"></div>
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
            >
              {{-- Número del día --}}
              <span class="cal-dia-num{{ $tieneEventos ? ' tiene-eventos' : '' }}{{ $esHoy ? ' es-hoy' : '' }}">
                {{ $dia }}
              </span>

              {{-- Puntos indicadores de eventos --}}
              @if($tieneEventos)
                <div class="cal-chips-wrap">
                  @for($p = 0; $p < min($numEventos, 3); $p++)
                    <span class="evento-chip"></span>
                  @endfor
                  @if($numEventos > 3)
                    <span class="evento-chip-extra">+{{ $numEventos - 3 }}</span>
                  @endif
                </div>
              @else
                <div class="cal-espacio"></div>
              @endif
            </div>
          @endfor
        </div>

        {{-- Leyenda --}}
        <div class="cal-leyenda">
          <div class="cal-leyenda-item">
            <span class="evento-chip"></span>
            <span class="cal-leyenda-txt">Evento programado</span>
          </div>
          <div class="cal-leyenda-item">
            <span class="cal-leyenda-hoy-dot">{{ now()->day }}</span>
            <span class="cal-leyenda-txt">Hoy</span>
          </div>
          <div class="cal-leyenda-total">
            {{ $eventos->count() }} evento{{ $eventos->count() !== 1 ? 's' : '' }} este mes
          </div>
        </div>
      </div>

      {{-- PANEL LATERAL: eventos del día seleccionado --}}
      <div id="cal-panel">

        {{-- Estado vacío --}}
        <div id="panel-vacio">
          <div class="panel-vacio-icon">
            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="#a855f7" stroke-width="1.5">
              <rect x="3" y="4" width="18" height="18" rx="2"/>
              <line x1="16" y1="2" x2="16" y2="6"/>
              <line x1="8" y1="2" x2="8" y2="6"/>
              <line x1="3" y1="10" x2="21" y2="10"/>
            </svg>
          </div>
          <p class="panel-vacio-titulo">Selecciona un día</p>
          <p class="panel-vacio-sub">Los eventos aparecerán aquí</p>
        </div>

        {{-- Contenido con eventos del día --}}
        <div id="panel-contenido">
          <div id="panel-titulo" class="panel-titulo"></div>
          <p id="panel-subtitulo" class="panel-subtitulo"></p>
          <div id="panel-lista"></div>
        </div>
      </div>
    </div>

    {{-- ── LISTA COMPLETA DEL MES ─────────────────────────── --}}
    @if($eventos->count() > 0)
      <div class="cal-lista-mes">
        <h2 class="cal-lista-titulo">
          TODOS LOS EVENTOS — <span>{{ strtoupper($inicio->locale('es')->isoFormat('MMMM YYYY')) }}</span>
        </h2>
        <div class="cal-eventos-grid">
          @foreach($eventos as $evento)
            <a href="{{ route('eventos.detalle', $evento->id) }}" class="cal-evento-card">

              {{-- Portada con fecha --}}
              <div class="cal-evento-portada">
                <img src="{{ $evento->url_portada }}" alt="{{ $evento->titulo }}">
                <div class="cal-evento-portada-overlay"></div>
                <div class="cal-evento-fecha-badge">
                  {{ \Carbon\Carbon::parse($evento->fecha_inicio)->locale('es')->isoFormat('D MMM') }}
                </div>
              </div>

              {{-- Info del evento --}}
              <div class="cal-evento-info">
                <p class="cal-evento-categoria">
                  {{ $evento->categorias->pluck('nombre')->join(' · ') ?: 'Evento' }}
                </p>
                <h3 class="cal-evento-titulo">{{ $evento->titulo }}</h3>
                <div class="cal-evento-footer">
                  <span class="cal-evento-hora">
                    {{ \Carbon\Carbon::parse($evento->fecha_inicio)->format('H:i') }}
                    @if($evento->ubicacion_nombre)
                      · {{ Str::limit($evento->ubicacion_nombre, 22) }}
                    @endif
                  </span>
                  <span class="cal-evento-precio">{{ $evento->precio_formateado }}</span>
                </div>
              </div>
            </a>
          @endforeach
        </div>
      </div>
    @else
      <div class="cal-vacio">
        <div class="cal-vacio-icon">
          <svg width="30" height="30" viewBox="0 0 24 24" fill="none" stroke="#a855f7" stroke-width="1.5">
            <rect x="3" y="4" width="18" height="18" rx="2"/>
            <line x1="16" y1="2" x2="16" y2="6"/>
            <line x1="8" y1="2" x2="8" y2="6"/>
            <line x1="3" y1="10" x2="21" y2="10"/>
          </svg>
        </div>
        <p class="cal-vacio-txt">No hay eventos este mes</p>
        <a href="{{ route('eventos.index') }}" class="cal-vacio-link">Ver todos los eventos →</a>
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
    html += '<p style="font-family:\'Archivo Narrow\',sans-serif;font-size:10px;text-transform:uppercase;letter-spacing:0.1em;color:var(--cal-magenta);margin:0 0 4px;">' + ev.categoria + '</p>';
    html += '<p style="font-family:\'DM Sans\',sans-serif;font-size:13px;font-weight:700;color:var(--cal-ink);margin:0 0 6px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">' + ev.titulo + '</p>';
    html += '<div style="display:flex;align-items:center;justify-content:space-between;gap:6px;">';
    html += '<span style="font-family:\'Archivo Narrow\',sans-serif;font-size:11px;color:var(--cal-ink-dim);">' + horaStr + '</span>';
    html += '<span style="font-family:\'Anton\',sans-serif;font-size:13px;color:var(--cal-magenta);flex-shrink:0;">' + ev.precio + '</span>';
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
