<?php

namespace App\Http\Controllers;

use App\Models\Evento;
use App\Models\PaginaContenido;
use App\Models\SeccionImagen;
use App\Models\TestimonioVideo;
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
    ) {}

    /**
     * Recupera contenido editable sin almacenar un modelo Eloquent completo
     * en caché. Guardar solo el ID evita serializaciones y datos obsoletos.
     */
    private function paginaContenido(string $slug): ?PaginaContenido
    {
        $paginaId = Cache::remember(SiteCache::key("pagina_contenido.{$slug}"), SiteCache::ttl(), function () use ($slug) {
            return PaginaContenido::where('slug', $slug)->value('id');
        });

        $pagina = $paginaId ? PaginaContenido::find($paginaId) : null;

        return $this->localizarPaginaContenido($slug, $pagina);
    }

    private function localizarPaginaContenido(string $slug, ?PaginaContenido $pagina): ?PaginaContenido
    {
        if (! $pagina || app()->getLocale() === 'es') {
            return $pagina;
        }

        $translationMap = [
            'inicio' => [
                'subtitulo' => 'site.pages.home.about_subtitle',
                'titulo' => 'site.pages.home.about_title',
                'descripcion' => 'site.pages.home.about_text',
            ],
            'nosotros' => [
                'subtitulo' => 'site.pages.about.hero_subtitle',
                'titulo' => 'site.pages.about.hero_title',
                'descripcion' => 'site.pages.about.hero_text',
            ],
            'oferta-academica' => [
                'subtitulo' => 'site.pages.offer.default_subtitle',
                'titulo' => 'site.pages.offer.default_title',
                'descripcion' => 'site.pages.offer.default_text',
            ],
            'protagonistas' => [
                'subtitulo' => 'site.pages.community.hero_subtitle',
                'titulo' => 'site.pages.community.hero_title',
                'descripcion' => 'site.pages.community.hero_text',
            ],
            'contacto' => [
                'subtitulo' => 'site.pages.contact.hero_subtitle',
                'titulo' => 'site.pages.contact.hero_title',
                'descripcion' => 'site.pages.contact.hero_text',
            ],
        ];

        if (! isset($translationMap[$slug])) {
            return $pagina;
        }

        $localized = clone $pagina;

        foreach ($translationMap[$slug] as $attribute => $translationKey) {
            $localized->setAttribute($attribute, __($translationKey));
        }

        return $localized;
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
                    $url = $this->media->publicUploadUrl($evento->imagen_url)
                        ?? $this->media->urlIfExists($evento->imagen_media_path);

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
                        'nivel' => $evento->nivel ?: 'general',
                        'nivel_etiqueta' => Evento::levelOptions()[$evento->nivel ?: 'general'] ?? 'Toda la comunidad',
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
                    'titulo' => 'Inicio - Logo '.ucfirst($nivel),
                    'referencia' => 'Logo mostrado en la tarjeta del nivel dentro de Inicio.',
                    'media_path' => $path,
                ],
            ])
            ->all());
        $bannerInicioSlides = $this->homeBanners->get();
        $proximasFechas = $this->schoolCalendar->upcoming();

        $imagenesInicio = $this->media->images('inicio', [
            'sobre_nosotros' => [
                'titulo' => 'Inicio - Sobre Nosotros',
                'referencia' => 'Imagen lateral de la sección Sobre Nosotros en la página de inicio.',
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
        $mediaAcademias = $this->mediaAcademiasVespertinas();

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
            'titulo' => 'Recursos escolares - Calendario escolar',
            'referencia' => 'Imagen del calendario escolar mostrada en Recursos escolares.',
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

        return view('pages.contacto', compact('pagina', 'imagenesContacto'));
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

        return response()->file($filePath);
    }

    private function eventosInicioDefault(): array
    {
        return collect(config('colegio.inicio.eventos_default', []))
            ->map(fn (array $evento) => [
                'titulo' => $evento['titulo'],
                'descripcion' => $evento['descripcion'],
                'nivel' => 'general',
                'nivel_etiqueta' => Evento::levelOptions()['general'],
                'url' => $this->media->urlIfExists($evento['media_path'] ?? null),
                'imagen' => [
                    'url' => $this->media->urlIfExists($evento['media_path'] ?? null),
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
                $url = $this->media->publicUploadUrl($video->video)
                    ?? $this->media->urlIfExists($video->video_media_path);

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

        return $this->media->files('Testimonios Alumni')
            ->filter(fn (string $path) => in_array(strtolower(pathinfo($path, PATHINFO_EXTENSION)), config('colegio.media.video_extensions', [])))
            ->map(fn (string $path) => [
                'titulo' => pathinfo($path, PATHINFO_FILENAME),
                'url' => $this->media->url($path),
            ])
            ->values()
            ->all();
    }

    private function mediaAcademiasVespertinas(): array
    {
        $imageExtensions = config('colegio.media.image_extensions', []);
        $videoExtensions = config('colegio.media.video_extensions', []);

        $archivos = $this->media->files('Academias vespertinas')->sort();

        $imagenesDefault = $archivos
            ->filter(fn (string $path) => in_array(strtolower(pathinfo($path, PATHINFO_EXTENSION)), $imageExtensions, true))
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
        $videos = $archivos
            ->filter(fn (string $path) => in_array(strtolower(pathinfo($path, PATHINFO_EXTENSION)), $videoExtensions, true))
            ->map(fn (string $path) => [
                'titulo' => str_replace(['-', '_'], ' ', pathinfo($path, PATHINFO_FILENAME)),
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

    private function mapearMediaPaths(array $paths): array
    {
        return collect($paths)
            ->map(fn (string $path) => $this->media->urlIfExists($path))
            ->filter()
            ->all();
    }

    private function universidadesVinculacion(): array
    {
        return [
            [
                'nombre' => 'UVM',
                'logo' => asset('images/universidades/uvm.png'),
                'sitio' => 'https://uvm.mx/',
                'resumen' => 'Acompañamiento durante el proceso de ingreso.',
                'beneficios' => ['Atención preuniversitaria', 'Examen de admisión sin costo', 'Asesoría sobre becas y visitas al campus'],
            ],
            [
                'nombre' => 'UPAEP',
                'logo' => asset('images/universidades/upaep.png'),
                'sitio' => 'https://www.upaep.mx/',
                'resumen' => 'Pase directo y opciones de beca por mérito.',
                'beneficios' => ['Pase directo con promedio de 8.0, excepto Medicina', 'Beca Mérito de hasta 80%', 'Examen diagnóstico, orientación y visitas'],
                'convocatoria' => true,
            ],
            [
                'nombre' => 'UDLAP',
                'logo' => asset('images/universidades/udlap.png'),
                'sitio' => 'https://www.udlap.mx/web/',
                'resumen' => 'Beneficios de admisión y becas para egresados IB®.',
                'beneficios' => ['Examen de admisión sin costo', 'Beca de 20% para egresados de preparatoria IB® sin diploma', 'Con Diploma IB®: becas de 35% a 50% según puntaje'],
            ],
            [
                'nombre' => 'Anáhuac Puebla',
                'logo' => asset('images/universidades/anahuac.png'),
                'sitio' => 'https://www.anahuac.mx/puebla/',
                'resumen' => 'Orientación y distintas modalidades de apoyo financiero.',
                'beneficios' => ['Beca al mejor estudiante de hasta 60%', 'Becas académicas, deportivas y artísticas', 'Opciones de financiamiento educativo'],
                'convocatoria' => true,
            ],
            [
                'nombre' => 'Ibero Puebla',
                'logo' => asset('images/universidades/ibero.png'),
                'sitio' => 'https://www.iberopuebla.mx/',
                'resumen' => 'Ingreso preferente y oportunidades de Beca Mérito.',
                'beneficios' => ['Sin examen con promedio mayor a 8.5', 'Examen de admisión sin costo', 'Tres Becas Mérito de 50% con promedio de 9.0'],
                'convocatoria' => true,
            ],
            [
                'nombre' => 'Tec de Monterrey',
                'logo' => asset('images/universidades/tec-de-monterrey.png'),
                'sitio' => 'https://tec.mx/es',
                'resumen' => 'Acompañamiento de admisión y reconocimiento del Diploma IB®.',
                'beneficios' => ['Asesoría, orientación y visitas', 'Reconocimiento a promedios destacados', 'Posible acreditación de materias del Diploma IB® durante admisión'],
            ],
            [
                'nombre' => 'Escuela Libre de Derecho de Puebla',
                'logo' => asset('images/universidades/escuela-libre-de-derecho.png'),
                'sitio' => 'https://eldp.edu.mx/',
                'resumen' => 'Ingreso automático y becas académicas.',
                'beneficios' => ['Admisión automática con promedio mayor a 8.0', 'Beca de 25% con promedio de 8.5', 'Beca de 50% con promedio de 9.0'],
                'convocatoria' => true,
            ],
            [
                'nombre' => 'Vatel',
                'logo' => asset('images/universidades/vatel.png'),
                'sitio' => 'https://www.vatel.mx/',
                'resumen' => 'Beneficios de ingreso para estudios de hotelería.',
                'beneficios' => ['Beneficio en el pago del examen y cuota de nuevo ingreso', 'Becas de excelencia de 20% a 50% según promedio', 'Visitas y conferencias de hotelería'],
                'convocatoria' => true,
            ],
            [
                'nombre' => 'ITAM',
                'logo' => asset('images/universidades/itam.png'),
                'sitio' => 'https://www.itam.mx/',
                'resumen' => 'Apoyos de admisión, beca y financiamiento.',
                'beneficios' => ['Examen sin costo con promedio mayor a 8.0', 'Beca o beca-préstamo de hasta 90%', 'Beca Bachilleres de 100% para el puntaje más alto por carrera'],
                'convocatoria' => true,
            ],
            [
                'nombre' => 'ISU Universidad',
                'logo' => asset('images/universidades/isu.png'),
                'sitio' => 'https://isu.edu.mx/',
                'resumen' => 'Ingreso preferente, becas y experiencias universitarias.',
                'beneficios' => ['Sin examen con promedio mayor a 8.5', 'Una beca de 50% y dos becas de 25%', 'Visitas, talleres y clases muestra'],
                'convocatoria' => true,
            ],
            [
                'nombre' => 'INQBA',
                'logo' => asset('images/universidades/inqba.png'),
                'sitio' => 'https://inqba.edu.mx/',
                'resumen' => 'Becas empresariales, de excelencia y por proyecto.',
                'beneficios' => ['Examen diagnóstico sin costo', 'Beca empresarial de 40% con promedio de 8.5', 'Becas de 60% a 80% sujetas a requisitos'],
                'convocatoria' => true,
            ],
            [
                'nombre' => 'Unilomas',
                'logo' => asset('images/universidades/unilomas.png'),
                'sitio' => 'https://www.unilomas.mx/',
                'resumen' => 'Acercamiento directo a la experiencia universitaria.',
                'beneficios' => ['Eventos y actividades académicas', 'Pláticas, talleres y orientación', 'Tours y visitas universitarias'],
            ],
        ];
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
                    'titulo' => $nivel['titulo'].' - Logo',
                    'referencia' => 'Logo mostrado en Oferta Educativa y en el encabezado del nivel.',
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
            $imagenesDirectorio = $this->media->files($default['media_directory'])
                ->filter(fn (string $path) => in_array(strtolower(pathinfo($path, PATHINFO_EXTENSION)), config('colegio.media.image_extensions', []), true))
                ->sort()
                ->map(fn (string $path) => [
                    'url' => $this->media->url($path),
                    'titulo' => $default['titulo'] ?? pathinfo($path, PATHINFO_FILENAME),
                    'referencia' => $default['referencia'] ?? null,
                    'pendiente' => false,
                ]);
        }

        $imagenes = $imagenesDirectorio;

        if ($imagenes->isEmpty()) {
            $imagenes = $registros
                ->map(function (SeccionImagen $registro) {
                    $url = $this->media->publicUploadUrl($registro->imagen)
                        ?? $this->media->urlIfExists($registro->respaldo_media_path);

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
