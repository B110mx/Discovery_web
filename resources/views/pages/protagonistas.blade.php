@extends('layouts.app')

@section('content')

@php
    $imagenesProtagonistas = $comunidad['protagonistas'] ?? [];

    $protagonistas = [
        'alumnos' => [
            'titulo' => 'Explorers',
            'subtitulo' => 'Explorers que aprenden, crean y participan.',
            'texto' => 'Nuestros Explorers son el centro de la vida escolar. En cada proyecto, clase, presentación y experiencia, desarrollan creatividad, pensamiento crítico y mentalidad internacional.',
            'puntos' => ['Proyectos interdisciplinarios', 'Arte, deporte y tecnología', 'Acompañamiento académico y emocional'],
        ],
        'docentes' => [
            'titulo' => 'Docentes',
            'subtitulo' => 'Guías cercanos para cada etapa.',
            'texto' => 'El equipo docente acompaña a cada Explorer con planeación, escucha y metodologías activas que conectan el aprendizaje con la vida real.',
            'puntos' => ['Profesores preparados', 'Seguimiento personalizado', 'Comunidad de aprendizaje'],
        ],
        'padres' => [
            'titulo' => 'Padres de familia',
            'subtitulo' => 'Familias que construyen comunidad.',
            'texto' => 'La participación de madres, padres y tutores fortalece el crecimiento de los Explorers. Trabajamos en equipo para formar una comunidad cercana, informada y comprometida.',
            'puntos' => ['Comunicación constante', 'Actividades para familias', 'Acompañamiento en el proceso educativo'],
        ],
        'alumni' => [
            'titulo' => 'Alumni',
            'subtitulo' => 'Historias que siguen creciendo.',
            'texto' => 'Nuestros egresados llevan el sello Discovery® a nuevas etapas académicas y profesionales, manteniendo vivo el sentido de pertenencia a la comunidad.',
            'puntos' => ['Generaciones Discovery®', 'Testimonios de egresados', 'Vínculo con la comunidad escolar'],
        ],
    ];

    $protagonistas = collect($protagonistas)
        ->map(function ($item, $clave) use ($imagenesProtagonistas) {
            $item['imagen'] = $imagenesProtagonistas[$clave]['imagen'] ?? [
                'url' => null,
                'titulo' => $item['titulo'],
                'referencia' => 'Imagen para ' . $item['titulo'] . ' en Comunidad.',
            ];
            $item['imagenes'] = $imagenesProtagonistas[$clave]['imagenes'] ?? [$item['imagen']];
            $item['color'] = $imagenesProtagonistas[$clave]['color'] ?? 'bg-blue-700';

            return $item;
        })
        ->all();

    $tabColors = [
        'bg-red-600' => [
            'active' => 'border-red-600 bg-red-600 text-white',
            'inactive' => 'border-gray-200 text-gray-700 hover:border-red-600 hover:text-red-700',
        ],
        'bg-blue-700' => [
            'active' => 'border-blue-700 bg-blue-700 text-white',
            'inactive' => 'border-gray-200 text-gray-700 hover:border-blue-700 hover:text-blue-700',
        ],
        'bg-lime-500' => [
            'active' => 'border-lime-500 bg-lime-500 text-black',
            'inactive' => 'border-gray-200 text-gray-700 hover:border-lime-500 hover:text-lime-700',
        ],
        'bg-green-600' => [
            'active' => 'border-green-600 bg-green-600 text-white',
            'inactive' => 'border-gray-200 text-gray-700 hover:border-green-600 hover:text-green-700',
        ],
    ];

@endphp

