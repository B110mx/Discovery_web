@extends('layouts.app')

@section('content')

@php
    $horariosAcademias = [
        [
            'dias' => 'Lunes y miércoles',
            'academias' => [
                'Fútbol - Elementary',
                'Basketball - Elementary',
                'Origami - Kinder, Elementary, Middle, High y papás Explorers',
                'Conversation Club for Explorers Parents',
            ],
        ],
        [
            'dias' => 'Lunes',
            'academias' => ['Pickleball para papás Explorers'],
        ],
        [
            'dias' => 'Martes',
            'academias' => ['Ajedrez - Kinder y Elementary'],
        ],
        [
            'dias' => 'Martes y miércoles',
            'academias' => ['Música para mamás Explorers'],
        ],
        [
            'dias' => 'Martes y jueves',
            'academias' => [
                'Basketball - Middle y High',
                'Urban Kids - Elementary',
                'Urban Dance - Middle y High',
                'Pickleball - Elementary',
                'Zumba Explorers Moms',
                'Flamenco para mamás',
            ],
        ],
    ];

    $academiasVespertinas = [
        [
            'nombre' => 'Fútbol',
            'dirigido' => 'Explorers de Elementary',
            'dias' => 'Lunes y miércoles',
            'beneficios' => 'Coordinación, disciplina, trabajo en equipo, toma de decisiones y perseverancia.',
        ],
        [
            'nombre' => 'Basketball Elementary',
            'dirigido' => 'Explorers de Elementary',
            'dias' => 'Lunes y miércoles',
            'beneficios' => 'Condición física, estrategia, colaboración, concentración y confianza.',
        ],
        [
            'nombre' => 'Basketball Middle & High',
            'dirigido' => 'Explorers de Middle y High',
            'dias' => 'Martes y jueves',
            'beneficios' => 'Liderazgo, comunicación, resistencia, pensamiento estratégico y sentido de equipo.',
        ],
        [
            'nombre' => 'Pickleball',
            'dirigido' => 'Papás Explorers y Explorers de Elementary',
            'dias' => 'Lunes para papás; martes y jueves para Elementary',
            'beneficios' => 'Coordinación, reflejos, agilidad, convivencia y bienestar físico y emocional.',
        ],
        [
            'nombre' => 'Ajedrez',
            'dirigido' => 'Explorers de Kinder y Elementary',
            'dias' => 'Martes',
            'beneficios' => 'Pensamiento lógico, concentración, memoria, paciencia y resolución de problemas.',
        ],
        [
            'nombre' => 'Origami',
            'dirigido' => 'Kinder, Elementary, Middle, High y papás Explorers',
            'dias' => 'Lunes y miércoles',
            'beneficios' => 'Creatividad, precisión, paciencia, motricidad fina y atención al detalle.',
        ],
        [
            'nombre' => 'Urban Kids',
            'dirigido' => 'Explorers de Elementary',
            'dias' => 'Martes y jueves',
            'beneficios' => 'Coordinación, ritmo, seguridad, creatividad y confianza.',
        ],
        [
            'nombre' => 'Urban Dance',
            'dirigido' => 'Explorers de Middle y High',
            'dias' => 'Martes y jueves',
            'beneficios' => 'Expresión personal, disciplina, autoestima, trabajo en equipo y bienestar emocional.',
        ],
        [
            'nombre' => 'Música para mamás Explorers',
            'dirigido' => 'Mamás Explorers',
            'dias' => 'Martes y miércoles',
            'beneficios' => 'Sensibilidad musical, creatividad, relajación y convivencia.',
        ],
        [
            'nombre' => 'Zumba Explorers Moms',
            'dirigido' => 'Mamás Explorers',
            'dias' => 'Martes y jueves',
            'beneficios' => 'Bienestar físico, energía, coordinación, alegría y equilibrio emocional.',
        ],
        [
            'nombre' => 'Flamenco para mamás',
            'dirigido' => 'Mamás Explorers',
            'dias' => 'Martes y jueves',
            'beneficios' => 'Expresión corporal, seguridad personal, disciplina, coordinación y conexión emocional.',
        ],
        [
            'nombre' => 'Conversation Club for Explorers Parents',
            'dirigido' => 'Papás y mamás Explorers',
            'dias' => 'Lunes y miércoles',
            'beneficios' => 'Comunicación en inglés, confianza al expresarse, convivencia e integración familiar.',
        ],
    ];
