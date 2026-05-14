@extends('layouts.app')

@section('content')

<div class="bg-blue-600 text-white py-20 text-center rounded-xl shadow-lg">
    <h1 class="text-5xl font-bold mb-6">
        Colegio Internacional Discovery
    </h1>

    <p class="text-xl mb-6">
        Formando lideres del futuro con educacion de excelencia
    </p>

    <a href="{{ route('nosotros') }}" class="bg-white text-blue-600 px-6 py-3 rounded-lg font-semibold hover:bg-gray-200">
        Conocer mas
    </a>
</div>

@if (! empty($eventos))
    <section class="mt-16 overflow-hidden rounded-xl shadow-lg bg-blue-600">
        <div class="relative min-h-[520px] bg-cover bg-center" style="background-image: linear-gradient(rgba(50, 178, 205, 0.72), rgba(50, 178, 205, 0.72)), url('https://images.unsplash.com/photo-1497633762265-9d179a990aa6?auto=format&fit=crop&w=1600&q=80');">
            <div class="max-w-5xl mx-auto px-6 py-10">
                <div class="text-center mb-8">
                    <p class="font-semibold uppercase tracking-wide text-sm text-blue-900">Eventos escolares</p>
                    <h2 class="text-4xl font-extrabold text-gray-950 mt-2">Momentos Discovery</h2>
                </div>

                <div class="relative max-w-3xl mx-auto" data-event-carousel>
                    <div class="overflow-hidden">
                        <div class="flex transition-transform duration-500 ease-out" data-event-track>
                            @foreach ($eventos as $evento)
                                <article class="min-w-full px-2">
                                    <div class="bg-white rounded-lg overflow-hidden shadow-2xl">
                                        <img
                                            src="{{ $evento['url'] }}"
                                            alt="{{ $evento['titulo'] }}"
                                            class="w-full h-80 md:h-[400px] object-cover"
                                            loading="lazy"
                                        >
                                        <div class="p-5 text-center">
                                            <h3 class="text-2xl font-extrabold text-blue-700">{{ $evento['titulo'] }}</h3>
                                            <p class="text-gray-600 mt-2">{{ $evento['descripcion'] }}</p>
                                        </div>
                                    </div>
                                </article>
                            @endforeach
                        </div>
                    </div>

                    <button
                        type="button"
                        data-event-prev
                        class="absolute left-2 top-1/2 -translate-y-1/2 h-12 w-12 rounded-full bg-white/80 text-blue-700 text-3xl font-bold shadow hover:bg-white"
                        aria-label="Evento anterior"
                    >
                        &lt;
                    </button>
                    <button
                        type="button"
                        data-event-next
                        class="absolute right-2 top-1/2 -translate-y-1/2 h-12 w-12 rounded-full bg-white/80 text-blue-700 text-3xl font-bold shadow hover:bg-white"
                        aria-label="Evento siguiente"
                    >
                        &gt;
                    </button>

                    <div class="flex justify-center gap-2 mt-5" data-event-dots>
                        @foreach ($eventos as $evento)
                            <button
                                type="button"
                                class="h-3 w-3 rounded-full bg-white/70"
                                aria-label="Ir al evento {{ $loop->iteration }}"
                                data-event-dot="{{ $loop->index }}"
                            ></button>
                        @endforeach
                    </div>
                </div>

                <a href="{{ route('recursos-escolares') }}" class="mt-8 inline-flex text-red-600 font-extrabold hover:underline">
                    Calendario de Eventos Anteriores ->
                </a>
            </div>
        </div>
    </section>
@endif

<section class="mt-16 bg-white px-6 py-10 rounded-xl shadow-md">
    <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-10">
        <article class="flex flex-col">
            <h2 class="text-xl font-extrabold text-center uppercase text-lime-400 mb-10">Preescolar</h2>
            <p class="text-gray-600 leading-7 flex-1">
                <span class="text-sky-500">El mejor Kinder de Tehuacan.</span>
                Nuestro modelo educativo se enfoca en el desarrollo integral del nino atendiendo sus necesidades
                intelectuales, emocionales, fisicas, sociales y culturales, que permitira que logren enfrentarse
                a nuevos retos de aprendizaje con seguridad y confianza en
                <span class="text-sky-500">El mejor colegio de Tehuacan</span>.
            </p>
            <a href="{{ route('nivel', 'preescolar') }}" class="mt-10 inline-flex w-fit items-center justify-center rounded bg-lime-400 px-6 py-3 font-bold text-white hover:bg-lime-500">
                Descubre mas ->
            </a>
        </article>

        <article class="flex flex-col">
            <h2 class="text-xl font-extrabold text-center uppercase text-red-600 mb-10">Primaria</h2>
            <p class="text-gray-600 leading-7 flex-1">
                <span class="text-sky-500">La mejor primaria de Tehuacan.</span>
                En Discovery tenemos como mision brindar un modelo educativo de vanguardia, bilingue, holistico
                y personalizado. Mision que comulga con el perfil de aprendizaje de los colegios del mundo en la
                mejor escuela de Tehuacan.
            </p>
            <a href="{{ route('nivel', 'primaria') }}" class="mt-10 inline-flex w-fit items-center justify-center rounded bg-red-600 px-6 py-3 font-bold text-white hover:bg-red-700">
                Descubre mas ->
            </a>
        </article>

        <article class="flex flex-col">
            <h2 class="text-xl font-extrabold text-center uppercase text-blue-700 mb-10">Secundaria</h2>
            <p class="text-gray-600 leading-7 flex-1">
                <span class="text-sky-500">La mejor Secundaria de Tehuacan.</span>
                En Discovery fomentamos el desarrollo del pensamiento critico, creativo y reflexivo a traves del
                aprendizaje por proyectos interdisciplinarios en nuestras diferentes asignaturas, propiciando el
                trabajo colaborativo y cooperativo.
            </p>
            <a href="{{ route('nivel', 'secundaria') }}" class="mt-10 inline-flex w-fit items-center justify-center rounded bg-blue-700 px-6 py-3 font-bold text-white hover:bg-blue-800">
                Descubre mas ->
            </a>
        </article>

        <article class="flex flex-col">
            <h2 class="text-xl font-extrabold text-center uppercase text-green-500 mb-10">Bachillerato</h2>
            <p class="text-gray-600 leading-7 flex-1">
                <span class="text-sky-500">El mejor Bachillerato de Tehuacan.</span>
                Somos una Institucion Educativa en Nivel Medio Superior de calidad y a la vanguardia para establecer
                nuevas oportunidades de aprendizaje holistico en nuestros alumnos en
                <span class="text-sky-500">La mejor preparatoria de Tehuacan</span>.
            </p>
            <a href="{{ route('nivel', 'bachillerato') }}" class="mt-10 inline-flex w-fit items-center justify-center rounded bg-green-500 px-6 py-3 font-bold text-white hover:bg-green-600">
                Descubre mas ->
            </a>
        </article>
    </div>