<section class="space-y-12">
    <div class="overflow-hidden rounded-xl bg-blue-700 text-white shadow-lg">
        <div class="grid lg:grid-cols-[.95fr_1.05fr]">
            <div class="p-8 md:p-12 lg:p-14">
                <p class="font-semibold uppercase tracking-wide text-sm text-blue-100">{{ $paginaProtagonistas?->subtitulo ?? 'Comunidad Discovery®' }}</p>
                <h1 class="mt-3 text-4xl md:text-5xl font-extrabold">{{ $paginaProtagonistas?->titulo ?? 'Protagonistas' }}</h1>
                <p class="mt-5 max-w-2xl text-lg leading-8 text-blue-50">
                    {{ $paginaProtagonistas?->descripcion ?? 'En Discovery® Explorers, padres de familia, docentes y alumni trabajamos en equipo para formar una comunidad de aprendizaje con mentalidad internacional.' }}
                </p>
                <a href="#testimonios" class="mt-8 inline-flex w-fit items-center justify-center rounded bg-red-600 px-6 py-3 font-bold text-white hover:bg-red-700">
                    Ver testimonios
                </a>
            </div>

            <div class="grid grid-cols-2 gap-3 bg-white p-4">
                @foreach ($protagonistas as $protagonista)
                    <article
                        class="relative overflow-hidden rounded-lg bg-gray-100"
                        data-community-hero-card
                        data-community-hero-images='@json($protagonista['imagenes'] ?? [$protagonista['imagen']])'
                    >
                        <a href="{{ $protagonista['imagen']['url'] }}" class="glightbox block" data-title="{{ $protagonista['titulo'] }}" data-community-hero-link>
                            <x-imagen-seccion
                                :imagen="$protagonista['imagen']"
                                alt="{{ $protagonista['titulo'] }} Discovery®"
                                class="h-36 w-full object-contain p-2 md:h-40"
                                placeholder-class="h-36 md:h-40"
                                data-community-hero-photo
                            />
                        </a>
                        <div class="absolute inset-x-0 bottom-0 flex items-center gap-2 bg-white/90 px-3 py-2">
                            <span class="h-2.5 w-8 rounded-full {{ $protagonista['color'] }}"></span>
                            <span class="text-sm font-bold text-black">{{ $protagonista['titulo'] }}</span>
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
                    @php
                        $tabColor = $tabColors[$item['color']] ?? $tabColors['bg-blue-700'];
                    @endphp
                    <button
                        type="button"
                        class="rounded border px-4 py-2 text-sm font-bold transition {{ $loop->first ? $tabColor['active'] : $tabColor['inactive'] }}"
                        data-protagonista-tab="{{ $clave }}"
                        data-active-classes="{{ $tabColor['active'] }}"
                        data-inactive-classes="{{ $tabColor['inactive'] }}"
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
                        data-protagonista-images='@json($item['imagenes'] ?? [$item['imagen']])'
                    >
                        @if (! empty($item['imagen']['url']))
                            <img
                                src="{{ $item['imagen']['url'] }}"
                                alt="{{ $item['titulo'] }} Discovery®"
                                class="h-72 w-full bg-gray-100 object-contain md:h-96"
                                data-protagonista-photo
                            >
                        @else
                            <x-imagen-seccion
                                :imagen="$item['imagen']"
                                alt="{{ $item['titulo'] }} Discovery®"
                                class="h-72 w-full object-cover md:h-96"
                                placeholder-class="h-72 md:h-96"
                            />
                        @endif
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
                <div class="flex h-full flex-col justify-between rounded-xl border border-blue-100 bg-white p-6 shadow-sm md:p-8">
                    <div>
                        <div class="flex items-center gap-3">
                            <span class="inline-flex h-12 w-12 items-center justify-center rounded-full bg-blue-700 text-xl font-extrabold text-white">D</span>
                            <div>
                                <p class="text-sm font-bold uppercase tracking-wide text-blue-700">Mensaje de nuestra fundadora</p>
                                <p class="mt-1 text-sm font-semibold text-gray-500">Una comunidad que deja huella</p>
                            </div>
                        </div>
                        <blockquote class="mt-8 border-l-4 border-red-600 pl-5">
                            <h2 class="text-2xl font-extrabold leading-snug text-black md:text-3xl">Nos define lo que somos como esencia.</h2>
                            <div class="mt-5 space-y-4 text-base leading-8 text-gray-700">
                                <p>
                                    En Discovery® nuestros grupos reducidos aseguran una atención personalizada, fundamental para el desarrollo de las facultades de cada Explorer y para la adquisición de hábitos.
                                </p>
                                <p>
                                    Somos una institución bilingüe con programas de inglés impartidos por profesores internacionales y una comunidad que aprende de forma natural, cercana y sana.
                                </p>
                            </div>
                        </blockquote>
                    </div>

                    <div class="mt-8 grid gap-3 sm:grid-cols-2">
                        <div class="rounded-lg bg-blue-50 p-4">
                            <span class="inline-flex h-1.5 w-10 rounded-full bg-blue-700"></span>
                            <p class="mt-3 text-sm font-extrabold uppercase text-blue-800">Acompañamiento</p>
                            <p class="mt-1 text-sm leading-6 text-gray-600">Cercanía en cada etapa escolar.</p>
                        </div>
                        <div class="rounded-lg bg-red-50 p-4">
                            <span class="inline-flex h-1.5 w-10 rounded-full bg-red-600"></span>
                            <p class="mt-3 text-sm font-extrabold uppercase text-red-700">Alumni</p>
                            <p class="mt-1 text-sm leading-6 text-gray-600">Historias que siguen creciendo.</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="flex flex-col bg-blue-700 p-6 text-white md:p-8">
                <p class="font-semibold uppercase tracking-wide text-sm text-blue-100">Videos testimoniales</p>
                <h2 class="mt-2 text-3xl font-extrabold md:text-4xl">Historias Discovery®</h2>
                <p class="mt-4 max-w-2xl leading-8 text-blue-50">
                    Alumni y familias comparten lo que significa crecer dentro de nuestra comunidad.
                </p>

                @if (! empty($testimonios))
                    <div class="mt-8">
                        <div class="relative mx-auto w-full max-w-[650px]">
                            <button
                                type="button"
                                data-community-video-prev
                                class="hidden absolute -left-12 top-1/2 z-10 h-10 w-10 -translate-y-1/2 rounded-full bg-white font-bold text-blue-700 shadow-lg transition hover:bg-blue-50 md:block"
                                aria-label="Video anterior"
                            >
                                &lt;
                            </button>

                            <div class="overflow-hidden rounded-xl" data-community-video-carousel>
                                <div class="flex transition-transform duration-500 ease-out" data-community-video-track>
                                    @foreach ($testimonios as $video)
                                        <article class="min-w-full flex-shrink-0">
                                            <div class="overflow-hidden rounded-lg border border-white/20 bg-white shadow-lg">
                                                <div class="relative aspect-video w-full bg-white md:h-[350px]">
                                                    <video
                                                        src="{{ $video['url'] }}"
                                                        controls
                                                        preload="none"
                                                        playsinline
                                                        class="block h-full w-full bg-white object-contain"
                                                    ></video>
                                                    <button
                                                        type="button"
                                                        class="absolute inset-0 flex items-center justify-center bg-white p-10 transition-opacity"
                                                        data-community-video-poster
                                                        aria-label="Reproducir {{ $video['titulo'] }}"
                                                    >
                                                        <img
                                                            src="{{ url('/media/Logos%20principales/' . rawurlencode('LOGO DISCOVERY PNG.png')) }}"
                                                            alt=""
                                                            class="max-h-44 max-w-[78%] object-contain"
                                                            loading="lazy"
                                                        >
                                                    </button>
                                                </div>
                                                <div class="p-3">
                                                    <span class="inline-flex h-1.5 w-10 rounded-full bg-red-600"></span>
                                                    <h3 class="mt-2 text-base font-extrabold leading-snug text-black">{{ $video['titulo'] }}</h3>
                                                </div>
                                            </div>
                                        </article>
                                    @endforeach
                                </div>
                            </div>

                            <button
                                type="button"
                                data-community-video-next
                                class="hidden absolute -right-12 top-1/2 z-10 h-10 w-10 -translate-y-1/2 rounded-full bg-red-600 font-bold text-white shadow-lg transition hover:bg-red-700 md:block"
                                aria-label="Video siguiente"
                            >
                                &gt;
                            </button>
                        </div>

                        <div class="mt-4 flex justify-center gap-3 md:hidden">
                            <button
                                type="button"
                                data-community-video-prev
                                class="h-10 w-10 rounded-full bg-white font-bold text-blue-700 shadow-lg transition hover:bg-blue-50"
                                aria-label="Video anterior"
                            >
                                &lt;
                            </button>
                            <button
                                type="button"
                                data-community-video-next
                                class="h-10 w-10 rounded-full bg-red-600 font-bold text-white shadow-lg transition hover:bg-red-700"
                                aria-label="Video siguiente"
                            >
                                &gt;
                            </button>
                        </div>
                    </div>
                @else
                    <div class="mt-8 rounded-lg border border-dashed border-white/40 p-6 text-blue-50">
                        Pronto agregaremos videos testimoniales.
                    </div>
                @endif
            </div>
        </div>
    </section>
