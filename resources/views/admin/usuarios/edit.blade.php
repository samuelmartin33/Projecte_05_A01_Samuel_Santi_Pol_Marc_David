@extends('admin.layouts.dashboard')

@section('title', 'Admin | Editar usuario')

@section('content')
    <header class="admin-header">
        <div>
            <h1>Editar Usuario #{{ $usuario->id }}</h1>
            <p>Actualiza los datos de la cuenta seleccionada.</p>
        </div>
    </header>

    <section class="card">
        <form method="POST" action="{{ route('admin.usuarios.update', $usuario) }}" class="evento-form">
            @csrf
            @method('PUT')
            @include('admin.usuarios._form')
        </form>
    </section>
@endsection