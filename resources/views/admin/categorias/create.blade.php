@extends('admin.layouts.dashboard')

@section('title', 'Admin | Crear categoría')

@section('content')
    <header class="admin-header">
        <div>
            <h1>Crear Categoría</h1>
            <p>Completa los datos para registrar una nueva categoría de evento.</p>
        </div>
    </header>

    <section class="card">
        <form action="{{ route('admin.categorias.store') }}" method="POST" class="evento-form">
            @csrf
            @include('admin.categorias._form', ['categoria' => $categoria])
        </form>
    </section>
@endsection
