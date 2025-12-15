## Front sepd.es

Sitio web **sepd.es** (Laravel) y sus subáreas.

## Stack

- **Backend**: Laravel 8 (PHP ^8.0)
- **Panel CMS**: Voyager (tcg/voyager 1.5.0)
- **Frontend build**: Laravel Mix / Webpack (Node)

## Requisitos

- PHP 8.0+
- Composer
- Node.js + npm
- MySQL/MariaDB

## Puesta en marcha (local)

- **1) Instalar dependencias PHP**

  `composer install`

- **2) Configurar entorno**

  Crear `.env` (si no existe) y ajustar al menos:

  - `APP_URL`
  - `DB_HOST`, `DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD`

- **3) Generar clave de la app**

  `php artisan key:generate`

- **4) Migraciones/seed (si aplica en tu entorno)**

  `php artisan migrate`

- **5) Assets (opcional)**

  - Desarrollo: `npm install` y `npm run dev`
  - Producción: `npm run prod`

## Plantillas

Las vistas están en `resources/views` (ej.: cabecera en `resources/views/puzzle/cabecera.blade.php`).