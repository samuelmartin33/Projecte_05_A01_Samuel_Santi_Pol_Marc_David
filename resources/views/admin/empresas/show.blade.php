@extends('admin.layouts.dashboard')

@section('title', 'Admin | Detalle empresa')

{{-- Estilos específicos añadidos en public/css/admin-vibez.css --}}

@section('content')
    <header class="admin-header">
        <div>
            <h1>Solicitud de empresa</h1>
            <p>Revisa los datos del promotor antes de aprobar o rechazar.</p>
        </div>
        <a class="btn btn-secondary" href="{{ route('admin.empresas.index') }}">← Volver al listado</a>
    </header>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if (session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <div class="adm-empresa-grid" style="display:grid;grid-template-columns:1fr 1fr;gap:1.5rem;margin-bottom:1.5rem;">

        {{-- Bloque 1: Datos del responsable --}}
        <section class="card admin-panel-section">
            <div class="panel-header"><h2>Datos del responsable</h2></div>
            <table class="admin-table" style="margin:0">
                <tbody>
                    <tr>
                        <th style="width:40%">Nombre completo</th>
                        <td>{{ $usuario->nombre }} {{ $usuario->apellido1 }} {{ $usuario->apellido2 }}</td>
                    </tr>
                    <tr>
                        <th>Email</th>
                        <td><a href="mailto:{{ $usuario->email }}">{{ $usuario->email }}</a></td>
                    </tr>
                    <tr>
                        <th>Teléfono</th>
                        <td>{{ $usuario->telefono ?? '—' }}</td>
                    </tr>
                    <tr>
                        <th>Fecha nacimiento</th>
                        <td>{{ $usuario->fecha_nacimiento ? \Carbon\Carbon::parse($usuario->fecha_nacimiento)->format('d/m/Y') : '—' }}</td>
                    </tr>
                    <tr>
                        <th>Registrado el</th>
                        <td>{{ \Carbon\Carbon::parse($usuario->fecha_creacion)->format('d/m/Y H:i') }}</td>
                    </tr>
                    <tr>
                        <th>Estado</th>
                        <td>
                            @if ($usuario->estado_registro === 'pendiente')
                                <span class="badge" style="background:#f59e0b;color:#fff;padding:3px 10px;border-radius:999px;font-size:12px;">Pendiente</span>
                            @elseif ($usuario->estado_registro === 'aprobado')
                                <span class="badge badge-success">Aprobada</span>
                            @else
                                <span class="badge badge-danger">Rechazada</span>
                            @endif
                        </td>
                    </tr>
                </tbody>
            </table>
        </section>

        {{-- Bloque 2: Datos de la empresa --}}
        <section class="card admin-panel-section">
            <div class="panel-header"><h2>Datos de la empresa</h2></div>
            @if ($usuario->empresa)
                @php $emp = $usuario->empresa; @endphp
                <table class="admin-table" style="margin:0">
                    <tbody>
                        <tr>
                            <th style="width:40%">Nombre empresa</th>
                            <td>{{ $emp->nombre_empresa ?? '—' }}</td>
                        </tr>
                        <tr>
                            <th>NIF / CIF</th>
                            <td>{{ $emp->nif_cif ?? '—' }}</td>
                        </tr>
                        <tr>
                            <th>Tipo de promotor</th>
                            <td>
                                @php
                                    $tiposPromotor = [
                                        'sala_club'  => 'Sala / Club nocturno',
                                        'promotora'  => 'Promotora de eventos',
                                        'festival'   => 'Festival',
                                        'artista'    => 'Artista / DJ',
                                        'autonomo'   => 'Autónomo',
                                        'otro'       => 'Otro',
                                    ];
                                @endphp
                                {{ $tiposPromotor[$emp->tipo_promotor] ?? ($emp->tipo_promotor ?? '—') }}
                            </td>
                        </tr>
                        <tr>
                            <th>Sitio web</th>
                            <td>
                                @if ($emp->sitio_web)
                                    <a href="{{ $emp->sitio_web }}" target="_blank" rel="noopener">{{ $emp->sitio_web }}</a>
                                @else
                                    —
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th>Descripción</th>
                            <td>{{ $emp->descripcion ?? '—' }}</td>
                        </tr>
                        <tr>
                            <th>Perfil fiscal</th>
                            <td>
                                @if ($emp->perfil_fiscal_completo)
                                    <span class="badge badge-success">Completo</span>
                                @else
                                    <span class="badge" style="background:#64748b;color:#fff;padding:3px 10px;border-radius:999px;font-size:12px;">Pendiente</span>
                                @endif
                            </td>
                        </tr>
                    </tbody>
                </table>
            @else
                <div class="panel-empty">
                    <p class="text-muted">Esta cuenta no tiene registro de empresa asociado.</p>
                </div>
            @endif
        </section>

    </div>

    {{-- Botones de acción (solo si está pendiente) --}}
    @if ($usuario->estado_registro === 'pendiente')
        <section class="card admin-panel-section">
            <div class="panel-header"><h2>Acciones</h2></div>
            <div style="display:flex;gap:12px;padding:12px 0 4px;">
                <form method="POST" action="{{ route('admin.empresas.aprobar', $usuario->id) }}" class="js-confirm-empresa" data-action-label="aprobar" data-empresa="{{ $usuario->nombre }} {{ $usuario->apellido1 }}">
                    @csrf
                    <button type="submit" class="btn btn-success">
                        ✔ Aprobar empresa
                    </button>
                </form>
                <form method="POST" action="{{ route('admin.empresas.rechazar', $usuario->id) }}" class="js-confirm-empresa" data-action-label="rechazar" data-empresa="{{ $usuario->nombre }} {{ $usuario->apellido1 }}">
                    @csrf
                    <button type="submit" class="btn btn-danger">
                        ✖ Rechazar solicitud
                    </button>
                </form>
            </div>
        </section>
    @endif

<script src="{{ asset('js/admin-empresas.js') }}"></script>
@endsection
