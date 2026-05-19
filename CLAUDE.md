# VIBEZ Project Guide

## Project Context
VIBEZ es una plataforma de gestión de eventos para jóvenes (16-35 años). Incluye descubrimiento de eventos, creación de contenido, bolsa de trabajo y sistema de cupones. Inspiración visual: DICE app.

## Tech Stack & Language
- **Lenguaje Principal:** Todo el código (comentarios, variables, UI) debe estar en **Español**.
- **Backend:** Laravel (PHP).
- **Frontend:** Blade, Tailwind CSS, SweetAlert 2.
- **Interacciones:** JS (AJAX/Fetch) - **PROHIBIDO EventListeners**. Usar atributos inline (`onclick`, `onchange`, etc.).
- **Mapas:** API de Leaflet.

## Design & UI (Estilo VIBEZ/DICE)
- **Colores:** Azul marino oscuro (`#0f172a`) como principal para texto/UI.
- **Acento:** Degradado de violeta a púrpura (`#7c3aed → #a855f7`) para botones, iconos y el isotipo.
- **Estética:** Tarjetas (cards) con bordes redondeados, tipografía moderna sans-serif (Inter) y diseño limpio.

## Coding & Behavior Rules
- **No Planning:** Ejecuta las tareas directamente. No generes planes de pasos previos ni confirmaciones largas para ahorrar tokens.
- **Documentación:** Comentar CADA función y bloque de lógica importante de forma clara para facilitar el aprendizaje.
- **Git:** Los mensajes de commit deben ser muy simplificados y siempre en español (Ej: "feat: mapa de eventos", "fix: error login").
- **JS Pattern:** Centralizar lógica en funciones globales llamadas desde el HTML.

## Módulos Críticos
1. **Eventos:** Mapa interactivo, filtros y QR para entradas.
2. **Bolsa de Trabajo:** Subida de CV y contacto organizador-candidato.
3. **Cupones:** Generación y aplicación de descuentos.
