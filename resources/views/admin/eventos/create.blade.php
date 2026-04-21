@extends('admin.layouts.dashboard')

@section('title', 'Admin | Crear evento')

@section('content')
    <header class="admin-header">
        <div>
            <h1>Crear Evento</h1>
            <p>Completa los datos para registrar un nuevo evento.</p>
        </div>
    </header>

    <section class="card">
        <form method="POST" action="{{ route('admin.eventos.store') }}" class="evento-form">
            @csrf
            @include('admin.eventos._form')
        </form>
    </section>
@endsection

