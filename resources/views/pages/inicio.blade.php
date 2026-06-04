@extends('layouts.app')

@section('content')

<section class="relative overflow-hidden rounded-xl bg-white shadow-lg" data-home-hero-carousel>
    <div class="overflow-hidden">
        <div class="flex transition-transform duration-700 ease-out" data-home-hero-track>
            @foreach ($bannerInicioSlides ?? [$bannerInicio] as $banner)
                <a href="{{ route('nosotros') }}" class="block min-w-full" aria-label="Conocer mas sobre Colegio Discovery®">
                    <x-imagen-seccion
                        :imagen="$banner"
                        alt="{{ $banner['titulo'] ?? ($paginaInicio?->titulo ?? 'Colegio Internacional Discovery®') }}"
                        class="aspect-[1916/657] w-full bg-white object-contain"
                        placeholder-class="aspect-[1916/657] w-full"
                        loading="{{ $loop->first ? 'eager' : 'lazy' }}"
                        fetchpriority="{{ $loop->first ? 'high' : 'auto' }}"
                    />
                </a>
            @endforeach
        </div>
    </div>

    <a
        href="{{ route('nosotros') }}"
        class="absolute right-4 top-4 inline-flex items-center justify-center rounded bg-white px-5 py-2.5 text-sm font-extrabold text-blue-700 shadow-md transition hover:bg-blue-50 md:right-6 md:top-6 md:text-base"
    >
        Saber más
    </a>
    @if (count($bannerInicioSlides ?? []) > 1)
        <div class="absolute inset-x-0 bottom-4 flex justify-center gap-2" data-home-hero-dots>
            @foreach ($bannerInicioSlides as $banner)
                <button
                    type="button"
                    class="h-3 w-3 rounded-full bg-white/70 shadow"
                    aria-label="Ir al banner {{ $loop->iteration }}"
                    data-home-hero-dot="{{ $loop->index }}"
                ></button>
            @endforeach
        </div>
    @endif
</section>


@if (! empty($eventos))
    <section class="mt-16 overflow-hidden rounded-xl bg-white shadow-lg animate-on-scroll">
        <div class="grid lg:grid-cols-[.85fr_1.15fr]">
            <div class="bg-blue-700 p-8 text-white md:p-10">
                <p class="font-semibold uppercase tracking-wide text-sm text-blue-100">Agenda Discovery®</p>
                <h2 class="mt-3 text-4xl font-extrabold">Próximos eventos</h2>
                <p class="mt-5 leading-8 text-blue-50">
                    Consulta las actividades que vienen para nuestra comunidad escolar. Este carrusel muestra las fotos o carteles de los eventos activos.
                </p>

                <div class="mt-8 flex items-center gap-3">
                    <button
                        type="button"
                        data-event-prev
                        class="h-11 w-11 rounded-full bg-white text-2xl font-bold text-blue-700 shadow hover:bg-blue-50"
                        aria-label="Evento anterior"
                    >
                        &lt;
                    </button>
                    <button
                        type="button"
                        data-event-next
                        class="h-11 w-11 rounded-full bg-red-600 text-2xl font-bold text-white shadow hover:bg-red-700"
                        aria-label="Evento siguiente"
                    >
                        &gt;
                    </button>
                </div>

                <div class="mt-6 flex gap-2" data-event-dots>
                    @foreach ($eventos as $evento)
                        <button
                            type="button"
                            class="h-3 w-3 rounded-full bg-white/60"
                            aria-label="Ir al evento {{ $loop->iteration }}"
                            data-event-dot="{{ $loop->index }}"
                        ></button>
                    @endforeach
                </div>

                <a href="{{ route('recursos-escolares') }}" class="mt-8 inline-flex font-extrabold text-white underline decoration-red-400 decoration-4 underline-offset-4 hover:text-blue-100">
                    Ver recursos escolares
                </a>
            </div>

            <div class="relative" data-event-carousel>
                <div class="overflow-hidden">
                    <div class="flex transition-transform duration-500 ease-out" data-event-track>
                        @foreach ($eventos as $evento)
                            <article class="min-w-full">
                                <div class="grid md:grid-cols-[1fr_.55fr]">
                                    <div class="bg-gray-100">
                                        <x-imagen-seccion
                                            :imagen="$evento['imagen'] ?? ['url' => $evento['url'] ?? null, 'titulo' => $evento['titulo'], 'referencia' => 'Modulo del carrusel de eventos.']"
                                            :alt="$evento['titulo']"
                                            class="h-72 w-full object-contain p-3 md:h-[430px]"
                                            placeholder-class="h-72 md:h-[430px]"
                                        />
                                    </div>

                                    <div class="flex flex-col justify-center p-6 md:p-8">
                                        <p class="text-sm font-bold uppercase tracking-wide text-red-600">Evento próximo</p>
                                        <h3 class="mt-3 text-3xl font-extrabold text-blue-700">{{ $evento['titulo'] }}</h3>
                                        <p class="mt-4 leading-8 text-gray-600">{{ $evento['descripcion'] }}</p>
                                    </div>
                                </div>
                            </article>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </section>
