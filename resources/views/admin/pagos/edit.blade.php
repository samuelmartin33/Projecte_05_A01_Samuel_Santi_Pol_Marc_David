@extends('admin.layouts.dashboard')

@section('title', 'Admin | Editar pago')

@section('content')
    <header class="admin-header">
        <div>
            <h1>Editar Pago #{{ $pago->id }}</h1>
            <p>Actualiza los datos del pago seleccionado.</p>
        </div>
    </header>

    <section class="card">
        <form method="POST" action="{{ route('admin.pagos.update', $pago) }}" class="evento-form">
            @csrf
            @method('PUT')
            @include('admin.pagos._form')
        </form>
    </section>
@endsection