@endphp

<section class="space-y-10">
    <div class="overflow-hidden rounded-xl bg-blue-700 text-white shadow-lg">
        <div class="grid lg:grid-cols-[.9fr_1.1fr]">
            <div class="p-8 md:p-12 lg:p-14">
                <p class="font-semibold uppercase tracking-wide text-sm text-blue-100">Comunidad Discovery®</p>
                <h1 class="mt-3 text-4xl font-extrabold md:text-5xl">Academias Vespertinas</h1>
                <div class="mt-5 max-w-3xl space-y-4 text-lg leading-8 text-blue-50">
                    <p>
                        Espacios diseñados para que nuestros Explorers y familias continúen desarrollando sus talentos, habilidades y bienestar más allá del horario escolar.
                    </p>
                    <p>
                        A través del deporte, el arte, la música, el pensamiento estratégico y la convivencia, fortalecemos creatividad, disciplina, concentración, comunicación, trabajo en equipo, seguridad personal y bienestar emocional.
                    </p>
                </div>
            </div>

            <div class="bg-white p-6 md:p-8">
                <div class="grid h-full gap-4 sm:grid-cols-2">
                    <div class="rounded-lg bg-blue-50 p-5">
                        <span class="inline-flex h-2 w-12 rounded-full bg-blue-700"></span>
                        <h2 class="mt-4 text-xl font-extrabold text-blue-900">Explorers</h2>
                        <p class="mt-2 leading-7 text-gray-700">Actividades para Kinder, Elementary, Middle y High.</p>
                    </div>
                    <div class="rounded-lg bg-red-50 p-5">
                        <span class="inline-flex h-2 w-12 rounded-full bg-red-600"></span>
                        <h2 class="mt-4 text-xl font-extrabold text-red-800">Familias</h2>
                        <p class="mt-2 leading-7 text-gray-700">Espacios para mamás, papás y comunidad Explorers.</p>
                    </div>
                    <div class="rounded-lg bg-yellow-50 p-5 sm:col-span-2">
                        <span class="inline-flex h-2 w-12 rounded-full bg-yellow-400"></span>
                        <h2 class="mt-4 text-xl font-extrabold text-yellow-900">Desarrollo integral</h2>
                        <p class="mt-2 leading-7 text-gray-700">Movimiento, creación, pensamiento, convivencia y experiencias que impulsan una comunidad más activa y unida.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if (! empty($mediaAcademias['imagenes']) || ! empty($mediaAcademias['videos']))
        <section class="rounded-xl bg-white p-6 shadow-md md:p-8">
            <div class="flex flex-col gap-3 md:flex-row md:items-end md:justify-between">
                <div>
                    <p class="text-sm font-bold uppercase tracking-wide text-blue-700">Galería</p>
                    <h2 class="mt-2 text-3xl font-extrabold text-black">Academias en acción</h2>
                </div>
                <p class="max-w-xl leading-7 text-gray-600">
                    Momentos de nuestras academias vespertinas, donde la comunidad Discovery® se mueve, crea, aprende y convive.
                </p>
            </div>

            @if (! empty($mediaAcademias['imagenes']))
                <div class="mt-6 grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
                    @foreach ($mediaAcademias['imagenes'] as $imagen)
                        <article class="overflow-hidden rounded-lg bg-gray-100 shadow-sm">
                            <a href="{{ $imagen['url'] }}" class="glightbox block" data-title="{{ $imagen['titulo'] }}">
                                <img
                                    src="{{ $imagen['url'] }}"
                                    alt="{{ $imagen['titulo'] }} en Academias Vespertinas Discovery®"
                                    class="h-60 w-full object-cover"
                                    loading="lazy"
                                >
                            </a>
                            <div class="border-t border-gray-100 bg-white p-4">
                                <span class="inline-flex h-1.5 w-10 rounded-full bg-red-600"></span>
                                <h3 class="mt-2 text-lg font-extrabold text-blue-700">{{ $imagen['titulo'] }}</h3>
                            </div>
                        </article>
                    @endforeach
                </div>
            @endif

            @if (! empty($mediaAcademias['videos']))
                <div class="mt-8 grid gap-5 md:grid-cols-2">
                    @foreach ($mediaAcademias['videos'] as $video)
                        <article class="overflow-hidden rounded-lg border border-gray-100 bg-gray-50 shadow-sm">
                            <video
                                src="{{ $video['url'] }}"
                                controls
                                preload="none"
                                playsinline
                                class="aspect-video w-full bg-black object-contain"
                            ></video>
                            <div class="bg-white p-4">
                                <span class="inline-flex h-1.5 w-10 rounded-full bg-blue-700"></span>
                                <h3 class="mt-2 text-lg font-extrabold text-black">{{ $video['titulo'] }}</h3>
                            </div>
                        </article>
                    @endforeach
                </div>
            @endif
        </section>
    @endif

    <section class="grid gap-8 lg:grid-cols-[.75fr_1.25fr]">
        <aside class="rounded-xl bg-blue-700 p-6 text-white shadow-lg md:p-8">
            <p class="font-semibold uppercase tracking-wide text-sm text-blue-100">Horarios</p>
            <h2 class="mt-2 text-3xl font-extrabold">Por día</h2>

            <div class="mt-6 space-y-5">
                @foreach ($horariosAcademias as $horario)
                    <div class="border-t border-white/20 pt-5 first:border-t-0 first:pt-0">
                        <p class="font-bold text-yellow-300">{{ $horario['dias'] }}</p>
                        <ul class="mt-3 space-y-2 text-sm leading-6 text-blue-50">
                            @foreach ($horario['academias'] as $academia)
                                <li>{{ $academia }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endforeach
            </div>
        </aside>

        <div class="rounded-xl bg-white p-6 shadow-md md:p-8">
            <div class="flex flex-col gap-3 md:flex-row md:items-end md:justify-between">
                <div>
                    <p class="text-sm font-bold uppercase tracking-wide text-red-600">Oferta Discovery®</p>
                    <h2 class="mt-2 text-3xl font-extrabold text-black">Academias disponibles</h2>
                </div>
                <span class="inline-flex w-fit rounded-full bg-yellow-100 px-4 py-2 text-sm font-bold text-yellow-800">Para Explorers y familias</span>
            </div>

            <div class="mt-6 grid gap-4 md:grid-cols-2">
                @foreach ($academiasVespertinas as $academia)
                    <article class="rounded-lg border border-gray-100 bg-gray-50 p-5 shadow-sm">
                        <div class="flex flex-col gap-3 sm:flex-row sm:items-start sm:justify-between">
                            <h3 class="text-lg font-extrabold text-blue-700">{{ $academia['nombre'] }}</h3>
                            <span class="w-fit shrink-0 rounded bg-red-50 px-3 py-1 text-xs font-bold text-red-700">{{ $academia['dias'] }}</span>
                        </div>
                        <p class="mt-3 text-sm font-bold text-gray-800">{{ $academia['dirigido'] }}</p>
                        <p class="mt-2 text-sm leading-6 text-gray-600">{{ $academia['beneficios'] }}</p>
                    </article>
                @endforeach
            </div>
        </div>
    </section>

    <section class="rounded-xl border-l-4 border-blue-700 bg-blue-50 p-6 shadow-sm md:p-8">
        <p class="text-lg font-semibold leading-8 text-blue-900">
            Cada academia vespertina Discovery® representa una oportunidad para descubrir talentos, fortalecer habilidades y construir una comunidad más activa, creativa y unida.
        </p>
        <p class="mt-3 leading-8 text-gray-700">
            En Discovery®, creemos que el aprendizaje también sucede cuando nos movemos, creamos, pensamos, convivimos y compartimos experiencias que impulsan el desarrollo integral de nuestros Explorers y sus familias.
        </p>
    </section>
</section>

@endsection