@endif

<section class="mt-16 bg-white px-6 py-10 rounded-xl shadow-md">
    <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-10">
        <article class="flex flex-col animate-on-scroll" style="transition-delay: 100ms;">
            <img
                src="{{ $logosNiveles['preescolar'] ?? '' }}"
                alt="Kinder Discovery®"
                class="mx-auto mb-5 h-24 w-auto object-contain"
                loading="lazy"
            >
            <h2 class="text-xl font-extrabold text-center uppercase text-lime-400 mb-10">Kinder</h2>
            <p class="text-gray-600 leading-7 flex-1">
                <span class="text-sky-500">El mejor Kinder de Tehuacán.</span>
                Nuestro modelo educativo se enfoca en el desarrollo integral del niño atendiendo sus necesidades
                intelectuales, emocionales, físicas, sociales y culturales, que permitirá que logren enfrentarse
                a nuevos retos de aprendizaje con seguridad y confianza en
                <span class="text-sky-500">El mejor colegio de Tehuacán</span>.
            </p>
            <a href="{{ route('nivel', 'preescolar') }}" class="mt-10 inline-flex w-fit items-center justify-center rounded bg-lime-400 px-6 py-3 font-bold text-white hover:bg-lime-500">
                Descubre más ->
            </a>
        </article>

        <article class="flex flex-col animate-on-scroll" style="transition-delay: 200ms;">
            <img
                src="{{ $logosNiveles['primaria'] ?? '' }}"
                alt="Elementary Discovery®"
                class="mx-auto mb-5 h-24 w-auto object-contain"
                loading="lazy"
            >
            <h2 class="text-xl font-extrabold text-center uppercase text-red-600 mb-10">Elementary</h2>
            <p class="text-gray-600 leading-7 flex-1">
                <span class="text-sky-500">El mejor Elementary de Tehuacán.</span>
                En Discovery® tenemos como misión brindar un modelo educativo de vanguardia, bilingüe, holístico
                y personalizado. Misión que comulga con el perfil de aprendizaje de los colegios del mundo en la
                mejor escuela de Tehuacán.
            </p>
            <a href="{{ route('nivel', 'primaria') }}" class="mt-10 inline-flex w-fit items-center justify-center rounded bg-red-600 px-6 py-3 font-bold text-white hover:bg-red-700">
                Descubre más ->
            </a>
        </article>

        <article class="flex flex-col animate-on-scroll" style="transition-delay: 300ms;">
            <img
                src="{{ $logosNiveles['secundaria'] ?? '' }}"
                alt="Middle Discovery®"
                class="mx-auto mb-5 h-24 w-auto object-contain"
                loading="lazy"
            >
            <h2 class="text-xl font-extrabold text-center uppercase text-blue-700 mb-10">Middle</h2>
            <p class="text-gray-600 leading-7 flex-1">
                <span class="text-sky-500">El mejor Middle de Tehuacán.</span>
                En Discovery® fomentamos el desarrollo del pensamiento crítico, creativo y reflexivo a través del
                aprendizaje por proyectos interdisciplinarios en nuestras diferentes asignaturas, propiciando el
                trabajo colaborativo y cooperativo.
            </p>
            <a href="{{ route('nivel', 'secundaria') }}" class="mt-10 inline-flex w-fit items-center justify-center rounded bg-blue-700 px-6 py-3 font-bold text-white hover:bg-blue-800">
                Descubre más ->
            </a>
        </article>

        <article class="flex flex-col animate-on-scroll" style="transition-delay: 400ms;">
            <img
                src="{{ $logosNiveles['bachillerato'] ?? '' }}"
                alt="High Discovery®"
                class="mx-auto mb-5 h-24 w-auto object-contain"
                loading="lazy"
            >
            <h2 class="text-xl font-extrabold text-center uppercase text-green-500 mb-10">High</h2>
            <p class="text-gray-600 leading-7 flex-1">
                <span class="text-sky-500">El mejor High de Tehuacán.</span>
                Somos una Institución Educativa en Nivel Medio Superior de calidad y a la vanguardia para establecer
                nuevas oportunidades de aprendizaje holístico en nuestros Explorers en
                <span class="text-sky-500">El mejor High de Tehuacán</span>.
            </p>
            <a href="{{ route('nivel', 'bachillerato') }}" class="mt-10 inline-flex w-fit items-center justify-center rounded bg-green-500 px-6 py-3 font-bold text-white hover:bg-green-600">
                Descubre más ->
            </a>
        </article>
    </div>
