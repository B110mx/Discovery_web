@extends('layouts.app')

@section('content')

@php
    $nivelVisual = collect($comunidad['niveles'] ?? []);
    $imagenesProtagonistas = $comunidad['protagonistas'] ?? [];

    $protagonistas = [
        'alumnos' => [
            'titulo' => 'Alumnos',
            'subtitulo' => 'Explorers que aprenden, crean y participan.',
            'texto' => 'Nuestros alumnos son el centro de la vida escolar. En cada proyecto, clase, presentacion y experiencia, desarrollan liderazgo, creatividad, pensamiento critico y mentalidad internacional.',
            'puntos' => ['Proyectos interdisciplinarios', 'Arte, deporte y tecnologia', 'Acompanamiento academico y emocional'],
        ],
        'docentes' => [
            'titulo' => 'Docentes',
            'subtitulo' => 'Guias cercanos para cada etapa.',
            'texto' => 'El equipo docente acompana a cada estudiante con planeacion, escucha y metodologias activas que conectan el aprendizaje con la vida real.',
            'puntos' => ['Profesores preparados', 'Seguimiento personalizado', 'Comunidad de aprendizaje'],
        ],
        'padres' => [
            'titulo' => 'Padres de familia',
            'subtitulo' => 'Familias que construyen comunidad.',
            'texto' => 'La participacion de madres, padres y tutores fortalece el crecimiento de los alumnos. Trabajamos en equipo para formar una comunidad cercana, informada y comprometida.',
            'puntos' => ['Comunicacion constante', 'Actividades para familias', 'Acompanamiento en el proceso educativo'],
        ],
        'alumni' => [
            'titulo' => 'Alumni',
            'subtitulo' => 'Historias que siguen creciendo.',
            'texto' => 'Nuestros egresados llevan el sello Discovery a nuevas etapas academicas y profesionales, manteniendo vivo el sentido de pertenencia a la comunidad.',
            'puntos' => ['Generaciones Discovery', 'Testimonios de egresados', 'Vinculo con la comunidad escolar'],
        ],
    ];

    $protagonistas = collect($protagonistas)
        ->map(function ($item, $clave) use ($imagenesProtagonistas) {
            $item['imagen'] = $imagenesProtagonistas[$clave]['imagen'] ?? [
                'url' => null,
                'titulo' => $item['titulo'],
                'referencia' => 'Imagen para ' . $item['titulo'] . ' en Comunidad.',
            ];
            $item['color'] = $imagenesProtagonistas[$clave]['color'] ?? 'bg-blue-700';

            return $item;
        })
        ->all();
@endphp

