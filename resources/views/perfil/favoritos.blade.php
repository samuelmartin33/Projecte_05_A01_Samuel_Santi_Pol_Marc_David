
@extends('layouts.app')

@section('titulo', 'Favoritos — VIBEZ')

@push('estilos')
<link rel="stylesheet" href="{{ asset('css/perfil.css') }}">
@endpush

@section('content')

@include('partials.home.nav')

<section class="perfil-hero">
  <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-6 flex flex-col sm:flex-row items-center sm:items-end gap-4">

    <div style="position:relative; display:inline-block; flex-shrink:0;">
      <div class="perfil-avatar-wrap">
        <div class="perfil-avatar">
          @if($usuario->foto_url)
            <img src="{{ $usuario->foto_url }}" alt="{{ $usuario->nombre }}">
          @else
            <span class="perfil-avatar-iniciales">{{ strtoupper(substr($usuario->nombre,0,1)) }}{{ strtoupper(substr($usuario->apellido1 ?? '',0,1)) }}</span>
          @endif
        </div>
      </div>
    </div>

    <div class="flex-1">
      <h1 class="text-xl sm:text-2xl font-black text-white">{{ $usuario->nombre }} {{ $usuario->apellido1 }}</h1>
      <p class="text-white/50 text-sm mt-1">{{ $usuario->email }}</p>
      @if($usuario->biografia)
        <p class="perfil-bio-hero">{{ $usuario->biografia }}</p>
      @endif
    </div>

  </div>
</section>

<div class="perfil-page-wrap">
  <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 pt-2">
  @if(session('exito'))
    <div class="perfil-alerta perfil-alerta-ok">✓ {{ session('exito') }}</div>
  @endif

  @if($errors->any())
    <div class="perfil-alerta perfil-alerta-error">
      @foreach($errors->all() as $error)
        {{ $error }}<br>
      @endforeach
    </div>
  @endif
  </div>

  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 flex flex-col gap-6">

  <div class="perfil-card">
    <h2 class="perfil-card-titulo">Tus favoritos</h2>
    <p class="perfil-card-sub">Eventos que has guardado para más tarde</p>

    {{-- Chips de categoría para filtrar favoritos --}}
    <div style="margin-top:1.2rem;display:flex;gap:8px;flex-wrap:wrap;align-items:center;">
      <span style="font-family:'Archivo Narrow',sans-serif;font-size:10px;text-transform:uppercase;letter-spacing:0.14em;color:rgba(245,241,234,0.4);margin-right:4px;">Filtrar:</span>
      <button class="chip active vibez-cat-chip" data-cat="Todo"
              onclick="filtrarFavoritos('Todo')">Todo</button>
      @foreach($categorias as $cat)
        <button class="chip vibez-cat-chip" data-cat="{{ $cat->nombre }}"
                onclick="filtrarFavoritos('{{ $cat->nombre }}')">{{ $cat->nombre }}</button>
      @endforeach
    </div>

    <div style="margin-top:1rem;">
      <script>window.FAVORITOS_IDS = @json($favoritosIds ?? []);</script>
      
      @include('partials.home.grid-eventos')
    </div>
  </div>

  </div>
</div>

@endsection

@section('scripts')
  <script src="{{ asset('js/favoritos.js') }}"></script>
  <script src="{{ asset('js/perfil.js') }}"></script>
@endsection

@push('scripts')
<script>
  window.vibezGridLimpiar = function() {
  window.vibezFiltrarCiudad = function() {
</script>
<script src="{{ asset('js/perfil-favoritos.js') }}"></script>
{{-- JS en public/js/perfil-favoritos.js --}}
@endpush