</section>

<div class="mt-20 grid md:grid-cols-2 gap-10 items-center animate-on-scroll">
    <div>
        <h2 class="text-3xl font-bold text-blue-600 mb-4">
            Sobre Nosotros
        </h2>

        <p class="text-gray-700 mb-4">
            En el Colegio Internacional Discovery® nos enfocamos en brindar
            una educación integral que combine valores, tecnología e innovación.
        </p>

        <p class="text-gray-700">
            Nuestro objetivo es formar Explorers preparados para enfrentar
            los retos del futuro con confianza.
        </p>
    </div>

    <div>
        <x-imagen-seccion
            :imagen="$imagenesInicio['sobre_nosotros']"
            alt="Escuela"
            class="h-80 w-full rounded-xl object-cover shadow-lg"
            placeholder-class="h-80"
        />
    </div>
</div>

@if (! empty($testimonios))
    <section class="mt-20 bg-white rounded-xl shadow-md p-6 md:p-8 animate-on-scroll">
        <div class="flex flex-col md:flex-row md:items-end md:justify-between gap-4 mb-6">
            <div>
                <p class="font-semibold uppercase tracking-wide text-sm text-blue-700">Testimonios Alumni</p>
                <h2 class="text-3xl font-bold text-black mt-2">Historias de nuestra comunidad</h2>
            </div>

            <div class="flex gap-3">
                <button
                    type="button"
                    data-video-prev
                    class="h-11 w-11 rounded-full bg-gray-100 text-blue-700 font-bold hover:bg-blue-100"
                    aria-label="Video anterior"
                >
                    &lt;
                </button>
                <button
                    type="button"
                    data-video-next
                    class="h-11 w-11 rounded-full bg-blue-700 text-white font-bold hover:bg-blue-800"
                    aria-label="Video siguiente"
                >
                    &gt;
                </button>
            </div>
        </div>

        <div class="overflow-hidden" data-video-carousel>
            <div class="flex transition-transform duration-500 ease-out" data-video-track>
                @foreach ($testimonios as $video)
                    <article class="flex-shrink-0 w-full sm:w-1/2 lg:w-1/3 px-1 md:px-3">
                        <div class="relative aspect-video overflow-hidden rounded-lg bg-white shadow-sm">
                            <video
                                src="{{ $video['url'] }}"
                                controls
                                preload="none"
                                playsinline
                                class="block h-full w-full bg-white object-contain"
                            ></video>
                            <button
                                type="button"
                                class="absolute inset-0 flex items-center justify-center bg-white p-8 transition-opacity"
                                data-home-video-poster
                                aria-label="Reproducir {{ $video['titulo'] }}"
                            >
                                <img
                                    src="{{ url('/media/Logos%20principales/' . rawurlencode('LOGO DISCOVERY PNG.png')) }}"
                                    alt=""
                                    class="max-h-32 max-w-[78%] object-contain"
                                    loading="lazy"
                                >
                            </button>
                        </div>
                        <h3 class="text-xl font-bold text-black mt-4">{{ $video['titulo'] }}</h3>
                    </article>
                @endforeach
            </div>
        </div>

        <div class="flex justify-center gap-2 mt-6" data-video-dots>
            @foreach ($testimonios as $video)
                <button
                    type="button"
                    class="h-3 w-3 rounded-full bg-gray-300"
                    aria-label="Ir al video {{ $loop->iteration }}"
                    data-video-dot="{{ $loop->index }}"
                ></button>
            @endforeach
        </div>
    </section>
