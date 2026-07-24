<?php

namespace App\Http\Controllers;

use App\Models\Evento;
use App\Models\PaginaContenido;
use App\Models\SeccionImagen;
use App\Models\TestimonioVideo;
use App\Services\EditablePageContentService;
use App\Services\HistoryTimelineService;
use App\Services\HomeBannerService;
use App\Services\LevelGalleryService;
use App\Services\LevelContentService;
use App\Services\MediaResolver;
use App\Services\PromotionalVideoService;
use App\Services\SchoolCalendarService;
use App\Services\SchoolSupplyListService;
use App\Support\SiteCache;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
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
    public function __construct(
        private readonly MediaResolver $media,
        private readonly HistoryTimelineService $historyTimeline,
        private readonly SchoolSupplyListService $schoolSupplyLists,
        private readonly LevelContentService $levelContent,
        private readonly LevelGalleryService $levelGallery,
        private readonly HomeBannerService $homeBanners,
        private readonly SchoolCalendarService $schoolCalendar,
        private readonly PromotionalVideoService $promotionalVideos,
        private readonly EditablePageContentService $editablePages,
    ) {}

    /**
     * Recupera contenido editable sin almacenar un modelo Eloquent completo
     * en caché. Guardar solo el ID evita serializaciones y datos obsoletos.
     */
    private function paginaContenido(string $slug): ?PaginaContenido
    {
        return $this->editablePages->get($slug);
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
                    $level = $evento->nivel ?: 'general';
                    $title = $this->eventTitle($evento->titulo, $level);
                    $description = $this->eventDescription($evento->descripcion, $level);
                    $imagen = $eventosDefault[$index]['imagen'] ?? null;
                    $url = $this->media->uploadedOrMediaUrl($evento->imagen_url, $evento->imagen_media_path);

                    if ($url) {
                        $imagen = [
                            'url' => $url,
                            'titulo' => $title,
                            'referencia' => __('site.pages.home.event_image_reference'),
                            'pendiente' => false,
                        ];
                    }

                    return [
                        'titulo' => $title,
                        'descripcion' => $description,
                        'nivel' => $evento->nivel ?: 'general',
                        'nivel_etiqueta' => $this->eventLevelLabel($level),
                        'url' => $imagen['url'] ?? null,
                        'imagen' => $imagen,
                    ];
                })->values()->all();

            if (! empty($eventos)) {
                return $eventos;
            }

            if ($hayEventosAdmin) {
                return [];
            }

            return $eventosDefault;
        });

        // La lectura de videos del disco es costosa; se reutiliza entre visitas.
        $testimonios = Cache::remember(SiteCache::key('inicio_testimonios'), SiteCache::ttl(), fn () => $this->testimoniosAlumni());

        $logosNiveles = $this->media->images('inicio', collect(config('colegio.inicio.logos_niveles', []))
            ->mapWithKeys(fn (string $path, string $nivel) => [
                "logo_{$nivel}" => [
                    'titulo' => __('site.pages.home.level_logo_title', ['level' => __("site.nav.levels.{$nivel}")]),
                    'referencia' => __('site.pages.home.level_logo_reference'),
                    'media_path' => $path,
                ],
            ])
            ->all());
        $bannerInicioSlides = $this->homeBanners->get();
        $proximasFechas = $this->schoolCalendar->upcoming();

        $imagenesInicio = $this->media->images('inicio', [
            'sobre_nosotros' => [
                'titulo' => __('site.pages.home.about_image_title'),
                'referencia' => __('site.pages.home.about_image_reference'),
                'url' => $this->media->publicUploadUrl($paginaInicio?->imagen_principal),
                'media_path' => 'Kinder fotos actuales/IMG_5775.JPG',
            ],
        ]);

        $nivelesInicio = collect($this->levelContent->offerDefinitions())
            ->only(['preescolar', 'primaria', 'secundaria', 'bachillerato'])
            ->all();
        $videosPromocionales = $this->promotionalVideos->featured();

        return view('pages.inicio', compact('eventos', 'testimonios', 'logosNiveles', 'imagenesInicio', 'paginaInicio', 'bannerInicioSlides', 'nivelesInicio', 'proximasFechas', 'videosPromocionales'));
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

    private function eventLevelLabel(string $level): string
    {
        return __("site.event_levels.{$level}");
    }

    private function eventTitle(string $title, string $level): string
    {
        return app()->getLocale() === 'es'
            ? $title
            : __('site.pages.home.dynamic_event_title', ['level' => $this->eventLevelLabel($level)]);
    }

    private function eventDescription(?string $description, string $level): string
    {
        if (app()->getLocale() === 'es') {
            return $description ?? __('site.pages.home.dynamic_event_description', ['level' => $this->eventLevelLabel($level)]);
        }

        return __('site.pages.home.dynamic_event_description', ['level' => $this->eventLevelLabel($level)]);
    }

    private function localizedMapUrl(?string $url): ?string
    {
        if (! $url) {
            return null;
        }

        $locale = app()->getLocale() === 'en' ? 'en-US' : 'es-MX';
        $separator = str_contains($url, '?') ? '&' : '?';

        return preg_match('/([?&])hl=[^&]*/', $url)
            ? preg_replace('/([?&])hl=[^&]*/', '$1hl='.$locale, $url)
            : $url.$separator.'hl='.$locale;
    }

    private function localizedVideoTitle(string $title, int $index): string
    {
        return app()->getLocale() === 'es'
            ? $title
            : __('site.pages.community.video_generic_title', ['number' => $index + 1]);
    }

    public function nosotros(): View
    {
        $paginaNosotros = $this->paginaContenido('nosotros');

        $imagenesNosotros = $this->media->images('nosotros', [
            'hero' => [
                'titulo' => 'Nosotros - Imagen principal',
                'referencia' => 'Imagen grande del encabezado de la página Nosotros.',
                'url' => $this->media->publicUploadUrl($paginaNosotros?->imagen_principal),
                'media_path' => 'Logos principales/LOGO DISCOVERY PNG.png',
            ],
            'modelo' => [
                'titulo' => 'Nosotros - Modelo educativo',
                'referencia' => 'Imagen de apoyo para la sección de modelo educativo.',
                'url' => $this->media->publicUploadUrl($paginaNosotros?->imagen_secundaria),
                'media_path' => 'Modelos educativos/modelo-educativo-Principal.png',
            ],
        ]);

        $historiaNosotros = Cache::remember(SiteCache::key('nosotros_historia'), SiteCache::ttl(), fn () => $this->historyTimeline->get());
        return view('pages.nosotros', compact('imagenesNosotros', 'historiaNosotros', 'paginaNosotros'));
    }

    public function ofertaAcademica(): View
    {
        $paginaOferta = $this->paginaContenido('oferta-academica');

        // La estructura de los niveles vive en configuración; aquí se agregan
        // rutas públicas y las imágenes reemplazables desde Filament.
        $ofertaNiveles = collect($this->levelContent->offerDefinitions())
            ->map(fn (array $nivel, string $slug) => $this->prepararNivelOferta($slug, $nivel))
            ->all();
        $universidadesVinculacion = $this->universidadesVinculacion();
        $videosPromocionales = $this->promotionalVideos->getAll();

        return view('pages.oferta-academica', compact('ofertaNiveles', 'paginaOferta', 'universidadesVinculacion', 'videosPromocionales'));
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
        $mediaAcademias = Cache::remember(
            SiteCache::key('academias_media'),
            SiteCache::ttl(),
            fn () => $this->mediaAcademiasVespertinas(),
        );

        return view('pages.academias-vespertinas', compact('mediaAcademias'));
    }

    public function recursosEscolares(Request $request): View
    {
        $listasUtiles = Cache::remember(
            SiteCache::key('recursos_listas_utiles'),
            SiteCache::ttl(),
            fn () => $this->schoolSupplyLists->get(),
        );

        $calendarioEscolar = $this->media->image('recursos-escolares', 'calendario', [
            'titulo' => __('site.pages.resources.school_calendar'),
            'referencia' => __('site.pages.resources.school_calendar_image_reference'),
            'media_path' => 'Calendario Escolar/Calendario Escolar 2025-2026.jpg',
        ]);
        $calendarioMensual = $this->schoolCalendar->month($request->query('mes'));

        return view('pages.recursos-escolares', compact('listasUtiles', 'calendarioEscolar', 'calendarioMensual'));
    }

    public function contacto(): View
    {
        // Igual que paginaContenido(), se almacena el ID y se recupera un
        // modelo fresco para evitar serializar Eloquent dentro del caché.
        $pagina = $this->paginaContenido('contacto');
        $imagenesContacto = $this->media->images('contacto', [
            'hero' => [
                'titulo' => 'Contacto - Imagen principal',
                'referencia' => 'Imagen principal de la vista Contacto.',
                'url' => $this->media->publicUploadUrl($pagina?->imagen_principal),
            ],
            'secundaria' => [
                'titulo' => 'Contacto - Imagen secundaria',
                'referencia' => 'Imagen secundaria de apoyo de la vista Contacto.',
                'url' => $this->media->publicUploadUrl($pagina?->imagen_secundaria),
            ],
        ]);

        $mapaUrl = $this->localizedMapUrl($pagina?->mapaEmbedUrl() ?? config('colegio.contacto.mapa_embed_url'));
        $mapaExternoUrl = $this->localizedMapUrl(config('colegio.contacto.mapa_url'));

        return view('pages.contacto', compact('pagina', 'imagenesContacto', 'mapaUrl', 'mapaExternoUrl'));
    }

    /**
     * Lógica detallada para los niveles académicos.
     */
    public function nivel(string $nivel): View
    {
        // Los slugs permitidos se definen en config/colegio.php. No se acepta
        // cualquier valor de URL porque después se usa para buscar multimedia.
        $niveles = $this->levelContent->definitions();
        abort_unless(isset($niveles[$nivel]), 404);

        $carpetas = config('colegio.niveles.carpetas_galeria', []);

        $galeria = $this->levelGallery->get($nivel, $niveles[$nivel]['titulo']);

        $nivelContenido = $niveles[$nivel];
        $nivelContenido['slug'] = $nivel;
        $nivelContenido['tema'] = $this->obtenerTemaNivel($nivel);
        $logoNivel = $this->media->image($nivel, 'logo', [
            'titulo' => $nivelContenido['titulo'].' - Logo del encabezado',
            'referencia' => 'Logo mostrado sobre el título en el encabezado del nivel.',
            'media_path' => $nivelContenido['logo_extendido_path'] ?? $nivelContenido['logo_path'] ?? null,
        ]);
        $nivelContenido['logo'] = $logoNivel['url'];
        $nivelContenido['logo_extendido'] = $logoNivel['url'];
        $nivelContenido['hoja_informativa_url'] = isset($nivelContenido['hoja_informativa_path'])
            ? $this->media->url($nivelContenido['hoja_informativa_path'])
            : null;
        $nivelContenido['modelo_academico'] = ! empty($nivelContenido['modelo_academico_path'])
            ? $this->media->image($nivel, 'modelo_academico', [
                'titulo' => $nivelContenido['titulo'].' - Modelo académico',
                'referencia' => 'Infografía del modelo académico mostrada en la página del nivel.',
                'media_path' => $nivelContenido['modelo_academico_path'],
            ])
            : null;
        if (! empty($nivelContenido['informacion']['imagenes_referencia'])) {
            $nivelContenido['informacion']['imagenes_referencia'] = collect($nivelContenido['informacion']['imagenes_referencia'])
                ->map(function (array $imagen) {
                    $imagen['url'] = $imagen['url'] ?? $this->media->urlIfExists($imagen['media_path'] ?? null);

                    return $imagen;
                })
                ->all();
        }
        if (! empty($nivelContenido['informacion']['imagen_enfoque'])) {
            $nivelContenido['informacion']['imagen_enfoque'] = $this->media->image(
                $nivel,
                'imagen_enfoque',
                $nivelContenido['informacion']['imagen_enfoque'],
            );
        }
        // POP usa posiciones de imagen administrables propias; los demás
        // layouts resuelven sus imágenes mediante las claves generales.
        if (($nivelContenido['layout'] ?? null) === 'pop' && ! empty($nivelContenido['informacion']['imagenes'])) {
            $nivelContenido['informacion']['imagenes'] = $this->media->images(
                'pop-del-ib',
                $nivelContenido['informacion']['imagenes'],
            );
        }
        $imagenGaleriaPrincipal = $galeria[0]['url'] ?? null;
        $mediaPathGaleriaPrincipal = $nivelContenido['hero_media_path'] ?? (isset($carpetas[$nivel], $imagenGaleriaPrincipal)
            ? $carpetas[$nivel].'/'.basename($imagenGaleriaPrincipal)
            : ($nivelContenido['usar_placeholder_hero'] ?? false ? null : ($nivelContenido['logo_path'] ?? null)));
        $imagenPrincipalDefault = [
            'titulo' => $nivelContenido['titulo'].' - Imagen principal',
            'referencia' => 'Imagen principal del encabezado del nivel '.$nivelContenido['titulo'].'.',
            'url' => $imagenGaleriaPrincipal,
            'media_path' => $mediaPathGaleriaPrincipal,
        ];

        // High comparte la imagen promocional de Oferta Educativa. El resto de
        // niveles administra su hero bajo su propia vista y la clave "hero".
        if ($nivel === 'bachillerato') {
            $ofertaHigh = $this->levelContent->offerDefinitions()['bachillerato'] ?? [];
            $nivelContenido['imagen_principal'] = $this->media->image(
                'oferta-academica',
                $ofertaHigh['imagen_clave'] ?? 'bachillerato',
                $this->media->defaultWithUrl($ofertaHigh['imagen_default'] ?? $imagenPrincipalDefault),
            );
        } else {
            $nivelContenido['imagen_principal'] = $this->media->image($nivel, 'hero', $imagenPrincipalDefault);
        }

        return view('pages.nivel', [
            'nivel' => $nivelContenido,
            'galeria' => $galeria,
            'videosPromocionales' => $this->promotionalVideos->get($nivel),
        ]);
    }

    /**
     * Sirve los archivos multimedia de forma segura.
     */
    public function serveMedia(string $path): BinaryFileResponse
    {
        $filePath = null;

        try {
            $filePath = $this->media->filePath($path);
        } catch (Throwable $exception) {
            Log::warning('No se pudo servir el archivo multimedia.', [
                'path' => $path,
                'disk' => $this->media->diskName(),
                'error' => $exception->getMessage(),
            ]);

            abort(404);
        }

        abort_unless($filePath && is_file($filePath), 404);

        return response()->file($filePath, [
            'Cache-Control' => 'public, max-age=31536000, immutable',
        ]);
    }

    private function eventosInicioDefault(): array
    {
        return collect(config('colegio.inicio.eventos_default', []))
            ->map(fn (array $evento, int $index) => [
                'titulo' => __('site.pages.home.default_events.'.$index.'.title'),
                'descripcion' => __('site.pages.home.default_events.'.$index.'.description'),
                'nivel' => 'general',
                'nivel_etiqueta' => $this->eventLevelLabel('general'),
                'url' => $this->media->urlIfExists($evento['media_path'] ?? null),
                'imagen' => [
                    'url' => $this->media->urlIfExists($evento['media_path'] ?? null),
                    'titulo' => __('site.pages.home.default_events.'.$index.'.title'),
                    'referencia' => __('site.pages.home.event_image_reference'),
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
                $url = $this->media->uploadedOrMediaUrl($video->video, $video->video_media_path);

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

        return $this->media->videoFiles('Testimonios Alumni')
            ->map(fn (string $path, int $index) => [
                'titulo' => $this->localizedVideoTitle(pathinfo($path, PATHINFO_FILENAME), $index),
                'url' => $this->media->url($path),
            ])
            ->values()
            ->all();
    }

    private function mediaAcademiasVespertinas(): array
    {
        $imagenesDefault = $this->media->imageFiles('Academias vespertinas')
            ->sort()
            ->mapWithKeys(function (string $path): array {
                $nombre = pathinfo($path, PATHINFO_FILENAME);
                $clave = 'academia_'.str($nombre)->slug('_');

                return [$clave => [
                    'titulo' => str_replace(['-', '_'], ' ', $nombre),
                    'referencia' => 'Imagen de la academia '.str_replace(['-', '_'], ' ', $nombre).' en la vista Academias Vespertinas.',
                    'media_path' => $path,
                ]];
            })
            ->all();

        // Los archivos actuales sirven como respaldo, pero cada posición puede
        // reemplazarse desde Imágenes del sitio.
        $imagenes = collect($this->media->images('academias-vespertinas', $imagenesDefault))
            ->values()
            ->all();

        // Los videos siguen leyéndose desde la carpeta porque este recurso
        // administrativo está destinado únicamente a imágenes.
        $videos = $this->media->videoFiles('Academias vespertinas')
            ->sort()
            ->map(fn (string $path, int $index) => [
                'titulo' => app()->getLocale() === 'es'
                    ? str_replace(['-', '_'], ' ', pathinfo($path, PATHINFO_FILENAME))
                    : __('site.pages.academies.media_title', ['number' => $index + 1]),
                'url' => $this->media->url($path),
                'tipo' => 'video',
            ])
            ->values()
            ->all();

        return [
            'imagenes' => $imagenes,
            'videos' => $videos,
        ];
    }

    private function universidadesVinculacion(): array
    {
        $universidades = [
            [
                'key' => 'uvm',
                'nombre' => 'UVM',
                'logo' => asset('images/universidades/uvm.png'),
                'sitio' => 'https://uvm.mx/',
            ],
            [
                'key' => 'upaep',
                'nombre' => 'UPAEP',
                'logo' => asset('images/universidades/upaep.png'),
                'sitio' => 'https://www.upaep.mx/',
                'convocatoria' => true,
            ],
            [
                'key' => 'udlap',
                'nombre' => 'UDLAP',
                'logo' => asset('images/universidades/udlap.png'),
                'sitio' => 'https://www.udlap.mx/web/',
            ],
            [
                'key' => 'anahuac',
                'nombre' => 'Anáhuac Puebla',
                'logo' => asset('images/universidades/anahuac.png'),
                'sitio' => 'https://www.anahuac.mx/puebla/',
                'convocatoria' => true,
            ],
            [
                'key' => 'ibero',
                'nombre' => 'Ibero Puebla',
                'logo' => asset('images/universidades/ibero.png'),
                'sitio' => 'https://www.iberopuebla.mx/',
                'convocatoria' => true,
            ],
            [
                'key' => 'tec',
                'nombre' => 'Tec de Monterrey',
                'logo' => asset('images/universidades/tec-de-monterrey.png'),
                'sitio' => 'https://tec.mx/es',
            ],
            [
                'key' => 'eldp',
                'nombre' => 'Escuela Libre de Derecho de Puebla',
                'logo' => asset('images/universidades/escuela-libre-de-derecho.png'),
                'sitio' => 'https://eldp.edu.mx/',
                'convocatoria' => true,
            ],
            [
                'key' => 'vatel',
                'nombre' => 'Vatel',
                'logo' => asset('images/universidades/vatel.png'),
                'sitio' => 'https://www.vatel.mx/',
                'convocatoria' => true,
            ],
            [
                'key' => 'itam',
                'nombre' => 'ITAM',
                'logo' => asset('images/universidades/itam.png'),
                'sitio' => 'https://www.itam.mx/',
                'convocatoria' => true,
            ],
            [
                'key' => 'isu',
                'nombre' => 'ISU Universidad',
                'logo' => asset('images/universidades/isu.png'),
                'sitio' => 'https://isu.edu.mx/',
                'convocatoria' => true,
            ],
            [
                'key' => 'inqba',
                'nombre' => 'INQBA',
                'logo' => asset('images/universidades/inqba.png'),
                'sitio' => 'https://inqba.edu.mx/',
                'convocatoria' => true,
            ],
            [
                'key' => 'unilomas',
                'nombre' => 'Unilomas',
                'logo' => asset('images/universidades/unilomas.png'),
                'sitio' => 'https://www.unilomas.mx/',
            ],
        ];

        $traducciones = __('site.pages.offer.universities');

        return array_map(function (array $universidad) use ($traducciones): array {
            $contenido = is_array($traducciones)
                ? ($traducciones[$universidad['key']] ?? [])
                : [];

            return [
                ...$universidad,
                'resumen' => $contenido['summary'] ?? '',
                'beneficios' => $contenido['benefits'] ?? [],
            ];
        }, $universidades);
    }

    private function prepararNivelOferta(string $slug, array $nivel): array
    {
        $default = $this->media->defaultWithUrl($nivel['imagen_default'] ?? []);
        $definicionNivel = $this->levelContent->definitions()[$slug] ?? [];
        $logoDefault = $definicionNivel['logo_extendido_path']
            ?? $definicionNivel['logo_path']
            ?? $nivel['logo_path']
            ?? null;

        return [
            ...$nivel,
            'ruta' => route('nivel', $slug),
            'logo' => $logoDefault
                ? $this->media->image($slug, 'logo', [
                    'titulo' => __('site.pages.offer.level_logo_title', ['level' => $nivel['titulo']]),
                    'referencia' => __('site.pages.offer.level_logo_reference'),
                    'media_path' => $logoDefault,
                ])['url']
                : null,
            'imagen' => $this->media->image('oferta-academica', $nivel['imagen_clave'], $default),
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
                    'titulo' => __('site.pages.community.image_title'),
                    'referencia' => __('site.pages.community.image_reference'),
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
            $imagenesDirectorio = $this->media->imageFiles($default['media_directory'])
                ->sort()
                ->map(fn (string $path) => [
                    'url' => $this->media->url($path),
                    'titulo' => app()->getLocale() === 'es'
                        ? ($default['titulo'] ?? pathinfo($path, PATHINFO_FILENAME))
                        : __('site.pages.community.image_title'),
                    'referencia' => app()->getLocale() === 'es'
                        ? ($default['referencia'] ?? null)
                        : __('site.pages.community.image_reference'),
                    'pendiente' => false,
                ]);
        }

        $imagenes = $imagenesDirectorio;

        if ($imagenes->isEmpty()) {
            $imagenes = $registros
                ->map(function (SeccionImagen $registro) {
                    $url = $this->media->uploadedOrMediaUrl($registro->imagen, $registro->respaldo_media_path);

                    return $url ? [
                        'url' => $url,
                        'titulo' => app()->getLocale() === 'es' ? $registro->titulo : __('site.pages.community.image_title'),
                        'referencia' => app()->getLocale() === 'es' ? $registro->referencia : __('site.pages.community.image_reference'),
                        'pendiente' => false,
                    ] : null;
                })
                ->filter()
                ->values();
        }

        if ($imagenes->isEmpty()) {
            return [$this->media->image('protagonistas', $clave, $default)];
        }

        return $imagenes
            ->unique('url')
            ->values()
            ->all();
    }

    private function obtenerTemaNivel(string $nivel): array
    {
        return config("colegio.temas_niveles.{$nivel}", config('colegio.temas_niveles.default', []));
    }
}
