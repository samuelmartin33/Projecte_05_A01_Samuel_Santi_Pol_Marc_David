@extends('layouts.app')

@section('titulo', 'VIBEZ — Descubre tu próximo evento')

{{-- @section('content') suprime el nav/footer del layout --}}
@section('content')

{{-- ── Estilos ── --}}
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css">
<link rel="stylesheet" href="{{ asset('css/vibez-home.css') }}">

{{-- ── Datos globales para JS ── --}}
<script>
  window.EVENTOS_DATA   = @json($eventosParaJs ?? []);
  window.FAVORITOS_IDS  = @json($favoritosIds ?? []);
  window.CATEGORIAS     = @json($categorias->pluck('nombre')->prepend('Todo')->values());
  window.USER_AUTH      = @json(Auth::check());
  window.LOGIN_URL      = @json(route('login'));
</script>

{{-- ════════════════════════════════════════════════════
     NAV
════════════════════════════════════════════════════ --}}
@include('partials.home.nav')

{{-- ════════════════════════════════════════════════════
     HERO — diferente según estado de sesión
════════════════════════════════════════════════════ --}}
@auth
  @include('partials.home.logged-hero', [
    'user'    => Auth::user(),
    'eventos' => $eventos,
  ])
@else
  @include('partials.home.hero-poster', ['eventoFeatured' => $eventoFeatured])
@endauth

{{-- ════════════════════════════════════════════════════
     SECCIONES SOLO PARA USUARIOS AUTENTICADOS
════════════════════════════════════════════════════ --}}
@auth
  {{-- Para Ti: slider de recomendados --}}
  @include('partials.home.para-ti', [
    'user'    => Auth::user(),
    'eventos' => $eventos->take(6),
  ])

  {{-- De tus promotoras: solo si el usuario sigue alguna y hay eventos --}}
  @if(isset($eventosPromotor) && $eventosPromotor->isNotEmpty())
  @include('partials.home.promotoras-seguidas', [
    'eventosPromotor' => $eventosPromotor,
    'seguimientosIds' => $seguimientosIds ?? [],
    'favoritosIds'    => $favoritosIds ?? [],
  ])
  @endif
@endauth

{{-- ════════════════════════════════════════════════════
     MARQUEE
════════════════════════════════════════════════════ --}}
@include('partials.home.marquee')

{{-- ════════════════════════════════════════════════════
     MAPA DE EVENTOS
════════════════════════════════════════════════════ --}}
@include('partials.home.map-eventos', [
  'totalEventos'  => count($eventosParaJs ?? []),
  'eventosParaJs' => $eventosParaJs ?? [],
])

{{-- ════════════════════════════════════════════════════
     FOOTER
════════════════════════════════════════════════════ --}}
@include('partials.home.footer')

{{-- Toast --}}
<div id="vibez-toast" class="toast" style="display:none;"></div>

{{-- ════════════════════════════════════════════════════
     SCRIPTS
════════════════════════════════════════════════════ --}}
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script src="{{ asset('js/vibez-home.js') }}"></script>
<script>
  vibezInitMap();
</script>

@endsection
