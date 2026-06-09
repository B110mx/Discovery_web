# Guía de mantenimiento

Esta guía permite localizar rápidamente el código responsable de cada parte del sitio.

## Flujo de una página pública

1. `routes/web.php` recibe la URL.
2. El middleware `VerificarVistaPublicada` decide si la vista está publicada.
3. `PageController` consulta contenido, configuración y archivos multimedia.
4. El controlador entrega datos a una plantilla de `resources/views/pages`.
5. `resources/js/app.js` agrega las interacciones globales.

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

`PageController::imagenesVista` resuelve cada imagen en este orden:

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

La estructura de niveles vive en `config/colegio.php`, bajo `oferta_academica` y `niveles.definiciones`.

El hero de `oferta-academica.blade.php` tiene una altura controlada en escritorio. Sus tarjetas y ficha activa están deliberadamente compactadas para mostrar toda la información sin desplazar la página. Al agregar textos:

- conserva títulos y edades breves;
- prueba el nivel con la descripción más larga;
- verifica anchos de 1024, 1280 y 1920 píxeles;
- evita recuperar una altura automática sin revisar el objetivo de diseño.

## Inicio

- El hero usa todos los archivos de `videosyfotos/Banner de inicio`.
- El bloque Sobre Nosotros usa los campos de la página `inicio`.
- Su imagen usa `vista=inicio`, `clave=sobre_nosotros`.
- Los eventos se administran en el módulo de carrusel.
- Si existen eventos activos en el administrador, no se muestran eventos predeterminados.

## Línea del tiempo

La línea del tiempo se administra exclusivamente desde `HitoHistoriaResource`. Las imágenes pertenecen al propio hito. No deben crearse duplicados en `Imágenes del sitio`.

`PageController::obtenerHistoriaNosotros` conserva datos predeterminados como respaldo únicamente cuando no existen hitos administrativos.

## Publicación y mantenimiento

`config/publicacion.php` enumera las vistas controlables. El widget del dashboard actualiza `vistas_publicacion`.

El middleware:

- deja pasar vistas publicadas;
- permite previsualización a administradores autenticados;
- responde con HTTP `503` al público;
- respeta la relación `padre` de los niveles de Oferta Educativa.

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

## Multimedia

La ruta `/media/{path}` sirve archivos del disco configurado en `colegio.media.disk`.

Las rutas:

- se normalizan a `/`;
- rechazan segmentos `..`;
- codifican cada segmento para soportar espacios y acentos;
- agregan la fecha de modificación como versión de caché.

No construyas URLs de `videosyfotos` manualmente. Usa los helpers de `PageController`.

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
