@extends('layouts.app')

@section('content')

@php
    $nivelVisual = collect($comunidad['niveles'] ?? []);

    $protagonistas = [
        'alumnos' => [
            'titulo' => 'Alumnos',
            'subtitulo' => 'Explorers que aprenden, crean y participan.',
            'texto' => 'Nuestros alumnos son el centro de la vida escolar. En cada proyecto, clase, presentacion y experiencia, desarrollan liderazgo, creatividad, pensamiento critico y mentalidad internacional.',
            'puntos' => ['Proyectos interdisciplinarios', 'Arte, deporte y tecnologia', 'Acompanamiento academico y emocional'],
            'nivel_activo' => 'Primaria',
        ],
        'docentes' => [
            'titulo' => 'Docentes',
            'subtitulo' => 'Guias cercanos para cada etapa.',
            'texto' => 'El equipo docente acompana a cada estudiante con planeacion, escucha y metodologias activas que conectan el aprendizaje con la vida real.',
            'puntos' => ['Profesores preparados', 'Seguimiento personalizado', 'Comunidad de aprendizaje'],
            'nivel_activo' => 'Secundaria',
        ],
        'padres' => [
            'titulo' => 'Padres de familia',
            'subtitulo' => 'Familias que construyen comunidad.',
            'texto' => 'La participacion de madres, padres y tutores fortalece el crecimiento de los alumnos. Trabajamos en equipo para formar una comunidad cercana, informada y comprometida.',
            'puntos' => ['Comunicacion constante', 'Actividades para familias', 'Acompanamiento en el proceso educativo'],
            'nivel_activo' => 'Preescolar',
        ],
        'alumni' => [
            'titulo' => 'Alumni',
            'subtitulo' => 'Historias que siguen creciendo.',
            'texto' => 'Nuestros egresados llevan el sello Discovery a nuevas etapas academicas y profesionales, manteniendo vivo el sentido de pertenencia a la comunidad.',
            'puntos' => ['Generaciones Discovery', 'Testimonios de egresados', 'Vinculo con la comunidad escolar'],
            'nivel_activo' => 'Bachillerato',
        ],
    ];
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
                <div class="grid grid-cols-2 gap-3">
                    @foreach ($nivelVisual as $nivel)
                        @php
                            $esInicial = $nivel['titulo'] === $protagonistas['alumnos']['nivel_activo'];
                        @endphp

                        <article
                            class="relative overflow-hidden rounded-lg bg-white shadow-sm transition duration-500 ease-out {{ $esInicial ? 'ring-4 ring-red-500 scale-[1.02]' : 'opacity-75' }}"
                            data-nivel-card="{{ $nivel['titulo'] }}"
                        >
                            <x-imagen-seccion
                                :imagen="$nivel['imagen']"
                                alt="{{ $nivel['titulo'] }} Discovery"
                                class="h-32 w-full object-contain p-2 md:h-36"
                                placeholder-class="h-32 md:h-36"
                            />
                            <div class="border-t border-gray-100 px-3 py-2">
                                <span class="inline-flex h-2 w-8 rounded-full {{ $nivel['color'] }}"></span>
                                <h3 class="mt-1 text-sm font-bold text-black">{{ $nivel['titulo'] }}</h3>
                            </div>
                        </article>
                    @endforeach
                </div>

                <div class="mt-4 rounded-lg bg-white p-4">
                    <p class="text-sm font-bold uppercase tracking-wide text-blue-700">Niveles educativos</p>
                    <p class="mt-1 text-gray-700">La comunidad Discovery crece en todas sus etapas: Preescolar, Primaria, Secundaria y Bachillerato.</p>
                </div>
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

    <section class="grid gap-8 lg:grid-cols-[.8fr_1.2fr]">
        <div class="rounded-xl bg-white p-6 shadow-md md:p-8">
            <p class="font-semibold uppercase tracking-wide text-sm text-blue-700">Mensaje de nuestra fundadora</p>
            <h2 class="mt-2 text-3xl font-bold text-black">Nos define lo que somos como esencia</h2>
            <p class="mt-5 leading-8 text-gray-700">
                En Discovery nuestros grupos reducidos aseguran una atencion personalizada, fundamental para el desarrollo de las facultades de cada alumno y para la adquisicion de habitos.
            </p>
            <p class="mt-4 leading-8 text-gray-700">
                Somos una institucion bilingue con programas de ingles impartidos por profesores internacionales y una comunidad que aprende de forma natural, cercana y sana.
            </p>
        </div>

        <div id="testimonios" class="rounded-xl bg-blue-700 p-6 text-white shadow-md md:p-8">
            <p class="font-semibold uppercase tracking-wide text-sm text-blue-100">Videos testimoniales</p>
            <h2 class="mt-2 text-3xl font-bold">Historias Discovery</h2>
            <p class="mt-4 text-blue-50">Alumni y familias comparten lo que significa crecer dentro de nuestra comunidad.</p>
        </div>
    </section>

    @if (! empty($testimonios))
        <section class="grid gap-6 md:grid-cols-2 xl:grid-cols-3">
            @foreach ($testimonios as $video)
                <article class="overflow-hidden rounded-xl bg-white shadow-md">
                    <video
                        src="{{ $video['url'] }}"
                        controls
                        preload="metadata"
                        playsinline
                        class="w-full aspect-video bg-black"
                    ></video>
                    <div class="p-5">
                        <h3 class="text-xl font-bold text-black">{{ $video['titulo'] }}</h3>
                    </div>
                </article>
            @endforeach
        </section>
    @endif
</section>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const tabs = Array.from(document.querySelectorAll('[data-protagonista-tab]'));
        const panels = Array.from(document.querySelectorAll('[data-protagonista-panel]'));
        const levelCards = Array.from(document.querySelectorAll('[data-nivel-card]'));
        const activeLevels = @json(collect($protagonistas)->mapWithKeys(fn ($item, $clave) => [$clave => $item['nivel_activo']])->all());
        const ringClasses = ['ring-red-500', 'ring-blue-600', 'ring-lime-500', 'ring-green-600'];

        tabs.forEach((tab) => {
            tab.addEventListener('click', () => {
                const target = tab.dataset.protagonistaTab;
                const activeLevel = activeLevels[target];

                tabs.forEach((currentTab) => {
                    currentTab.dataset.active = currentTab === tab ? 'true' : 'false';
                });

                levelCards.forEach((card) => {
                    const isActive = card.dataset.nivelCard === activeLevel;
                    card.classList.toggle('opacity-75', !isActive);
                    card.classList.toggle('scale-[1.02]', isActive);
                    card.classList.toggle('ring-4', isActive);
                    card.classList.remove(...ringClasses);

                    if (isActive) {
                        card.classList.add({
                            alumnos: 'ring-red-500',
                            docentes: 'ring-blue-600',
                            padres: 'ring-lime-500',
                            alumni: 'ring-green-600',
                        }[target]);
                    }
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
