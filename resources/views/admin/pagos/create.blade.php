@extends('admin.layouts.dashboard')

@section('title', 'Admin | Crear pago')

@section('content')
    <header class="admin-header">
        <div>
            <h1>Crear Pago</h1>
            <p>Registra un nuevo pago manualmente desde el panel.</p>
        </div>
    </header>

    <section class="card">
        <form method="POST" action="{{ route('admin.pagos.store') }}" class="evento-form">
            @csrf
            @include('admin.pagos._form')
        </form>
    </section>
@endsection