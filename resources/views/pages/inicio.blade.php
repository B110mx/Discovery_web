@extends('layouts.app')

@section('content')

<section class="mx-auto max-w-6xl rounded-2xl bg-blue-700 px-6 py-8 text-white shadow-xl md:px-10 md:py-10 animate-on-scroll">
    <div class="grid items-center gap-8 lg:grid-cols-[1fr_auto]">
        <div>
            <p class="text-sm font-bold uppercase tracking-[0.2em] text-blue-100">Una comunidad para crecer juntos</p>
            <h1 class="mt-3 max-w-4xl text-3xl font-extrabold leading-tight md:text-4xl">
                El lugar donde tus hijos pueden descubrir quiénes son y todo lo que pueden llegar a ser.
            </h1>
            <p class="mt-4 max-w-3xl text-lg leading-8 text-blue-50">
                {{ config('experiencia.promesa') }}
            </p>
        </div>

        <div class="flex flex-col gap-3 sm:flex-row lg:flex-col">
            <a href="{{ route(config('experiencia.cta_principal.route')) }}" class="inline-flex items-center justify-center rounded-lg bg-red-600 px-7 py-3.5 font-extrabold text-white shadow-md transition hover:bg-red-700">
                {{ config('experiencia.cta_principal.texto') }}
            </a>
            <a href="{{ route('oferta-academica') }}" class="inline-flex items-center justify-center rounded-lg border-2 border-white px-7 py-3 font-extrabold text-white transition hover:bg-white hover:text-blue-700">
                Conoce nuestra propuesta
            </a>
        </div>
    </div>

    <div class="mt-8 grid gap-3 border-t border-white/20 pt-6 text-sm font-semibold sm:grid-cols-3">
        <p>Acompañamiento cercano en cada etapa</p>
        <p>Formación con visión internacional</p>
        <p>Una comunidad para toda la familia</p>
    </div>
</section>

@if (! empty($bannerInicioSlides))
    <section class="relative mt-8 overflow-hidden rounded-xl bg-white shadow-lg" data-home-hero-carousel>
        <div class="overflow-hidden">
            <div class="flex transition-transform duration-700 ease-out" data-home-hero-track>
                @foreach ($bannerInicioSlides as $banner)
                    @if (! empty($banner['enlace']))
                        <a href="{{ $banner['enlace'] }}" class="block min-w-full">
                    @else
                        <div class="block min-w-full">
                    @endif
                    <x-imagen-seccion
                        :imagen="$banner"
                        alt="{{ $banner['alt'] ?? $banner['titulo'] ?? 'Colegio Internacional Discovery®' }}"
                        class="aspect-[1916/657] w-full bg-white object-contain"
                        placeholder-class="aspect-[1916/657] w-full"
                        loading="{{ $loop->first ? 'eager' : 'lazy' }}"
                        fetchpriority="{{ $loop->first ? 'high' : 'auto' }}"
                    />
                    @if (! empty($banner['enlace']))
                        </a>
                    @else
                        </div>
                    @endif
                @endforeach
            </div>
        </div>

        @if (count($bannerInicioSlides) > 1)
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
@endif


@if (! empty($eventos))
    <section class="mt-16 overflow-hidden rounded-xl bg-white shadow-lg animate-on-scroll">
        <div class="grid lg:grid-cols-[.85fr_1.15fr]">
            <div class="bg-blue-700 p-8 text-white md:p-10">
                <p class="font-semibold uppercase tracking-wide text-sm text-blue-100">La vida en Discovery®</p>
                <h2 class="mt-3 text-4xl font-extrabold">Momentos que compartimos en familia</h2>
                <p class="mt-5 leading-8 text-blue-50">
                    Conoce las actividades que acercan a Explorers, docentes y familias, y que hacen de cada ciclo una experiencia compartida.
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

@if (! empty($proximasFechas))
    @php
        $upcomingStyles = [
            'general' => 'border-slate-200 bg-slate-50 text-slate-800',
            'preescolar' => 'border-lime-200 bg-lime-50 text-lime-900',
            'primaria' => 'border-red-200 bg-red-50 text-red-900',
            'secundaria' => 'border-blue-200 bg-blue-50 text-blue-900',
            'bachillerato' => 'border-green-200 bg-green-50 text-green-900',
        ];
    @endphp

    <section class="mt-12 rounded-2xl bg-white p-6 shadow-md md:p-8 animate-on-scroll">
        <div class="flex flex-col gap-4 md:flex-row md:items-end md:justify-between">
            <div>
                <p class="text-sm font-bold uppercase tracking-[0.18em] text-red-600">Para tenerlo presente</p>
                <h2 class="mt-2 text-3xl font-extrabold text-blue-700">Próximas fechas en Discovery®</h2>
                <p class="mt-3 text-gray-600">Actividades y momentos importantes para acompañar la vida escolar de tus hijos.</p>
            </div>
            <a
                href="{{ route('recursos-escolares', ['mes' => substr($proximasFechas[0]['date'], 0, 7)]) }}#calendario-mensual"
                class="inline-flex shrink-0 items-center justify-center rounded-lg bg-blue-700 px-6 py-3 font-extrabold text-white transition hover:bg-blue-800"
            >
                Ver calendario mensual
            </a>
        </div>

        <div class="mt-7 grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
            @foreach ($proximasFechas as $event)
                <article class="flex gap-4 rounded-xl border p-4 {{ $upcomingStyles[$event['level']] ?? $upcomingStyles['general'] }}">
                    <div class="flex h-16 w-16 shrink-0 flex-col items-center justify-center rounded-lg bg-white shadow-sm">
                        <span class="text-2xl font-extrabold leading-none">{{ $event['day'] }}</span>
                        <span class="mt-1 text-xs font-extrabold uppercase">{{ $event['month_short'] }}</span>
                    </div>
                    <div>
                        <p class="text-xs font-bold uppercase tracking-wide opacity-70">{{ $event['level_label'] }}</p>
                        <h3 class="mt-1 font-extrabold leading-snug">{{ $event['title'] }}</h3>
                    </div>
                </article>
            @endforeach
        </div>
    </section>
