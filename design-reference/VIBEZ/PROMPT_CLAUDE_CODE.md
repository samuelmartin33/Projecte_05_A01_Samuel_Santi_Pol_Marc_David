# Prompt para Claude Code — Migración VIBEZ a Laravel

Pega este bloque completo en Claude Code, dentro de tu proyecto Laravel, después de haber copiado la carpeta de diseños a `design-reference/` (o a la raíz del proyecto).

---

Tengo el sistema de diseño completo de **VIBEZ**, una plataforma de eventos nocturnos (techno, disco, raves, fiestas en BCN). Antes de tocar nada, **lee TODOS estos archivos** y úsalos como única fuente de verdad visual:

## Archivos de referencia (en `design-reference/` o raíz)

| Archivo | Qué es | Migra a |
|---|---|---|
| `vibez-welcome.html` | **Landing pública** (usuario NO logueado): hero gigante, marquee, grid de eventos, mapa, CTA registro | `resources/views/welcome.blade.php` |
| `vibez-home.html` + `app.jsx` + `components.jsx` | **Home logueado**: saludo personalizado, mis tickets con QR, recomendaciones "Para ti", carrusel top, mood selector. Tiene un tweak `loggedIn` que alterna entre versión pública y logueada — usa la versión LOGUEADA como referencia. | `resources/views/home.blade.php` |
| `vibez-login.html` + `auth.css` | Login con logo VIBEZ, fondo morado oscuro, branding lateral | `resources/views/auth/login.blade.php` |
| `vibez-register.html` + `auth.css` | Registro con tabs Raver / Promotor | `resources/views/auth/register.blade.php` |
| `vibez-admin.html` | Panel admin con sidebar, tabla de eventos, métricas | `resources/views/admin/dashboard.blade.php` |
| `assets/logo_vibez.png` | Logo oficial | `public/images/logo_vibez.png` |

## Sistema de diseño (NO inventar valores)

**Paleta** (todo morado/lila, NUNCA rosa):
```css
--bg: #07060c;          /* fondo casi negro */
--bg-2: #0d0a18;        /* segundo nivel */
--cream: #f5f1e8;       /* texto principal y acentos */
--ink: #f5f1e8;
--ink-dim: #8b8595;     /* texto secundario */
--morado: #7c3aed;      /* primario */
--magenta: #a855f7;     /* acento (alias del morado claro) */
--magenta-2: #c084fc;   /* hover/glow */
--line: rgba(168,85,247,0.3);  /* bordes de cards */
```

**Fuentes** (Google Fonts, cargar en `<head>` del layout principal):
```html
<link href="https://fonts.googleapis.com/css2?family=Anton&family=Bebas+Neue&family=Archivo:wght@400;500;600;700;800;900&family=Archivo+Narrow:wght@400;500;600;700&family=JetBrains+Mono:wght@400;500&display=swap" rel="stylesheet">
```

- **Anton** → display titulares (clamp 56px–132px)
- **Bebas Neue** → acentos itálicos dentro de titulares
- **Archivo Narrow** → uppercase con letter-spacing 0.08–0.1em
- **Archivo** → cuerpo
- **JetBrains Mono** (clase `.mono`) → meta-info, códigos, badges

**Estética**: editorial nocturno, fondo casi negro con grain SVG sutil, tipografía gigante en estilo poster, halos morados suaves (`box-shadow: 0 0 30px rgba(168,85,247,0.15)`), cards con borde `1px solid rgba(168,85,247,0.3)`, radios 14–18px.

**Layout**: `max-width: 1480px`, padding lateral `48px` desktop / `20px` móvil. Grid responsive con `grid-template-columns: repeat(auto-fill, minmax(320px, 1fr))`.

## Reglas DURAS del proyecto (CLAUDE.md)

1. ❌ **PROHIBIDO `addEventListener`** — usar SIEMPRE `onclick="..."` inline en el HTML
2. ❌ NO romper rutas existentes (`route('login')`, `route('register')`, `route('home')`, etc.)
3. ✅ Mantener intactas todas las directivas Blade existentes:
   - `@csrf`, `@method`, `@auth`, `@guest`, `@error`, `@foreach`, `@if`, `@push`, `@yield`
   - `action="{{ route('...') }}"` y `name="..."` de los inputs
   - Variables del controlador (`$eventos`, `$categorias`, `$ubicaciones`, `auth()->user()`, etc.)
