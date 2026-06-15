# Guía de mantenimiento

Esta guía permite localizar rápidamente el código responsable de cada parte del sitio.

La estrategia de tono, recorrido y llamados a acción para madres, padres y
tutores vive en `docs/EXPERIENCIA_FAMILIAS.md`. La configuración global del CTA
principal y la promesa de comunicación vive en `config/experiencia.php`.

## Flujo de una página pública

1. `routes/web.php` recibe la URL.
2. El middleware `VerificarVistaPublicada` decide si la vista está publicada.
3. `PageController` coordina servicios de contenido y prepara la vista.
4. El controlador entrega datos a una plantilla de `resources/views/pages`.
5. `resources/js/app.js` agrega las interacciones globales.

## Fuentes de verdad

Cada dato debe tener un único propietario principal. Los respaldos mantienen el
sitio visible cuando todavía no existe contenido administrativo, pero no deben
convertirse en un segundo lugar de edición.

| Tipo de dato | Fuente principal | Respaldo o estructura | Regla |
| --- | --- | --- | --- |
| Títulos y descripciones generales de páginas | `PaginaContenido` mediante `Páginas del sitio` | Texto predeterminado en Blade o `config/colegio.php` | Si existe el registro, Filament manda |
| Imágenes fijas por sección | `SeccionImagen` mediante `Imágenes del sitio` | Ruta de `videosyfotos` declarada en el registro o en configuración | La carga en `storage/public` tiene prioridad |
| Eventos de Inicio | `Evento` | `colegio.inicio.eventos_default` | Si existen eventos administrativos activos, los defaults dejan de ser contenido |
| Testimonios | `TestimonioVideo` | Archivos de `videosyfotos/Testimonios Alumni` | Los registros activos y válidos sustituyen la lectura automática |
| Línea del tiempo | `HitoHistoria` | Hitos predeterminados de `PageController` | Los defaults solo se usan cuando la tabla no tiene hitos |
| Listas de útiles | `ListaUtil` | PDFs de `videosyfotos/Listas de útiles` | Cualquier conjunto administrativo activo sustituye la lectura de carpeta |
| Banners de Inicio | Panel `Banners de Inicio` | `videosyfotos/Banner de inicio` | El panel sustituye la carpeta cuando tiene registros |
| Galerías de niveles | Carpetas definidas en `colegio.niveles.carpetas_galeria` | Imagen fija o logo del nivel | No se administran como `SeccionImagen` |
| Textos principales de niveles y tarjetas de oferta | `NivelContenido` mediante `Contenido de niveles` | `database/data/nivel_contenidos.php` | La BD manda; el archivo conserva valores iniciales y fallback |
| Estructura de niveles, slugs, layouts y temas | `config/colegio.php` | Ninguno | Es configuración técnica, no contenido editorial |
| Datos globales de WhatsApp y URLs técnicas | `config/colegio.php` y variables de entorno | Valores predeterminados de configuración | No pertenecen a `PaginaContenido` |

### Jerarquía multimedia

Cuando una posición visual admite reemplazo desde Filament, el orden es:

1. Archivo cargado en `storage/public`.
2. Respaldo elegido en el registro administrativo.
3. URL predeterminada preparada por la página.
4. Ruta predeterminada en `videosyfotos`.
5. Marcador de imagen pendiente.

No copies contenido editable a `config/colegio.php`. Tampoco agregues una tabla
para datos técnicos como slugs, clases Tailwind, nombres de discos o layouts.
Si un texto necesita edición frecuente por el colegio, su destino es Filament;
si define cómo funciona o se compone la aplicación, permanece en configuración.

## Mapa de páginas

| Página | Método | Vista | Contenido principal |
| --- | --- | --- | --- |
| Inicio | `PageController::inicio` | `pages/inicio.blade.php` | Página Inicio, eventos, testimonios y banners |
| Conócenos | `PageController::nosotros` | `pages/nosotros.blade.php` | Página Nosotros, imágenes y línea del tiempo |
| Oferta Educativa | `PageController::ofertaAcademica` | `pages/oferta-academica.blade.php` | Configuración de niveles e imágenes |
| Nivel académico | `PageController::nivel` | `pages/nivel.blade.php` | `config/colegio.php`, galerías e imágenes del sitio |
| Comunidad | `PageController::protagonistas` | `pages/protagonistas.blade.php` | Página Protagonistas, grupos y testimonios |
| Academias | `PageController::academiasVespertinas` | `pages/academias-vespertinas.blade.php` | Carpeta multimedia de academias |
| Recursos | `PageController::recursosEscolares` | `pages/recursos-escolares.blade.php` | Listas de útiles y calendario |
| Contacto | `PageController::contacto` | `pages/contacto.blade.php` | Página Contacto e imágenes |

