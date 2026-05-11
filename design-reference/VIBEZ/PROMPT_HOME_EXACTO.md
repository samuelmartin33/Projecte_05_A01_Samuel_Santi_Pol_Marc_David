# Prompt — Home VIBEZ pixel-perfect

Pega esto en Claude Code dentro de tu proyecto Laravel. **Antes**: copia los archivos de referencia a `design-reference/` en la raíz:

```
design-reference/
  vibez-home.html
  components.jsx
  app.jsx
  tweaks-panel.jsx
  auth.css
  assets/logo_vibez.png
```

---

Necesito que `resources/views/home.blade.php` se vea **exactamente igual** que el prototipo en `design-reference/vibez-home.html`. No interpretes, no simplifiques, no "mejores": replica.

## Paso 1 — Lee TODO antes de tocar código

Lee en orden y por completo (no parcial):

1. `design-reference/vibez-home.html` (584 líneas) — estructura HTML, `<style>` con todas las CSS vars, animaciones, media queries, marquee, parallax, leaflet pins, hero variants
2. `design-reference/components.jsx` — componentes React: `VibezNav`, `HeroPoster`, `Marquee`, `ChipBar`, `EventCard`, `Carousel`, `MoodSelector`, `MapEventos`, `DetailModal`, `VibezFooter`, `LoggedHero`, `MisTickets`, `ParaTi`
3. `design-reference/app.jsx` — orquestación: estado, filtros por categoría/mood, datos de usuario mock, secciones logueado vs público

Después confirma que entiendes cada componente y dime si falta algo antes de generar Blade.

## Paso 2 — Reglas duras

- **Vista única `home.blade.php`** que renderiza:
  - Si `@auth`: `LoggedHero` + `MisTickets` + `ParaTi` + Marquee + Carousel "Lo que rompe" + MoodSelector + MapEventos + CTA promotor + Footer
  - Si `@guest`: `HeroPoster` (evento featured) + Marquee + Carousel + MoodSelector + MapEventos + CTA promotor + Footer
- **Sin React, sin Babel, sin JSX en producción.** Convierte cada componente a HTML + Blade `@foreach`. Las animaciones (parallax, marquee, mood toggles, modal, mapa) en JS vanilla con funciones globales y `onclick="..."` inline. **Prohibido `addEventListener`** (regla del proyecto).
- **CSS:** copia el bloque `<style>` completo de `vibez-home.html` (líneas 19–408 aprox) a un archivo nuevo `public/css/vibez-home.css`. No omitas ninguna regla, ni siquiera las animaciones (`marquee`, `pulse-dot`, `twinkle`, `pulse-glow`), ni las variantes `body[data-aesthetic="..."]`, ni las media queries de `1100px / 780px / 480px`.
- **Fuentes:** añade en `layouts/app.blade.php` (head):
  ```html
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Anton&family=Bebas+Neue&family=Archivo:wght@400;500;600;700;800;900&family=Archivo+Narrow:wght@400;500;600;700&display=swap" rel="stylesheet">
  ```
- **Leaflet** (mapa): incluye CSS y JS desde unpkg como en el HTML de referencia. Si el mapa rompe, déjalo opcional (toggle por config) pero no quites el bloque CSS de `.leaflet-container` y `.vibez-pin`.
- **Logo:** copia `design-reference/assets/logo_vibez.png` a `public/images/logo_vibez.png` y úsalo con `{{ asset('images/logo_vibez.png') }}`.
- **Body:** `<body class="grain" data-aesthetic="italo" data-hero="poster">` — el grain noise SVG y los data-attrs son críticos para el look.

## Paso 3 — Mapeo de componentes a Blade

Para cada componente JSX, crea un partial en `resources/views/partials/home/`:

- `nav.blade.php` ← `VibezNav` (recibe `$user` o null)
- `hero-poster.blade.php` ← `HeroPoster` (recibe `$evento` featured + countdown JS)
- `logged-hero.blade.php` ← `LoggedHero` (recibe `$user`, `$proxEvento`)
- `mis-tickets.blade.php` ← `MisTickets` (recibe `$tickets` collection)
- `para-ti.blade.php` ← `ParaTi` (recibe `$recomendados` collection)
- `marquee.blade.php` ← `Marquee` (items array)
- `carousel.blade.php` ← `Carousel` (recibe `$eventos`, `$kicker`, `$title`, `$subtitle`)
- `mood-selector.blade.php` ← `MoodSelector` (moods estáticos)
- `map-eventos.blade.php` ← `MapEventos` (recibe `$eventos` con coords)
- `detail-modal.blade.php` ← `DetailModal` (oculto por defecto, se rellena con JS al abrir)
- `footer.blade.php` ← `VibezFooter`

`home.blade.php` solo hace `@include` de los partials y pasa los datos del controller.

## Paso 4 — Datos del controlador

`HomeController@index` debe pasar:
```php
return view('home', [
    'eventoFeatured' => Evento::where('featured', true)->first() ?? Evento::orderBy('fecha')->first(),
    'eventos' => Evento::where('id', '!=', $featured->id)->orderBy('fecha')->take(10)->get(),
    'tickets' => auth()->check() ? auth()->user()->tickets()->with('evento')->where('fecha','>=', now())->get() : collect(),
    'recomendados' => auth()->check() ? Evento::recomendadosFor(auth()->user()) : collect(),
    'categorias' => ['Todo','Techno','Festival','Disco','Bass','Concierto','Reggaeton','Jazz','Remember'],
    'moods' => [/* mismos 6 del app.jsx */],
]);
```

Si los modelos `Evento`/`Ticket` no tienen estos campos, añade los que falten (`featured`, `tagline`, `coords`, `categoria`, `cupos`, `precio`, `img`, `fechaFmt`).

## Paso 5 — JS interactivo (vanilla, sin frameworks)

Crea `public/js/vibez-home.js` con funciones globales llamadas con `onclick`:

- `vibezOpenModal(idEvento)` / `vibezCloseModal()` — abre/cierra `#detail-modal` y rellena con datos desde `window.EVENTOS_DATA` (impreso por Blade como `<script>window.EVENTOS_DATA = @json($eventos);</script>`)
- `vibezFilterCategoria(cat)` — toggle clase `active` en chips, oculta cards que no matchean `data-categoria`
- `vibezPickMood(moodId)` — igual con `data-mood`
- `vibezBuy(idEvento)` — POST a `/tickets` con CSRF (form oculto)
- Countdown del hero: `setInterval` que actualiza los 4 dígitos
- Parallax del hero: `window.onscroll` (no addEventListener)
- Inicialización Leaflet: función llamada al final del body

Inline en el body:
```html
<script>window.EVENTOS_DATA = @json($eventos); vibezInitMap(); vibezStartCountdown();</script>
```

## Paso 6 — Responsive

Las media queries `@media (max-width: 1100/780/480px)` ya están en el `<style>` de `vibez-home.html`. Cópialas tal cual. **No las cambies.**

## Paso 7 — Verifica

Después de generar todo:
1. Abre `home.blade.php` logueado y desde guest: ambos deben verse exactamente como el prototipo.
2. Compara con screenshots de `vibez-home.html` (puedes abrirlo en navegador).
3. Lista qué se desvió y por qué.

**Empieza ahora**: léete los 3 archivos de referencia completos y dime qué modelos/migraciones necesito tocar en mi proyecto Laravel antes de que generes el Blade.
