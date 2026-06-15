# Discovery Web

Sitio institucional del Colegio Internacional Discovery®, desarrollado con Laravel, Filament, Blade, Tailwind CSS y Vite.

## Documentación

Antes de modificar el proyecto, consulta [docs/MANTENIMIENTO.md](docs/MANTENIMIENTO.md). Ahí se explica:

- qué archivo controla cada página;
- dónde se administran textos, imágenes, videos y documentos;
- cómo funciona el modo mantenimiento por vista;
- cómo se resuelven los archivos de `videosyfotos`;
- qué cachés deben invalidarse;
- cómo verificar un cambio antes de publicarlo.

Para cambios de contenido, navegación o experiencia pública, consulta también
[docs/EXPERIENCIA_FAMILIAS.md](docs/EXPERIENCIA_FAMILIAS.md).

Los comentarios dentro del código describen responsabilidades, prioridades de datos y decisiones que no son evidentes. No se comenta cada instrucción individual porque el código y el comentario podrían dejar de coincidir; se documentan módulos, contratos y bloques con comportamiento especial.

## Requisitos

- PHP 8.3 o compatible
- Composer
- Node.js y npm
- MySQL o la base configurada en `.env`

## Instalación local

```bash
composer install
npm install
copy .env.example .env
php artisan key:generate
php artisan migrate
php artisan db:seed
npm run build
php artisan serve
```

En Laragon, el proyecto se encuentra normalmente en:

```text
C:\laragon\www\discovery-web
```

## Comandos frecuentes

```bash
# Servidor de desarrollo con Vite
npm run dev

# Compilar CSS y JavaScript para producción
npm run build

# Ejecutar todas las pruebas
php artisan test

# Limpiar cachés después de cambios en configuración o vistas
php artisan optimize:clear

# Aplicar cambios de base de datos
php artisan migrate --force
```

## Estructura principal

```text
app/Http/Controllers/PageController.php  Coordina las páginas públicas.
app/Services/                           Prepara multimedia y contenido especializado.
app/Filament/                            Panel administrativo.
app/Models/                              Datos editables y limpieza de caché.
app/Support/                             Utilidades de caché y multimedia.
config/colegio.php                       Contenido estructural y rutas multimedia.
config/publicacion.php                   Vistas que pueden ponerse en mantenimiento.
resources/views/pages/                   Plantillas públicas.
resources/js/app.js                      Interacciones globales del sitio.
videosyfotos/                            Biblioteca multimedia local.
tests/Feature/                           Pruebas de los flujos principales.
```

## Regla de contenido

- Textos de páginas: `Páginas del sitio`.
- Imágenes de secciones: `Imágenes del sitio`.
- Línea del tiempo: `Nosotros - Línea del tiempo`.
- Banners de Inicio: carpeta `videosyfotos/Banner de inicio`.
- Eventos, videos y listas: sus módulos específicos de Filament.
- Textos principales de niveles: `Contenido de niveles`.
- Slugs, layouts, temas y rutas de respaldo: `config/colegio.php`.

Evita crear una segunda fuente para el mismo contenido. La sección
`Fuentes de verdad` de la guía de mantenimiento detalla la prioridad de cada
origen y sus respaldos.