</section>

<div class="mt-20 grid md:grid-cols-2 gap-10 items-center">
    <div>
        <h2 class="text-3xl font-bold text-blue-600 mb-4">
            Sobre Nosotros
        </h2>

        <p class="text-gray-700 mb-4">
            En el Colegio Internacional Discovery nos enfocamos en brindar
            una educacion integral que combine valores, tecnologia e innovacion.
        </p>

        <p class="text-gray-700">
            Nuestro objetivo es formar estudiantes preparados para enfrentar
            los retos del futuro con confianza y liderazgo.
        </p>
    </div>

    <div>
        <img src="https://images.unsplash.com/photo-1588072432836-e10032774350"
             class="rounded-xl shadow-lg"
             alt="Escuela">
    </div>
</div>

<div class="mt-20 bg-white p-8 rounded-xl shadow-md">
    <h2 class="text-2xl font-bold text-blue-600 mb-6">
        Contactanos
    </h2>

    <form action="/contacto" method="POST" class="space-y-4">
        @csrf

        <input type="text" name="nombre" placeholder="Nombre"
            class="w-full border p-3 rounded">

        <input type="email" name="email" placeholder="Correo"
            class="w-full border p-3 rounded">

        <textarea name="mensaje" placeholder="Mensaje"
            class="w-full border p-3 rounded"></textarea>

        <button class="bg-blue-600 text-white px-6 py-3 rounded">
            Enviar
        </button>
    </form>
</div>

@if (! empty($testimonios))
    <section class="mt-20 bg-white rounded-xl shadow-md p-6 md:p-8">
        <div class="flex flex-col md:flex-row md:items-end md:justify-between gap-4 mb-6">
            <div>
                <p class="font-semibold uppercase tracking-wide text-sm text-blue-700">Testimonios Alumni</p>
                <h2 class="text-3xl font-bold text-gray-900 mt-2">Historias de nuestra comunidad</h2>
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
                    <article class="min-w-full px-1 md:px-3">
                        <div class="bg-gray-100 rounded-xl overflow-hidden shadow-sm">
                            <video
                                src="{{ $video['url'] }}"
                                controls
                                preload="metadata"
                                playsinline
                                class="w-full aspect-video bg-black"
                            ></video>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 mt-4">{{ $video['titulo'] }}</h3>
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
            activateDots(eventDots, eventIndex, 'h-3 w-8 rounded-full bg-blue-700', 'h-3 w-3 rounded-full bg-white/70');
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

        const videoTrack = document.querySelector('[data-video-track]');
        const videoSlides = videoTrack ? Array.from(videoTrack.children) : [];
        const videoDots = Array.from(document.querySelectorAll('[data-video-dot]'));
        const videos = Array.from(document.querySelectorAll('[data-video-track] video'));
        let videoIndex = 0;

        const pauseOtherVideos = (currentVideo) => {
            videos.forEach((video) => {
                if (video !== currentVideo && !video.paused) {
                    video.pause();
                }
            });
        };

        const showVideo = (index) => {
            if (!videoTrack || videoSlides.length === 0) {
                return;
            }

            videoIndex = (index + videoSlides.length) % videoSlides.length;
            videoTrack.style.transform = `translateX(-${videoIndex * 100}%)`;
            activateDots(videoDots, videoIndex, 'h-3 w-8 rounded-full bg-blue-700', 'h-3 w-3 rounded-full bg-gray-300');

            videos.forEach((video, currentIndex) => {
                if (currentIndex !== videoIndex && !video.paused) {
                    video.pause();
                }
            });
        };

        document.querySelector('[data-video-prev]')?.addEventListener('click', () => showVideo(videoIndex - 1));
        document.querySelector('[data-video-next]')?.addEventListener('click', () => showVideo(videoIndex + 1));

        videoDots.forEach((dot) => {
            dot.addEventListener('click', () => showVideo(Number(dot.dataset.videoDot)));
        });

        videos.forEach((video, index) => {
            video.addEventListener('play', () => {
                showVideo(index);
                pauseOtherVideos(video);
            });
        });

        showVideo(0);
    });
</script>

@endsection