@endif

@php
    $nivelEstilos = [
        'preescolar' => ['logo' => 'logo_preescolar', 'accent' => 'text-lime-500', 'button' => 'bg-lime-500 hover:bg-lime-600'],
        'primaria' => ['logo' => 'logo_primaria', 'accent' => 'text-red-600', 'button' => 'bg-red-600 hover:bg-red-700'],
        'secundaria' => ['logo' => 'logo_secundaria', 'accent' => 'text-blue-700', 'button' => 'bg-blue-700 hover:bg-blue-800'],
        'bachillerato' => ['logo' => 'logo_bachillerato', 'accent' => 'text-green-600', 'button' => 'bg-green-600 hover:bg-green-700'],
    ];
@endphp

<section class="mt-16 rounded-2xl bg-white px-6 py-12 shadow-md md:px-10">
    <div class="mx-auto mb-10 max-w-3xl text-center animate-on-scroll">
        <p class="text-sm font-bold uppercase tracking-[0.2em] text-sky-600">Una etapa, un acompañamiento</p>
        <h2 class="mt-3 text-3xl font-extrabold text-blue-700 md:text-4xl">Encuentra el espacio que acompaña lo que tus hijos necesitan hoy</h2>
        <p class="mt-4 text-lg leading-8 text-gray-600">Cada etapa tiene sus propios retos. En Discovery® crecemos junto a tu familia con atención cercana y experiencias que preparan para el siguiente paso.</p>
    </div>

    <div class="grid gap-6 md:grid-cols-2 lg:grid-cols-4">
        @foreach ($nivelesInicio as $slug => $nivel)
            @php($estilo = $nivelEstilos[$slug])
            <article class="flex flex-col rounded-2xl border border-gray-100 p-6 shadow-sm transition hover:-translate-y-1 hover:shadow-lg animate-on-scroll" style="transition-delay: {{ $loop->iteration * 100 }}ms;">
                <img
                    src="{{ $logosNiveles[$estilo['logo']]['url'] ?? '' }}"
                    alt="{{ $nivel['titulo'] }} Discovery®"
                    class="mx-auto mb-5 h-20 w-auto object-contain"
                    loading="lazy"
                >
                <p class="text-center text-xs font-bold uppercase tracking-widest {{ $estilo['accent'] }}">{{ $nivel['edad'] }}</p>
                <h3 class="mt-2 text-center text-2xl font-extrabold text-gray-900">{{ $nivel['titulo'] }}</h3>
                <p class="mt-5 flex-1 leading-7 text-gray-600">{{ $nivel['descripcion'] }}</p>
                <a href="{{ route('nivel', $slug) }}" class="mt-7 inline-flex items-center justify-center rounded-lg px-5 py-3 text-center font-bold text-white transition {{ $estilo['button'] }}">
                    Conoce esta etapa
                </a>
            </article>
        @endforeach
    </div>
</section>

<div class="mt-20 grid items-center gap-10 rounded-2xl bg-blue-50 p-6 md:grid-cols-2 md:p-10 animate-on-scroll">
    <div>
        @if ($paginaInicio?->subtitulo)
            <p class="mb-2 text-sm font-semibold uppercase tracking-wide text-sky-600">
                {{ $paginaInicio->subtitulo }}
            </p>
        @endif

        <h2 class="mb-4 text-3xl font-extrabold leading-tight text-blue-700 md:text-4xl">
            {{ $paginaInicio?->titulo ?? 'Tu familia también puede encontrar su lugar en Discovery®' }}
        </h2>

        <p class="whitespace-pre-line text-lg leading-8 text-gray-700">
            {{ $paginaInicio?->descripcion ?? 'Sabemos que elegir colegio es elegir quién acompañará a tus hijos mientras crecen. En Discovery® unimos formación académica, bienestar y una comunidad cercana para que cada Explorer avance con confianza y cada familia se sienta parte del camino.' }}
        </p>
        <a href="{{ route('nosotros') }}" class="mt-7 inline-flex font-extrabold text-blue-700 underline decoration-red-500 decoration-4 underline-offset-4 hover:text-blue-900">
            Conoce lo que nos une
        </a>
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

