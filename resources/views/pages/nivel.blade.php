@extends('layouts.app')

@section('content')

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
            'texto' => 'Seguimiento cercano para que cada estudiante avance con confianza.',
        ],
        [
            'titulo' => 'Aprendizaje',
            'texto' => 'Actividades académicas y proyectos que conectan conocimiento con experiencia.',
        ],
        [
            'titulo' => 'Comunidad',
            'texto' => 'Una relación cercana entre estudiantes, docentes y familias.',
        ],
    ];
@endphp

<section class="max-w-6xl mx-auto">
    <div class="{{ $tema['hero'] }} rounded-xl shadow-lg overflow-hidden">
        <div class="grid md:grid-cols-[.95fr_1.05fr] md:items-stretch">
            <div class="flex flex-col justify-center p-6 md:p-8">
                @if (! empty($nivel['logo']))
                    <img
                        src="{{ $nivel['logo'] }}"
                        alt="Logo {{ $nivel['titulo'] }}"
                        class="mb-4 h-16 w-auto max-w-full rounded bg-white p-2 object-contain shadow-md md:h-20"
                    >
                @endif

                <p class="font-semibold uppercase tracking-wide text-xs md:text-sm">Oferta Educativa</p>
                <h1 class="mt-2 text-3xl font-extrabold md:text-4xl">{{ $nivel['titulo'] }}</h1>
                <p class="{{ $tema['heroMuted'] }} mt-3 max-w-2xl text-base leading-7">{{ $nivel['descripcion'] }}</p>
            </div>

            <x-imagen-seccion
                :imagen="$imagenPrincipal"
                alt="{{ $nivel['titulo'] }}"
                class="h-48 w-full {{ ($nivel['slug'] ?? null) === 'ib-en-discovery' ? 'bg-white object-contain p-4' : 'object-cover' }} sm:h-56 md:h-full md:max-h-72"
                placeholder-class="h-48 sm:h-56 md:h-72"
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

    @if (in_array($nivel['layout'] ?? null, ['ib', 'ingles'], true) && ! empty($nivel['informacion']))
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
                            {{ $nivel['informacion']['cierre']['texto'] ?? 'El Programa del Diploma del IB permite que los estudiantes profundicen en sus conocimientos, fortalezcan dos lenguas, desarrollen criterio ético y construyan una visión internacional para su siguiente etapa académica.' }}
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

                    @if (! empty($nivel['modelo_academico_url']))
                        <aside class="rounded-xl border border-gray-200 bg-gray-50 p-4">
                            <div class="mb-4">
                                <p class="text-sm font-bold uppercase tracking-wide {{ $tema['eyebrow'] }}">Modelo académico</p>
                                <h3 class="mt-1 text-2xl font-bold text-black">{{ $nivel['titulo'] }}</h3>
                            </div>
                            <img
                                src="{{ $nivel['modelo_academico_url'] }}"
                                alt="Modelo académico {{ $nivel['titulo'] }}"
                                class="max-h-[420px] w-full rounded-lg bg-white object-contain p-3 shadow-sm"
                                loading="lazy"
                            >
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
                    <h2 class="text-3xl font-bold text-black mt-2">{{ $nivel['titulo'] }} en Discovery</h2>
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
