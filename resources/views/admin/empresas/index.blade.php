@extends('admin.layouts.dashboard')

@section('title', 'Admin | Empresas')

@section('content')
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
                                    <form method="POST" action="{{ route('admin.empresas.aprobar', $empresa->id) }}" style="display:inline;">
                                        @csrf
                                        <button type="submit" class="btn btn-success btn-sm"
                                            onclick="return confirm('¿Aprobar la cuenta de {{ addslashes($empresa->nombre.' '.$empresa->apellido1) }}?')">
                                            Aprobar
                                        </button>
                                    </form>
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
@endsection
