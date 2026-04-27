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
    <section class="card" style="margin-bottom: 2rem;">
        <h2 style="margin-bottom: 1rem;">
            Solicitudes pendientes
            @if ($pendientes->count() > 0)
                <span class="badge-count">{{ $pendientes->count() }}</span>
            @endif
        </h2>

        @if ($pendientes->isEmpty())
            <p class="text-muted">No hay solicitudes pendientes en este momento.</p>
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
                                <td>{{ $empresa->id }}</td>
                                <td>{{ $empresa->nombre }} {{ $empresa->apellido1 }} {{ $empresa->apellido2 }}</td>
                                <td>{{ $empresa->email }}</td>
                                <td>{{ $empresa->telefono ?? '—' }}</td>
                                <td>{{ \Carbon\Carbon::parse($empresa->fecha_creacion)->format('d/m/Y H:i') }}</td>
                                <td class="actions-cell">
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
    <section class="card">
        <h2 style="margin-bottom: 1rem;">Historial</h2>

        @if ($gestionadas->isEmpty())
            <p class="text-muted">Todavía no hay empresas gestionadas.</p>
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
                                <td>{{ $empresa->id }}</td>
                                <td>{{ $empresa->nombre }} {{ $empresa->apellido1 }} {{ $empresa->apellido2 }}</td>
                                <td>{{ $empresa->email }}</td>
                                <td>{{ $empresa->telefono ?? '—' }}</td>
                                <td>{{ \Carbon\Carbon::parse($empresa->fecha_creacion)->format('d/m/Y H:i') }}</td>
                                <td>
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

    <style>
        .badge-count {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            background: #e53e3e;
            color: #fff;
            font-size: .75rem;
            font-weight: 700;
            min-width: 1.4rem;
            height: 1.4rem;
            border-radius: 999px;
            padding: 0 .4rem;
            margin-left: .5rem;
            vertical-align: middle;
        }
        .table-wrap { overflow-x: auto; }
        .admin-table { width: 100%; border-collapse: collapse; font-size: .875rem; }
        .admin-table th,
        .admin-table td { padding: .6rem 1rem; text-align: left; border-bottom: 1px solid #e2e8f0; }
        .admin-table th { font-weight: 600; color: #4a5568; background: #f7fafc; }
        .admin-table tbody tr:hover { background: #f7fafc; }
        .actions-cell { display: flex; gap: .5rem; align-items: center; flex-wrap: wrap; }
        .btn-sm { padding: .3rem .75rem; font-size: .8rem; border-radius: 4px; border: none; cursor: pointer; font-weight: 600; }
        .btn-success { background: #38a169; color: #fff; }
        .btn-success:hover { background: #2f855a; }
        .btn-danger { background: #e53e3e; color: #fff; }
        .btn-danger:hover { background: #c53030; }
        .badge { display: inline-block; padding: .2rem .6rem; border-radius: 999px; font-size: .75rem; font-weight: 700; }
        .badge-success { background: #c6f6d5; color: #22543d; }
        .badge-danger  { background: #fed7d7; color: #742a2a; }
        .alert { padding: .75rem 1rem; border-radius: 6px; margin-bottom: 1rem; font-size: .9rem; }
        .alert-success { background: #c6f6d5; color: #22543d; border: 1px solid #9ae6b4; }
        .alert-danger  { background: #fed7d7; color: #742a2a; border: 1px solid #fc8181; }
        .text-muted { color: #718096; font-size: .9rem; }
    </style>
@endsection