4. ✅ JS en funciones globales (no módulos ES6)

## Estructura de archivos a crear

```
public/
├── css/
│   ├── vibez-welcome.css       ← extraer del <style> de vibez-welcome.html
│   ├── vibez-home.css          ← extraer del <style> de vibez-home.html
│   ├── vibez-auth.css          ← copiar auth.css tal cual
│   └── vibez-admin.css         ← extraer del <style> de vibez-admin.html
├── images/
│   └── logo_vibez.png
└── js/
    └── vibez-home.js           ← carrusel, mood selector, modal (vanilla JS, sin addEventListener)
```

En cada Blade, cargar su CSS:
```blade
@push('estilos')
<link rel="stylesheet" href="{{ asset('css/vibez-home.css') }}">
@endpush
```

## Tareas en orden

### 1. `welcome.blade.php` (landing pública)

Toma `vibez-welcome.html` como base. Debe tener:
- Nav superior con logo + botones "Iniciar sesión" / "Registrarse" (rutas Laravel)
- Hero gigante: titular "Tu próxima fiesta empieza ya" con palabra acentuada en itálica morada
- Marquee de palabras clave (techno, disco, rave, BCN…)
- Grid de eventos públicos (`@foreach($eventos as $evento)`) con imagen, fecha, lugar, precio
- Mood selector con chips
- Mapa de eventos (placeholder estático con pins)
- CTA final "Únete a VIBEZ" → `route('register')`
- Footer con créditos

### 2. `home.blade.php` (home logueado)

Toma `vibez-home.html` con `loggedIn: true` como base. Debe tener:
- Nav con avatar (iniciales de `auth()->user()->name`) + contador de tickets activos
- **LoggedHero**: saludo "Hola de nuevo, {nombre}" + tarjeta del próximo evento del usuario
- **Mis Tickets**: 3 últimas entradas con QR mock y código `#VBZ-XXXX` (datos reales desde `auth()->user()->tickets()` o relación equivalente)
- **Para ti**: carrusel horizontal de eventos recomendados con badge `% match`
- **Top eventos**: carrusel principal con `$eventos`
- **Mood selector**: chips de filtro por categoría
- **Mapa** de eventos con `$ubicaciones`
- **Marquee** decorativo
- **Footer**

### 3. `auth/login.blade.php` y `auth/register.blade.php`

- Mantener `@csrf`, `action`, `name` de inputs, `@error('campo')` debajo de cada input
- Aplicar el HTML/CSS de `vibez-login.html` y `vibez-register.html`
- Logo VIBEZ en lugar de imágenes default
- En register, conservar tabs Raver/Promotor con campo `name="tipo_usuario"` (o el que use tu controlador)
- Cargar `vibez-auth.css`

### 4. `admin/dashboard.blade.php`

- Sidebar con secciones (Dashboard, Eventos, Usuarios, Promotores, Tickets)
- Tabla de eventos con `@foreach($eventos as $evento)` y acciones (editar/borrar) usando rutas existentes
- Cards de métricas arriba (total eventos, usuarios, ingresos)

### 5. Responsive

Los HTML de referencia ya traen media queries `@media (max-width: 768px)` y `(max-width: 480px)`. **Cópialas tal cual** al CSS de cada vista — no las rehagas.

## Importante sobre los componentes React

`components.jsx` y `app.jsx` son referencia visual del home logueado. **NO los conviertas a JS** — reescribe los componentes como HTML estático en Blade con `@foreach` para listas. Por ejemplo, `<EventCard>` se vuelve un partial Blade `_event-card.blade.php` que recibe `$evento`.

## Empieza por

1. Lee los 5 HTML de referencia + `auth.css` + `components.jsx`
2. Confírmame qué controladores y modelos existen (`User`, `Evento`, `Ticket`, `Categoria`, `Ubicacion`…) y qué relaciones tienen
3. Empieza por `welcome.blade.php` (es la más visible y la más sencilla) y enséñame el resultado antes de seguir con el resto

No toques rutas, controladores ni migraciones salvo que te lo pida explícitamente. Solo vistas y assets.
