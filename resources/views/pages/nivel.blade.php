@extends('layouts.app')

@section('content')

@php
    $imagenPrincipal = $nivel['imagen_principal'] ?? [
        'url' => $galeria[0]['url'] ?? null,
        'titulo' => $nivel['titulo'] . ' - Imagen principal',
        'referencia' => 'Imagen principal del encabezado del nivel ' . $nivel['titulo'] . '.',
    ];
@endphp

<section class="max-w-6xl mx-auto">
    <div class="bg-blue-700 text-white rounded-xl shadow-lg overflow-hidden">
        <div class="grid md:grid-cols-2">
            <div class="p-8 md:p-12">
                @if (! empty($nivel['logo']))
                    <img
                        src="{{ $nivel['logo'] }}"
                        alt="Logo {{ $nivel['titulo'] }}"
                        class="mb-6 h-28 w-auto max-w-full rounded bg-white p-3 object-contain shadow-md"
                    >
                @endif

                <p class="font-semibold uppercase tracking-wide text-sm">Oferta Educativa</p>
                <h1 class="text-4xl md:text-5xl font-bold mt-3">{{ $nivel['titulo'] }}</h1>
                <p class="text-blue-50 text-lg mt-5">{{ $nivel['descripcion'] }}</p>
            </div>

            <x-imagen-seccion
                :imagen="$imagenPrincipal"
                alt="{{ $nivel['titulo'] }}"
                class="h-72 w-full object-cover md:h-full"
                placeholder-class="h-72 md:h-full"
            />
        </div>
    </div>

    <div class="grid md:grid-cols-3 gap-6 mt-10">
        <div class="bg-white p-6 rounded-xl shadow-md">
            <h2 class="text-2xl font-bold text-blue-700 mb-2">Acompanamiento</h2>
            <p class="text-gray-600">Seguimiento cercano para que cada estudiante avance con confianza.</p>
        </div>

        <div class="bg-white p-6 rounded-xl shadow-md">
            <h2 class="text-2xl font-bold text-blue-700 mb-2">Aprendizaje</h2>
            <p class="text-gray-600">Actividades academicas y proyectos que conectan conocimiento con experiencia.</p>
        </div>

        <div class="bg-white p-6 rounded-xl shadow-md">
            <h2 class="text-2xl font-bold text-blue-700 mb-2">Comunidad</h2>
            <p class="text-gray-600">Una relacion cercana entre estudiantes, docentes y familias.</p>
        </div>
    </div>

    @if (! empty($nivel['informacion']))
        <section class="mt-12 overflow-hidden rounded-xl bg-white shadow-md">
            <div class="bg-blue-700 p-6 text-white md:p-8">
                <div class="flex flex-col gap-6 lg:flex-row lg:items-end lg:justify-between">
                    <div class="max-w-4xl">
                        <p class="font-semibold uppercase tracking-wide text-sm text-blue-100">Hoja informativa maquetada</p>
                        <h2 class="mt-2 text-3xl font-extrabold md:text-4xl">{{ $nivel['informacion']['titulo'] }}</h2>
                        <p class="mt-4 text-lg leading-8 text-blue-50">{{ $nivel['informacion']['intro'] }}</p>
                    </div>

                    @if (! empty($nivel['hoja_informativa_url']))
                        <a
                            href="{{ $nivel['hoja_informativa_url'] }}"
                            target="_blank"
                            rel="noopener"
                            class="inline-flex w-fit items-center justify-center rounded bg-red-600 px-5 py-3 font-bold text-white hover:bg-red-700"
                        >
                            Abrir PDF original
                        </a>
                    @endif
                </div>
            </div>

            <div class="p-6 md:p-8">
                <div class="grid gap-8 lg:grid-cols-[1.05fr_.95fr]">
                    <div>
                        <h3 class="text-2xl font-bold text-blue-700">Puntos clave</h3>
                        <div class="mt-5 grid gap-3 sm:grid-cols-2">
                            @foreach ($nivel['informacion']['puntos'] as $punto)
                                <div class="flex gap-3 rounded-lg border border-blue-100 bg-blue-50 p-4 text-gray-700">
                                    <span class="mt-2 h-2.5 w-2.5 flex-shrink-0 rounded-full bg-red-600"></span>
                                    <span class="font-semibold leading-7">{{ $punto }}</span>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    @if (! empty($nivel['modelo_academico_url']))
                        <aside class="rounded-xl border border-gray-200 bg-gray-50 p-4">
                            <div class="mb-4">
                                <p class="text-sm font-bold uppercase tracking-wide text-blue-700">Modelo academico</p>
                                <h3 class="mt-1 text-2xl font-bold text-black">{{ $nivel['titulo'] }}</h3>
                            </div>
                            <img
                                src="{{ $nivel['modelo_academico_url'] }}"
                                alt="Modelo academico {{ $nivel['titulo'] }}"
                                class="max-h-[420px] w-full rounded-lg bg-white object-contain p-3 shadow-sm"
                                loading="lazy"
                            >
                        </aside>
                    @endif
                </div>

                <div class="mt-10 grid gap-5 md:grid-cols-2">
                    @foreach ($nivel['informacion']['secciones'] as $seccion)
                        <article class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm">
                            <span class="inline-flex h-2 w-12 rounded-full bg-blue-700"></span>
                            <h3 class="mt-4 text-xl font-extrabold text-black">{{ $seccion['titulo'] }}</h3>
                            <p class="mt-3 leading-8 text-gray-600">{{ $seccion['texto'] }}</p>
                        </article>
                    @endforeach
                </div>

                @if (! empty($nivel['informacion']['experiencias']))
                    <div class="mt-10 rounded-xl bg-gray-50 p-6">
                        <h3 class="text-2xl font-bold text-blue-700">Comunidad y experiencias</h3>
                        <div class="mt-5 flex flex-wrap gap-3">
                            @foreach ($nivel['informacion']['experiencias'] as $experiencia)
                                <span class="rounded-full bg-white px-4 py-2 text-sm font-bold text-blue-700 shadow-sm">
                                    {{ $experiencia }}
                                </span>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>
        </section>
    @endif

    @if (! empty($galeria))
        <section class="mt-12">
            <div class="flex flex-col md:flex-row md:items-end md:justify-between gap-4 mb-6">
                <div>
                    <p class="font-semibold uppercase tracking-wide text-sm text-blue-700">Galeria</p>
                    <h2 class="text-3xl font-bold text-black mt-2">{{ $nivel['titulo'] }} en Discovery</h2>
                </div>
                <p class="text-gray-600 max-w-2xl">
                    Momentos reales de nuestra comunidad educativa en este nivel.
                </p>
            </div>

            <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-5">
                @foreach ($galeria as $imagen)
                    <img
                        src="{{ $imagen['url'] }}"
                        alt="{{ $imagen['alt'] }}"
                        loading="lazy"
                        class="w-full aspect-[4/3] object-cover rounded-xl shadow-md"
                    >
                @endforeach
            </div>
        </section>
    @else
        <section class="mt-12 bg-white rounded-xl shadow-md p-8">
            <h2 class="text-2xl font-bold text-blue-700 mb-2">Galeria en preparacion</h2>
            <p class="text-gray-600">
                Pronto agregaremos mas imagenes de este nivel educativo.
            </p>
        </section>
    @endif
</section>

@endsection
