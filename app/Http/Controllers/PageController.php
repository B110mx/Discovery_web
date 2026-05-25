<?php

namespace App\Http\Controllers;

use App\Models\Evento;
use App\Models\PaginaContenido;
use App\Models\SeccionImagen;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class PageController extends Controller
{
    /**
     * Muestra la página de inicio con eventos y testimonios cacheados.
     */
    public function inicio(): View
    {
        // Caché de 12 horas para evitar consultas repetitivas a la BD
        $eventos = Cache::remember('inicio_eventos', 43200, function () {
            $eventos = Evento::where('activo', true)
                ->orderBy('orden')
                ->get()
                ->map(fn ($evento) => [
                    'titulo' => $evento->titulo,
                    'descripcion' => $evento->descripcion ?? 'Proximo evento de la comunidad Discovery.',
                    'url' => asset('storage/' . $evento->imagen_url),
                ])->values()->all();

            if (!empty($eventos)) {
                return $eventos;
            }

            return [
                [
                    'titulo' => 'Evento Preescolar',
                    'descripcion' => 'Actividades proximas para nuestras familias de preescolar.',
                    'url' => $this->generarMediaUrlDesdeRuta('Kinder/Colegio Discovery-15.jpg'),
                ],
                [
                    'titulo' => 'Evento Primaria',
                    'descripcion' => 'Experiencias, proyectos y encuentros para nuestros explorers de primaria.',
                    'url' => $this->generarMediaUrlDesdeRuta('Elementary (Primaria)/Colegio Discovery-66.jpg'),
                ],
                [
                    'titulo' => 'Evento Secundaria',
                    'descripcion' => 'Actividades academicas y de comunidad para middle school.',
                    'url' => $this->generarMediaUrlDesdeRuta('Middle (Secundaria)/Colegio Discovery-59.jpg'),
                ],
            ];
        });

        // Caché para la lectura del disco duro (evita latencia de I/O)
        $testimonios = Cache::remember('inicio_testimonios_v2', 43200, function () {
            $testimoniosPath = base_path('videosyfotos/Testimonios Alumni');
            
            if (!File::isDirectory($testimoniosPath)) {
                return [];
            }

            return collect(File::files($testimoniosPath))
                ->filter(fn ($file) => in_array(strtolower($file->getExtension()), ['mp4', 'mov', 'webm', 'm4v']))
                ->map(fn ($file) => [
                    'titulo' => pathinfo($file->getFilename(), PATHINFO_FILENAME),
                    'url' => $this->generarMediaUrl($file),
            ])->values()->all();
        });

        $logosNiveles = [
            'preescolar' => $this->generarMediaUrlDesdeRuta('Logos de niveles educativos/preescolar.png'),
            'primaria' => $this->generarMediaUrlDesdeRuta('Logos de niveles educativos/primaria.png'),
            'secundaria' => $this->generarMediaUrlDesdeRuta('Logos de niveles educativos/secundaria.png'),
            'bachillerato' => $this->generarMediaUrlDesdeRuta('Logos de niveles educativos/bachillerato.png'),
        ];
        $imagenesInicio = $this->imagenesVista('inicio', [
            'sobre_nosotros' => [
                'titulo' => 'Inicio - Sobre Nosotros',
                'referencia' => 'Imagen lateral de la seccion Sobre Nosotros en la pagina de inicio.',
            ],
        ]);

        return view('pages.inicio', compact('eventos', 'testimonios', 'logosNiveles', 'imagenesInicio'));
    }

    public function nosotros(): View
    {
        $imagenesNosotros = $this->imagenesVista('nosotros', [
            'hero' => [
                'titulo' => 'Nosotros - Imagen principal',
                'referencia' => 'Imagen grande del encabezado de la pagina Nosotros.',
            ],
            'modelo' => [
                'titulo' => 'Nosotros - Modelo educativo',
                'referencia' => 'Imagen de apoyo para la seccion de modelo educativo.',
            ],
        ]);

        $historiaNosotros = $this->obtenerHistoriaNosotros();

        return view('pages.nosotros', compact('imagenesNosotros', 'historiaNosotros'));
    }

    public function ofertaAcademica(): View
    {
        $ofertaNiveles = [
            'preescolar' => [
                'titulo' => 'Preescolar',
                'subtitulo' => 'Primeros pasos con seguridad, juego y acompanamiento.',
                'descripcion' => 'Un entorno cercano para iniciar el aprendizaje con creatividad, bienestar emocional y bases bilingues.',
                'edad' => 'Maternal a Pre-primary',
                'color' => 'lime',
                'ruta' => route('nivel', 'preescolar'),
                'logo' => $this->generarMediaUrlDesdeRuta('Logos de niveles educativos/logo preescolar.png'),
                'imagen' => $this->imagenVista('oferta-academica', 'preescolar', [
                    'titulo' => 'Oferta Educativa - Preescolar',
                    'referencia' => 'Imagen destacada para Preescolar en la vista Oferta Educativa.',
                    'url' => $this->generarMediaUrlDesdeRuta('Kinder/Colegio Discovery-15.jpg'),
                ]),
                'puntos' => ['Neuroaprendizaje', 'Ingles natural', 'Grupos reducidos'],
            ],
            'primaria' => [
                'titulo' => 'Primaria',
                'subtitulo' => 'Bases solidas para explorar, investigar y crear.',
                'descripcion' => 'Aprendizaje activo con enfoque bilingue, pensamiento logico, proyectos IB y desarrollo social.',
                'edad' => '1 a 6 grado',
                'color' => 'red',
                'ruta' => route('nivel', 'primaria'),
                'logo' => $this->generarMediaUrlDesdeRuta('Logos de niveles educativos/logo primaria.png'),
                'imagen' => $this->imagenVista('oferta-academica', 'primaria', [
                    'titulo' => 'Oferta Educativa - Primaria',
                    'referencia' => 'Imagen destacada para Primaria en la vista Oferta Educativa.',
                    'url' => $this->generarMediaUrlDesdeRuta('Elementary (Primaria)/Colegio Discovery-66.jpg'),
                ]),
                'puntos' => ['Ingles diario', 'Frances desde 5 grado', 'Tecnologia y arte'],
            ],
            'secundaria' => [
                'titulo' => 'Secundaria',
                'subtitulo' => 'Acompanamiento para descubrir voz, criterio y talentos.',
                'descripcion' => 'Formacion academica con idiomas, tecnologia, proyectos interdisciplinarios y bienestar emocional.',
                'edad' => '7 a 9 grado',
                'color' => 'blue',
                'ruta' => route('nivel', 'secundaria'),
                'logo' => $this->generarMediaUrlDesdeRuta('Logos de niveles educativos/logo secundaria.png'),
                'imagen' => $this->imagenVista('oferta-academica', 'secundaria', [
                    'titulo' => 'Oferta Educativa - Secundaria',
                    'referencia' => 'Imagen destacada para Secundaria en la vista Oferta Educativa.',
                    'url' => $this->generarMediaUrlDesdeRuta('Middle (Secundaria)/Colegio Discovery-59.jpg'),
                ]),
                'puntos' => ['Tres idiomas', 'Deporte diario', 'Liderazgo y proyectos'],
            ],
            'bachillerato' => [
                'titulo' => 'Bachillerato',
                'subtitulo' => 'Preparacion universitaria con vision internacional.',
                'descripcion' => 'Una etapa retadora con Programa del Diploma IB, orientacion vocacional, liderazgo y proyectos reales.',
                'edad' => '10 a 12 grado',
                'color' => 'green',
                'ruta' => route('nivel', 'bachillerato'),
                'logo' => $this->generarMediaUrlDesdeRuta('Logos de niveles educativos/logo bachillerato.png'),
                'imagen' => $this->imagenVista('oferta-academica', 'bachillerato', [
                    'titulo' => 'Oferta Educativa - Bachillerato',
                    'referencia' => 'Imagen destacada para Bachillerato en la vista Oferta Educativa.',
                    'url' => $this->generarMediaUrlDesdeRuta('High (Prepa)/IMG_7346-scaled.jpg'),
                ]),
                'puntos' => ['Diploma IB', 'Orientacion vocacional', 'Becas universitarias'],
            ],
            'ib-en-discovery' => [
                'titulo' => 'IB en Discovery',
                'subtitulo' => 'Mentalidad internacional en la vida escolar.',
                'descripcion' => 'Un enfoque que fortalece investigacion, pensamiento critico, comunicacion y responsabilidad global.',
                'edad' => 'Enfoque transversal',
                'color' => 'amber',
                'ruta' => route('nivel', 'ib-en-discovery'),
                'logo' => $this->generarMediaUrlDesdeRuta('Logos de niveles educativos/Logo IB cl.jpeg'),
                'imagen' => $this->imagenVista('oferta-academica', 'ib', [
                    'titulo' => 'Oferta Educativa - IB en Discovery',
                    'referencia' => 'Imagen destacada para IB en Discovery en la vista Oferta Educativa.',
                    'url' => $this->generarMediaUrlDesdeRuta('Logos de niveles educativos/Logo IB cl.jpeg'),
                ]),
                'puntos' => ['Perfil IB', 'Indagacion', 'Accion con impacto'],
            ],
            'certificacion-de-ingles' => [
                'titulo' => 'Certificacion de Ingles',
                'subtitulo' => 'Metas claras para avanzar con confianza.',
                'descripcion' => 'Acompanamiento academico para fortalecer reading, writing, listening y speaking con objetivos medibles.',
                'edad' => 'Preparacion continua',
                'color' => 'sky',
                'ruta' => route('nivel', 'certificacion-de-ingles'),
                'logo' => null,
                'imagen' => $this->imagenVista('oferta-academica', 'certificacion_ingles', [
                    'titulo' => 'Oferta Educativa - Certificacion de Ingles',
                    'referencia' => 'Imagen destacada para Certificacion de Ingles en la vista Oferta Educativa.',
                ]),
                'puntos' => ['Practica guiada', 'Habilidades comunicativas', 'Seguimiento academico'],
            ],
        ];

        return view('pages.oferta-academica', compact('ofertaNiveles'));
    }

    public function protagonistas(): View
    {
        $testimonios = Cache::remember('protagonistas_testimonios_v1', 43200, function () {
            $testimoniosPath = base_path('videosyfotos/Testimonios Alumni');

            if (!File::isDirectory($testimoniosPath)) {
                return [];
            }

            return collect(File::files($testimoniosPath))
                ->filter(fn ($file) => in_array(strtolower($file->getExtension()), ['mp4', 'mov', 'webm', 'm4v']))
                ->map(fn ($file) => [
                    'titulo' => pathinfo($file->getFilename(), PATHINFO_FILENAME),
                    'url' => $this->generarMediaUrl($file),
                ])->values()->all();
        });

        $comunidad = [
            'niveles' => [
                [
                    'titulo' => 'Preescolar',
                    'imagen' => $this->imagenVista('protagonistas', 'preescolar', [
                        'titulo' => 'Comunidad - Preescolar',
                        'referencia' => 'Imagen para representar Preescolar dentro de Comunidad.',
                        'url' => $this->generarMediaUrlDesdeRuta('Kinder/Colegio Discovery-15.jpg'),
                    ]),
                    'color' => 'bg-lime-500',
                ],
                [
                    'titulo' => 'Primaria',
                    'imagen' => $this->imagenVista('protagonistas', 'primaria', [
                        'titulo' => 'Comunidad - Primaria',
                        'referencia' => 'Imagen para representar Primaria dentro de Comunidad.',
                        'url' => $this->generarMediaUrlDesdeRuta('Elementary (Primaria)/Colegio Discovery-66.jpg'),
                    ]),
                    'color' => 'bg-red-600',
                ],
                [
                    'titulo' => 'Secundaria',
                    'imagen' => $this->imagenVista('protagonistas', 'secundaria', [
                        'titulo' => 'Comunidad - Secundaria',
                        'referencia' => 'Imagen para representar Secundaria dentro de Comunidad.',
                        'url' => $this->generarMediaUrlDesdeRuta('Middle (Secundaria)/Colegio Discovery-61.jpg'),
                    ]),
                    'color' => 'bg-blue-700',
                ],
                [
                    'titulo' => 'Bachillerato',
                    'imagen' => $this->imagenVista('protagonistas', 'bachillerato', [
                        'titulo' => 'Comunidad - Bachillerato',
                        'referencia' => 'Imagen para representar Bachillerato dentro de Comunidad.',
                        'url' => $this->generarMediaUrlDesdeRuta('Logos de niveles educativos/bachillerato.png'),
                    ]),
                    'color' => 'bg-green-600',
                ],
            ],
        ];

        return view('pages.protagonistas', compact('testimonios', 'comunidad'));
    }

    public function recursosEscolares(): View
    {
        $listasUtiles = Cache::remember('recursos_listas_utiles_v2', 43200, function () {
            $listasPath = base_path('videosyfotos/Listas de útiles');

            if (!File::isDirectory($listasPath)) {
                return [];
            }

            return collect(File::files($listasPath))
                ->filter(fn ($file) => strtolower($file->getExtension()) === 'pdf')
                ->map(fn ($file) => [
                    'grado' => $this->obtenerGradoListaUtiles($file->getFilename()),
                    'nivel' => $this->obtenerNivelListaUtiles($file->getFilename()),
                    'titulo' => pathinfo($file->getFilename(), PATHINFO_FILENAME),
                    'url' => $this->generarMediaUrl($file),
                ])
                ->sortBy(fn ($lista) => $this->ordenarListaUtiles($lista['grado']))
                ->groupBy('nivel')
                ->map(fn ($listas) => $listas->values()->all())
                ->all();
        });

        return view('pages.recursos-escolares', compact('listasUtiles'));
    }

    public function contacto(): View
    {
        // 🔥 CAMBIO IMPORTANTE:
        // Antes guardabas el modelo completo en caché (ESO ROMPE Laravel)
        // Ahora guardamos SOLO el ID

        $paginaId = Cache::remember('contacto_pagina_id', 43200, function () {
            return PaginaContenido::where('slug', 'contacto')->value('id');
        });

        // 🔥 Recuperamos el modelo limpio desde la BD
        $pagina = $paginaId ? PaginaContenido::find($paginaId) : null;
        $imagenesContacto = $this->imagenesVista('contacto', [
            'hero' => [
                'titulo' => 'Contacto - Imagen principal',
                'referencia' => 'Imagen principal de la vista Contacto.',
                'url' => $pagina?->imagen_principal ? Storage::disk('public')->url($pagina->imagen_principal) : null,
            ],
            'secundaria' => [
                'titulo' => 'Contacto - Imagen secundaria',
                'referencia' => 'Imagen secundaria de apoyo de la vista Contacto.',
                'url' => $pagina?->imagen_secundaria ? Storage::disk('public')->url($pagina->imagen_secundaria) : null,
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

        $carpetas = [
            'preescolar' => 'Kinder',
            'primaria' => 'Elementary (Primaria)',
            'secundaria' => 'Middle (Secundaria)',
            'bachillerato' => 'High (Prepa)',
        ];

        $galeria = Cache::remember("galeria_{$nivel}", 43200, function () use ($nivel, $carpetas, $niveles) {
            if (!isset($carpetas[$nivel])) return [];

            $rutaGaleria = base_path('videosyfotos/' . $carpetas[$nivel]);
            if (!File::isDirectory($rutaGaleria)) return [];

            return collect(File::files($rutaGaleria))
                ->filter(fn ($file) => in_array(strtolower($file->getExtension()), ['jpg', 'jpeg', 'png', 'webp']))
                ->sortByDesc(fn ($file) => $nivel === 'secundaria' && $file->getFilename() === 'Colegio Discovery-59.jpg')
                ->take(12)
                ->map(fn ($file) => [
                    'alt' => $niveles[$nivel]['titulo'],
                    'url' => $this->generarMediaUrl($file),
                ])->values()->all();
        });

        $nivelContenido = $niveles[$nivel];
        $nivelContenido['logo'] = isset($nivelContenido['logo_path'])
            ? $this->generarMediaUrlDesdeRuta($nivelContenido['logo_path'])
            : null;
        $nivelContenido['hoja_informativa_url'] = isset($nivelContenido['hoja_informativa_path'])
            ? $this->generarMediaUrlDesdeRuta($nivelContenido['hoja_informativa_path'])
            : null;
        $nivelContenido['modelo_academico_url'] = isset($nivelContenido['modelo_academico_path'])
            ? $this->generarMediaUrlDesdeRuta($nivelContenido['modelo_academico_path'])
            : null;
        $nivelContenido['imagen_principal'] = $this->imagenVista($nivel, 'hero', [
            'titulo' => $nivelContenido['titulo'] . ' - Imagen principal',
            'referencia' => 'Imagen principal del encabezado del nivel ' . $nivelContenido['titulo'] . '.',
            'url' => $galeria[0]['url'] ?? null,
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
        $basePath = str_replace('\\', '/', realpath(base_path('videosyfotos')));
        $filePath = str_replace('\\', '/', realpath(base_path('videosyfotos/' . $path)) ?: '');

        abort_unless($basePath && $filePath && ($filePath === $basePath || str_starts_with($filePath, $basePath . '/')) && is_file($filePath), 404);

        return response()->file($filePath);
    }

    /**
     * --- MÉTODOS PRIVADOS (Helpers) ---
     */
    private function generarMediaUrl($file): string
    {
        $basePath = str_replace('\\', '/', realpath(base_path('videosyfotos')));
        $filePath = str_replace('\\', '/', $file->getRealPath());
        $relativePath = ltrim(substr($filePath, strlen($basePath)), '/');

        return url('/media/' . collect(explode('/', $relativePath))->map(fn ($segment) => rawurlencode($segment))->implode('/'));
    }

    private function generarMediaUrlDesdeRuta(string $relativePath): string
    {
        return url('/media/' . collect(explode('/', $relativePath))->map(fn ($segment) => rawurlencode($segment))->implode('/'));
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
                $imagen = $registro?->imagen
                    ? Storage::disk('public')->url($registro->imagen)
                    : ($default['url'] ?? null);

                return [
                    'url' => $imagen,
                    'titulo' => $registro?->titulo ?? $default['titulo'] ?? $clave,
                    'referencia' => $registro?->referencia ?? $default['referencia'] ?? null,
                    'pendiente' => empty($imagen),
                ];
            })
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
        return [
            'preescolar' => [
                'titulo' => 'Preescolar',
                'descripcion' => 'Un entorno cercano para iniciar el aprendizaje con seguridad, creatividad y acompanamiento.',
                'logo_path' => 'Logos de niveles educativos/logo preescolar.png',
                'hoja_informativa_path' => 'Hojas informativas/Kinder _ hoja informativa.pdf.pdf',
                'modelo_academico_path' => 'Modelos educativos/modelo educativo kínder.png',
                'informacion' => [
                    'titulo' => 'El kinder ideal para tus hijos',
                    'intro' => 'Una infancia feliz, segura y bilingue, con programas de neuroaprendizaje y bases solidas para Elementary.',
                    'puntos' => [
                        'Programas de neuroaprendizaje: Neuromotor, Audiomotor y Spark.',
                        'Aprendizaje natural en ingles y espanol con estrategias multisensoriales.',
                        'Programas alineados al Bachillerato Internacional desde los primeros anos.',
                        'Grupos reducidos de 14 a 24 alumnos con atencion personalizada.',
                        'Bienestar emocional, expresion oral y acompanamiento cercano.',
                    ],
                    'secciones' => [
                        [
                            'titulo' => 'Estimulación para cuerpo y mente',
                            'texto' => 'Neuromotor y Audiomotor fortalecen coordinacion, atencion, creatividad, autodominio fisico y madurez neurologica.',
                        ],
                        [
                            'titulo' => 'Aprendizaje en dos idiomas',
                            'texto' => 'Spark impulsa lectoescritura, vocabulario, comprension y expresion en ingles y espanol desde los primeros anos.',
                        ],
                        [
                            'titulo' => 'Pensamiento logico jugando',
                            'texto' => 'Con Bancubi aprenden matematicas de forma concreta: clasifican, ordenan, comparan y resuelven problemas manipulando objetos reales.',
                        ],
                        [
                            'titulo' => 'Crecimiento en comunidad',
                            'texto' => 'Crecen en un entorno de amor, respeto y seguridad, con valores del perfil IB, empatia y mentalidad abierta.',
                        ],
                    ],
                    'experiencias' => [
                        'Convivio de Navidad',
                        'Clase Neuromotora',
                        'Visita de Pre-primary a Elementary',
                        'Profesiones y Oficios',
                        'Festival de la Primavera',
                        'Dia del nino y la nina',
                    ],
                ],
            ],
            'primaria' => [
                'titulo' => 'Primaria',
                'descripcion' => 'Bases academicas solidas, valores y experiencias que despiertan la curiosidad.',
                'logo_path' => 'Logos de niveles educativos/logo primaria.png',
                'hoja_informativa_path' => 'Hojas informativas/Elementary _hoja informativa.pdf',
                'modelo_academico_path' => 'Modelos educativos/modelo educativo elementary.png',
                'informacion' => [
                    'titulo' => 'La primaria para tus hijos',
                    'intro' => 'Un entorno bilingue y trilingue donde los alumnos desarrollan mentalidad internacional, creatividad, tecnologia y bienestar emocional.',
                    'puntos' => [
                        'Tres bloques diarios en ingles con materias como Science y Civics.',
                        'Frances desde 5° de primaria como tercer idioma.',
                        'Mock Cambridge desde 3° de primaria para practicar reading, writing, listening y speaking.',
                        'Unidades de indagacion del modelo IB para investigar, cuestionar y resolver problemas reales.',
                        'Artes, deporte diario y academias vespertinas como futbol, basquetbol, ajedrez, origami, atletismo y UrbanKids.',
                    ],
                    'secciones' => [
                        [
                            'titulo' => 'Formacion bilingue y trilingue',
                            'texto' => 'Aprenden ingles en un entorno de inmersion y suman frances para ampliar su vision como ciudadanos globales.',
                        ],
                        [
                            'titulo' => 'Perfil IB y pensamiento global',
                            'texto' => 'Trabajan atributos como ser pensadores, informados, comunicadores, solidarios y de mentalidad abierta.',
                        ],
                        [
                            'titulo' => 'Pensamiento logico y algoritmico',
                            'texto' => 'Tecnologia y concursos de programacion fortalecen analisis, secuenciacion, creatividad y toma de decisiones.',
                        ],
                        [
                            'titulo' => 'Cuerpo, atencion y creatividad',
                            'texto' => 'Neuromotor continua en Elementary junto con deporte diario y artes integradas para una formacion equilibrada.',
                        ],
                    ],
                    'experiencias' => [
                        'Mini Olimpiadas',
                        'Obras de teatro',
                        'Festival Dia del Nino',
                        'Graduacion',
                        'Unidades de Indagacion',
                    ],
                ],
            ],
            'secundaria' => [
                'titulo' => 'Secundaria',
                'descripcion' => 'Formacion integral con tecnologia, proyectos y desarrollo personal.',
                'logo_path' => 'Logos de niveles educativos/logo secundaria.png',
                'hoja_informativa_path' => 'Hojas informativas/Secundaria _hoja informativa.pdf.pdf',
                'modelo_academico_path' => 'Modelos educativos/modelo educativo middle.png',
                'informacion' => [
                    'titulo' => 'La secundaria que ayuda a tus hijos a convertirse en su mejor version',
                    'intro' => 'Middle School es la etapa donde los jovenes despiertan su voz, definen su camino y conectan localmente con una vision global.',
                    'puntos' => [
                        'Ingles, espanol y frances con mas de 15 bloques a la semana.',
                        'Enfoques de aprendizaje: pensamiento, autogestion, comunicacion, investigacion y habilidades sociales.',
                        'Proyectos interdisciplinarios con mentalidad internacional.',
                        'Deporte diario: futbol, basquetbol, voleibol o tenis.',
                        'Bienestar emocional y preceptoria hasta el ultimo ano.',
                    ],
                    'secciones' => [
                        [
                            'titulo' => 'Idiomas para comunicarse con el mundo',
                            'texto' => 'Viven el ingles todos los dias, incorporan frances y se preparan para certificaciones como Cambridge y Lengua B del Programa Diploma.',
                        ],
                        [
                            'titulo' => 'Deporte diario',
                            'texto' => 'La practica deportiva fortalece salud fisica, disciplina, autoestima, trabajo en equipo y habitos saludables.',
                        ],
                        [
                            'titulo' => 'Liderazgo y proyectos globales',
                            'texto' => 'Programas como DKMun y WASP desarrollan investigacion, debate, diplomacia y liderazgo desde edades tempranas.',
                        ],
                        [
                            'titulo' => 'Talentos y pasiones',
                            'texto' => 'Exploran arte, ciencia, ferias escolares y experiencias que conectan lo aprendido con el mundo real.',
                        ],
                    ],
                    'experiencias' => [
                        'Dia del Arte',
                        'Deportes',
                        'Aniversario',
                        'Clanes',
                        'Presentaciones',
                    ],
                ],
            ],
            'bachillerato' => [
                'titulo' => 'Bachillerato',
                'descripcion' => 'Preparacion para la universidad con orientacion vocacional y alto nivel academico.',
                'logo_path' => 'Logos de niveles educativos/logo bachillerato.png',
                'hoja_informativa_path' => 'Hojas informativas/High school _ hoja informativa.pdf.pdf',
                'modelo_academico_path' => 'Modelos educativos/modelo educativo high.png',
                'informacion' => [
                    'titulo' => 'Un bachillerato que te reta, forma y tambien se disfruta',
                    'intro' => 'Preparacion universitaria con Programa del Diploma IB, orientacion vocacional, idiomas, liderazgo y proyectos reales.',
                    'puntos' => [
                        'Bachillerato IB oficial con validez internacional.',
                        'Programa del Diploma y desarrollo del perfil IB.',
                        'Ingles, espanol y frances con 15 bloques semanales en ingles.',
                        'Mas del 70% de egresados obtiene becas en universidades de Mexico y el extranjero.',
                        'Orientacion vocacional, asesoria para admisiones y seguimiento individual.',
                    ],
                    'secciones' => [
                        [
                            'titulo' => 'Pensamiento global',
                            'texto' => 'Teoria del Conocimiento y CAS fortalecen pensamiento critico, vision internacional y capacidad de tomar decisiones.',
                        ],
                        [
                            'titulo' => 'Certificaciones e idiomas',
                            'texto' => 'La formacion en ingles y frances prepara para comunicar argumentos, escribir ensayos y abrir puertas universitarias.',
                        ],
                        [
                            'titulo' => 'Debates y liderazgo',
                            'texto' => 'DKMUN permite negociar, argumentar, proponer soluciones y representar paises en asuntos internacionales.',
                        ],
                        [
                            'titulo' => 'Impacto social y expresion',
                            'texto' => 'CAS, arte y deporte diario hacen que cada estudiante participe en proyectos culturales, sociales, ambientales o deportivos.',
                        ],
                    ],
                    'experiencias' => [
                        'Expo Arte',
                        'Graduacion',
                        'Dia del Estudiante',
                        'DKMUN',
                        'Proyectos IB',
                    ],
                ],
            ],
            'ib-en-discovery' => [
                'titulo' => 'IB en Discovery',
                'descripcion' => 'Una experiencia educativa con enfoque internacional y pensamiento critico.',
                'logo_path' => 'Logos de niveles educativos/Logo IB cl.jpeg',
            ],
            'certificacion-de-ingles' => [
                'titulo' => 'Certificacion de Ingles',
                'descripcion' => 'Desarrollo del idioma ingles con metas claras y acompanamiento academico.',
            ],
        ];
    }

    private function obtenerHistoriaNosotros(): array
    {
        $imagenesHistoria = $this->imagenesVista('nosotros', [
            'historia_2003' => [
                'titulo' => 'Nosotros - Historia 2003',
                'referencia' => 'Imagen para el hito Discovery Kinder en la linea del tiempo de Nosotros.',
                'url' => $this->generarMediaUrlDesdeRuta('Linea del tiempo/2003-1.jpg'),
            ],
            'historia_2003_2' => [
                'titulo' => 'Nosotros - Historia 2003 - Imagen secundaria',
                'referencia' => 'Imagen secundaria para el hito Discovery Kinder en la linea del tiempo de Nosotros.',
                'url' => $this->generarMediaUrlDesdeRuta('Linea del tiempo/2003-2.jpg'),
            ],
            'historia_2005' => [
                'titulo' => 'Nosotros - Historia 2005',
                'referencia' => 'Imagen para el hito Discovery Primaria en la linea del tiempo de Nosotros.',
                'url' => $this->generarMediaUrlDesdeRuta('Linea del tiempo/2005-1.jpg'),
            ],
            'historia_2005_2' => [
                'titulo' => 'Nosotros - Historia 2005 - Imagen secundaria',
                'referencia' => 'Imagen secundaria para el hito Discovery Primaria en la linea del tiempo de Nosotros.',
                'url' => $this->generarMediaUrlDesdeRuta('Linea del tiempo/2005-2.jpg'),
            ],
            'historia_2011' => [
                'titulo' => 'Nosotros - Historia 2011',
                'referencia' => 'Imagen para el hito Discovery Secundaria en la linea del tiempo de Nosotros.',
                'url' => $this->generarMediaUrlDesdeRuta('Linea del tiempo/2011-1.jpg'),
            ],
            'historia_2016' => [
                'titulo' => 'Nosotros - Historia 2016',
                'referencia' => 'Imagen para el hito Discovery Bachillerato en la linea del tiempo de Nosotros.',
                'url' => $this->generarMediaUrlDesdeRuta('Linea del tiempo/2016-1.jpg'),
            ],
            'historia_2018' => [
                'titulo' => 'Nosotros - Historia 2018',
                'referencia' => 'Imagen para el hito Colegio del Mundo IB en la linea del tiempo de Nosotros.',
                'url' => $this->generarMediaUrlDesdeRuta('Linea del tiempo/2018-1.jpg'),
            ],
            'historia_2019' => [
                'titulo' => 'Nosotros - Historia 2019',
                'referencia' => 'Imagen para el hito Nuevas instalaciones en la linea del tiempo de Nosotros.',
                'url' => $this->generarMediaUrlDesdeRuta('Linea del tiempo/2019-1.jpg'),
            ],
            'historia_2019_2' => [
                'titulo' => 'Nosotros - Historia 2019 - Imagen secundaria',
                'referencia' => 'Imagen secundaria para el hito Nuevas instalaciones en la linea del tiempo de Nosotros.',
                'url' => $this->generarMediaUrlDesdeRuta('Linea del tiempo/2019-2.jpg'),
            ],
            'historia_2023' => [
                'titulo' => 'Nosotros - Historia 2023',
                'referencia' => 'Imagen para el hito DKMUN primera edicion en la linea del tiempo de Nosotros.',
                'url' => $this->generarMediaUrlDesdeRuta('Linea del tiempo/2023-1.jpg'),
            ],
            'historia_2023_2' => [
                'titulo' => 'Nosotros - Historia 2023 - Imagen secundaria',
                'referencia' => 'Imagen secundaria para el hito DKMUN primera edicion en la linea del tiempo de Nosotros.',
                'url' => $this->generarMediaUrlDesdeRuta('Linea del tiempo/2023-2.jpg'),
            ],
            'historia_2025' => [
                'titulo' => 'Nosotros - Historia 2025',
                'referencia' => 'Imagen para el hito Actualmente en la linea del tiempo de Nosotros.',
                'url' => $this->generarMediaUrlDesdeRuta('Linea del tiempo/2025-1.jpg'),
            ],
        ]);

        return [
            ['anio' => '2003', 'titulo' => 'Discovery Kinder', 'texto' => 'Nace Discovery Kinder, el inicio de un sueno educativo porque los primeros pasos trascienden.', 'imagenes' => [$imagenesHistoria['historia_2003'], $imagenesHistoria['historia_2003_2']]],
            ['anio' => '2005', 'titulo' => 'Discovery Primaria', 'texto' => 'Inauguracion de Discovery Primaria, creciendo con pasos firmes.', 'imagenes' => [$imagenesHistoria['historia_2005'], $imagenesHistoria['historia_2005_2']]],
            ['anio' => '2011', 'titulo' => 'Discovery Secundaria', 'texto' => 'Se suma Discovery Secundaria, ampliando horizontes.', 'imagenes' => [$imagenesHistoria['historia_2011']]],
            ['anio' => '2016', 'titulo' => 'Discovery Bachillerato', 'texto' => 'Llega Discovery Bachillerato, preparando grandes lideres y descubriendo su potencial.', 'imagenes' => [$imagenesHistoria['historia_2016']]],
            ['anio' => '2018', 'titulo' => 'Colegio del Mundo', 'texto' => 'Nos convertimos en Colegio del Mundo IB, abrazando la educacion internacional.', 'imagenes' => [$imagenesHistoria['historia_2018']]],
            ['anio' => '2019', 'titulo' => 'Nuevas instalaciones', 'texto' => 'Estrenamos nuevas instalaciones para seguir innovando.', 'imagenes' => [$imagenesHistoria['historia_2019'], $imagenesHistoria['historia_2019_2']]],
            ['anio' => '2023', 'titulo' => 'DKMUN primera edicion', 'texto' => 'Realizamos nuestra primera edicion DKMUN, un espacio para el liderazgo y la diplomacia.', 'imagenes' => [$imagenesHistoria['historia_2023'], $imagenesHistoria['historia_2023_2']]],
            ['anio' => '2025', 'titulo' => 'Actualmente', 'texto' => 'Seguimos escribiendo nuestra historia, creciendo y evolucionando juntos.', 'imagenes' => [$imagenesHistoria['historia_2025']]],
        ];
    }
}