## Prioridad de imágenes

`MediaResolver::images` resuelve cada imagen en este orden:

1. Archivo cargado en `Imágenes del sitio`.
2. Ruta de respaldo registrada en ese mismo módulo.
3. URL predeterminada enviada por el controlador.
4. Archivo predeterminado de `videosyfotos`.
5. Marcador visual de imagen pendiente.

Esta prioridad permite reemplazar una imagen desde Filament sin modificar código. No agregues campos de imagen en otro recurso si la sección ya tiene una clave en `seccion_imagenes`.

## Claves de imágenes

Cada registro usa:

- `vista`: página o grupo propietario, por ejemplo `inicio` o `pop-del-ib`;
- `clave`: identificador estable de la posición, por ejemplo `sobre_nosotros`;
- `referencia`: explicación para el administrador;
- `respaldo_media_path`: archivo opcional dentro de `videosyfotos`.

Las claves forman parte del contrato entre controlador, base de datos y Blade. Si cambias una clave, actualiza los tres lugares y agrega una migración para los registros existentes.

## Oferta Educativa

Los textos principales se administran en `Contenido de niveles`. La estructura
técnica permanece en `config/colegio.php`, bajo `oferta_academica`,
`niveles.definiciones` y `temas_niveles`.

El hero de `oferta-academica.blade.php` tiene una altura controlada en escritorio. Sus tarjetas y ficha activa están deliberadamente compactadas para mostrar toda la información sin desplazar la página. Al agregar textos:

- conserva títulos y edades breves;
- prueba el nivel con la descripción más larga;
- verifica anchos de 1024, 1280 y 1920 píxeles;
- evita recuperar una altura automática sin revisar el objetivo de diseño.

## Inicio

- El carrusel se administra en `Contenido del sitio > Banners de Inicio`.
- Permite subir uno o varios banners, ordenarlos, ocultarlos y agregar un enlace opcional.
- Mientras no existan registros administrativos, usa todos los archivos de
  `videosyfotos/Banner de inicio` y coloca primero el banner de bienvenida.
- `BannerInicioSeeder` importa los archivos actuales como registros editables
  sin duplicarlos ni sobrescribir cambios posteriores.
- El bloque Sobre Nosotros usa los campos de la página `inicio`.
- Su imagen usa `vista=inicio`, `clave=sobre_nosotros`.
- Los eventos se administran en el módulo de carrusel.
- Si existen eventos activos en el administrador, no se muestran eventos predeterminados.

## Línea del tiempo

La línea del tiempo se administra exclusivamente desde `HitoHistoriaResource`. Las imágenes pertenecen al propio hito. No deben crearse duplicados en `Imágenes del sitio`.

`HistoryTimelineService` conserva datos predeterminados como respaldo únicamente cuando no existen hitos administrativos.

## Galerías de niveles

Las galerías de Kinder, Elementary, Middle y High se administran desde
`Contenido del sitio > Galerías`.

- Permite subir una o varias imágenes.
- Cada imagen puede ordenarse por arrastre, ocultarse o eliminarse.
- El texto alternativo describe la imagen para accesibilidad.
- También puede seleccionarse un archivo existente de `/videosyfotos`.
- Cuando un nivel tiene al menos un registro administrativo, el panel sustituye
  por completo a la carpeta de respaldo.
- Si todavía no existen registros para ese nivel, se leen las imágenes de la
  carpeta configurada en `colegio.niveles.carpetas_galeria`.
- `GaleriaImagenSeeder` importa las imágenes que alimentaban originalmente cada
  galería como registros editables, respetando el orden y límite anteriores.

## Publicación y mantenimiento

`config/publicacion.php` enumera las vistas controlables. El widget del dashboard actualiza `vistas_publicacion`.

El middleware:

- deja pasar vistas publicadas;
- permite previsualización a administradores autenticados;
- responde con HTTP `503` al público;
- controla cada ruta de forma independiente. Oferta Educativa conserva sus
  niveles agrupados en el panel, pero su interruptor no cambia el estado de ellos.

Para agregar una vista:

1. Añade la clave en `config/publicacion.php`.
2. Aplica `vista.publicada:clave` en `routes/web.php`.
3. Si es una ruta dinámica de nivel, revisa `resolverClave`.
4. Agrega una prueba en `VistaPublicacionTest`.

## Caché

`SiteCache` centraliza nombres y duración. Los modelos editables invalidan las claves relacionadas al guardar o eliminar.

