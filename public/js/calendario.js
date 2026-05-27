/**
 * calendario.js — logica del calendario de eventos
 */
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