<section class="space-y-12">
    <div class="overflow-hidden rounded-xl bg-blue-700 text-white shadow-lg">
        <div class="grid lg:grid-cols-[.95fr_1.05fr]">
            <div class="p-8 md:p-12 lg:p-14">
                <p class="font-semibold uppercase tracking-wide text-sm text-blue-100">Comunidad Discovery</p>
                <h1 class="mt-3 text-4xl md:text-5xl font-extrabold">Protagonistas</h1>
                <p class="mt-5 max-w-2xl text-lg leading-8 text-blue-50">
                    En Discovery alumnos, padres de familia, docentes y alumni trabajamos en equipo para formar una comunidad de aprendizaje con mentalidad internacional.
                </p>
                <a href="#testimonios" class="mt-8 inline-flex w-fit items-center justify-center rounded bg-red-600 px-6 py-3 font-bold text-white hover:bg-red-700">
                    Ver testimonios
                </a>
            </div>

            <div class="grid grid-cols-2 gap-3 bg-white p-4">
                @foreach ($nivelVisual as $nivel)
                    <article class="relative overflow-hidden rounded-lg bg-gray-100">
                        <x-imagen-seccion
                            :imagen="$nivel['imagen']"
                            alt="{{ $nivel['titulo'] }} Discovery"
                            class="h-36 w-full object-contain p-2 md:h-40"
                            placeholder-class="h-36 md:h-40"
                        />
                        <div class="absolute inset-x-0 bottom-0 flex items-center gap-2 bg-white/90 px-3 py-2">
                            <span class="h-2.5 w-8 rounded-full {{ $nivel['color'] }}"></span>
                            <span class="text-sm font-bold text-black">{{ $nivel['titulo'] }}</span>
                        </div>
                    </article>
                @endforeach
            </div>
        </div>
    </div>

    <section class="rounded-xl bg-white p-6 shadow-md md:p-8" data-protagonistas>
        <div class="flex flex-col gap-5 lg:flex-row lg:items-end lg:justify-between">
            <div>
                <p class="font-semibold uppercase tracking-wide text-sm text-blue-700">Protagonistas</p>
                <h2 class="mt-2 text-3xl font-bold text-black">Quienes hacen viva nuestra comunidad</h2>
            </div>

            <div class="grid grid-cols-2 gap-2 md:flex" role="tablist" aria-label="Grupos de protagonistas">
                @foreach ($protagonistas as $clave => $item)
                    <button
                        type="button"
                        class="rounded border border-gray-200 px-4 py-2 text-sm font-bold text-gray-700 transition hover:border-blue-600 hover:text-blue-700 data-[active=true]:border-blue-700 data-[active=true]:bg-blue-700 data-[active=true]:text-white"
                        data-protagonista-tab="{{ $clave }}"
                        data-active="{{ $loop->first ? 'true' : 'false' }}"
                    >
                        {{ $item['titulo'] }}
                    </button>
                @endforeach
            </div>
        </div>

        <div class="mt-8 grid gap-8 lg:grid-cols-[.85fr_1.15fr]">
            <div class="rounded-xl bg-gray-100 p-4 shadow-md">
                @foreach ($protagonistas as $clave => $item)
                    <article
                        class="{{ $loop->first ? '' : 'hidden' }} overflow-hidden rounded-lg bg-white shadow-sm transition duration-500 ease-out"
                        data-protagonista-image="{{ $clave }}"
                    >
                        <x-imagen-seccion
                            :imagen="$item['imagen']"
                            alt="{{ $item['titulo'] }} Discovery"
                            class="h-72 w-full object-cover md:h-96"
                            placeholder-class="h-72 md:h-96"
                        />
                        <div class="border-t border-gray-100 p-4">
                            <span class="inline-flex h-2 w-12 rounded-full {{ $item['color'] }}"></span>
                            <h3 class="mt-3 text-xl font-extrabold text-black">{{ $item['titulo'] }}</h3>
                            <p class="mt-2 leading-7 text-gray-700">{{ $item['subtitulo'] }}</p>
                        </div>
                    </article>
                @endforeach
            </div>

            @foreach ($protagonistas as $clave => $item)
                <article class="transition duration-500 ease-out {{ $loop->first ? 'opacity-100 translate-y-0' : 'hidden opacity-0 translate-y-3' }}" data-protagonista-panel="{{ $clave }}">
                    <p class="text-sm font-bold uppercase tracking-wide text-red-600">{{ $item['subtitulo'] }}</p>
                    <h3 class="mt-2 text-3xl font-extrabold text-blue-700">{{ $item['titulo'] }}</h3>
                    <p class="mt-4 text-lg leading-8 text-gray-700">{{ $item['texto'] }}</p>

                    <div class="mt-6 grid gap-3 sm:grid-cols-3">
                        @foreach ($item['puntos'] as $punto)
                            <div class="rounded-lg border border-blue-100 bg-blue-50 p-4 text-sm font-semibold text-blue-800">
                                {{ $punto }}
                            </div>
                        @endforeach
                    </div>
                </article>
            @endforeach
        </div>
    </section>

    <section id="testimonios" class="overflow-hidden rounded-xl bg-white shadow-lg">
        <div class="grid lg:grid-cols-[.9fr_1.1fr]">
            <div class="bg-gray-50 p-6 md:p-8">
                <div class="rounded-xl bg-white p-6 shadow-sm md:p-8">
                    <span class="inline-flex h-1.5 w-16 rounded-full bg-red-600"></span>
                    <p class="mt-5 font-semibold uppercase tracking-wide text-sm text-blue-700">Mensaje de nuestra fundadora</p>
                    <h2 class="mt-2 text-3xl font-extrabold text-black">Nos define lo que somos como esencia</h2>
                    <div class="mt-6 space-y-4 border-l-4 border-blue-700 pl-5">
                        <p class="leading-8 text-gray-700">
                            En Discovery nuestros grupos reducidos aseguran una atencion personalizada, fundamental para el desarrollo de las facultades de cada alumno y para la adquisicion de habitos.
                        </p>
                        <p class="leading-8 text-gray-700">
                            Somos una institucion bilingue con programas de ingles impartidos por profesores internacionales y una comunidad que aprende de forma natural, cercana y sana.
                        </p>
                    </div>
                </div>
            </div>

            <div class="bg-blue-700 p-6 text-white md:p-8">
                <p class="font-semibold uppercase tracking-wide text-sm text-blue-100">Videos testimoniales</p>
                <h2 class="mt-2 text-3xl font-extrabold md:text-4xl">Historias Discovery</h2>
                <p class="mt-4 max-w-2xl leading-8 text-blue-50">
                    Alumni y familias comparten lo que significa crecer dentro de nuestra comunidad.
                </p>

                <div class="mt-7 grid grid-cols-2 gap-3">
                    <div class="rounded-lg bg-white/90 p-4 text-black">
                        <span class="text-3xl font-extrabold">{{ count($testimonios) }}</span>
                        <p class="mt-1 text-xs font-bold uppercase text-blue-700">Videos disponibles</p>
                    </div>
                    <div class="rounded-lg bg-yellow-500 p-4 text-black">
                        <span class="text-3xl font-extrabold">Alumni</span>
                        <p class="mt-1 text-xs font-bold uppercase">Voces de comunidad</p>
                    </div>
                </div>
            </div>
        </div>

        @if (! empty($testimonios))
            <div class="grid gap-5 p-6 md:grid-cols-2 md:p-8 xl:grid-cols-3">
                @foreach ($testimonios as $video)
                    <article class="overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm transition hover:-translate-y-1 hover:shadow-lg">
                        <div class="bg-black p-2">
                            <video
                                src="{{ $video['url'] }}"
                                controls
                                preload="metadata"
                                playsinline
                                class="w-full aspect-video rounded-lg bg-black"
                            ></video>
                        </div>
                        <div class="p-5">
                            <span class="inline-flex h-1.5 w-10 rounded-full bg-red-600"></span>
                            <h3 class="mt-3 text-lg font-extrabold leading-snug text-black">{{ $video['titulo'] }}</h3>
                        </div>
                    </article>
                @endforeach
            </div>
        @else
            <div class="p-6 md:p-8">
                <div class="rounded-lg border border-dashed border-gray-300 p-6 text-gray-600">
                    Pronto agregaremos videos testimoniales.
                </div>
            </div>
        @endif
    </section>
