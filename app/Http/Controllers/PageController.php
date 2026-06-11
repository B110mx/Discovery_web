<?php

namespace App\Http\Controllers;

use App\Models\Evento;
use App\Models\HitoHistoria;
use App\Models\ListaUtil;
use App\Models\PaginaContenido;
use App\Models\SeccionImagen;
use App\Models\TestimonioVideo;
use App\Support\SiteCache;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Carbon;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Throwable;

/**
 * Prepara los datos de todas las páginas públicas.
 *
 * Este controlador funciona como capa de composición: combina registros del
 * panel, configuración estática y archivos de videosyfotos antes de entregar
 * arreglos simples a las vistas Blade.
 */
class PageController extends Controller
{
    /**
     * Recupera contenido editable sin almacenar un modelo Eloquent completo
     * en caché. Guardar solo el ID evita serializaciones y datos obsoletos.
     */
    private function paginaContenido(string $slug): ?PaginaContenido
    {
        $paginaId = Cache::remember(SiteCache::key("pagina_contenido.{$slug}"), SiteCache::ttl(), function () use ($slug) {
            return PaginaContenido::where('slug', $slug)->value('id');
        });

        return $paginaId ? PaginaContenido::find($paginaId) : null;
    }

    /**
     * Muestra la página de inicio con eventos y testimonios cacheados.
     */
    public function inicio(): View
    {
        $paginaInicio = $this->paginaContenido('inicio');

        // Los eventos del panel tienen prioridad. Los defaults solo aparecen
        // cuando nunca se ha configurado contenido administrativo.
        $eventos = Cache::remember(SiteCache::key('inicio_eventos'), $this->eventosInicioCacheTtl(), function () {
            $eventosDefault = $this->eventosInicioDefault();
            $hayEventosAdmin = Evento::where('activo', true)->exists();
            $ahora = now();
            $hoy = $ahora->toDateString();
            $horaCorte = $ahora->copy()->setTime(15, 0);

            $eventos = Evento::where('activo', true)
                ->where(function (Builder $query) use ($ahora, $hoy, $horaCorte) {
                    $query->whereNull('fecha_evento')
                        ->orWhereDate('fecha_evento', '>', $hoy);

                    if ($ahora->lt($horaCorte)) {
                        $query->orWhereDate('fecha_evento', $hoy);
                    }
                })
                ->orderBy('orden')
                ->get()
                ->map(function (Evento $evento, int $index) use ($eventosDefault) {
                    $imagen = $eventosDefault[$index]['imagen'] ?? null;
                    $url = $this->publicUploadUrl($evento->imagen_url)
                        ?? $this->mediaUrlIfExists($evento->imagen_media_path);

                    if ($url) {
                        $imagen = [
                            'url' => $url,
                            'titulo' => $evento->titulo,
                            'referencia' => $evento->descripcion ?? 'Imagen del carrusel de eventos en Inicio.',
                            'pendiente' => false,
                        ];
                    }

                    return [
                        'titulo' => $evento->titulo,
                        'descripcion' => $evento->descripcion ?? 'Próximo evento de la comunidad Discovery®.',
                        'url' => $imagen['url'] ?? null,
                        'imagen' => $imagen,
                    ];
                })->values()->all();

            if (!empty($eventos)) {
                return $eventos;
            }

            if ($hayEventosAdmin) {
                return [];
            }

            return $eventosDefault;
        });

        // La lectura de videos del disco es costosa; se reutiliza entre visitas.
        $testimonios = Cache::remember(SiteCache::key('inicio_testimonios'), SiteCache::ttl(), fn () => $this->testimoniosAlumni());

        $logosNiveles = $this->imagenesVista('inicio', collect(config('colegio.inicio.logos_niveles', []))
            ->mapWithKeys(fn (string $path, string $nivel) => [
                "logo_{$nivel}" => [
                    'titulo' => 'Inicio - Logo ' . ucfirst($nivel),
                    'referencia' => 'Logo mostrado en la tarjeta del nivel dentro de Inicio.',
                    'media_path' => $path,
                ],
            ])
            ->all());
        $bannerInicio = [
            'url' => $this->mediaUrlIfExists('Banner de inicio/Banner de bienvenida.png'),
            'titulo' => 'Banner de bienvenida',
            'referencia' => 'Banner principal de la vista de inicio.',
        ];
        // El hero no se administra como PaginaContenido: cada imagen de esta
        // carpeta se convierte en una diapositiva, ordenando primero bienvenida.
        $bannerInicioSlides = $this->mediaFiles('Banner de inicio')
            ->filter(fn (string $path) => in_array(strtolower(pathinfo($path, PATHINFO_EXTENSION)), config('colegio.media.image_extensions', []), true))
            ->sortBy(fn (string $path) => str_starts_with(strtolower(pathinfo($path, PATHINFO_FILENAME)), 'banner de bienvenida') ? '000-' . $path : '100-' . $path)
            ->map(fn (string $path) => [
                'url' => $this->generarMediaUrl($path),
                'titulo' => pathinfo($path, PATHINFO_FILENAME),
                'referencia' => 'Banner principal de la vista de inicio.',
                'pendiente' => false,
            ])
            ->values()
            ->all();

        if (empty($bannerInicioSlides)) {
            $bannerInicioSlides = [$bannerInicio];
        }

        $imagenesInicio = $this->imagenesVista('inicio', [
            'sobre_nosotros' => [
                'titulo' => 'Inicio - Sobre Nosotros',
                'referencia' => 'Imagen lateral de la sección Sobre Nosotros en la página de inicio.',
                'url' => $this->publicUploadUrl($paginaInicio?->imagen_principal),
                'media_path' => 'Kinder fotos actuales/IMG_5775.JPG',
            ],
        ]);

        return view('pages.inicio', compact('eventos', 'testimonios', 'logosNiveles', 'imagenesInicio', 'paginaInicio', 'bannerInicio', 'bannerInicioSlides'));
    }

