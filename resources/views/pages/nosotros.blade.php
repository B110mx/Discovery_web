@extends('layouts.app')

@section('content')

@php
    $incorporaciones = [
        'Maternal: 21PDI0093H',
        'Preescolar: 21PJN0912U',
        'Primaria: 21PPR0078B',
        'Secundaria: 21PES0097J',
        'Bachillerato: 21PBH0513D',
    ];

    $areas = [
        'Área bilingüe',
        'Área de matemáticas',
        'Área de tecnologías',
        'Área de comprensión lectora',
    ];

    $historia = $historiaNosotros;
@endphp

<section class="max-w-7xl mx-auto space-y-14 py-8">
    
    <div class="bg-white rounded-xl overflow-hidden shadow-lg">
        <div class="grid lg:grid-cols-2">
            <div class="p-8 md:p-12 flex flex-col justify-center">
                <p class="font-semibold uppercase tracking-wide text-sm text-blue-700">{{ $paginaNosotros?->subtitulo ?? 'Conocenos' }}</p>
                <h1 class="text-4xl md:text-5xl font-bold mt-3 text-black">{{ $paginaNosotros?->titulo ?? 'Colegio Discovery' }}</h1>
                <p class="text-gray-600 text-lg mt-5 max-w-xl">
                    {{ $paginaNosotros?->descripcion ?? 'Una comunidad educativa que acompana a cada estudiante en su crecimiento academico, humano y social.' }}
                </p>
                <div class="mt-8">
                    <a href="#historia" class="bg-blue-700 text-white px-6 py-3 rounded font-semibold hover:bg-blue-800 transition-colors">
                        Ver historia
                    </a>
                </div>
            </div>
            <x-imagen-seccion
                :imagen="$imagenesNosotros['hero']"
                alt="Colegio Discovery"
                class="h-80 w-full bg-white object-contain p-8 lg:h-full lg:p-10"
                placeholder-class="h-80 lg:h-full"
            />
        </div>
    </div>

    <div class="grid lg:grid-cols-5 gap-8 items-start">
        <section class="lg:col-span-3 bg-white rounded-xl shadow-md p-8">
            <p class="font-semibold uppercase tracking-wide text-sm text-blue-700">Bienvenidos</p>
            <h2 class="text-3xl font-bold text-black mt-2">Una comunidad que forma para trascender</h2>
            <p class="text-gray-700 leading-7 mt-5">
                En Discovery formamos en valores, actitudes y virtudes que impactan en el crecimiento personal y social del individuo.
            </p>
            <p class="text-gray-700 leading-7 mt-4">
                Nuestros estudiantes desarrollan herramientas para liderar, participar responsablemente y aportar a un mundo mejor.
            </p>
        </section>

        <aside class="lg:col-span-2 bg-blue-700 text-white rounded-xl shadow-md p-8">
            <h3 class="text-2xl font-bold mb-5">Incorporados a la SEP</h3>
            <ul class="space-y-3">
                @foreach ($incorporaciones as $incorporacion)
                    <li class="border-b border-blue-500 pb-3 last:border-b-0 last:pb-0">{{ $incorporacion }}</li>
                @endforeach
            </ul>
        </aside>
    </div>

    <section class="bg-white rounded-xl shadow-md overflow-hidden">
        <div class="grid lg:grid-cols-2">
            <x-imagen-seccion
                :imagen="$imagenesNosotros['modelo']"
                alt="Modelo educativo Colegio Discovery"
                class="h-72 w-full object-cover lg:h-full"
                placeholder-class="h-72 lg:h-full"
            />
            <div class="p-8 md:p-10">
                <p class="font-semibold uppercase tracking-wide text-sm text-blue-700">Modelo Educativo</p>
                <h2 class="text-3xl font-bold text-black mt-2">Planeación estratégica para el futuro</h2>
                <p class="text-gray-700 leading-7 mt-5">
                    Trabajamos con una planeación que enfoca los recursos en las necesidades futuras de nuestros alumnos.
                </p>
                <div class="grid sm:grid-cols-2 gap-4 mt-7">
                    @foreach ($areas as $area)
                        <div class="bg-blue-50 text-blue-800 rounded-lg p-4 font-semibold">{{ $area }}</div>
                    @endforeach
                </div>
            </div>
        </div>
    </section>

    <section class="grid md:grid-cols-2 gap-8">
        <div class="bg-blue-700 text-white rounded-xl shadow-md p-8">
            <p class="font-semibold uppercase tracking-wide text-sm text-blue-100">Misión</p>
            <h2 class="text-3xl font-bold mt-3">Contribuir a la formación de Explorers que trascienden en un mundo globalizado.</h2>
        </div>
        <div class="bg-green-500 text-white rounded-xl shadow-md p-8">
            <p class="font-semibold uppercase tracking-wide text-sm text-green-50">Visión</p>
            <h2 class="text-3xl font-bold mt-3">Ser una comunidad unida que empodera agentes de cambio que contribuyen a un mundo mejor.</h2>
        </div>
    </section>

    <section class="bg-white rounded-xl shadow-md overflow-hidden">
        <div class="grid lg:grid-cols-[.95fr_1.05fr]">
            <div class="p-8 md:p-10 flex flex-col justify-center">
                <p class="font-semibold uppercase tracking-wide text-sm text-blue-700">Proyeccion academica</p>
                <h2 class="text-3xl md:text-4xl font-bold text-black mt-2">Vinculacion Universitaria</h2>
                <p class="text-gray-700 leading-8 mt-5">
                    Acompanamos a nuestros estudiantes en la construccion de su proyecto de vida, acercandolos a opciones universitarias, experiencias de orientacion vocacional y herramientas para tomar decisiones informadas sobre su futuro.
                </p>

                <div class="grid sm:grid-cols-3 gap-3 mt-7">
                    <div class="rounded-lg border border-blue-100 bg-blue-50 p-4">
                        <span class="block text-2xl font-extrabold text-blue-700">01</span>
                        <p class="mt-2 text-sm font-semibold leading-6 text-blue-900">Orientacion para elegir camino profesional</p>
                    </div>
                    <div class="rounded-lg border border-green-100 bg-green-50 p-4">
                        <span class="block text-2xl font-extrabold text-green-600">02</span>
                        <p class="mt-2 text-sm font-semibold leading-6 text-green-900">Acercamiento con instituciones universitarias</p>
                    </div>
                    <div class="rounded-lg border border-red-100 bg-red-50 p-4">
                        <span class="block text-2xl font-extrabold text-red-600">03</span>
                        <p class="mt-2 text-sm font-semibold leading-6 text-red-900">Preparacion para nuevas etapas academicas</p>
                    </div>
                </div>
            </div>

            <div class="bg-gray-100 p-4 md:p-6">
                <div class="grid grid-cols-2 gap-3 sm:grid-cols-3">
                    @foreach ($universidadesVinculacion as $universidad)
                        <div class="flex h-28 items-center justify-center rounded-lg border border-gray-200 bg-white p-4 shadow-sm md:h-32">
                            <img
                                src="{{ $universidad['url'] }}"
                                alt="{{ $universidad['nombre'] }}"
                                class="max-h-20 w-full object-contain"
                                loading="lazy"
                            >
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </section>

    <section id="historia" class="bg-white rounded-xl shadow-md p-8 md:p-12 mb-10">
        <div class="max-w-3xl mb-16 text-center md:text-left mx-auto md:mx-0">
            <p class="font-semibold uppercase tracking-wide text-sm text-blue-700">Línea temporal</p>
            <h2 class="text-3xl md:text-4xl font-bold text-black mt-2">Nuestra Historia</h2>
            <p class="text-gray-600 mt-4 text-lg">
                Un recorrido por los momentos que han construido a Colegio Discovery como una comunidad educativa en constante crecimiento.
            </p>
        </div>

        <div class="relative max-w-5xl mx-auto">
            <div class="absolute left-6 md:left-1/2 md:-translate-x-1/2 top-0 bottom-0 w-1 bg-blue-100 rounded-full"></div>

            <div class="space-y-12">
                @foreach ($historia as $evento)
                    <div class="relative flex flex-col md:flex-row {{ $loop->even ? 'md:flex-row-reverse' : '' }} items-center group">
                        
                        <div class="absolute left-6 md:left-1/2 md:-translate-x-1/2 top-6 z-10">
                            <span class="block h-5 w-5 rounded-full bg-blue-600 ring-4 ring-white shadow-md transition-transform duration-300 group-hover:scale-125"></span>
                        </div>
                        
                        <div class="w-full md:w-1/2 pl-16 md:pl-0 {{ $loop->even ? 'md:pl-12' : 'md:pr-12' }}">
                            <div class="bg-gray-50 p-6 md:p-8 rounded-xl border border-gray-100 shadow-sm transition-all duration-300 group-hover:shadow-md group-hover:-translate-y-1">
                                <span class="text-blue-700 font-extrabold text-3xl block mb-1">{{ $evento['anio'] }}</span>
                                <h3 class="text-xl font-bold text-black mb-2">{{ $evento['titulo'] }}</h3>
                                <p class="text-gray-600 leading-relaxed">{{ $evento['texto'] }}</p>
                            </div>
                        </div>
                        
                        <div class="w-full md:w-1/2 pl-16 md:pl-0 mt-6 md:mt-0 {{ $loop->even ? 'md:pr-12' : 'md:pl-12' }}">
                            <div class="{{ count($evento['imagenes']) > 1 ? 'grid grid-cols-2 gap-3' : '' }}">
                                @foreach ($evento['imagenes'] as $imagen)
                                    <x-imagen-seccion
                                        :imagen="$imagen"
                                        :alt="$evento['titulo']"
                                        class="w-full h-48 md:h-64 object-cover rounded-xl shadow-sm border border-gray-100"
                                        placeholder-class="w-full min-h-48 md:min-h-64"
                                    />
                                @endforeach
                            </div>
                        </div>

                    </div>
                @endforeach
            </div>
        </div>
    </section>

</section>

@endsection