<section class="mt-20 overflow-hidden rounded-2xl bg-gradient-to-br from-blue-700 to-blue-900 px-6 py-12 text-white shadow-xl md:px-12 animate-on-scroll">
    <div class="mx-auto max-w-4xl text-center">
        <p class="text-sm font-bold uppercase tracking-[0.2em] text-blue-100">Aquí también hay un lugar para ustedes</p>
        <h2 class="mt-3 text-3xl font-extrabold md:text-4xl">{{ config('experiencia.pertenencia') }}</h2>
    </div>
    <div class="mt-10 grid gap-5 md:grid-cols-3">
        <article class="rounded-xl bg-white/10 p-6 backdrop-blur-sm">
            <h3 class="text-xl font-extrabold">Conocemos a cada Explorer</h3>
            <p class="mt-3 leading-7 text-blue-50">Escuchamos, acompañamos y reconocemos el ritmo, los talentos y las necesidades de tus hijos.</p>
        </article>
        <article class="rounded-xl bg-white/10 p-6 backdrop-blur-sm">
            <h3 class="text-xl font-extrabold">Crecemos junto a las familias</h3>
            <p class="mt-3 leading-7 text-blue-50">La comunicación cercana permite construir confianza y compartir cada logro importante.</p>
        </article>
        <article class="rounded-xl bg-white/10 p-6 backdrop-blur-sm">
            <h3 class="text-xl font-extrabold">Aprendemos como comunidad</h3>
            <p class="mt-3 leading-7 text-blue-50">Explorers, docentes y familias hacemos del colegio un espacio donde pertenecer y participar.</p>
        </article>
    </div>
</section>

@if (! empty($testimonios))
    <section class="mt-20 bg-white rounded-xl shadow-md p-6 md:p-8 animate-on-scroll">
        <div class="flex flex-col md:flex-row md:items-end md:justify-between gap-4 mb-6">
            <div>
                <p class="font-semibold uppercase tracking-wide text-sm text-blue-700">Voces de nuestra comunidad</p>
                <h2 class="text-3xl font-bold text-black mt-2">Historias que muestran lo que significa crecer en Discovery®</h2>
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

<section class="mt-20 rounded-2xl bg-red-600 px-6 py-10 text-center text-white shadow-xl md:px-12 animate-on-scroll">
    <p class="text-sm font-bold uppercase tracking-[0.2em] text-red-100">El siguiente paso puede comenzar aquí</p>
    <h2 class="mx-auto mt-3 max-w-3xl text-3xl font-extrabold md:text-4xl">Ven a conocer el lugar donde tus hijos pueden crecer con confianza y tu familia puede sentirse parte.</h2>
    <div class="mt-8 flex flex-col justify-center gap-3 sm:flex-row">
        <a href="{{ route(config('experiencia.cta_principal.route')) }}" class="inline-flex items-center justify-center rounded-lg bg-white px-7 py-3.5 font-extrabold text-red-600 shadow transition hover:bg-red-50">
            {{ config('experiencia.cta_principal.texto') }}
        </a>
        <a href="{{ route('nosotros') }}" class="inline-flex items-center justify-center rounded-lg border-2 border-white px-7 py-3 font-extrabold text-white transition hover:bg-white hover:text-red-600">
            Conoce nuestra comunidad
        </a>
    </div>
</section>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const activateDots = (dots, index, activeClasses, inactiveClasses) => {
            dots.forEach((dot, dotIndex) => {
                dot.className = dotIndex === index ? activeClasses : inactiveClasses;
            });
        };

        const enableTouchSwipe = (element, onPrevious, onNext) => {
            if (!element) {
                return;
            }

            let startX = 0;
            let startY = 0;

            element.addEventListener('touchstart', (event) => {
                const touch = event.touches[0];

                startX = touch.clientX;
                startY = touch.clientY;
            }, { passive: true });

            element.addEventListener('touchend', (event) => {
                const touch = event.changedTouches[0];
                const deltaX = touch.clientX - startX;
                const deltaY = touch.clientY - startY;

                if (Math.abs(deltaX) < 45 || Math.abs(deltaX) < Math.abs(deltaY) * 1.2) {
                    return;
                }

                if (deltaX < 0) {
                    onNext();
                } else {
                    onPrevious();
                }
            }, { passive: true });
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

        enableTouchSwipe(
            document.querySelector('[data-home-hero-carousel]'),
            () => {
                showHero(heroIndex - 1);
                restartHeroTimer();
            },
            () => {
                showHero(heroIndex + 1);
                restartHeroTimer();
            },
        );

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

        enableTouchSwipe(
            document.querySelector('[data-event-carousel]'),
            () => {
                showEvent(eventIndex - 1);
                restartEventTimer();
            },
            () => {
                showEvent(eventIndex + 1);
                restartEventTimer();
            },
        );

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

        enableTouchSwipe(
            videoCarousel,
            () => {
                pauseAllVideos();
                showVideo(videoIndex - 1);
            },
            () => {
                pauseAllVideos();
                showVideo(videoIndex + 1);
            },
        );

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
