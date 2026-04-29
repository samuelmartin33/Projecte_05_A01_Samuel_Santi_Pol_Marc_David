@extends('admin.layouts.dashboard')

@section('title', 'Admin | Crear usuario')

@section('content')
    <header class="admin-header">
        <div>
            <h1>Crear Usuario</h1>
            <p>Registra una nueva cuenta desde el panel de administración.</p>
        </div>
    </header>

    <section class="card">
        <form method="POST" action="{{ route('admin.usuarios.store') }}" class="evento-form">
            @csrf
            @include('admin.usuarios._form')
        </form>
    </section>
@endsection