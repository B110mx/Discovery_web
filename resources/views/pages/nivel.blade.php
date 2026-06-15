@extends('layouts.app')

@section('content')

{{--
    Plantilla compartida por Kinder, Elementary, Middle, High, IB, POP e Inglés.
    El arreglo $nivel define tema, layout y contenido; evita condicionar por URL
    fuera de los bloques de layout ya previstos.
--}}
@php
    $imagenPrincipal = $nivel['imagen_principal'] ?? [
        'url' => $galeria[0]['url'] ?? null,
        'titulo' => $nivel['titulo'] . ' - Imagen principal',
        'referencia' => 'Imagen principal del encabezado del nivel ' . $nivel['titulo'] . '.',
    ];
    $tema = $nivel['tema'];
    $destacados = $nivel['informacion']['destacados'] ?? [
        [
            'titulo' => 'Acompañamiento',
            'texto' => 'Seguimiento cercano para que cada Explorer avance con confianza.',
        ],
        [
            'titulo' => 'Aprendizaje',
            'texto' => 'Actividades académicas y proyectos que conectan conocimiento con experiencia.',
        ],
        [
            'titulo' => 'Comunidad',
            'texto' => 'Una relación cercana entre Explorers, docentes y familias.',
        ],
    ];
    $kinderIdeal = [
        [
            'icono' => 'feet',
            'titulo' => 'Programas de neuroaprendizaje',
            'texto' => 'Neuromotor, audiomotor y Spark.',
        ],
        [
            'icono' => 'language',
            'titulo' => 'Educación Bilingüe y Trilingüe',
            'texto' => 'Inglés, Español y desde 5° de Elementary se incorpora Francés.',
        ],
        [
            'icono' => 'ib',
            'titulo' => 'Perfil IB',
            'texto' => 'Nuestros programas están alineados a la Organización del Bachillerato Internacional (IB), incluso desde los primeros años.',
        ],
        [
            'icono' => 'academias',
            'titulo' => 'Academias Vespertinas',
            'texto' => 'Fútbol, Básquetbol, Ajedrez, Origami, Atletismo y UrbanKids. Para padres: Club de conversación en Inglés, Flamenco y Música.',
        ],
        [
            'icono' => 'atencion',
            'titulo' => 'Atención personalizada',
            'texto' => 'Grupos reducidos de 14 a 24 Explorers.',
        ],
        [
            'icono' => 'bienestar',
            'titulo' => 'Bienestar emocional y preceptoria',
            'texto' => 'Los acompañamos en su desarrollo emocional y académico.',
        ],
    ];
    $primariaIdeal = [
        [
            'icono' => 'language',
            'titulo' => 'Educación Bilingüe y Trilingüe',
            'texto' => 'Inglés, Español y desde 5° de Elementary se incorpora Francés.',
        ],
        [
            'icono' => 'ib',
            'titulo' => 'Perfil IB',
            'texto' => 'Nuestros explorers desarrollan una mentalidad internacional a través de Unidades de Indagación.',
        ],
        [
            'icono' => 'arts',
            'titulo' => 'Artes y Deportes integrados',
            'texto' => 'Danza, Teatro, Música, Artes Visuales y deporte diario.',
        ],
        [
            'icono' => 'academias',
            'titulo' => 'Academias Vespertinas',
            'texto' => 'Fútbol, Básquetbol, Ajedrez, Origami, Atletismo y UrbanKids. Para padres: Club de conversación en Inglés, Flamenco y Música.',
        ],
        [
            'icono' => 'atencion',
            'titulo' => 'Atención personalizada',
            'texto' => 'Grupos reducidos de 14 a 24 Explorers. Esto permite acompañamiento individualizado en lo académico y emocional.',
        ],
        [
            'icono' => 'bienestar',
            'titulo' => 'Bienestar emocional y preceptoria',
            'texto' => 'Los acompañamos en su desarrollo emocional y académico con sesiones que fortalecen su autoestima, empatía y habilidades sociales.',
        ],
    ];
    $secundariaIdeal = [
        [
            'icono' => 'language',
            'titulo' => 'Educación Bilingüe y Trilingüe',
            'texto' => 'Inglés, Español y desde 5° de Elementary se incorpora Francés. Inglés, Español y Francés con más de 15 bloques a la semana.',
        ],
        [
            'icono' => 'skills',
            'titulo' => 'Habilidades del Siglo XXI',
            'texto' => 'Enfoques de aprendizaje de pensamiento, autogestión, comunicación, investigación y sociales.',
        ],
        [
            'icono' => 'ib',
            'titulo' => 'Perfil IB',
            'texto' => 'Nuestros explorers desarrollan una mentalidad internacional a través de Proyectos Interdisciplinarios.',
        ],
        [
            'icono' => 'arts',
            'titulo' => 'Artes y Deportes integrados',
            'texto' => 'Deporte diario: Fútbol, Básquetbol, Voleibol y Tenis, Danza, Teatro, Música y Artes Visuales. Equipos de Voleibol y Básquetbol que compiten en ligas.',
        ],
        [
            'icono' => 'academias',
            'titulo' => 'Academias Vespertinas',
            'texto' => 'Fútbol, Básquetbol, Ajedrez, Origami, Atletismo y UrbanKids. Para padres: Club de conversación en Inglés, Flamenco y Música.',
        ],
        [
            'icono' => 'bienestar-head',
            'titulo' => 'Bienestar emocional y preceptoria',
            'texto' => 'Programa de bienestar emocional y preceptoría hasta el último año.',
        ],
    ];
    $bachilleratoIdeal = [
        [
            'icono' => 'language',
            'titulo' => 'Educación Bilingüe y Trilingüe',
            'texto' => 'Inglés, Español y Francés. 15 bloques a la semana en Inglés.',
        ],
        [
            'icono' => 'graduation',
            'titulo' => 'Becas universitarios',
            'texto' => 'Más del 70% de nuestros egresados obtienen becas en las mejores universidades del país y en el extranjero.',
        ],
        [
            'icono' => 'ib',
            'titulo' => 'Perfil IB',
            'texto' => 'Somos High con Programa IB oficial y validez internacional. Programa del Diploma y desarrollo del perfil IB.',
        ],
        [
            'icono' => 'arts',
            'titulo' => 'Artes y Deportes integrados',
            'texto' => 'Formación artística y deportiva, que favorecen tu desarrollo físico y emocional.',
        ],
        [
            'icono' => 'vocation',
            'titulo' => 'Orientación vocacional personalizada',
            'texto' => 'Te acompañamos con orientación vocacional, asesoría para admisiones y seguimiento individual.',
        ],
        [
            'icono' => 'bienestar-head',
            'titulo' => 'Bienestar emocional y preceptoria',
            'texto' => 'Programa de bienestar emocional y preceptoría hasta el último año.',
        ],
        [
            'icono' => 'skills',
            'titulo' => 'Habilidades del Siglo XXI',
            'texto' => 'Enfoques de aprendizaje de pensamiento, autogestión, comunicación, investigación y sociales.',
        ],
    ];
    $bloqueIdeal = match ($nivel['slug'] ?? null) {
        'preescolar' => [
            'titulo' => '¿Por qué somos el kinder ideal para tus hijos?',
            'items' => $kinderIdeal,
        ],
        'primaria' => [
            'titulo' => '¿Por qué somos Elementary para tus hijos?',
            'items' => $primariaIdeal,
        ],
        'secundaria' => [
            'titulo' => '¿Por qué Middle ayudará a tus hijos a convertirse en su mejor versión?',
            'items' => $secundariaIdeal,
        ],
        'bachillerato' => [
            'titulo' => '¿Por qué somos el mejor High?',
            'items' => $bachilleratoIdeal,
        ],
        default => null,
    };
    $bloqueIdealGrid = $bloqueIdeal && count($bloqueIdeal['items']) === 7 ? 'xl:grid-cols-7' : 'xl:grid-cols-6';