</section>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const tabs = Array.from(document.querySelectorAll('[data-protagonista-tab]'));
        const panels = Array.from(document.querySelectorAll('[data-protagonista-panel]'));
        const images = Array.from(document.querySelectorAll('[data-protagonista-image]'));

        tabs.forEach((tab) => {
            tab.addEventListener('click', () => {
                const target = tab.dataset.protagonistaTab;

                tabs.forEach((currentTab) => {
                    currentTab.dataset.active = currentTab === tab ? 'true' : 'false';
                });

                images.forEach((image) => {
                    image.classList.toggle('hidden', image.dataset.protagonistaImage !== target);
                });

                panels.forEach((panel) => {
                    const isActive = panel.dataset.protagonistaPanel === target;

                    if (isActive) {
                        panel.classList.remove('hidden');
                        requestAnimationFrame(() => {
                            panel.classList.remove('opacity-0', 'translate-y-3');
                            panel.classList.add('opacity-100', 'translate-y-0');
                        });
                    } else {
                        panel.classList.remove('opacity-100', 'translate-y-0');
                        panel.classList.add('opacity-0', 'translate-y-3');
                        window.setTimeout(() => {
                            if (panel.dataset.protagonistaPanel !== target) {
                                panel.classList.add('hidden');
                            }
                        }, 250);
                    }
                });
            });
        });
    });
</script>

@endsection