    private function eventosInicioCacheTtl()
    {
        $ttlDefault = SiteCache::ttl();
        $ahora = now();
        $hoy = $ahora->toDateString();

        $proximaFechaEvento = Evento::where('activo', true)
            ->whereNotNull('fecha_evento')
            ->where(function (Builder $query) use ($ahora, $hoy) {
                $query->whereDate('fecha_evento', '>', $hoy);

                if ($ahora->lt($ahora->copy()->setTime(15, 0))) {
                    $query->orWhereDate('fecha_evento', $hoy);
                }
            })
            ->orderBy('fecha_evento')
            ->value('fecha_evento');

        if (! $proximaFechaEvento) {
            return $ttlDefault;
        }

        // Un evento del día deja de mostrarse a las 15:00. El caché nunca debe
        // sobrevivir más allá de ese corte aunque el TTL general sea mayor.
        $proximoCorte = Carbon::parse($proximaFechaEvento)->setTime(15, 0);

        return $proximoCorte->lt($ttlDefault) ? $proximoCorte : $ttlDefault;
    }

    public function nosotros(): View
    {
        $paginaNosotros = $this->paginaContenido('nosotros');

        $imagenesNosotros = $this->imagenesVista('nosotros', [
            'hero' => [
                'titulo' => 'Nosotros - Imagen principal',
                'referencia' => 'Imagen grande del encabezado de la página Nosotros.',
                'url' => $this->publicUploadUrl($paginaNosotros?->imagen_principal),
                'media_path' => 'Logos principales/LOGO DISCOVERY PNG.png',
            ],
            'modelo' => [
                'titulo' => 'Nosotros - Modelo educativo',
                'referencia' => 'Imagen de apoyo para la sección de modelo educativo.',
                'url' => $this->publicUploadUrl($paginaNosotros?->imagen_secundaria),
                'media_path' => 'Modelos educativos/modelo-educativo-Principal.png',
            ],
        ]);

        $historiaNosotros = Cache::remember(SiteCache::key('nosotros_historia'), SiteCache::ttl(), fn () => $this->obtenerHistoriaNosotros());
        $universidadesVinculacion = $this->universidadesVinculacion();

        return view('pages.nosotros', compact('imagenesNosotros', 'historiaNosotros', 'paginaNosotros', 'universidadesVinculacion'));
    }

    public function ofertaAcademica(): View
    {
        $paginaOferta = $this->paginaContenido('oferta-academica');

        // La estructura de los niveles vive en configuración; aquí se agregan
        // rutas públicas y las imágenes reemplazables desde Filament.
        $ofertaNiveles = collect(config('colegio.oferta_academica', []))
            ->map(fn (array $nivel, string $slug) => $this->prepararNivelOferta($slug, $nivel))
            ->all();

        return view('pages.oferta-academica', compact('ofertaNiveles', 'paginaOferta'));
    }

    public function protagonistas(): View
    {
        $paginaProtagonistas = $this->paginaContenido('protagonistas');
        $testimonios = Cache::remember(SiteCache::key('protagonistas_testimonios'), SiteCache::ttl(), fn () => $this->testimoniosAlumni());
        $comunidad = $this->prepararComunidadProtagonistas();

        return view('pages.protagonistas', compact('testimonios', 'comunidad', 'paginaProtagonistas'));
    }

    public function academiasVespertinas(): View
    {
        $mediaAcademias = $this->mediaAcademiasVespertinas();

        return view('pages.academias-vespertinas', compact('mediaAcademias'));
    }

    public function recursosEscolares(): View
    {
        $listasUtiles = Cache::remember(SiteCache::key('recursos_listas_utiles'), SiteCache::ttl(), function () {
            $listasAdmin = $this->listasUtilesDesdeAdmin();

            // Una vez que el panel tiene listas activas, estas sustituyen por
            // completo la lectura automática de PDFs desde videosyfotos.
            if (! empty($listasAdmin)) {
                return $listasAdmin;
            }

            return $this->listasUtilesDesdeCarpeta();
        });

        $calendarioEscolar = $this->imagenVista('recursos-escolares', 'calendario', [
            'titulo' => 'Recursos escolares - Calendario escolar',
            'referencia' => 'Imagen del calendario escolar mostrada en Recursos escolares.',
            'media_path' => 'Calendario Escolar/Calendario Escolar 2025-2026.jpg',
        ]);

        return view('pages.recursos-escolares', compact('listasUtiles', 'calendarioEscolar'));
    }