@endphp

<section class="max-w-6xl mx-auto">
    <div class="{{ $tema['hero'] }} rounded-xl shadow-lg overflow-hidden">
        <div class="grid md:grid-cols-[.95fr_1.05fr] md:items-stretch">
            <div class="flex flex-col justify-center p-6 md:p-8">
                @if (! empty($nivel['logo_extendido'] ?? $nivel['logo']))
                    @php
                        $logoHero = $nivel['logo_extendido'] ?? $nivel['logo'];
                        $usaLogoExtendido = ! empty($nivel['logo_extendido']);
                    @endphp
                    <div class="{{ $usaLogoExtendido ? 'aspect-[3/1] w-80 overflow-hidden md:w-[26rem]' : 'flex h-16 w-fit items-center p-2 md:h-20' }} mx-auto mb-4 max-w-full rounded bg-white shadow-md">
                        <img
                            src="{{ $logoHero }}"
                            alt="Logo {{ $nivel['titulo'] }}"
                            class="{{ $usaLogoExtendido ? 'h-full w-full object-cover' : 'h-full w-auto max-w-full object-contain' }}"
                        >
                    </div>
                @endif

                <p class="font-semibold uppercase tracking-wide text-xs md:text-sm">Oferta Educativa</p>
                <h1 class="mt-2 text-3xl font-extrabold md:text-4xl">{{ $nivel['titulo'] }}</h1>
                <p class="{{ $tema['heroMuted'] }} mt-3 max-w-2xl text-base leading-7">{{ $nivel['descripcion'] }}</p>
            </div>

            <x-imagen-seccion
                :imagen="$imagenPrincipal"
                alt="{{ $nivel['titulo'] }}"
                class="h-48 w-full {{ ($nivel['slug'] ?? null) === 'ib-en-discovery' ? 'bg-white object-contain p-4' : 'object-cover' }} sm:h-56 md:h-[26rem]"
                placeholder-class="h-48 sm:h-56 md:h-[26rem]"
            />
        </div>
    </div>

    <div class="grid gap-4 md:grid-cols-3 mt-6">
        @foreach ($destacados as $destacado)
            <div class="bg-white p-5 rounded-xl shadow-md">
                <span class="mb-3 inline-flex h-1.5 w-10 rounded-full {{ $tema['bar'] }}"></span>
                <h2 class="text-xl font-bold {{ $tema['heading'] }} mb-2">{{ $destacado['titulo'] }}</h2>
                <p class="text-sm leading-6 text-gray-600">{{ $destacado['texto'] }}</p>
            </div>
        @endforeach
    </div>

    @if (($nivel['layout'] ?? null) === 'pop' && ! empty($nivel['informacion']))
        @php
            $pop = $nivel['informacion'];
            $imagenesPop = $pop['imagenes'] ?? [];
        @endphp

        <section class="mt-12 overflow-hidden rounded-xl bg-white shadow-md">
            <div class="grid lg:grid-cols-[1.05fr_.95fr]">
                <div class="p-6 md:p-10">
                    <p class="text-sm font-bold uppercase tracking-wide {{ $tema['eyebrow'] }}">{{ $pop['eyebrow'] }}</p>
                    <h2 class="mt-3 text-3xl font-extrabold leading-tight text-gray-950 md:text-4xl">{{ $pop['titulo'] }}</h2>
                    <p class="mt-5 text-lg leading-8 text-gray-600">{{ $pop['intro'] }}</p>

                    <h3 class="mt-8 text-xl font-extrabold text-gray-950">Habilidades para decidir y construir</h3>
                    <div class="mt-4 flex flex-wrap gap-2">
                        @foreach ($pop['habilidades'] as $habilidad)
                            <span class="rounded-full bg-blue-50 px-4 py-2 text-sm font-bold text-blue-700">
                                {{ $habilidad }}
                            </span>
                        @endforeach
                    </div>
                </div>

                <x-imagen-seccion
                    :imagen="$imagenesPop['componentes']"
                    alt="Componentes del POP del IB"
                    class="h-full min-h-72 w-full object-cover"
                    placeholder-class="h-full min-h-72 w-full rounded-none"
                />
            </div>
        </section>

        <section class="mt-12">
            <div class="max-w-3xl">
                <p class="text-sm font-bold uppercase tracking-wide {{ $tema['eyebrow'] }}">Formación integral</p>
                <h2 class="mt-2 text-3xl font-extrabold text-gray-950">Componentes del POP</h2>
                <p class="mt-4 leading-8 text-gray-600">
                    Experiencias que conectan el aprendizaje académico con la vida universitaria, profesional y comunitaria.
                </p>
            </div>

            <div class="mt-7 grid gap-5 md:grid-cols-2">
                @foreach ($pop['componentes'] as $componente)
                    <article class="rounded-xl border border-blue-100 bg-white p-6 shadow-sm">
                        <span class="inline-flex h-10 w-10 items-center justify-center rounded-full bg-blue-700 text-lg font-extrabold text-white">
                            {{ $loop->iteration }}
                        </span>
                        <h3 class="mt-5 text-xl font-extrabold text-gray-950">{{ $componente['titulo'] }}</h3>
                        <p class="mt-3 leading-7 text-gray-600">{{ $componente['texto'] }}</p>
                    </article>
                @endforeach
            </div>
        </section>

        <section class="mt-12 overflow-hidden rounded-xl bg-gray-950 p-6 text-white shadow-lg md:p-10">
            <div class="max-w-3xl">
                <p class="text-sm font-bold uppercase tracking-wide text-yellow-500">Explorar antes de decidir</p>
                <h2 class="mt-2 text-3xl font-extrabold md:text-4xl">Rutas preuniversitarias Discovery</h2>
                <p class="mt-4 leading-8 text-gray-300">
                    No representan una especialización temprana. Son oportunidades para descubrir fortalezas, explorar posibilidades profesionales y tomar decisiones más informadas.
                </p>
            </div>

            <div class="mt-8 grid gap-6 lg:grid-cols-2">
                @foreach ($pop['rutas'] as $ruta)
                    @php($imagenRuta = $loop->first ? $imagenesPop['ruta_data_science'] : $imagenesPop['ruta_diseno_3d'])
                    <article class="overflow-hidden rounded-xl bg-white text-gray-950 shadow-md">
                        <x-imagen-seccion
                            :imagen="$imagenRuta"
                            :alt="$ruta['titulo']"
                            class="aspect-[16/9] w-full object-cover"
                            placeholder-class="aspect-[16/9] w-full rounded-none"
                        />
                        <div class="p-6">
                            <h3 class="text-2xl font-extrabold text-blue-700">{{ $ruta['titulo'] }}</h3>
                            <p class="mt-3 leading-7 text-gray-600">{{ $ruta['intro'] }}</p>

                            <div class="mt-5 flex flex-wrap gap-2">
                                @foreach ($ruta['habilidades'] as $habilidad)
                                    <span class="rounded-full bg-blue-50 px-3 py-1.5 text-xs font-bold text-blue-700">{{ $habilidad }}</span>
                                @endforeach
                            </div>

                            <p class="mt-5 text-sm leading-7 text-gray-600">
                                <strong class="text-gray-950">Perfiles relacionados:</strong>
                                {{ $ruta['perfiles'] }}
                            </p>
                        </div>
                    </article>
                @endforeach
            </div>
        </section>

        <section class="mt-12">
            <div class="grid gap-8 lg:grid-cols-[.9fr_1.1fr] lg:items-center">
                <x-imagen-seccion
                    :imagen="$imagenesPop['areas_academicas']"
                    alt="Áreas académicas de 11° y 12°"
                    class="aspect-[4/3] w-full rounded-xl object-cover shadow-md"
                    placeholder-class="aspect-[4/3] w-full"
                />

                <div>
                    <p class="text-sm font-bold uppercase tracking-wide {{ $tema['eyebrow'] }}">Trayectoria académica</p>
                    <h2 class="mt-2 text-3xl font-extrabold text-gray-950">Áreas de interés profesional en 11° y 12°</h2>
                    <p class="mt-4 leading-8 text-gray-600">
                        Los Explorers profundizan conocimientos relacionados con tres grandes áreas preuniversitarias.
                    </p>
                </div>
            </div>

            <div class="mt-8 grid gap-5 lg:grid-cols-3">
                @foreach ($pop['areas'] as $area)
                    <article class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm">
                        <span class="inline-flex h-2 w-14 rounded-full {{ $loop->iteration === 1 ? 'bg-red-600' : ($loop->iteration === 2 ? 'bg-blue-700' : 'bg-green-600') }}"></span>
                        <h3 class="mt-5 text-xl font-extrabold text-gray-950">{{ $area['titulo'] }}</h3>
                        <p class="mt-3 leading-7 text-gray-600">{{ $area['texto'] }}</p>
                        <p class="mt-5 text-sm leading-7 text-gray-600">
                            <strong class="text-gray-950">Perfiles universitarios:</strong>
                            {{ $area['perfiles'] }}
                        </p>
                    </article>
                @endforeach
            </div>
        </section>

        <section class="mt-12 overflow-hidden rounded-xl bg-blue-700 text-white shadow-lg">
            <div class="grid lg:grid-cols-[1.05fr_.95fr]">
                <div class="flex flex-col justify-center p-7 md:p-10">
                    <p class="text-sm font-bold uppercase tracking-wide text-yellow-500">Discovery High School</p>
                    <h2 class="mt-3 text-3xl font-extrabold md:text-4xl">{{ $pop['cierre']['titulo'] }}</h2>
                    <p class="mt-5 leading-8 text-blue-50">{{ $pop['cierre']['texto'] }}</p>
                    <p class="mt-6 text-2xl font-extrabold text-yellow-500">{{ $pop['cierre']['frase'] }}</p>
                    <a href="{{ route('contacto') }}" class="mt-7 inline-flex w-fit items-center justify-center rounded bg-yellow-500 px-6 py-3 font-extrabold text-black transition hover:bg-amber-500">
                        Solicitar informes
                    </a>
                </div>

                <x-imagen-seccion
                    :imagen="$imagenesPop['cierre']"
                    alt="Preparación universitaria POP del IB"
                    class="h-full min-h-80 w-full object-cover"
                    placeholder-class="h-full min-h-80 w-full rounded-none border-white/30 bg-blue-800"
                />
            </div>
        </section>
    @elseif (in_array($nivel['layout'] ?? null, ['ib', 'ingles'], true) && ! empty($nivel['informacion']))
        <section class="mt-12 overflow-hidden rounded-xl bg-white shadow-md">
            <div class="{{ $tema['hero'] }} p-6 md:p-10">
                <div class="max-w-5xl">
                    <p class="font-semibold uppercase tracking-wide text-sm {{ $tema['heroMuted'] }}">{{ $nivel['informacion']['eyebrow'] ?? 'Bachillerato Internacional' }}</p>
                    <h2 class="mt-2 text-3xl font-extrabold md:text-4xl">{{ $nivel['informacion']['titulo'] }}</h2>
                    <p class="mt-5 text-lg leading-8 {{ $tema['heroMuted'] }}">{{ $nivel['informacion']['intro'] }}</p>
                </div>
            </div>

            <div class="p-6 md:p-8">
                <div class="grid gap-8 lg:grid-cols-[.9fr_1.1fr]">
                    <div class="rounded-xl bg-gray-50 p-6">
                        <p class="text-sm font-bold uppercase tracking-wide {{ $tema['eyebrow'] }}">{{ $nivel['informacion']['eyebrow'] ?? 'Enfoques del aprendizaje' }}</p>
                        <h3 class="mt-2 text-2xl font-extrabold text-black">{{ $nivel['informacion']['puntos_titulo'] ?? 'Cinco categorías de habilidades' }}</h3>
                        <div class="mt-5 space-y-3">
                            @foreach ($nivel['informacion']['puntos'] as $punto)
                                <div class="flex gap-3 rounded-lg bg-white p-4 text-gray-700 shadow-sm">
                                    <span class="mt-2 h-2.5 w-2.5 flex-shrink-0 rounded-full {{ $tema['dot'] }}"></span>
                                    <span class="font-semibold leading-7">{{ $punto }}</span>
                                </div>
                            @endforeach
                        </div>

                        @if (! empty($nivel['informacion']['imagen_enfoque']['url']))
                            <div class="mt-6 overflow-hidden rounded-xl bg-white p-3 shadow-sm">
                                <x-imagen-seccion
                                    :imagen="$nivel['informacion']['imagen_enfoque']"
                                    alt="{{ $nivel['informacion']['imagen_enfoque']['titulo'] }}"
                                    class="w-full rounded-lg object-contain"
                                    placeholder-class="aspect-[4/3] w-full"
                                />
                            </div>
                        @endif
                    </div>

                    <div class="space-y-4">
                        @foreach ($nivel['informacion']['secciones'] as $seccion)
                            <article class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm">
                                <span class="inline-flex h-2 w-12 rounded-full {{ $tema['bar'] }}"></span>
                                <h3 class="mt-4 text-xl font-extrabold text-black">{{ $seccion['titulo'] }}</h3>
                                <p class="mt-3 leading-8 text-gray-600">{{ $seccion['texto'] }}</p>
                            </article>
                        @endforeach
                    </div>
                </div>

                <div class="mt-10 rounded-xl bg-gray-50 p-6">
                    <div class="grid gap-5 md:grid-cols-2">
                        <div>
                            <p class="text-sm font-bold uppercase tracking-wide {{ $tema['eyebrow'] }}">{{ $nivel['informacion']['eyebrow'] ?? 'Reconocimiento internacional' }}</p>
                            <h3 class="mt-2 text-2xl font-extrabold text-black">{{ $nivel['informacion']['cierre']['titulo'] ?? 'Colegio acreditado para impartir el Programa del Diploma' }}</h3>
                        </div>
                        <p class="leading-8 text-gray-600">
                            {{ $nivel['informacion']['cierre']['texto'] ?? 'El Programa del Diploma del IB permite que los Explorers profundicen en sus conocimientos, fortalezcan dos lenguas, desarrollen criterio ético y construyan una visión internacional para su siguiente etapa académica.' }}
                        </p>
                    </div>
                </div>

                <div class="mt-8 flex flex-wrap gap-3">
                    @foreach ($nivel['informacion']['experiencias'] ?? [] as $experiencia)
                        <span class="rounded-full bg-white px-4 py-2 text-sm font-bold {{ $tema['chip'] }} shadow-sm">
                            {{ $experiencia }}
                        </span>
                    @endforeach
                </div>
            </div>
        </section>
    @elseif (! empty($nivel['informacion']))
        @if ($bloqueIdeal)
            <section class="mt-12 rounded-xl border px-5 py-10 shadow-md {{ $tema['soft'] }} md:px-8 lg:px-10">
                <h2 class="text-center text-3xl font-extrabold uppercase {{ $tema['heading'] }} md:text-4xl">
                    {{ $bloqueIdeal['titulo'] }}
                </h2>

                <div class="mt-10 grid gap-8 sm:grid-cols-2 lg:grid-cols-3 {{ $bloqueIdealGrid }}">
                    @foreach ($bloqueIdeal['items'] as $item)
                        <article class="flex flex-col items-center text-center">
                            <div class="flex h-20 items-center justify-center {{ $tema['heading'] }}">
                                @switch($item['icono'])
                                    @case('feet')
                                        <svg class="h-16 w-16" viewBox="0 0 64 64" fill="currentColor" aria-hidden="true">
                                            <path d="M19.5 30.5c6.1 0 10.5 6 10.5 13.2 0 7.8-4.5 13.3-10.2 13.3-6.5 0-11.4-7.4-11.4-15 0-6.5 4.1-11.5 11.1-11.5z"></path>
                                            <path d="M43.7 22.2c5.2.8 8.5 6.5 7.5 12.6-1.1 6.8-5.8 11-10.7 10.2-5.7-.9-8.9-7.9-7.8-14.5 1-5.9 4.9-9.2 11-8.3z"></path>
                                            <circle cx="9.9" cy="26" r="2.2"></circle>
                                            <circle cx="15.1" cy="21" r="2.1"></circle>
                                            <circle cx="21.4" cy="18.4" r="2"></circle>
                                            <circle cx="28" cy="18.6" r="1.9"></circle>
                                            <circle cx="50.4" cy="16.3" r="1.9"></circle>
                                            <circle cx="45.4" cy="12.6" r="1.8"></circle>
                                            <circle cx="39.6" cy="10.9" r="1.7"></circle>
                                            <circle cx="33.9" cy="11.7" r="1.6"></circle>
                                        </svg>
                                        @break
                                    @case('language')
                                        <svg class="h-16 w-16" viewBox="0 0 64 64" fill="none" stroke="currentColor" stroke-width="3" aria-hidden="true">
                                            <circle cx="32" cy="32" r="23"></circle>
                                            <path d="M10 32h44M32 9c-8 8-10 34 0 46M32 9c8 8 10 34 0 46"></path>
                                            <path d="M17 20h30M17 44h30"></path>
                                            <rect x="35" y="13" width="21" height="16" rx="5" fill="#f3f4f6"></rect>
                                            <path d="M41 21h9M41 25h6"></path>
                                            <rect x="8" y="36" width="20" height="16" rx="5" fill="#f3f4f6"></rect>
                                            <path d="M14 44h8M14 48h6"></path>
                                        </svg>
                                        @break
                                    @case('ib')
                                        <svg class="h-16 w-16" viewBox="0 0 64 64" fill="none" aria-hidden="true">
                                            <circle cx="32" cy="32" r="24" fill="#fff" stroke="currentColor" stroke-width="3"></circle>
                                            <text x="32" y="42" text-anchor="middle" font-size="28" font-weight="800" fill="currentColor" font-family="Georgia, serif">ib</text>
                                            <circle cx="32" cy="32" r="29" stroke="currentColor" stroke-width="1" stroke-dasharray="2 3"></circle>
                                        </svg>
                                        @break
                                    @case('skills')
                                        <svg class="h-16 w-16" viewBox="0 0 64 64" fill="none" stroke="currentColor" stroke-width="3" aria-hidden="true">
                                            <rect x="8" y="12" width="16" height="14" rx="4"></rect>
                                            <rect x="40" y="12" width="16" height="14" rx="4"></rect>
                                            <rect x="8" y="38" width="16" height="14" rx="4"></rect>
                                            <rect x="40" y="38" width="16" height="14" rx="4"></rect>
                                            <circle cx="32" cy="32" r="8"></circle>
                                            <path d="M24 19h8v5M40 19h-8v5M24 45h8v-5M40 45h-8v-5"></path>
                                            <path d="M29 32h6M32 29v6"></path>
                                        </svg>
                                        @break
                                    @case('graduation')
                                        <svg class="h-16 w-16" viewBox="0 0 64 64" fill="none" stroke="currentColor" stroke-width="3" aria-hidden="true">
                                            <path d="M8 23 32 11l24 12-24 12L8 23z"></path>
                                            <path d="M18 30v11c7 7 21 7 28 0V30"></path>
                                            <path d="M48 27v16"></path>
                                            <path d="M22 51h20"></path>
                                            <path d="M28 42h8v9h-8z"></path>
                                        </svg>
                                        @break
                                    @case('academias')
                                        <svg class="h-16 w-16" viewBox="0 0 64 64" fill="currentColor" aria-hidden="true">
                                            <circle cx="20" cy="20" r="5"></circle>
                                            <circle cx="43" cy="19" r="5"></circle>
                                            <path d="M15 29c-3 2-5 6-5 11h7v14h7V40h5v14h7V39c0-6-4-10-10-10H15z"></path>
                                            <path d="M40 29c-5 0-9 4-9 9v3h6v13h7V41h4v13h7V40c0-7-5-11-15-11z"></path>
                                            <path d="M25 27c4 4 10 4 14 0" fill="none" stroke="#f3f4f6" stroke-width="3" stroke-linecap="round"></path>
                                        </svg>
                                        @break
                                    @case('arts')
                                        <svg class="h-16 w-16" viewBox="0 0 64 64" fill="none" stroke="currentColor" stroke-width="3" aria-hidden="true">
                                            <path d="M13 15c8-4 19-1 25 6-1 11-9 20-20 21-6-5-9-17-5-27z"></path>
                                            <path d="M18 24c2 2 5 2 7 0M31 25c2 1 4 1 6-1M22 34c4 3 9 2 12-2"></path>
                                            <circle cx="47" cy="36" r="12"></circle>
                                            <path d="M35 36h24M47 24c5 6 5 18 0 24M47 24c-5 6-5 18 0 24"></path>
                                            <path d="M11 44c6-4 13-3 17 1 4-4 11-5 17-1-3 6-9 10-17 10s-14-4-17-10z"></path>
                                        </svg>
                                        @break
                                    @case('atencion')
                                        <svg class="h-16 w-16" viewBox="0 0 64 64" fill="none" stroke="currentColor" stroke-width="3" aria-hidden="true">
                                            <path d="M17 54c5-8 14-9 23-8 7 1 12-3 14-10"></path>
                                            <path d="M18 42c4-8 12-10 20-8 6 1 10-1 14-6"></path>
                                            <circle cx="32" cy="23" r="8"></circle>
                                            <path d="M18 32c-2-6 2-14 10-17M46 32c4-5 1-14-7-17M25 24h14"></path>
                                            <path d="M21 17c-5 2-8 7-8 13M43 17c5 2 8 7 8 13"></path>
                                        </svg>
                                        @break
                                    @case('bienestar-head')
                                        <svg class="h-16 w-16" viewBox="0 0 64 64" fill="none" stroke="currentColor" stroke-width="3" aria-hidden="true">
                                            <path d="M25 54H14V36c0-12 9-22 22-22 11 0 20 8 20 19 0 7-3 12-8 16v5H36"></path>
                                            <path d="M39 27c-4-4-9-1-9 4 0 6 9 11 9 11s9-5 9-11c0-5-5-8-9-4z"></path>
                                            <path d="M18 38h10M18 45h13"></path>
                                        </svg>
                                        @break
                                    @case('vocation')
                                        <svg class="h-16 w-16" viewBox="0 0 64 64" fill="none" stroke="currentColor" stroke-width="3" aria-hidden="true">
                                            <path d="M9 42h12l10 7h14c5 0 9-4 10-9"></path>
                                            <path d="M9 31h13l10 7h9c3 0 5-2 5-5"></path>
                                            <path d="M14 30V14h16v14"></path>
                                            <path d="M18 14v-4h8v4"></path>
                                            <circle cx="47" cy="17" r="7"></circle>
                                            <path d="M47 24v12M39 38h16"></path>
                                            <path d="M43 18l3 3 6-7"></path>
                                        </svg>
                                        @break
                                    @default
                                        <svg class="h-16 w-16" viewBox="0 0 64 64" fill="none" stroke="currentColor" stroke-width="3" aria-hidden="true">
                                            <path d="M20 35c-6-6-12-2-12 4 0 8 12 15 12 15s12-7 12-15c0-6-6-10-12-4z"></path>
                                            <path d="M44 35c-6-6-12-2-12 4 0 8 12 15 12 15s12-7 12-15c0-6-6-10-12-4z"></path>
                                            <path d="M17 26 34 9c4-4 11 2 7 7L28 29"></path>
                                            <path d="M39 29 51 17c4-4 11 2 7 7L44 38"></path>
                                        </svg>
                                @endswitch
                            </div>
                            <h3 class="mt-4 text-lg font-extrabold leading-6 {{ $tema['heading'] }}">{{ $item['titulo'] }}</h3>
                            <p class="mt-5 text-base leading-7 text-black">{{ $item['texto'] }}</p>
                        </article>
                    @endforeach
                </div>
            </section>
        @endif

        <section class="mt-12 overflow-hidden rounded-xl bg-white shadow-md">
            <div class="{{ $tema['hero'] }} p-6 md:p-8">
                <div class="flex flex-col gap-6 lg:flex-row lg:items-end lg:justify-between">
                    <div class="max-w-4xl">
                        <p class="font-semibold uppercase tracking-wide text-sm {{ $tema['heroMuted'] }}">Hoja informativa maquetada</p>
                        <h2 class="mt-2 text-3xl font-extrabold md:text-4xl">{{ $nivel['informacion']['titulo'] }}</h2>
                        <p class="mt-4 text-lg leading-8 {{ $tema['heroMuted'] }}">{{ $nivel['informacion']['intro'] }}</p>
                    </div>

                    @if (! empty($nivel['hoja_informativa_url']))
                        <a
                            href="{{ $nivel['hoja_informativa_url'] }}"
                            target="_blank"
                            rel="noopener"
                            class="inline-flex w-fit items-center justify-center rounded px-5 py-3 font-bold {{ $tema['button'] }}"
                        >
                            Abrir PDF original
                        </a>
                    @endif
                </div>
            </div>

            <div class="p-6 md:p-8">
                <div class="grid gap-8 lg:grid-cols-[1.05fr_.95fr]">
                    <div>
                        <h3 class="text-2xl font-bold {{ $tema['heading'] }}">Puntos clave</h3>
                        <div class="mt-5 grid gap-3 sm:grid-cols-2">
                            @foreach ($nivel['informacion']['puntos'] as $punto)
                                <div class="flex gap-3 rounded-lg border {{ $tema['soft'] }} p-4 text-gray-700">
                                    <span class="mt-2 h-2.5 w-2.5 flex-shrink-0 rounded-full {{ $tema['dot'] }}"></span>
                                    <span class="font-semibold leading-7">{{ $punto }}</span>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    @if (! empty($nivel['modelo_academico']['url']))
                        <aside class="rounded-xl border border-gray-200 bg-gray-50 p-4">
                            <div class="mb-4">
                                <p class="text-sm font-bold uppercase tracking-wide {{ $tema['eyebrow'] }}">Modelo académico</p>
                                <h3 class="mt-1 text-2xl font-bold text-black">{{ $nivel['titulo'] }}</h3>
                            </div>
                            <a href="{{ $nivel['modelo_academico']['url'] }}" class="glightbox block" data-gallery="modelo-academico-{{ $nivel['slug'] }}" data-title="Modelo académico {{ $nivel['titulo'] }}">
                                <x-imagen-seccion
                                    :imagen="$nivel['modelo_academico']"
                                    alt="Modelo académico {{ $nivel['titulo'] }}"
                                    class="max-h-[420px] w-full rounded-lg bg-white object-contain p-3 shadow-sm transition-transform hover:scale-[1.02]"
                                    placeholder-class="min-h-80 w-full"
                                />
                            </a>
                        </aside>
                    @endif
                </div>

                <div class="mt-10 grid gap-5 md:grid-cols-2">
                    @foreach ($nivel['informacion']['secciones'] as $seccion)
                        <article class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm">
                            <span class="inline-flex h-2 w-12 rounded-full {{ $tema['bar'] }}"></span>
                            <h3 class="mt-4 text-xl font-extrabold text-black">{{ $seccion['titulo'] }}</h3>
                            <p class="mt-3 leading-8 text-gray-600">{{ $seccion['texto'] }}</p>
                        </article>
                    @endforeach
                </div>

                @if (! empty($nivel['informacion']['imagenes_referencia']))
                    <div class="mt-10">
                        <h3 class="text-2xl font-bold {{ $tema['heading'] }}">Imágenes de referencia</h3>
                        <div class="mt-5 grid gap-5 md:grid-cols-2">
                            @foreach ($nivel['informacion']['imagenes_referencia'] as $imagenReferencia)
                                <x-imagen-seccion
                                    :imagen="$imagenReferencia"
                                    alt="{{ $imagenReferencia['titulo'] ?? 'Imagen pendiente' }}"
                                    class="aspect-[4/3] w-full object-cover"
                                    placeholder-class="aspect-[4/3] w-full"
                                />
                            @endforeach
                        </div>
                    </div>
                @endif

                @if (! empty($nivel['informacion']['experiencias']))
                    <div class="mt-10 rounded-xl bg-gray-50 p-6">
                        <h3 class="text-2xl font-bold {{ $tema['heading'] }}">Comunidad y experiencias</h3>
                        <div class="mt-5 flex flex-wrap gap-3">
                            @foreach ($nivel['informacion']['experiencias'] as $experiencia)
                                <span class="rounded-full bg-white px-4 py-2 text-sm font-bold {{ $tema['chip'] }} shadow-sm">
                                    {{ $experiencia }}
                                </span>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>
        </section>
    @endif

    @if (! ($nivel['ocultar_galeria'] ?? false) && ! empty($galeria))
        <section class="mt-12">
            <div class="flex flex-col md:flex-row md:items-end md:justify-between gap-4 mb-6">
                <div>
                    <p class="font-semibold uppercase tracking-wide text-sm {{ $tema['eyebrow'] }}">Galería</p>
                    <h2 class="text-3xl font-bold text-black mt-2">{{ $nivel['titulo'] }} en Discovery®</h2>
                </div>
                <p class="text-gray-600 max-w-2xl">
                    Momentos reales de nuestra comunidad educativa en este nivel.
                </p>
            </div>

            <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-5">
                @foreach ($galeria as $imagen)
                    <a href="{{ $imagen['url'] }}" class="glightbox" data-gallery="gallery-nivel" data-title="{{ $imagen['alt'] }}">
                        <img
                            src="{{ $imagen['url'] }}"
                            alt="{{ $imagen['alt'] }}"
                            loading="lazy"
                            class="w-full aspect-[4/3] object-cover rounded-xl shadow-md transition-transform hover:scale-[1.02]"
                        >
                    </a>
                @endforeach
            </div>
        </section>
    @elseif (! ($nivel['ocultar_galeria'] ?? false))
        <section class="mt-12 bg-white rounded-xl shadow-md p-8">
            <h2 class="text-2xl font-bold {{ $tema['heading'] }} mb-2">Galería en preparación</h2>
            <p class="text-gray-600">
                Pronto agregaremos más imágenes de este nivel educativo.
            </p>
        </section>
    @endif
</section>

@endsection
