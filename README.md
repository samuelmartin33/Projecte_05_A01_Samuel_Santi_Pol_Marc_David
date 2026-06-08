<div align="center">

# VIBEZ

### Plataforma de descubrimiento y gestión de eventos para jóvenes

[![Laravel](https://img.shields.io/badge/Laravel-13.5-FF2D20?style=for-the-badge&logo=laravel&logoColor=white)](https://laravel.com)
[![PHP](https://img.shields.io/badge/PHP-8.3-777BB4?style=for-the-badge&logo=php&logoColor=white)](https://php.net)
[![Tailwind CSS](https://img.shields.io/badge/Tailwind_CSS-3.x-06B6D4?style=for-the-badge&logo=tailwindcss&logoColor=white)](https://tailwindcss.com)
[![Stripe](https://img.shields.io/badge/Stripe-Payments-635BFF?style=for-the-badge&logo=stripe&logoColor=white)](https://stripe.com)
[![MySQL](https://img.shields.io/badge/MySQL-Database-4479A1?style=for-the-badge&logo=mysql&logoColor=white)](https://mysql.com)

---

*Inspirado en DICE · Construido con Laravel + Blade · Pagos con Stripe*

</div>

---

## Índice

- [Descripción](#-descripción)
- [Tecnologías](#-tecnologías)
- [Roles de usuario](#-roles-de-usuario)
- [Funcionalidades](#-funcionalidades)
- [Estructura del proyecto](#-estructura-del-proyecto)
- [Instalación](#-instalación)
- [Variables de entorno](#-variables-de-entorno)
- [Equipo](#-equipo)

---

## 📖 Descripción

**VIBEZ** es una plataforma web full-stack orientada a jóvenes de 16 a 35 años que centraliza el descubrimiento de eventos, la compra de entradas con sistema QR, la bolsa de trabajo en el sector del ocio, y la gestión completa del negocio para empresas organizadoras.

La estética está inspirada en la app **DICE**: fondo oscuro, acentos en degradado violeta-púrpura, tipografía moderna y tarjetas con diseño limpio.

---

## 🛠 Tecnologías

| Capa | Tecnología |
|---|---|
| Backend | Laravel 13.5 (PHP 8.3) |
| Frontend | Blade, Tailwind CSS, JavaScript vanilla |
| Base de datos | MySQL (WAMP / XAMPP) |
| Mapas | Leaflet.js |
| Pagos | Stripe (PaymentIntent + Webhooks + Connect) |
| Generación QR | `endroid/qr-code` + `simplesoftwareio/simple-qrcode` |
| PDF / Facturas | `barryvdh/laravel-dompdf` |
| Auth social | Laravel Socialite (Google OAuth) |
| Fechas UI | Flatpickr |
| Alertas | SweetAlert2 |

---

## 👥 Roles de usuario

VIBEZ implementa un sistema de roles con permisos escalonados:

### 🙍 Usuario (cliente)
Cuenta estándar. Puede descubrir y comprar entradas, seguir empresas, chatear con otros usuarios, publicar en el feed social de eventos, gestionar sus favoritos y aplicar a ofertas de trabajo.

### ⭐ Usuario Premium
Mismo acceso que el cliente pero con beneficios exclusivos: descuentos adicionales en entradas, acceso a eventos premium y distintivo visual en el perfil.

### 🏢 Empresa (organizador)
Cuenta de empresa con panel de gestión completo. Puede crear y administrar eventos, gestionar su equipo de trabajadores (organizadores y porteros), publicar ofertas de empleo, emitir cupones de descuento, ver candidaturas, gestionar la facturación y validar entradas mediante QR.

### 🔑 Organizador (miembro de equipo)
Miembro del equipo de una empresa con acceso completo al panel: eventos, candidaturas, validación QR, etc.

### 🚪 Portero (miembro de equipo)
Miembro del equipo con acceso restringido exclusivamente al escáner de validación de entradas QR.

### 🛡 Moderador
Acceso especial para revisar y moderar el contenido generado por usuarios: posts, comentarios e historias. Puede eliminar contenido inapropiado y gestionar usuarios desde su propio panel.

### 👑 Administrador
Acceso total a la plataforma. Gestión completa de usuarios, empresas, eventos, cupones, pagos y categorías desde el panel de administración.

---

## ✨ Funcionalidades

### 🎫 Descubrimiento de eventos
- Grid de eventos con filtros AJAX (búsqueda por texto, orden, fechas) sin recarga de página
- Mapa interactivo con Leaflet: marcadores por evento, filtros por categoría, panel lateral con detalle del evento seleccionado y bottom sheet en móvil
- Detalle de evento con galería de imágenes, ubicación en mapa, valoraciones con estrellas, posts del organizador y sección de comentarios

### 🛒 Compra de entradas
- Selección de número de entradas con resumen de pedido en tiempo real
- Aplicación de **cupones de descuento** con validación en el cliente y el servidor
- Pago seguro integrado con **Stripe** (PaymentIntent API)
- Generación automática de código **QR único** por entrada tras el pago confirmado
- **Wallet de entradas** (`/mis-entradas`) con todos los QR del usuario descargables

### 💰 Sistema de reembolsos
- Solicitud de reembolso para eventos futuros con entradas no escaneadas
- Tramitación mediante **Stripe Refunds API**
- Historial de reembolsos en el perfil de usuario

### 📱 Feed social por evento
- Cada evento tiene su propio feed de posts con imágenes
- Likes, comentarios anidados (respuestas) y control de visibilidad
- Historias tipo Stories con expiración automática a las 24 horas

### 💬 Sistema de mensajería
- Chat en tiempo real entre usuarios con interfaz de panel lateral
- Lista de conversaciones, estado de lectura y vista responsive (full-screen en móvil)
- Sistema de seguimiento entre usuarios y perfil público

### ⭐ Favoritos y valoraciones
- Añadir/quitar eventos de favoritos con toggle AJAX desde cualquier tarjeta
- Valoraciones con estrellas (1-5) para eventos y empresas
- Sección "Mis favoritos" en el perfil con filtros por categoría

### 💼 Bolsa de trabajo
- Grid de ofertas con filtros por categoría y ciudad (AJAX)
- Detalle de oferta con descripción completa, salario, vacantes y fecha
- Formulario de candidatura con subida de CV (PDF) y carta de presentación

### 🎟 Cupones de descuento
- Cupones con código, porcentaje o importe fijo, fecha de expiración y límite de usos
- Validación en tiempo real al introducir el código en el checkout
- Historial de usos por cupón para la empresa

### 🔔 Notificaciones
- Sistema de notificaciones en tiempo real accesible desde el navbar
- Notificaciones por: nuevo seguidor, respuesta en post, candidatura recibida, etc.

### 🔐 Autenticación
- Registro y login tradicional con verificación de email
- Login con **Google OAuth** (Laravel Socialite)
- Recuperación de contraseña por email
- Protección CSRF en todos los formularios

---

### Panel de Empresa

#### 📊 Dashboard
Resumen visual de la actividad: eventos activos, entradas vendidas, candidaturas recibidas e ingresos del mes.

#### 📅 Gestión de eventos
- Crear, editar y eliminar eventos con múltiples imágenes
- Configuración de precio, aforo, categorías, ubicación y descripción
- Vista previa antes de publicar

#### 👥 Gestión de equipo
- Tabla de miembros con roles (Organizador / Portero)
- Añadir nuevos miembros directamente desde un modal con formulario completo
- Asignación de puesto de trabajo (categoría)
- Invitación por email con enlace tokenizado

#### 📋 Candidaturas
- Lista de candidatos por oferta de trabajo
- Acceso al CV y carta de presentación de cada candidato
- Seguimiento del estado de la candidatura

#### 🎟 Cupones empresa
- Crear cupones vinculados a eventos específicos o a toda la empresa
- Control de límite de usos, validez y porcentaje/importe de descuento

#### 📄 Facturación
- Facturas automáticas generadas por cada venta de entradas
- Descarga en **PDF** con datos fiscales de la empresa
- Historial completo de transacciones

#### 🏛 Perfil fiscal
- Configuración de razón social, NIF, dirección fiscal
- Integración con **Stripe Connect** para recibir pagos directamente

#### 📷 Validación QR
- Escáner de códigos QR para validar entradas en la puerta del evento
- Vista optimizada para móvil (porteros)
- Confirmación visual de entrada válida / ya usada / no encontrada

#### ⭐ Valoraciones recibidas
- Vista de todas las valoraciones que los asistentes han dejado sobre los eventos de la empresa

---

### Panel de Administración

#### 👤 Gestión de usuarios
- Tabla con búsqueda AJAX en tiempo real
- Crear, editar y desactivar cuentas
- Asignación de roles (admin, moderador, empresa, cliente)
- Validación de reglas: las cuentas de empresa no pueden ser admin/moderador

#### 🗓 Gestión de eventos
- Supervisión de todos los eventos de la plataforma
- Edición y eliminación desde el panel admin

#### 🎫 Gestión de cupones
- Control global de todos los cupones activos en la plataforma

#### 💳 Gestión de pagos y pedidos
- Historial de todos los pedidos y pagos procesados
- Control de reembolsos y estados de pago

#### 📦 Gestión de empresas
- Alta, edición y baja de cuentas de empresa
- Supervisión del estado de onboarding de Stripe

#### 🏷 Categorías
- Gestión de categorías de eventos y categorías de trabajo

---

### Panel de Moderación

- Revisión de posts, comentarios e historias reportados
- Eliminación de contenido inapropiado
- Gestión básica de usuarios (advertencias, desactivación)

---

## 📁 Estructura del proyecto

```
vibez/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Admin/          # Gestión administrativa
│   │   │   ├── Empresa/        # Panel de empresa
│   │   │   ├── Moderador/      # Panel de moderación
│   │   │   ├── Organizador/    # Dashboard organizador
│   │   │   └── *.php           # Controladores públicos
│   │   └── Middleware/
│   └── Models/                 # Modelos Eloquent (35+)
├── database/
│   ├── migrations/             # 60+ migraciones
│   └── seeders/
├── public/
│   ├── css/                    # Estilos compilados y custom
│   └── js/                     # JavaScript vanilla modular
├── resources/
│   └── views/
│       ├── admin/              # Vistas del panel admin
│       ├── empresa/            # Vistas del panel empresa
│       ├── moderador/          # Vistas del panel moderación
│       ├── partials/           # Componentes reutilizables
│       └── *.blade.php         # Vistas públicas
└── routes/
    └── web.php                 # ~100 rutas definidas
```

---

## 🚀 Instalación

### Requisitos previos
- PHP 8.2+
- Composer
- Node.js 18+
- MySQL 8+
- WAMP / XAMPP o servidor web equivalente

### Pasos

```bash
# 1. Clonar el repositorio
git clone https://github.com/tu-usuario/vibez.git
cd vibez

# 2. Instalar dependencias PHP
composer install

# 3. Instalar dependencias JS
npm install

# 4. Configurar entorno
cp .env.example .env
php artisan key:generate

# 5. Configurar la base de datos en .env y ejecutar migraciones
php artisan migrate

# 6. (Opcional) Ejecutar seeders con datos de prueba
php artisan db:seed

# 7. Compilar assets
npm run build

# 8. Levantar el servidor de desarrollo
php artisan serve
```

O usando el script all-in-one de Composer:

```bash
composer setup
```

---

## 🔑 Variables de entorno

Crea un archivo `.env` basado en `.env.example` y configura las siguientes variables clave:

```env
# Base de datos
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=vibez
DB_USERNAME=root
DB_PASSWORD=

# Stripe (pagos)
STRIPE_KEY=pk_test_...
STRIPE_SECRET=sk_test_...
STRIPE_WEBHOOK_SECRET=whsec_...

# Google OAuth
GOOGLE_CLIENT_ID=...
GOOGLE_CLIENT_SECRET=...
GOOGLE_REDIRECT_URI=http://localhost:8000/auth/google/callback

# Email (para verificación y recuperación de contraseña)
MAIL_MAILER=smtp
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=...
MAIL_PASSWORD=...
MAIL_FROM_ADDRESS=noreply@vibez.com
MAIL_FROM_NAME="VIBEZ"

# App
APP_NAME=VIBEZ
APP_URL=http://localhost:8000
```

---

## 👨‍💻 Equipo

Proyecto desarrollado en el marco del **Ciclo Formativo de Grado Superior en Desarrollo de Aplicaciones Web (DAW2)**.

| Nombre | GitHub |
|---|---|
| Samuel Martín | [@samuelmartin33](https://github.com/samuelmartin33) |
| Santi Giacometti | [@santigiac](https://github.com/santigiac) |
| Pol Esteve | — |
| Marc Ramos | — |
| David | — |

---

<div align="center">

**VIBEZ** · DAW2 · 2025-2026

*Hecho con 🎵 y mucho café*

</div>