Después de modificar configuración, rutas o Blade:

```bash
php artisan optimize:clear
```

No guardes modelos Eloquent completos en caché. Guarda IDs o arreglos simples y recupera el modelo después; esto evita problemas de serialización y relaciones obsoletas.

## Migraciones y seeders

A partir del 15 de junio de 2026:

- las migraciones crean o transforman estructura;
- una migración de datos solo se justifica cuando una versión desplegada
  necesita transformar registros existentes para conservar compatibilidad;
- una migración de datos excepcional debe incluir `_data_` en el nombre para
  hacer visible su propósito y pasar la prueba de convenciones;
- los datos iniciales repetibles viven en seeders idempotentes;
- el contenido opcional o de demostración no se ejecuta desde `DatabaseSeeder`;
- los seeders deben ser idempotentes;
- para contenido editable usa `firstOrCreate`, de modo que una nueva ejecución
  complete faltantes sin sobrescribir cambios hechos desde Filament;
- reserva `updateOrCreate` o `updateOrInsert` para catálogos técnicos cuyo valor
  sí deba sincronizarse con el código.

`NivelContenidoSeeder` carga los siete niveles administrables. `EventoSeeder`
es opcional y no forma parte del seeding normal.

Para una instalación nueva:

```bash
php artisan migrate
php artisan db:seed
```

No edites migraciones que ya hayan sido publicadas o compartidas. Este proyecto
solo separó la migración de niveles porque todavía pertenecía al cambio local
en desarrollo.

`MigrationConventionTest` revisa las migraciones creadas desde esta convención y
evita que una migración estructural vuelva a usar `DB`, `File` o `Storage`.

## Multimedia

La ruta `/media/{path}` sirve archivos del disco configurado en `colegio.media.disk`.

Las rutas:

- se normalizan a `/`;
- rechazan segmentos `..`;
- codifican cada segmento para soportar espacios y acentos;
- agregan la fecha de modificación como versión de caché.

No construyas URLs de `videosyfotos` manualmente. Usa `MediaResolver`.

## Servicios públicos

- `MediaResolver`: discos, URLs, seguridad de rutas y prioridad de imágenes.
- `HistoryTimelineService`: hitos administrativos y respaldo histórico.
- `SchoolSupplyListService`: listas administradas y lectura heredada de PDFs.
- `LevelContentService`: superpone textos editables sobre la estructura técnica de niveles.

`PageController` debe coordinar estos servicios y entregar arreglos a Blade. No
vuelvas a incorporar al controlador consultas o transformaciones que ya tengan
un servicio propietario.

## Panel Filament

Los recursos se dividen así:

- `Resources/Paginas`: editores específicos de cada página;
- `PaginaContenidos`: esquema y tabla compartidos;
- `SeccionImagenes`: imágenes por vista y clave;
- `HitoHistorias`: línea del tiempo;
- `Eventos`, `TestimonioVideos`, `ListaUtiles`: contenido especializado;
- `Usuarios`: acceso administrativo.

Las clases `Pages/Create`, `Pages/Edit` y `Pages/List` suelen ser adaptadores mínimos de Filament. La lógica importante normalmente está en `Resource`, `Schemas` o `Tables`.

## JavaScript

`resources/js/app.js` contiene:

- GLightbox;
- pausa de videos antes de navegación interna;
- animaciones globales al entrar al viewport;
- menú móvil y submenús;
- panel flotante de ayuda.

Las animaciones incluyen automáticamente `section` y `article` dentro de
`main`. Usa `data-scroll-reveal` para agregar otro elemento y
`data-no-scroll-animation` para excluir un bloque o todos sus descendientes.
Los estilos viven en `resources/css/app.css` y respetan la preferencia del
sistema operativo para reducir movimiento.

Las interacciones específicas de una sola página permanecen al final de su Blade, junto al HTML cuyos atributos `data-*` utilizan.

## Pruebas obligatorias

Antes de publicar:

```bash
php artisan test
npm run build
php artisan optimize:clear
```

Para cambios visuales, comprueba también:

- escritorio y móvil;
- contenido más largo que el predeterminado;
- imágenes faltantes;
- vista publicada y en mantenimiento;
- sesión pública y sesión administrativa.

## Convención de comentarios

Agrega comentarios cuando expliquen:

- por qué existe una condición;
- prioridad o respaldo entre fuentes;
- un contrato basado en claves;
- seguridad, caché o compatibilidad;
- estructura de un bloque Blade o JavaScript.

No agregues comentarios que repitan literalmente una asignación o una llamada. Cuando cambie el comportamiento, actualiza el comentario y la prueba correspondiente.
