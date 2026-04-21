@extends('admin.layouts.dashboard')

@section('title', 'Admin | Editar evento')

@section('content')
    <header class="admin-header">
        <div>
            <h1>Editar Evento #{{ $evento->id }}</h1>
            <p>Actualiza la informacion del evento seleccionado.</p>
        </div>
    </header>

    <section class="card">
        <form method="POST" action="{{ route('admin.eventos.update', $evento) }}" class="evento-form">
            @csrf
            @method('PUT')
            @include('admin.eventos._form')
        </form>
    </section>
@endsection

