@extends('admin.layouts.dashboard')

@section('title', 'Admin | Empresas')

@section('content')

{{-- ══════════════════════════════════════════════════════
     MODAL DE CONFIRMACIÓN — Aprobar empresa
     Se muestra al hacer clic en "Aprobar"; el admin confirma
     antes de enviar el formulario POST definitivo.
══════════════════════════════════════════════════════ --}}
<div id="modalAprobar"
     style="display:none;position:fixed;inset:0;z-index:9999;background:rgba(0,0,0,0.7);backdrop-filter:blur(4px);align-items:center;justify-content:center;">
  <div style="background:#0f172a;border:1px solid rgba(168,85,247,0.3);border-radius:16px;padding:32px;max-width:440px;width:90%;box-shadow:0 24px 60px rgba(0,0,0,0.6);">
    <div style="width:52px;height:52px;border-radius:50%;background:rgba(16,185,129,0.15);border:1px solid rgba(16,185,129,0.4);display:flex;align-items:center;justify-content:center;margin:0 auto 20px;font-size:22px;">✔</div>
    <h2 style="font-family:'Anton',sans-serif;font-size:22px;color:#f1f5f9;text-align:center;margin:0 0 8px;letter-spacing:0.02em;">¿Aprobar esta empresa?</h2>
    <p style="font-family:'Archivo Narrow',sans-serif;font-size:14px;color:rgba(241,245,249,0.6);text-align:center;margin:0 0 6px;">
      Empresa: <strong id="modalAprobarNombre" style="color:#f1f5f9;">—</strong>
    </p>
    <p style="font-family:'Archivo Narrow',sans-serif;font-size:12px;color:rgba(241,245,249,0.4);text-align:center;margin:0 0 28px;">
      La cuenta quedará activa y el promotor recibirá acceso inmediato.
    </p>
    <div style="display:flex;gap:12px;justify-content:center;">
      <button onclick="cerrarModalAprobar()"
              style="padding:10px 24px;background:rgba(241,245,249,0.06);border:1px solid rgba(241,245,249,0.15);border-radius:999px;color:#94a3b8;font-family:'Archivo Narrow',sans-serif;font-size:13px;text-transform:uppercase;letter-spacing:0.06em;cursor:pointer;">
        Cancelar
      </button>
      <form id="formAprobar" method="POST" action="" style="display:inline;">
        @csrf
        <button type="submit"
                style="padding:10px 28px;background:linear-gradient(135deg,#059669,#10b981);border:none;border-radius:999px;color:#fff;font-family:'Anton',sans-serif;font-size:14px;letter-spacing:0.04em;cursor:pointer;box-shadow:0 4px 16px rgba(16,185,129,0.35);">
          Confirmar aprobación
        </button>
      </form>
    </div>
  </div>
</div>
    <header class="admin-header">
        <div>
            <h1>Gestión de Empresas</h1>
            <p>Aprueba o rechaza las solicitudes de registro de cuentas de empresa.</p>
        </div>
        <a class="btn btn-secondary" href="{{ route('admin.dashboard') }}">Volver al inicio</a>
    </header>

    {{-- Alertas de resultado --}}
    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if (session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    {{-- Solicitudes pendientes --}}
    <section class="card admin-panel-section" style="margin-bottom: 2rem;">
        <div class="panel-header">
            <h2>
                Solicitudes pendientes
                @if ($pendientes->count() > 0)
                    <span class="badge-count">{{ $pendientes->count() }}</span>
                @endif
            </h2>
        </div>

        @if ($pendientes->isEmpty())
            <div class="panel-empty">
                <p class="text-muted">No hay solicitudes pendientes en este momento.</p>
            </div>
        @else
            <div class="table-wrap">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Nombre</th>
                            <th>Email</th>
                            <th>Teléfono</th>
                            <th>Fecha solicitud</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($pendientes as $empresa)
                            <tr>
                                <td data-label="#">{{ $empresa->id }}</td>
                                <td data-label="Nombre">{{ $empresa->nombre }} {{ $empresa->apellido1 }} {{ $empresa->apellido2 }}</td>
                                <td data-label="Email">{{ $empresa->email }}</td>
                                <td data-label="Teléfono">{{ $empresa->telefono ?? '—' }}</td>
                                <td data-label="Fecha solicitud">{{ \Carbon\Carbon::parse($empresa->fecha_creacion)->format('d/m/Y H:i') }}</td>
                                <td data-label="Acciones" class="actions-cell">
                                    <a href="{{ route('admin.empresas.show', $empresa->id) }}" class="btn btn-secondary btn-sm">
                                        Ver detalle
                                    </a>
                                    {{-- Aprobar: abre modal de confirmación --}}
                                    <button type="button" class="btn btn-success btn-sm"
                                        onclick="abrirModalAprobar('{{ route('admin.empresas.aprobar', $empresa->id) }}', '{{ addslashes($empresa->nombre.' '.$empresa->apellido1) }}')">
                                        Aprobar
                                    </button>
                                    <form method="POST" action="{{ route('admin.empresas.rechazar', $empresa->id) }}" style="display:inline;">
                                        @csrf
                                        <button type="submit" class="btn btn-danger btn-sm"
                                            onclick="return confirm('¿Rechazar la solicitud de {{ addslashes($empresa->nombre.' '.$empresa->apellido1) }}?')">
                                            Rechazar
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </section>

    {{-- Historial de solicitudes gestionadas --}}
    <section class="card admin-panel-section">
        <div class="panel-header">
            <h2>Historial</h2>
        </div>

        @if ($gestionadas->isEmpty())
            <div class="panel-empty">
                <p class="text-muted">Todavía no hay empresas gestionadas.</p>
            </div>
        @else
            <div class="table-wrap">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Nombre</th>
                            <th>Email</th>
                            <th>Teléfono</th>
                            <th>Fecha solicitud</th>
                            <th>Estado</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($gestionadas as $empresa)
                            <tr>
                                <td data-label="#">{{ $empresa->id }}</td>
                                <td data-label="Nombre">{{ $empresa->nombre }} {{ $empresa->apellido1 }} {{ $empresa->apellido2 }}</td>
                                <td data-label="Email">{{ $empresa->email }}</td>
                                <td data-label="Teléfono">{{ $empresa->telefono ?? '—' }}</td>
                                <td data-label="Fecha solicitud">{{ \Carbon\Carbon::parse($empresa->fecha_creacion)->format('d/m/Y H:i') }}</td>
                                <td data-label="Estado">
                                    @if ($empresa->estado_registro === 'aprobado')
                                        <span class="badge badge-success">Aprobada</span>
                                    @else
                                        <span class="badge badge-danger">Rechazada</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </section>
<script>
function abrirModalAprobar(url, nombre) {
    document.getElementById('modalAprobarNombre').textContent = nombre;
    document.getElementById('formAprobar').action = url;
    document.getElementById('modalAprobar').style.display = 'flex';
}
function cerrarModalAprobar() {
    document.getElementById('modalAprobar').style.display = 'none';
}
/* Cerrar modal al hacer clic en el fondo oscuro */
document.getElementById('modalAprobar').onclick = function(e) {
    if (e.target === this) cerrarModalAprobar();
};
</script>
@endsection