    public function contacto(): View
    {
        // Igual que paginaContenido(), se almacena el ID y se recupera un
        // modelo fresco para evitar serializar Eloquent dentro del caché.
        $paginaId = Cache::remember(SiteCache::key('pagina_contenido.contacto'), SiteCache::ttl(), function () {
            return PaginaContenido::where('slug', 'contacto')->value('id');
        });

        $pagina = $paginaId ? PaginaContenido::find($paginaId) : null;
        $imagenesContacto = $this->imagenesVista('contacto', [
            'hero' => [
                'titulo' => 'Contacto - Imagen principal',
                'referencia' => 'Imagen principal de la vista Contacto.',
                'url' => $this->publicUploadUrl($pagina?->imagen_principal),
            ],
            'secundaria' => [
                'titulo' => 'Contacto - Imagen secundaria',
                'referencia' => 'Imagen secundaria de apoyo de la vista Contacto.',
                'url' => $this->publicUploadUrl($pagina?->imagen_secundaria),
            ],
        ]);

        return view('pages.contacto', compact('pagina', 'imagenesContacto'));
    }

    /**
     * Lógica detallada para los niveles académicos.
     */
    public function nivel(string $nivel): View
    {
        // Los slugs permitidos se definen en config/colegio.php. No se acepta
        // cualquier valor de URL porque después se usa para buscar multimedia.
        $niveles = $this->obtenerDefinicionNiveles();
        abort_unless(isset($niveles[$nivel]), 404);

        $carpetas = config('colegio.niveles.carpetas_galeria', []);

        $galeria = Cache::remember(SiteCache::key("galeria.{$nivel}"), SiteCache::ttl(), function () use ($nivel, $carpetas, $niveles) {
            if (!isset($carpetas[$nivel])) return [];

            return $this->mediaFiles($carpetas[$nivel])
                ->filter(fn (string $file) => in_array(strtolower(pathinfo($file, PATHINFO_EXTENSION)), config('colegio.media.image_extensions', [])))
                ->sortByDesc(fn (string $file) => $nivel === 'secundaria' && basename($file) === 'Colegio Discovery-59.jpg')
                ->take(12)
                ->map(fn ($file) => [
                    'alt' => $niveles[$nivel]['titulo'],
                    'url' => $this->generarMediaUrl($file),
                ])->values()->all();
        });

        $nivelContenido = $niveles[$nivel];
        $nivelContenido['slug'] = $nivel;
        $nivelContenido['tema'] = $this->obtenerTemaNivel($nivel);
        $logoNivel = $this->imagenVista($nivel, 'logo', [
            'titulo' => $nivelContenido['titulo'] . ' - Logo del encabezado',
            'referencia' => 'Logo mostrado sobre el título en el encabezado del nivel.',
            'media_path' => $nivelContenido['logo_extendido_path'] ?? $nivelContenido['logo_path'] ?? null,
        ]);
        $nivelContenido['logo'] = $logoNivel['url'];
        $nivelContenido['logo_extendido'] = $logoNivel['url'];
        $nivelContenido['hoja_informativa_url'] = isset($nivelContenido['hoja_informativa_path'])
            ? $this->generarMediaUrlDesdeRuta($nivelContenido['hoja_informativa_path'])
            : null;
        $nivelContenido['modelo_academico'] = ! empty($nivelContenido['modelo_academico_path'])
            ? $this->imagenVista($nivel, 'modelo_academico', [
                'titulo' => $nivelContenido['titulo'] . ' - Modelo académico',
                'referencia' => 'Infografía del modelo académico mostrada en la página del nivel.',
                'media_path' => $nivelContenido['modelo_academico_path'],
            ])
            : null;
        if (! empty($nivelContenido['informacion']['imagenes_referencia'])) {
            $nivelContenido['informacion']['imagenes_referencia'] = collect($nivelContenido['informacion']['imagenes_referencia'])
                ->map(function (array $imagen) {
                    $imagen['url'] = $imagen['url'] ?? $this->mediaUrlIfExists($imagen['media_path'] ?? null);

                    return $imagen;
                })
                ->all();
        }
        if (! empty($nivelContenido['informacion']['imagen_enfoque'])) {
            $nivelContenido['informacion']['imagen_enfoque'] = $this->imagenVista(
                $nivel,
                'imagen_enfoque',
                $nivelContenido['informacion']['imagen_enfoque'],
            );
        }
        // POP usa posiciones de imagen administrables propias; los demás
        // layouts resuelven sus imágenes mediante las claves generales.
        if (($nivelContenido['layout'] ?? null) === 'pop' && ! empty($nivelContenido['informacion']['imagenes'])) {
            $nivelContenido['informacion']['imagenes'] = $this->imagenesVista(
                'pop-del-ib',
                $nivelContenido['informacion']['imagenes'],
            );
        }
        $imagenGaleriaPrincipal = $galeria[0]['url'] ?? null;
        $mediaPathGaleriaPrincipal = $nivelContenido['hero_media_path'] ?? (isset($carpetas[$nivel], $imagenGaleriaPrincipal)
            ? $carpetas[$nivel] . '/' . basename($imagenGaleriaPrincipal)
            : ($nivelContenido['usar_placeholder_hero'] ?? false ? null : ($nivelContenido['logo_path'] ?? null)));
        $imagenPrincipalDefault = [
            'titulo' => $nivelContenido['titulo'] . ' - Imagen principal',
            'referencia' => 'Imagen principal del encabezado del nivel ' . $nivelContenido['titulo'] . '.',
            'url' => $imagenGaleriaPrincipal,
            'media_path' => $mediaPathGaleriaPrincipal,
        ];

        // High comparte la imagen promocional de Oferta Educativa. El resto de
        // niveles administra su hero bajo su propia vista y la clave "hero".
        if ($nivel === 'bachillerato') {
            $ofertaHigh = config('colegio.oferta_academica.bachillerato', []);
            $nivelContenido['imagen_principal'] = $this->imagenVista(
                'oferta-academica',
                $ofertaHigh['imagen_clave'] ?? 'bachillerato',
                $this->defaultConMediaUrl($ofertaHigh['imagen_default'] ?? $imagenPrincipalDefault),
            );
        } else {
            $nivelContenido['imagen_principal'] = $this->imagenVista($nivel, 'hero', $imagenPrincipalDefault);
        }

        return view('pages.nivel', [
            'nivel' => $nivelContenido,
            'galeria' => $galeria,
        ]);
    }

