# Prompt para Claude Code — Migración VIBEZ a Laravel

Copia el bloque entre las líneas `---` y pégalo en Claude Code dentro de tu proyecto Laravel.

---

Tengo en `design-reference/` (o en la raíz del proyecto) el sistema de diseño completo de **VIBEZ**, una plataforma de eventos nocturnos. Léelo TODO antes de empezar:

**Archivos de referencia:**
- `vibez-welcome.html` → landing pública (usuarios no logueados)
- `vibez-home.html` + `components.jsx` + `app.jsx` → home con tweak `loggedIn` que alterna entre versión pública y logueada
- `vibez-login.html` + `vibez-register.html` + `auth.css` → autenticación
- `vibez-admin.html` → panel admin
- `assets/logo_vibez.png` → logo oficial

**Sistema de diseño (úsalo como única fuente de verdad):**
- Paleta: `--morado: #7c3aed`, `--magenta: #a855f7` (alias del morado, NO rosa), `--bg: #07060c`, `--cream: #f5f1e8`, `--ink: #f5f1e8`, `--ink-dim: #8b8595`
- Fuentes: **Anton** (display titulares), **Bebas Neue** (acentos itálicos), **Archivo Narrow** (uppercase tracking), **Archivo** (cuerpo), **JetBrains Mono** (mono/código)
- Estética: editorial nocturno, fondo casi negro con grain, tipografía gigante, halos sutiles morados, cards con borde `rgba(168,85,247,0.3)`
- Layout: max-width 1480px, padding lateral 48px desktop / 20px móvil

**Tareas:**

1. **Adapta las vistas Blade** a estos diseños. Archivos a modificar:
   - `resources/views/welcome.blade.php` ← desde `vibez-welcome.html`
   - `resources/views/home.blade.php` ← desde `vibez-home.html` (renderiza versión pública o logueada según `auth()->check()`)
   - `resources/views/auth/login.blade.php` ← desde `vibez-login.html`
   - `resources/views/auth/register.blade.php` ← desde `vibez-register.html`
   - `resources/views/admin/dashboard.blade.php` ← desde `vibez-admin.html`

2. **Mantén intacta toda la lógica Laravel existente:**
   - Directivas `@csrf`, `@method`, `@auth`, `@guest`, `@error`, `@foreach`, `@if`
   - `action="{{ route('...') }}"` y nombres de inputs (`name="email"`, `name="password"`, etc.)
   - Variables del controlador (`$eventos`, `$categorias`, `$ubicaciones`, `auth()->user()`, etc.)
   - Validación y mensajes de error de Laravel

3. **Reglas duras del proyecto (CLAUDE.md):**
   - **PROHIBIDO `addEventListener`** — usa `onclick="..."` inline en el HTML
   - JS en funciones globales, no en módulos
   - No romper rutas existentes

4. **Estructura de assets:**
   - CSS en `public/css/vibez-{nombre}.css` (uno por vista)
   - Cárgalos en cada Blade con:
     ```blade
     @push('estilos')
     <link rel="stylesheet" href="{{ asset('css/vibez-home.css') }}">
     @endpush
     ```
   - Fuentes en `<head>` del layout principal (`layouts/app.blade.php`):
     ```html
     <link href="https://fonts.googleapis.com/css2?family=Anton&family=Bebas+Neue&family=Archivo:wght@400;500;600;700;800;900&family=Archivo+Narrow:wght@400;500;600;700&family=JetBrains+Mono:wght@400;500&display=swap" rel="stylesheet">
     ```
   - Logo: copia `assets/logo_vibez.png` a `public/images/logo_vibez.png`

5. **Vista home logueado** (cuando `@auth` sea true): debe incluir
   - Hero personalizado con saludo + tarjeta del próximo evento del usuario
   - Sección "Mis tickets" con QR mock y código `#VBZ-XXXX` (datos reales desde `auth()->user()->tickets`)
   - Sección "Para ti" con recomendaciones (% match)
   - Nav con avatar (iniciales) + contador de tickets activos

6. **Responsive móvil:** los HTML de referencia ya tienen media queries `@media (max-width: 768px)` y `(max-width: 480px)`. Cópialas tal cual.

7. **No conviertas JSX a JS plano si no hace falta** — los componentes React de `components.jsx` son referencia visual; en Blade reescríbelos como HTML estático con `@foreach` para listas.

**Empieza por:** leer los 5 HTML de referencia + `components.jsx` + `auth.css`. Luego dime qué vista adaptamos primero (sugiero `welcome.blade.php` por ser la más sencilla y visible).
