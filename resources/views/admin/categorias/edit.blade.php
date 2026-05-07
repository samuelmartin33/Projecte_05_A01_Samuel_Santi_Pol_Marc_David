@extends('admin.layouts.dashboard')

@section('title', 'Admin | Editar categoría')

@section('content')
    <header class="admin-header">
        <div>
            <h1>Editar Categoría</h1>
            <p>Modifica los datos de la categoría seleccionada.</p>
        </div>
    </header>

    <section class="card">
        <form action="{{ route('admin.categorias.update', $categoria) }}" method="POST" class="evento-form">
            @csrf
            @method('PUT')
            @include('admin.categorias._form', ['categoria' => $categoria])
        </form>
    </section>
@endsection