    /**
     * Sirve los archivos multimedia de forma segura.
     */
    public function serveMedia(string $path): BinaryFileResponse
    {
        $path = $this->normalizarMediaPath($path);
        $filePath = null;

        if (! $path) {
            abort(404);
        }

        try {
            $disk = Storage::disk($this->mediaDisk());

            if ($disk->exists($path)) {
                $filePath = $disk->path($path);
            }
        } catch (Throwable $exception) {
            Log::warning('No se pudo servir el archivo multimedia.', [
                'path' => $path,
                'disk' => $this->mediaDisk(),
                'error' => $exception->getMessage(),
            ]);

            abort(404);
        }

        abort_unless($filePath && is_file($filePath), 404);

        return response()->file($filePath);
    }

    // ---------------------------------------------------------------------
    // Helpers de multimedia
    // ---------------------------------------------------------------------
    private function mediaDisk(): string
    {
        return config('colegio.media.disk', 'videosyfotos');
    }

    private function mediaFiles(string $directory): \Illuminate\Support\Collection
    {
        $disk = Storage::disk($this->mediaDisk());

        if (! $disk->directoryExists($directory)) {
            return collect();
        }

        return collect($disk->files($directory));
    }

    private function generarMediaUrl(string $path): string
    {
        return $this->generarMediaUrlDesdeRuta($path);
    }

    private function generarMediaUrlDesdeRuta(string $relativePath): string
    {
        $relativePath = $this->normalizarMediaPath($relativePath);
        $url = '/media/' . collect(explode('/', $relativePath))->map(fn ($segment) => rawurlencode($segment))->implode('/');
        $disk = Storage::disk($this->mediaDisk());

        // La versión basada en lastModified fuerza al navegador a descargar un
        // archivo reemplazado aunque conserve el mismo nombre.
        if ($disk->exists($relativePath)) {
            return $url . '?v=' . $disk->lastModified($relativePath);
        }

        return $url;
    }

    private function mediaUrlIfExists(?string $relativePath): ?string
    {
        if (empty($relativePath)) {
            return null;
        }

        $relativePath = $this->normalizarMediaPath($relativePath);

        if (! Storage::disk($this->mediaDisk())->exists($relativePath)) {
            return null;
        }

        return $this->generarMediaUrlDesdeRuta($relativePath);
    }

    private function publicUploadUrl(?string $path): ?string
    {
        if (empty($path)) {
            return null;
        }

        if (! Storage::disk('public')->exists($path)) {
            return null;
        }

        return '/storage/' . collect(explode('/', trim(str_replace('\\', '/', $path), '/')))
            ->map(fn ($segment) => rawurlencode($segment))
            ->implode('/');
    }

    private function normalizarMediaPath(string $path): string
    {
        $path = trim(str_replace('\\', '/', $path), '/');
        $segments = array_filter(explode('/', $path), fn (string $segment) => $segment !== '' && $segment !== '.');

        // Impide salir del disco configurado mediante path traversal.
        abort_if(collect($segments)->contains('..'), 404);

        return implode('/', $segments);
    }

    private function eventosInicioDefault(): array
    {
        return collect(config('colegio.inicio.eventos_default', []))
            ->map(fn (array $evento) => [
                'titulo' => $evento['titulo'],
                'descripcion' => $evento['descripcion'],
                'url' => $this->mediaUrlIfExists($evento['media_path'] ?? null),
                'imagen' => [
                    'url' => $this->mediaUrlIfExists($evento['media_path'] ?? null),
                    'titulo' => $evento['titulo'],
                    'referencia' => 'Imagen default del carrusel de eventos en Inicio.',
                    'pendiente' => false,
                ],
            ])
            ->all();
    }

