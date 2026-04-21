<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Panel Admin — VIBEZ</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    @vite(['resources/css/admin.css', 'resources/js/admin.js'])
</head>
<body>

<header class="topbar">
    <span class="topbar-brand">VIBEZ <span class="topbar-sub">/ Admin</span></span>
    <div class="topbar-right">
        <span class="topbar-user">{{ Auth::user()->nombre }} (admin)</span>
        <a href="{{ route('index') }}" class="btn-top">Ir al index</a>
        <form method="POST" action="{{ route('api.logout') }}">
            @csrf
            <button class="btn-top" type="submit">Cerrar sesión</button>
        </form>
    </div>
</header>

<main class="main">

    <h1 class="page-title">Verificación de usuarios</h1>
    <p class="page-subtitle">Gestiona las solicitudes de registro pendientes de verificación.</p>

    @if(session('success'))
        <div class="flash flash-success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="flash flash-error">{{ session('error') }}</div>
    @endif
    @if(session('warning'))
        <div class="flash flash-warning">{{ session('warning') }}</div>
    @endif

    <div class="tabs">
        <button class="tab-btn active" data-tab="pendientes">
            Pendientes de verificación
            @if($pendientes->count())
                <span class="tab-badge">{{ $pendientes->count() }}</span>
            @endif
        </button>
        <button class="tab-btn" data-tab="verificados">
            Verificados
            <span class="tab-badge grey">{{ $verificados->count() }}</span>
        </button>
    </div>

    {{-- TAB: Pendientes --}}
    <div id="tab-pendientes" class="tab-panel active">
        <div class="card">
            @if($pendientes->isEmpty())
                <div class="empty-state">
                    <svg width="40" height="40" viewBox="0 0 24 24" fill="none"
                         stroke="#9CA3AF" stroke-width="1.5" style="margin-bottom:.75rem;display:block;margin-inline:auto">
                        <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    No hay solicitudes pendientes de verificación.
                </div>
            @else
                <table>
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Usuario</th>
                            <th>Teléfono</th>
                            <th>F. nacimiento</th>
                            <th>Registrado</th>
                            <th>Acción</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($pendientes as $u)
                        <tr>
                            <td class="muted">{{ $u->id }}</td>
                            <td>
                                <div class="user-name">{{ $u->nombre }} {{ $u->apellido1 }} {{ $u->apellido2 }}</div>
                                <div class="user-email">{{ $u->email }}</div>
                            </td>
                            <td>{{ $u->telefono ?? '—' }}</td>
                            <td>{{ $u->fecha_nacimiento
                                    ? \Carbon\Carbon::parse($u->fecha_nacimiento)->format('d/m/Y')
                                    : '—' }}</td>
                            <td>{{ \Carbon\Carbon::parse($u->fecha_creacion)->format('d/m/Y H:i') }}</td>
                            <td>
                                <form method="POST" action="{{ route('admin.verificar', $u->id) }}">
                                    @csrf
                                    <button class="btn-verificar" type="submit"
                                        onclick="return confirm('¿Verificar la cuenta de {{ addslashes($u->nombre) }}?')">
                                        Verificar
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>
    </div>

    {{-- TAB: Verificados --}}
    <div id="tab-verificados" class="tab-panel">
        <div class="card">
            @if($verificados->isEmpty())
                <div class="empty-state">Ningún usuario verificado todavía.</div>
            @else
                <table>
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Usuario</th>
                            <th>Teléfono</th>
                            <th>Estado</th>
                            <th>Verificado</th>
                            <th>Último acceso</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($verificados as $u)
                        <tr>
                            <td class="muted">{{ $u->id }}</td>
                            <td>
                                <div class="user-name">{{ $u->nombre }} {{ $u->apellido1 }}</div>
                                <div class="user-email">{{ $u->email }}</div>
                            </td>
                            <td>{{ $u->telefono ?? '—' }}</td>
                            <td><span class="badge-ok">✓ Verificado</span></td>
                            <td>{{ \Carbon\Carbon::parse($u->fecha_actualizacion)->format('d/m/Y H:i') }}</td>
                            <td>{{ $u->ultimo_acceso
                                    ? \Carbon\Carbon::parse($u->ultimo_acceso)->format('d/m/Y H:i')
                                    : '—' }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>
    </div>

</main>

</body>
</html>