@endif

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const activateDots = (dots, index, activeClasses, inactiveClasses) => {
            dots.forEach((dot, dotIndex) => {
                dot.className = dotIndex === index ? activeClasses : inactiveClasses;
            });
        };

        const heroTrack = document.querySelector('[data-home-hero-track]');
        const heroSlides = heroTrack ? Array.from(heroTrack.children) : [];
        const heroDots = Array.from(document.querySelectorAll('[data-home-hero-dot]'));
        let heroIndex = 0;
        let heroTimer = null;

        const showHero = (index) => {
            if (!heroTrack || heroSlides.length === 0) {
                return;
            }

            heroIndex = (index + heroSlides.length) % heroSlides.length;
            heroTrack.style.transform = `translateX(-${heroIndex * 100}%)`;
            activateDots(heroDots, heroIndex, 'h-3 w-8 rounded-full bg-red-600 shadow', 'h-3 w-3 rounded-full bg-white/70 shadow');
        };

        const restartHeroTimer = () => {
            if (heroTimer) {
                clearInterval(heroTimer);
            }

            if (heroSlides.length > 1) {
                heroTimer = setInterval(() => showHero(heroIndex + 1), 5000);
            }
        };

        heroDots.forEach((dot) => {
            dot.addEventListener('click', () => {
                showHero(Number(dot.dataset.homeHeroDot));
                restartHeroTimer();
            });
        });

        showHero(0);
        restartHeroTimer();

        const eventTrack = document.querySelector('[data-event-track]');
        const eventSlides = eventTrack ? Array.from(eventTrack.children) : [];
        const eventDots = Array.from(document.querySelectorAll('[data-event-dot]'));
        let eventIndex = 0;
        let eventTimer = null;

        const showEvent = (index) => {
            if (!eventTrack || eventSlides.length === 0) {
                return;
            }

            eventIndex = (index + eventSlides.length) % eventSlides.length;
            eventTrack.style.transform = `translateX(-${eventIndex * 100}%)`;
            activateDots(eventDots, eventIndex, 'h-3 w-8 rounded-full bg-red-500', 'h-3 w-3 rounded-full bg-white/60');
        };

        const restartEventTimer = () => {
            if (eventTimer) {
                clearInterval(eventTimer);
            }

            if (eventSlides.length > 1) {
                eventTimer = setInterval(() => showEvent(eventIndex + 1), 6000);
            }
        };

        document.querySelector('[data-event-prev]')?.addEventListener('click', () => {
            showEvent(eventIndex - 1);
            restartEventTimer();
        });

        document.querySelector('[data-event-next]')?.addEventListener('click', () => {
            showEvent(eventIndex + 1);
            restartEventTimer();
        });

        eventDots.forEach((dot) => {
            dot.addEventListener('click', () => {
                showEvent(Number(dot.dataset.eventDot));
                restartEventTimer();
            });
        });

        showEvent(0);
        restartEventTimer();

        const videoCarousel = document.querySelector('[data-video-carousel]');
        const videoTrack = document.querySelector('[data-video-track]');
        const videoSlides = videoTrack ? Array.from(videoTrack.children) : [];
        const videoDots = Array.from(document.querySelectorAll('[data-video-dot]'));
        const videos = Array.from(document.querySelectorAll('[data-video-track] video'));
        let videoIndex = 0;

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
            videoTrack.style.transform = `translateX(-${videoIndex === 0 ? 0 : videoSlides[videoIndex].offsetLeft}px)`;
            activateDots(videoDots, videoIndex, 'h-3 w-8 rounded-full bg-blue-700', 'h-3 w-3 rounded-full bg-gray-300');
        };

        document.querySelector('[data-video-prev]')?.addEventListener('click', () => {
            pauseAllVideos();
            showVideo(videoIndex - 1);
        });
        document.querySelector('[data-video-next]')?.addEventListener('click', () => {
            pauseAllVideos();
            showVideo(videoIndex + 1);
        });

        videoDots.forEach((dot) => {
            dot.addEventListener('click', () => {
                pauseAllVideos();
                showVideo(Number(dot.dataset.videoDot));
            });
        });

        videos.forEach((video) => {
            const poster = video.parentElement?.querySelector('[data-home-video-poster]');

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