    private function testimoniosAlumni(): array
    {
        // Los registros del panel tienen prioridad sobre los videos descubiertos
        // automáticamente en la carpeta Testimonios Alumni.
        $testimonios = TestimonioVideo::where('activo', true)
            ->orderBy('orden')
            ->get()
            ->map(function (TestimonioVideo $video) {
                $url = $this->publicUploadUrl($video->video)
                    ?? $this->mediaUrlIfExists($video->video_media_path);

                return $url ? [
                    'titulo' => $video->titulo,
                    'url' => $url,
                ] : null;
            })
            ->filter()
            ->values()
            ->all();

        if (! empty($testimonios)) {
            return $testimonios;
        }

        return $this->mediaFiles('Testimonios Alumni')
            ->filter(fn (string $path) => in_array(strtolower(pathinfo($path, PATHINFO_EXTENSION)), config('colegio.media.video_extensions', [])))
            ->map(fn (string $path) => [
                'titulo' => pathinfo($path, PATHINFO_FILENAME),
                'url' => $this->generarMediaUrl($path),
            ])
            ->values()
            ->all();
    }

    private function mediaAcademiasVespertinas(): array
    {
        $imageExtensions = config('colegio.media.image_extensions', []);
        $videoExtensions = config('colegio.media.video_extensions', []);

        $archivos = $this->mediaFiles('Academias vespertinas')->sort();

        $imagenesDefault = $archivos
            ->filter(fn (string $path) => in_array(strtolower(pathinfo($path, PATHINFO_EXTENSION)), $imageExtensions, true))
            ->mapWithKeys(function (string $path): array {
                $nombre = pathinfo($path, PATHINFO_FILENAME);
                $clave = 'academia_' . str($nombre)->slug('_');

                return [$clave => [
                    'titulo' => str_replace(['-', '_'], ' ', $nombre),
                    'referencia' => 'Imagen de la academia ' . str_replace(['-', '_'], ' ', $nombre) . ' en la vista Academias Vespertinas.',
                    'media_path' => $path,
                ]];
            })
            ->all();

        // Los archivos actuales sirven como respaldo, pero cada posición puede
        // reemplazarse desde Imágenes del sitio.
        $imagenes = collect($this->imagenesVista('academias-vespertinas', $imagenesDefault))
            ->values()
            ->all();

        // Los videos siguen leyéndose desde la carpeta porque este recurso
        // administrativo está destinado únicamente a imágenes.
        $videos = $archivos
            ->filter(fn (string $path) => in_array(strtolower(pathinfo($path, PATHINFO_EXTENSION)), $videoExtensions, true))
            ->map(fn (string $path) => [
                'titulo' => str_replace(['-', '_'], ' ', pathinfo($path, PATHINFO_FILENAME)),
                'url' => $this->generarMediaUrl($path),
                'tipo' => 'video',
            ])
            ->values()
            ->all();

        return [
            'imagenes' => $imagenes,
            'videos' => $videos,
        ];
    }

    private function mapearMediaPaths(array $paths): array
    {
        return collect($paths)
            ->map(fn (string $path) => $this->mediaUrlIfExists($path))
            ->filter()
            ->all();
    }

    private function universidadesVinculacion(): array
    {
        return [
            ['nombre' => 'UVM', 'url' => asset('images/universidades/uvm.png')],
            ['nombre' => 'UPAEP', 'url' => asset('images/universidades/upaep.png')],
            ['nombre' => 'UDLAP', 'url' => asset('images/universidades/udlap.png')],
            ['nombre' => 'Anáhuac', 'url' => asset('images/universidades/anahuac.png')],
            ['nombre' => 'Ibero', 'url' => asset('images/universidades/ibero.png')],
            ['nombre' => 'Tec de Monterrey', 'url' => asset('images/universidades/tec-de-monterrey.png')],
            ['nombre' => 'Escuela Libre de Derecho', 'url' => asset('images/universidades/escuela-libre-de-derecho.png')],
            ['nombre' => 'Vatel', 'url' => asset('images/universidades/vatel.png')],
            ['nombre' => 'ITAM', 'url' => asset('images/universidades/itam.png')],
            ['nombre' => 'ISU', 'url' => asset('images/universidades/isu.png')],
            ['nombre' => 'Universidad Panamericana', 'url' => asset('images/universidades/universidad-panamericana.png')],
            ['nombre' => 'Unilomas', 'url' => asset('images/universidades/unilomas.png')],
        ];
    }

    private function prepararNivelOferta(string $slug, array $nivel): array
    {
        $default = $this->defaultConMediaUrl($nivel['imagen_default'] ?? []);
        $definicionNivel = config("colegio.niveles.definiciones.{$slug}", []);
        $logoDefault = $definicionNivel['logo_extendido_path']
            ?? $definicionNivel['logo_path']
            ?? $nivel['logo_path']
            ?? null;

        return [
            ...$nivel,
            'ruta' => route('nivel', $slug),
            'logo' => $logoDefault
                ? $this->imagenVista($slug, 'logo', [
                    'titulo' => $nivel['titulo'] . ' - Logo',
                    'referencia' => 'Logo mostrado en Oferta Educativa y en el encabezado del nivel.',
                    'media_path' => $logoDefault,
                ])['url']
                : null,
            'imagen' => $this->imagenVista('oferta-academica', $nivel['imagen_clave'], $default),
        ];
    }