</section>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const tabs = Array.from(document.querySelectorAll('[data-protagonista-tab]'));
        const panels = Array.from(document.querySelectorAll('[data-protagonista-panel]'));
        const images = Array.from(document.querySelectorAll('[data-protagonista-image]'));
        const heroCards = Array.from(document.querySelectorAll('[data-community-hero-card]'));

        const imageListFromDataset = (element, datasetKey) => {
            try {
                return JSON.parse(element.dataset[datasetKey] || '[]').filter((image) => image && image.url);
            } catch (error) {
                return [];
            }
        };

        const nextImageFrom = (availableImages, currentSrc) => {
            const nextOptions = availableImages.length > 1
                ? availableImages.filter((image) => image.url !== currentSrc)
                : availableImages;

            return nextOptions[Math.floor(Math.random() * nextOptions.length)];
        };

        const rotateHeroPhoto = (heroCard) => {
            const photo = heroCard.querySelector('[data-community-hero-photo]');
            const link = heroCard.querySelector('[data-community-hero-link]');
            const validImages = imageListFromDataset(heroCard, 'communityHeroImages');

            if (!photo || validImages.length === 0) {
                return;
            }

            const nextImage = nextImageFrom(validImages, photo.getAttribute('src'));

            if (!nextImage?.url) {
                return;
            }

            photo.src = nextImage.url;

            if (link) {
                link.href = nextImage.url;
            }
        };

        const pickRandomPhoto = (imageCard) => {
            const photo = imageCard.querySelector('[data-protagonista-photo]');

            if (!photo) {
                return;
            }

            const validImages = imageListFromDataset(imageCard, 'protagonistaImages');

            if (validImages.length === 0) {
                return;
            }

            const nextImage = nextImageFrom(validImages, photo.getAttribute('src'));

            photo.src = nextImage.url;
        };

        if (heroCards.length > 0) {
            window.setInterval(() => {
                heroCards.forEach(rotateHeroPhoto);
            }, 5000);
        }

        tabs.forEach((tab) => {
            tab.addEventListener('click', () => {
                const target = tab.dataset.protagonistaTab;

                tabs.forEach((currentTab) => {
                    const isCurrentTab = currentTab === tab;
                    const activeClasses = (currentTab.dataset.activeClasses || '').split(' ').filter(Boolean);
                    const inactiveClasses = (currentTab.dataset.inactiveClasses || '').split(' ').filter(Boolean);

                    currentTab.classList.remove(...activeClasses, ...inactiveClasses);
                    currentTab.classList.add(...(isCurrentTab ? activeClasses : inactiveClasses));
                    currentTab.dataset.active = isCurrentTab ? 'true' : 'false';
                });

                images.forEach((image) => {
                    const isActive = image.dataset.protagonistaImage === target;

                    image.classList.toggle('hidden', !isActive);

                    if (isActive) {
                        pickRandomPhoto(image);
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

        const videoCarousel = document.querySelector('[data-community-video-carousel]');
        const videoTrack = document.querySelector('[data-community-video-track]');
        const videoSlides = videoTrack ? Array.from(videoTrack.children) : [];
        const videoDots = Array.from(document.querySelectorAll('[data-community-video-dot]'));
        const videos = Array.from(document.querySelectorAll('[data-community-video-track] video'));
        let videoIndex = 0;

        const setActiveVideoDot = () => {
            videoDots.forEach((dot, dotIndex) => {
                dot.className = dotIndex === videoIndex
                    ? 'h-3 w-8 rounded-full bg-yellow-400'
                    : 'h-3 w-3 rounded-full bg-white/40';
            });
        };

        const getVideosPerView = () => {
            if (!videoCarousel || videoSlides.length === 0 || videoSlides[0].offsetWidth === 0) {
                return 1;
            }

            return Math.max(1, Math.round(videoCarousel.clientWidth / videoSlides[0].offsetWidth));
        };

        const getMaxVideoIndex = () => Math.max(0, videoSlides.length - getVideosPerView());

        const pauseOtherVideos = (currentVideo) => {
            videos.forEach((video) => {
                if (video !== currentVideo && !video.paused) {
                    video.pause();
                }
            });
        };

        const pauseAllVideos = () => {
            videos.forEach((video) => {
                if (!video.paused) {
                    video.pause();
                }
            });
        };

        const showVideo = (index) => {
            if (!videoTrack || videoSlides.length === 0) {
                return;
            }

            videoIndex = Math.max(0, Math.min(index, getMaxVideoIndex()));
            videoTrack.style.transform = `translateX(-${videoIndex * videoCarousel.clientWidth}px)`;
            setActiveVideoDot();
        };

        document.querySelectorAll('[data-community-video-prev]').forEach((button) => button.addEventListener('click', () => {
            pauseAllVideos();
            showVideo(videoIndex - 1);
        }));

        document.querySelectorAll('[data-community-video-next]').forEach((button) => button.addEventListener('click', () => {
            pauseAllVideos();
            showVideo(videoIndex + 1);
        }));

        videoDots.forEach((dot) => {
            dot.addEventListener('click', () => {
                pauseAllVideos();
                showVideo(Number(dot.dataset.communityVideoDot));
            });
        });

        videos.forEach((video) => {
            const poster = video.parentElement?.querySelector('[data-community-video-poster]');

            poster?.addEventListener('click', () => {
                video.play();
            });

            video.addEventListener('play', () => {
                poster?.classList.add('opacity-0', 'pointer-events-none');
                pauseOtherVideos(video);
            });

            video.addEventListener('pause', () => {
                poster?.classList.remove('opacity-0', 'pointer-events-none');
            });

            video.addEventListener('ended', () => {
                poster?.classList.remove('opacity-0', 'pointer-events-none');
            });
        });

        window.addEventListener('resize', () => showVideo(videoIndex));
        showVideo(0);
    });
</script>

@endsection
