@extends('admin.layouts.dashboard')

@section('title', 'Admin | Crear pedido')

@section('content')
    <header class="admin-header">
        <div>
            <h1>Crear Pedido</h1>
            <p>Registra un nuevo pedido manualmente desde el panel.</p>
        </div>
    </header>

    <section class="card">
        <form method="POST" action="{{ route('admin.pedidos.store') }}" class="evento-form">
            @csrf
            @include('admin.pedidos._form')
        </form>
    </section>
@endsection