    private function prepararComunidadProtagonistas(): array
    {
        $protagonistas = collect(config('colegio.protagonistas.protagonistas', []))
            ->map(fn (array $item, string $clave) => [
                'imagenes' => $this->imagenesGrupoProtagonista($clave, [
                    'titulo' => $item['titulo'],
                    'referencia' => $item['referencia'],
                    'media_path' => $item['media_path'],
                    'media_directory' => $item['media_directory'] ?? null,
                ]),
                'color' => $item['color'],
            ])
            ->map(function (array $item) {
                // Se elige una imagen distinta por petición para dar variedad al
                // mosaico, siempre dentro del grupo correspondiente.
                $item['imagen'] = $item['imagenes'][array_rand($item['imagenes'])] ?? [
                    'url' => null,
                    'titulo' => 'Comunidad Discovery®',
                    'referencia' => 'Imagen para la sección Quienes hacen viva nuestra comunidad.',
                    'pendiente' => true,
                ];

                return $item;
            })
            ->all();

        return compact('protagonistas');
    }

    private function imagenesGrupoProtagonista(string $clave, array $default): array
    {
        $registros = SeccionImagen::where('vista', 'protagonistas')
            ->where('activo', true)
            ->where(function ($query) use ($clave) {
                $query
                    ->where('clave', $clave)
                    ->orWhere('clave', 'like', "{$clave}\_%");
            })
            ->orderBy('orden')
            ->get();

        $imagenesDirectorio = collect();

        // Una carpeta configurada representa un grupo completo. Si está vacía,
        // se usan registros individuales del panel y finalmente el default.
        if (! empty($default['media_directory'])) {
            $imagenesDirectorio = $this->mediaFiles($default['media_directory'])
                ->filter(fn (string $path) => in_array(strtolower(pathinfo($path, PATHINFO_EXTENSION)), config('colegio.media.image_extensions', []), true))
                ->sort()
                ->map(fn (string $path) => [
                    'url' => $this->generarMediaUrl($path),
                    'titulo' => $default['titulo'] ?? pathinfo($path, PATHINFO_FILENAME),
                    'referencia' => $default['referencia'] ?? null,
                    'pendiente' => false,
                ]);
        }

        $imagenes = $imagenesDirectorio;

        if ($imagenes->isEmpty()) {
            $imagenes = $registros
                ->map(function (SeccionImagen $registro) {
                    $url = $this->publicUploadUrl($registro->imagen)
                        ?? $this->mediaUrlIfExists($registro->respaldo_media_path);

                    return $url ? [
                        'url' => $url,
                        'titulo' => $registro->titulo,
                        'referencia' => $registro->referencia,
                        'pendiente' => false,
                    ] : null;
                })
                ->filter()
                ->values();
        }

        if ($imagenes->isEmpty()) {
            return [$this->imagenVista('protagonistas', $clave, $default)];
        }

        return $imagenes
            ->unique('url')
            ->values()
            ->all();
    }

    private function defaultConMediaUrl(array $default): array
    {
        if (isset($default['media_path'])) {
            $default['url'] = $this->mediaUrlIfExists($default['media_path']);
        }

        return $default;
    }

    private function imagenVista(string $vista, string $clave, array $default): array
    {
        return $this->imagenesVista($vista, [$clave => $default])[$clave];
    }

    /**
     * Resuelve las posiciones visuales de una página.
     *
     * Prioridad: carga del panel, respaldo del panel, URL default, archivo
     * default de videosyfotos y finalmente un marcador pendiente.
     */
    private function imagenesVista(string $vista, array $defaults): array
    {
        $registros = SeccionImagen::where('vista', $vista)
            ->where('activo', true)
            ->whereIn('clave', array_keys($defaults))
            ->get()
            ->keyBy('clave');

        return collect($defaults)
            ->map(function (array $default, string $clave) use ($registros) {
                $registro = $registros->get($clave);
                $imagen = $this->publicUploadUrl($registro?->imagen)
                    ?? $this->mediaUrlIfExists($registro?->respaldo_media_path)
                    ?? ($default['url'] ?? null)
                    ?? $this->mediaUrlIfExists($default['media_path'] ?? null);

                return [
                    'url' => $imagen,
                    'titulo' => $registro?->titulo ?? $default['titulo'] ?? $clave,
                    'referencia' => $registro?->referencia ?? $default['referencia'] ?? null,
                    'pendiente' => empty($imagen),
                ];
            })
            ->all();
    }

    private function listasUtilesDesdeAdmin(): array
    {
        return ListaUtil::query()
            ->where('activo', true)
            ->orderByDesc('ciclo_escolar')
            ->orderBy('nivel')
            ->orderBy('orden')
            ->orderBy('grado')
            ->get()
            ->map(fn (ListaUtil $lista) => [
                'grado' => $lista->grado,
                'nivel' => $this->nombreNivelListaUtiles($lista->nivel),
                'titulo' => $lista->titulo,
                'ciclo' => $lista->ciclo_escolar,
                'url' => $this->publicUploadUrl($lista->archivo_pdf),
            ])
            ->filter(fn (array $lista) => ! empty($lista['url']))
            ->groupBy('nivel')
            ->sortKeysUsing(fn (string $a, string $b) => $this->ordenarNivelListaUtiles($a) <=> $this->ordenarNivelListaUtiles($b))
            ->map(fn ($listas) => $listas->values()->all())
            ->all();
    }

