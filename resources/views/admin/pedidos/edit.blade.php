@extends('admin.layouts.dashboard')

@section('title', 'Admin | Editar pedido')

@section('content')
    <header class="admin-header">
        <div>
            <h1>Editar Pedido #{{ $pedido->id }}</h1>
            <p>Actualiza los datos del pedido seleccionado.</p>
        </div>
    </header>

    <section class="card">
        <form method="POST" action="{{ route('admin.pedidos.update', $pedido) }}" class="evento-form">
            @csrf
            @method('PUT')
            @include('admin.pedidos._form')
        </form>
    </section>
@endsection