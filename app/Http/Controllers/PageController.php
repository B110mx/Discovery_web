<?php

namespace App\Http\Controllers;

use App\Models\Evento;
use App\Models\HitoHistoria;
use App\Models\ListaUtil;
use App\Models\PaginaContenido;
use App\Models\SeccionImagen;
use App\Models\TestimonioVideo;
use App\Support\SiteCache;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class PageController extends Controller
{
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

        // Caché de 12 horas para evitar consultas repetitivas a la BD
        $eventos = Cache::remember(SiteCache::key('inicio_eventos'), SiteCache::ttl(), function () {
            $eventosDefault = $this->eventosInicioDefault();
            $imagenesCarrusel = $this->imagenesCarruselEventosInicio();

            $eventos = Evento::where('activo', true)
                ->orderBy('orden')
                ->get()
                ->map(function (Evento $evento, int $index) use ($eventosDefault, $imagenesCarrusel) {
                    $imagen = $imagenesCarrusel[$index] ?? ($eventosDefault[$index]['imagen'] ?? null);
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
                        'descripcion' => $evento->descripcion ?? 'Proximo evento de la comunidad Discovery.',
                        'url' => $imagen['url'] ?? null,
                        'imagen' => $imagen,
                    ];
                })->values()->all();

            if (!empty($eventos)) {
                return $eventos;
            }

            return $eventosDefault;
        });

        // Caché para la lectura del disco duro (evita latencia de I/O)
        $testimonios = Cache::remember(SiteCache::key('inicio_testimonios'), SiteCache::ttl(), fn () => $this->testimoniosAlumni());

        $logosNiveles = $this->mapearMediaPaths(config('colegio.inicio.logos_niveles', []));
        $imagenesInicio = $this->imagenesVista('inicio', [
            'sobre_nosotros' => [
                'titulo' => 'Inicio - Sobre Nosotros',
                'referencia' => 'Imagen lateral de la seccion Sobre Nosotros en la pagina de inicio.',
                'url' => $this->publicUploadUrl($paginaInicio?->imagen_principal),
                'media_path' => 'Kinder fotos actuales/IMG_5775.JPG',
            ],
        ]);

        return view('pages.inicio', compact('eventos', 'testimonios', 'logosNiveles', 'imagenesInicio', 'paginaInicio'));
    }

    public function nosotros(): View
    {
        $paginaNosotros = $this->paginaContenido('nosotros');

        $imagenesNosotros = $this->imagenesVista('nosotros', [
            'hero' => [
                'titulo' => 'Nosotros - Imagen principal',
                'referencia' => 'Imagen grande del encabezado de la pagina Nosotros.',
                'url' => $this->publicUploadUrl($paginaNosotros?->imagen_principal),
                'media_path' => 'Logos principales/LOGO DISCOVERY PNG.png',
            ],
            'modelo' => [
                'titulo' => 'Nosotros - Modelo educativo',
                'referencia' => 'Imagen de apoyo para la seccion de modelo educativo.',
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

    public function recursosEscolares(): View
    {
        $listasUtiles = Cache::remember(SiteCache::key('recursos_listas_utiles'), SiteCache::ttl(), function () {
            $listasAdmin = $this->listasUtilesDesdeAdmin();

            if (! empty($listasAdmin)) {
                return $listasAdmin;
            }

            return $this->listasUtilesDesdeCarpeta();
        });

        $calendarioEscolarUrl = $this->generarMediaUrlDesdeRuta('Calendario Escolar/Calendario Escolar 2025-2026.jpg');

        return view('pages.recursos-escolares', compact('listasUtiles', 'calendarioEscolarUrl'));
    }

    public function contacto(): View
    {
        // 🔥 CAMBIO IMPORTANTE:
        // Antes guardabas el modelo completo en caché (ESO ROMPE Laravel)
        // Ahora guardamos SOLO el ID

        $paginaId = Cache::remember(SiteCache::key('pagina_contenido.contacto'), SiteCache::ttl(), function () {
            return PaginaContenido::where('slug', 'contacto')->value('id');
        });

        // 🔥 Recuperamos el modelo limpio desde la BD
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
        $nivelContenido['logo'] = isset($nivelContenido['logo_path'])
            ? $this->generarMediaUrlDesdeRuta($nivelContenido['logo_path'])
            : null;
        $nivelContenido['hoja_informativa_url'] = isset($nivelContenido['hoja_informativa_path'])
            ? $this->generarMediaUrlDesdeRuta($nivelContenido['hoja_informativa_path'])
            : null;
        $nivelContenido['modelo_academico_url'] = isset($nivelContenido['modelo_academico_path'])
            ? $this->generarMediaUrlDesdeRuta($nivelContenido['modelo_academico_path'])
            : null;
        if (! empty($nivelContenido['informacion']['imagenes_referencia'])) {
            $nivelContenido['informacion']['imagenes_referencia'] = collect($nivelContenido['informacion']['imagenes_referencia'])
                ->map(function (array $imagen) {
                    $imagen['url'] = $imagen['url'] ?? $this->mediaUrlIfExists($imagen['media_path'] ?? null);

                    return $imagen;
                })
                ->all();
        }
        $imagenGaleriaPrincipal = $galeria[0]['url'] ?? null;
        $mediaPathGaleriaPrincipal = isset($carpetas[$nivel], $imagenGaleriaPrincipal)
            ? $carpetas[$nivel] . '/' . basename($imagenGaleriaPrincipal)
            : ($nivelContenido['usar_placeholder_hero'] ?? false ? null : ($nivelContenido['logo_path'] ?? null));
        $nivelContenido['imagen_principal'] = $this->imagenVista($nivel, 'hero', [
            'titulo' => $nivelContenido['titulo'] . ' - Imagen principal',
            'referencia' => 'Imagen principal del encabezado del nivel ' . $nivelContenido['titulo'] . '.',
            'url' => $imagenGaleriaPrincipal,
            'media_path' => $mediaPathGaleriaPrincipal,
        ]);

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
        $disk = Storage::disk($this->mediaDisk());

        abort_unless($path && $disk->exists($path), 404);

        $filePath = $disk->path($path);

        abort_unless(is_file($filePath), 404);

        return response()->file($filePath);
    }

    /**
     * --- MÉTODOS PRIVADOS (Helpers) ---
     */
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

        return '/media/' . collect(explode('/', $relativePath))->map(fn ($segment) => rawurlencode($segment))->implode('/');
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

        abort_if(collect($segments)->contains('..'), 404);

        return implode('/', $segments);
    }

    private function eventosInicioDefault(): array
    {
        $imagenesCarrusel = $this->imagenesCarruselEventosInicio();

        return collect(config('colegio.inicio.eventos_default', []))
            ->map(fn (array $evento, int $index) => [
                'titulo' => $evento['titulo'],
                'descripcion' => $evento['descripcion'],
                'url' => $imagenesCarrusel[$index]['url'] ?? null,
                'imagen' => $imagenesCarrusel[$index] ?? [
                    'url' => null,
                    'titulo' => $evento['titulo'],
                    'referencia' => 'Espacio pendiente para el carrusel de eventos en Inicio.',
                    'pendiente' => true,
                ],
            ])
            ->all();
    }

    private function imagenesCarruselEventosInicio(): array
    {
        return array_values($this->imagenesVista('carruseles', $this->defaultsCarruselEventosInicio()));
    }

    private function defaultsCarruselEventosInicio(): array
    {
        return [
            'inicio_eventos_1' => [
                'titulo' => 'Inicio - Carrusel de eventos - Slide 1',
                'referencia' => 'Modulo del carrusel de eventos. Subir aqui la imagen definitiva desde el panel.',
            ],
            'inicio_eventos_2' => [
                'titulo' => 'Inicio - Carrusel de eventos - Slide 2',
                'referencia' => 'Modulo del carrusel de eventos. Subir aqui la imagen definitiva desde el panel.',
            ],
            'inicio_eventos_3' => [
                'titulo' => 'Inicio - Carrusel de eventos - Slide 3',
                'referencia' => 'Modulo del carrusel de eventos. Subir aqui la imagen definitiva desde el panel.',
            ],
        ];
    }

    private function testimoniosAlumni(): array
    {
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
            ['nombre' => 'UVM', 'url' => 'https://colegiodiscovery.edu.mx/wp-content/uploads/2022/08/uvm.png'],
            ['nombre' => 'UPAEP', 'url' => 'https://colegiodiscovery.edu.mx/wp-content/uploads/2022/08/upaep.png'],
            ['nombre' => 'UDLAP', 'url' => 'https://colegiodiscovery.edu.mx/wp-content/uploads/2022/08/udlap.png'],
            ['nombre' => 'Anahuac', 'url' => 'https://colegiodiscovery.edu.mx/wp-content/uploads/2022/08/anahuac.png'],
            ['nombre' => 'Ibero', 'url' => 'https://colegiodiscovery.edu.mx/wp-content/uploads/2022/08/ibero.png'],
            ['nombre' => 'Tec de Monterrey', 'url' => 'https://colegiodiscovery.edu.mx/wp-content/uploads/2022/08/tec-de-monterrey.png'],
            ['nombre' => 'Escuela Libre de Derecho', 'url' => 'https://colegiodiscovery.edu.mx/wp-content/uploads/2022/08/escuela-libre-de-derecho.png'],
            ['nombre' => 'Vatel', 'url' => 'https://colegiodiscovery.edu.mx/wp-content/uploads/2022/08/vatel.png'],
            ['nombre' => 'ITAM', 'url' => 'https://colegiodiscovery.edu.mx/wp-content/uploads/2022/08/itam.png'],
            ['nombre' => 'ISU', 'url' => 'https://colegiodiscovery.edu.mx/wp-content/uploads/2022/08/isu.png'],
            ['nombre' => 'Universidad Panamericana', 'url' => 'https://colegiodiscovery.edu.mx/wp-content/uploads/2022/08/universidad-panamericana.png'],
            ['nombre' => 'Unilomas', 'url' => 'https://colegiodiscovery.edu.mx/wp-content/uploads/2022/08/unilomas.png'],
        ];
    }

    private function prepararNivelOferta(string $slug, array $nivel): array
    {
        $default = $this->defaultConMediaUrl($nivel['imagen_default'] ?? []);

        return [
            ...$nivel,
            'ruta' => route('nivel', $slug),
            'logo' => ! empty($nivel['logo_path']) ? $this->mediaUrlIfExists($nivel['logo_path']) : null,
            'imagen' => $this->imagenVista('oferta-academica', $nivel['imagen_clave'], $default),
        ];
    }

    private function prepararComunidadProtagonistas(): array
    {
        $niveles = collect(config('colegio.protagonistas.niveles', []))
            ->map(function (array $nivel) {
                return [
                    'titulo' => $nivel['titulo'],
                    'imagen' => $this->imagenVista('protagonistas', $nivel['clave'], [
                        'titulo' => 'Comunidad - ' . $nivel['titulo'],
                        'referencia' => $nivel['referencia'],
                        'media_path' => $nivel['media_path'],
                    ]),
                    'color' => $nivel['color'],
                ];
            })
            ->all();

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
                $item['imagen'] = $item['imagenes'][array_rand($item['imagenes'])] ?? [
                    'url' => null,
                    'titulo' => 'Comunidad Discovery',
                    'referencia' => 'Imagen para la seccion Quienes hacen viva nuestra comunidad.',
                    'pendiente' => true,
                ];

                return $item;
            })
            ->all();

        return compact('niveles', 'protagonistas');
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
                'nivel' => $lista->nivel,
                'titulo' => $lista->titulo,
                'ciclo' => $lista->ciclo_escolar,
                'url' => $this->publicUploadUrl($lista->archivo_pdf),
            ])
            ->filter(fn (array $lista) => ! empty($lista['url']))
            ->groupBy('nivel')
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
            ->map(fn ($listas) => $listas->values()->all())
            ->all();
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
            return 'Primaria';
        }

        if (preg_match('/\b(7|8|9)\s*[º°]/u', $filename)) {
            return 'Secundaria';
        }

        if (preg_match('/\b(10|11|12)\s*[º°]/u', $filename)) {
            return 'Bachillerato';
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
        $imagenesHistoria = $this->imagenesVista('nosotros', [
            'historia_2003' => [
                'titulo' => 'Nosotros - Historia 2003',
                'referencia' => 'Imagen para el hito Discovery Kinder en la linea del tiempo de Nosotros.',
                'media_path' => 'Linea del tiempo/2003-1.jpg',
            ],
            'historia_2003_2' => [
                'titulo' => 'Nosotros - Historia 2003 - Imagen secundaria',
                'referencia' => 'Imagen secundaria para el hito Discovery Kinder en la linea del tiempo de Nosotros.',
                'media_path' => 'Linea del tiempo/2003-2.jpg',
            ],
            'historia_2005' => [
                'titulo' => 'Nosotros - Historia 2005',
                'referencia' => 'Imagen para el hito Discovery Primaria en la linea del tiempo de Nosotros.',
                'media_path' => 'Linea del tiempo/2005-1.jpg',
            ],
            'historia_2005_2' => [
                'titulo' => 'Nosotros - Historia 2005 - Imagen secundaria',
                'referencia' => 'Imagen secundaria para el hito Discovery Primaria en la linea del tiempo de Nosotros.',
                'media_path' => 'Linea del tiempo/2005-2.jpg',
            ],
            'historia_2011' => [
                'titulo' => 'Nosotros - Historia 2011',
                'referencia' => 'Imagen para el hito Discovery Secundaria en la linea del tiempo de Nosotros.',
                'media_path' => 'Linea del tiempo/2011-1.jpg',
            ],
            'historia_2016' => [
                'titulo' => 'Nosotros - Historia 2016',
                'referencia' => 'Imagen para el hito Discovery Bachillerato en la linea del tiempo de Nosotros.',
                'media_path' => 'Linea del tiempo/2016-1.jpg',
            ],
            'historia_2018' => [
                'titulo' => 'Nosotros - Historia 2018',
                'referencia' => 'Imagen para el hito Colegio del Mundo IB en la linea del tiempo de Nosotros.',
                'media_path' => 'Linea del tiempo/2018-1.jpg',
            ],
            'historia_2019' => [
                'titulo' => 'Nosotros - Historia 2019',
                'referencia' => 'Imagen para el hito Nuevas instalaciones en la linea del tiempo de Nosotros.',
                'media_path' => 'Linea del tiempo/2019-1.jpg',
            ],
            'historia_2019_2' => [
                'titulo' => 'Nosotros - Historia 2019 - Imagen secundaria',
                'referencia' => 'Imagen secundaria para el hito Nuevas instalaciones en la linea del tiempo de Nosotros.',
                'media_path' => 'Linea del tiempo/2019-2.jpg',
            ],
            'historia_2023' => [
                'titulo' => 'Nosotros - Historia 2023',
                'referencia' => 'Imagen para el hito DKMUN primera edicion en la linea del tiempo de Nosotros.',
                'media_path' => 'Linea del tiempo/2023-1.jpg',
            ],
            'historia_2023_2' => [
                'titulo' => 'Nosotros - Historia 2023 - Imagen secundaria',
                'referencia' => 'Imagen secundaria para el hito DKMUN primera edicion en la linea del tiempo de Nosotros.',
                'media_path' => 'Linea del tiempo/2023-2.jpg',
            ],
            'historia_2025' => [
                'titulo' => 'Nosotros - Historia 2025',
                'referencia' => 'Imagen para el hito Actualmente en la linea del tiempo de Nosotros.',
                'media_path' => 'Linea del tiempo/2025-1.jpg',
            ],
        ]);

        $historiaDefault = [
            ['anio' => '2003', 'titulo' => 'Discovery Kinder', 'texto' => 'Nace Discovery Kinder, el inicio de un sueno educativo porque los primeros pasos trascienden.', 'imagenes' => [$imagenesHistoria['historia_2003'], $imagenesHistoria['historia_2003_2']]],
            ['anio' => '2005', 'titulo' => 'Discovery Primaria', 'texto' => 'Inauguracion de Discovery Primaria, creciendo con pasos firmes.', 'imagenes' => [$imagenesHistoria['historia_2005'], $imagenesHistoria['historia_2005_2']]],
            ['anio' => '2011', 'titulo' => 'Discovery Secundaria', 'texto' => 'Se suma Discovery Secundaria, ampliando horizontes.', 'imagenes' => [$imagenesHistoria['historia_2011']]],
            ['anio' => '2016', 'titulo' => 'Discovery Bachillerato', 'texto' => 'Llega Discovery Bachillerato, preparando grandes lideres y descubriendo su potencial.', 'imagenes' => [$imagenesHistoria['historia_2016']]],
            ['anio' => '2018', 'titulo' => 'Colegio del Mundo', 'texto' => 'Nos convertimos en Colegio del Mundo IB, abrazando la educacion internacional.', 'imagenes' => [$imagenesHistoria['historia_2018']]],
            ['anio' => '2019', 'titulo' => 'Nuevas instalaciones', 'texto' => 'Estrenamos nuevas instalaciones para seguir innovando.', 'imagenes' => [$imagenesHistoria['historia_2019'], $imagenesHistoria['historia_2019_2']]],
            ['anio' => '2023', 'titulo' => 'DKMUN primera edicion', 'texto' => 'Realizamos nuestra primera edicion DKMUN, un espacio para el liderazgo y la diplomacia.', 'imagenes' => [$imagenesHistoria['historia_2023'], $imagenesHistoria['historia_2023_2']]],
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

                if ($url = $this->publicUploadUrl($hito->imagen_url)) {
                    $imagenes[0] = [
                        'url' => $url,
                        'titulo' => $hito->titulo,
                        'referencia' => 'Imagen principal del hito ' . $hito->titulo . '.',
                        'pendiente' => false,
                    ];
                }

                if ($url = $this->publicUploadUrl($hito->imagen_secundaria_url)) {
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