    private function listasUtilesDesdeCarpeta(): array
    {
        return $this->mediaFiles('Listas de útiles')
            ->filter(fn (string $path) => strtolower(pathinfo($path, PATHINFO_EXTENSION)) === 'pdf')
            ->map(fn ($file) => [
                'grado' => $this->obtenerGradoListaUtiles(basename($file)),
                'nivel' => $this->obtenerNivelListaUtiles(basename($file)),
                'titulo' => pathinfo($file, PATHINFO_FILENAME),
                'url' => $this->generarMediaUrl($file),
            ])
            ->sortBy(fn ($lista) => $this->ordenarListaUtiles($lista['grado']))
            ->groupBy('nivel')
            ->sortKeysUsing(fn (string $a, string $b) => $this->ordenarNivelListaUtiles($a) <=> $this->ordenarNivelListaUtiles($b))
            ->map(fn ($listas) => $listas->values()->all())
            ->all();
    }

    private function ordenarNivelListaUtiles(string $nivel): int
    {
        return [
            'Kinder' => 10,
            'Preescolar' => 10,
            'Elementary' => 20,
            'Primaria' => 20,
            'Middle' => 30,
            'Secundaria' => 30,
            'High' => 40,
            'Bachillerato' => 40,
            'General' => 50,
        ][$nivel] ?? 999;
    }

    private function nombreNivelListaUtiles(string $nivel): string
    {
        return [
            'Preescolar' => 'Kinder',
            'Primaria' => 'Elementary',
            'Secundaria' => 'Middle',
            'Bachillerato' => 'High',
        ][$nivel] ?? $nivel;
    }

    private function obtenerGradoListaUtiles(string $filename): string
    {
        if (preg_match('/(\d{1,2})\s*[º°]/u', $filename, $matches)) {
            return $matches[1] . ' grado';
        }

        return pathinfo($filename, PATHINFO_FILENAME);
    }

    private function obtenerNivelListaUtiles(string $filename): string
    {
        if (preg_match('/\b(1|2|3|4|5|6)\s*[º°]/u', $filename)) {
            return 'Elementary';
        }

        if (preg_match('/\b(7|8|9)\s*[º°]/u', $filename)) {
            return 'Middle';
        }

        if (preg_match('/\b(10|11|12)\s*[º°]/u', $filename)) {
            return 'High';
        }

        return 'General';
    }

    private function ordenarListaUtiles(string $grado): int
    {
        if (preg_match('/\d+/', $grado, $matches)) {
            return (int) $matches[0];
        }

        return 999;
    }

    private function obtenerDefinicionNiveles(): array
    {
        return config('colegio.niveles.definiciones', []);
    }

    private function obtenerTemaNivel(string $nivel): array
    {
        return config("colegio.temas_niveles.{$nivel}", config('colegio.temas_niveles.default', []));
    }

    private function obtenerHistoriaNosotros(): array
    {
        // Estas definiciones mantienen la página funcional en instalaciones
        // nuevas. Cuando existen hitos en Filament, ellos son la fuente oficial.
        $imagenesHistoria = collect([
            'historia_2003' => [
                'titulo' => 'Nosotros - Historia 2003',
                'referencia' => 'Imagen para el hito Discovery® Kinder en la línea del tiempo de Nosotros.',
                'media_path' => 'Linea del tiempo/2003-1.jpg',
            ],
            'historia_2003_2' => [
                'titulo' => 'Nosotros - Historia 2003 - Imagen secundaria',
                'referencia' => 'Imagen secundaria para el hito Discovery® Kinder en la línea del tiempo de Nosotros.',
                'media_path' => 'Linea del tiempo/2003-2.jpg',
            ],
            'historia_2005' => [
                'titulo' => 'Nosotros - Historia 2005',
                'referencia' => 'Imagen para el hito Discovery® Elementary en la línea del tiempo de Nosotros.',
                'media_path' => 'Linea del tiempo/2005-1.jpg',
            ],
            'historia_2005_2' => [
                'titulo' => 'Nosotros - Historia 2005 - Imagen secundaria',
                'referencia' => 'Imagen secundaria para el hito Discovery® Elementary en la línea del tiempo de Nosotros.',
                'media_path' => 'Linea del tiempo/2005-2.jpg',
            ],
            'historia_2011' => [
                'titulo' => 'Nosotros - Historia 2011',
                'referencia' => 'Imagen para el hito Discovery® Middle en la línea del tiempo de Nosotros.',
                'media_path' => 'Linea del tiempo/2011-1.jpg',
            ],
            'historia_2016' => [
                'titulo' => 'Nosotros - Historia 2016',
                'referencia' => 'Imagen para el hito Discovery® High en la línea del tiempo de Nosotros.',
                'media_path' => 'Linea del tiempo/2016-1.jpg',
            ],
            'historia_2018' => [
                'titulo' => 'Nosotros - Historia 2018',
                'referencia' => 'Imagen para el hito Colegio del Mundo IB en la línea del tiempo de Nosotros.',
                'media_path' => 'Linea del tiempo/2018-1.jpg',
            ],
            'historia_2019' => [
                'titulo' => 'Nosotros - Historia 2019',
                'referencia' => 'Imagen para el hito Nuevas instalaciones en la línea del tiempo de Nosotros.',
                'media_path' => 'Linea del tiempo/2019-1.jpg',
            ],
            'historia_2019_2' => [
                'titulo' => 'Nosotros - Historia 2019 - Imagen secundaria',
                'referencia' => 'Imagen secundaria para el hito Nuevas instalaciones en la línea del tiempo de Nosotros.',
                'media_path' => 'Linea del tiempo/2019-2.jpg',
            ],
            'historia_2023' => [
                'titulo' => 'Nosotros - Historia 2023',
                'referencia' => 'Imagen para el hito DKMUN primera edición en la línea del tiempo de Nosotros.',
                'media_path' => 'Linea del tiempo/2023-1.jpg',
            ],
            'historia_2023_2' => [
                'titulo' => 'Nosotros - Historia 2023 - Imagen secundaria',
                'referencia' => 'Imagen secundaria para el hito DKMUN primera edición en la línea del tiempo de Nosotros.',
                'media_path' => 'Linea del tiempo/2023-2.jpg',
            ],
            'historia_2025' => [
                'titulo' => 'Nosotros - Historia 2025',
                'referencia' => 'Imagen para el hito Actualmente en la línea del tiempo de Nosotros.',
                'media_path' => 'Linea del tiempo/2025-1.jpg',
            ],
        ])->map(fn (array $imagen) => [
            'url' => null,
            'titulo' => $imagen['titulo'],
            'referencia' => $imagen['referencia'],
            'pendiente' => true,
        ])->all();

        $historiaDefault = [
            ['anio' => '2003', 'titulo' => 'Discovery® Kinder', 'texto' => 'Nace Discovery® Kinder, el inicio de un sueño educativo porque los primeros pasos trascienden.', 'imagenes' => [$imagenesHistoria['historia_2003'], $imagenesHistoria['historia_2003_2']]],
            ['anio' => '2005', 'titulo' => 'Discovery® Elementary', 'texto' => 'Inauguración de Discovery® Elementary, creciendo con pasos firmes.', 'imagenes' => [$imagenesHistoria['historia_2005'], $imagenesHistoria['historia_2005_2']]],
            ['anio' => '2011', 'titulo' => 'Discovery® Middle', 'texto' => 'Se suma Discovery® Middle, ampliando horizontes.', 'imagenes' => [$imagenesHistoria['historia_2011']]],
            ['anio' => '2016', 'titulo' => 'Discovery® High', 'texto' => 'Llega Discovery® High, preparando grandes Explorers y descubriendo su potencial.', 'imagenes' => [$imagenesHistoria['historia_2016']]],
            ['anio' => '2018', 'titulo' => 'Colegio del Mundo', 'texto' => 'Nos convertimos en Colegio del Mundo IB, abrazando la educación internacional.', 'imagenes' => [$imagenesHistoria['historia_2018']]],
            ['anio' => '2019', 'titulo' => 'Nuevas instalaciones', 'texto' => 'Estrenamos nuevas instalaciones para seguir innovando.', 'imagenes' => [$imagenesHistoria['historia_2019'], $imagenesHistoria['historia_2019_2']]],
            ['anio' => '2023', 'titulo' => 'DKMUN primera edición', 'texto' => 'Realizamos nuestra primera edición DKMUN, un espacio para el debate y la diplomacia.', 'imagenes' => [$imagenesHistoria['historia_2023'], $imagenesHistoria['historia_2023_2']]],
            ['anio' => '2025', 'titulo' => 'Actualmente', 'texto' => 'Seguimos escribiendo nuestra historia, creciendo y evolucionando juntos.', 'imagenes' => [$imagenesHistoria['historia_2025']]],
        ];

        $registros = HitoHistoria::orderBy('orden')->get();

        if ($registros->isEmpty()) {
            return $historiaDefault;
        }

        $fallbacksPorAnio = collect($historiaDefault)->keyBy('anio');

        return $registros
            ->map(function (HitoHistoria $hito) use ($fallbacksPorAnio) {
                $fallback = $fallbacksPorAnio->get($hito->anio, ['imagenes' => []]);
                $imagenes = $fallback['imagenes'] ?? [];

                if ($url = $this->publicUploadUrl($hito->imagen_url) ?? $this->mediaUrlIfExists($hito->imagen_media_path)) {
                    $imagenes[0] = [
                        'url' => $url,
                        'titulo' => $hito->titulo,
                        'referencia' => 'Imagen principal del hito ' . $hito->titulo . '.',
                        'pendiente' => false,
                    ];
                }

                if ($url = $this->publicUploadUrl($hito->imagen_secundaria_url) ?? $this->mediaUrlIfExists($hito->imagen_secundaria_media_path)) {
                    $imagenes[1] = [
                        'url' => $url,
                        'titulo' => $hito->titulo . ' - Imagen secundaria',
                        'referencia' => 'Imagen secundaria del hito ' . $hito->titulo . '.',
                        'pendiente' => false,
                    ];
                }

                return [
                    'anio' => $hito->anio,
                    'titulo' => $hito->titulo,
                    'texto' => $hito->texto,
                    'imagenes' => collect($imagenes)->filter(fn (array $imagen) => ! empty($imagen['url']))->values()->all(),
                ];
            })
            ->values()
            ->all();
    }
}
