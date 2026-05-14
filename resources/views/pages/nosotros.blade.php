@extends('layouts.app')

@section('content')

@php
    $imagenPrincipal = 'https://images.unsplash.com/photo-1577896851231-70ef18881754?auto=format&fit=crop&w=1200&q=80';
    $imagenSecundaria = 'https://images.unsplash.com/photo-1509062522246-3755977927d7?auto=format&fit=crop&w=900&q=80';

    $incorporaciones = [
        'Maternal: 21PDI0093H',
        'Preescolar: 21PJN0912U',
        'Primaria: 21PPR0078B',
        'Secundaria: 21PES0097J',
        'Bachillerato: 21PBH0513D',
    ];

    $areas = [
        'Area bilingue',
        'Area de matematicas',
        'Area de tecnologias',
        'Area de comprension lectora',
    ];

    $historia = [
        ['anio' => '2003', 'titulo' => 'Discovery Kinder', 'texto' => 'Nace Discovery Kinder, el inicio de un sueno educativo porque los primeros pasos trascienden.'],
        ['anio' => '2005', 'titulo' => 'Discovery Primaria', 'texto' => 'Inauguracion de Discovery Primaria, creciendo con pasos firmes.'],
        ['anio' => '2011', 'titulo' => 'Discovery Secundaria', 'texto' => 'Se suma Discovery Secundaria, ampliando horizontes.'],
        ['anio' => '2016', 'titulo' => 'Discovery Bachillerato', 'texto' => 'Llega Discovery Bachillerato, preparando grandes lideres y descubriendo su potencial.'],
        ['anio' => '2018', 'titulo' => 'Colegio del Mundo', 'texto' => 'Nos convertimos en Colegio del Mundo IB, abrazando la educacion internacional.'],
        ['anio' => '2019', 'titulo' => 'Nuevas instalaciones', 'texto' => 'Estrenamos nuevas instalaciones para seguir innovando.'],
        ['anio' => '2023', 'titulo' => 'DKMUN primera edicion', 'texto' => 'Realizamos nuestra primera edicion DKMUN, un espacio para el liderazgo y la diplomacia.'],
        ['anio' => '2025', 'titulo' => 'Actualmente', 'texto' => 'Seguimos escribiendo nuestra historia, creciendo y evolucionando juntos.'],
    ];
@endphp

<section class="max-w-7xl mx-auto space-y-14">
    <div class="bg-white rounded-xl overflow-hidden shadow-lg">
        <div class="grid lg:grid-cols-2">
            <div class="p-8 md:p-12 flex flex-col justify-center">
                <p class="font-semibold uppercase tracking-wide text-sm text-blue-700">Conocenos</p>
                <h1 class="text-4xl md:text-5xl font-bold mt-3 text-gray-900">Colegio Discovery</h1>
                <p class="text-gray-600 text-lg mt-5 max-w-xl">
                    Una comunidad educativa que acompana a cada estudiante en su crecimiento academico,
                    humano y social.
                </p>
                <div class="mt-8">
                    <a href="#historia" class="bg-blue-700 text-white px-6 py-3 rounded font-semibold hover:bg-blue-800">
                        Ver historia
                    </a>
                </div>
            </div>

            <img
                src="{{ $imagenPrincipal }}"
                alt="Colegio Discovery"
                class="w-full h-80 lg:h-full object-cover"
            >
        </div>
    </div>

    <div class="grid lg:grid-cols-5 gap-8 items-start">
        <section class="lg:col-span-3 bg-white rounded-xl shadow-md p-8">
            <p class="font-semibold uppercase tracking-wide text-sm text-blue-700">Bienvenidos</p>
            <h2 class="text-3xl font-bold text-gray-900 mt-2">Una comunidad que forma para trascender</h2>
            <p class="text-gray-700 leading-7 mt-5">
                En Discovery formamos en valores, actitudes y virtudes que impactan en el crecimiento personal y
                social del individuo. Tenemos un enfasis especial en el desarrollo de la inteligencia emocional,
                con el proposito de preparar personas comprometidas con una sociedad mas humana.
            </p>
            <p class="text-gray-700 leading-7 mt-4">
                Nuestros estudiantes desarrollan herramientas para liderar, participar responsablemente y aportar
                a un mundo mejor desde cada etapa de su formacion.
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
            <img
                src="{{ $imagenSecundaria }}"
                alt="Modelo educativo Colegio Discovery"
                class="w-full h-72 lg:h-full object-cover"
            >

            <div class="p-8 md:p-10">
                <p class="font-semibold uppercase tracking-wide text-sm text-blue-700">Modelo Educativo</p>
                <h2 class="text-3xl font-bold text-gray-900 mt-2">Planeacion estrategica para el futuro</h2>
                <p class="text-gray-700 leading-7 mt-5">
                    Trabajamos con una planeacion que enfoca los recursos en las necesidades futuras de nuestros
                    alumnos, fortaleciendo disciplinas imprescindibles para su desarrollo academico y humano.
                </p>

                <div class="grid sm:grid-cols-2 gap-4 mt-7">
                    @foreach ($areas as $area)
                        <div class="bg-blue-50 text-blue-800 rounded-lg p-4 font-semibold">
                            {{ $area }}
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </section>

    <section class="grid md:grid-cols-2 gap-8">
        <div class="bg-blue-700 text-white rounded-xl shadow-md p-8">
            <p class="font-semibold uppercase tracking-wide text-sm text-blue-100">Mision</p>
            <h2 class="text-3xl font-bold mt-3">Contribuir a la formacion de Explorers que trascienden en un mundo globalizado.</h2>
        </div>

        <div class="bg-green-500 text-white rounded-xl shadow-md p-8">
            <p class="font-semibold uppercase tracking-wide text-sm text-green-50">Vision</p>
            <h2 class="text-3xl font-bold mt-3">Ser una comunidad unida que empodera agentes de cambio que contribuyen a un mundo mejor.</h2>
        </div>
    </section>

    <section id="historia" class="bg-white rounded-xl shadow-md p-8 md:p-10">
        <div class="max-w-3xl">
            <p class="font-semibold uppercase tracking-wide text-sm text-blue-700">Linea temporal</p>
            <h2 class="text-3xl font-bold text-gray-900 mt-2">La historia del colegio</h2>
            <p class="text-gray-600 mt-4">
                Un recorrido por los momentos que han construido a Colegio Discovery como una comunidad educativa
                en constante crecimiento.
            </p>
        </div>

        <div class="mt-10 grid md:grid-cols-2 gap-x-10 gap-y-8">
            @foreach ($historia as $evento)
                <article class="relative pl-8 border-l-4 border-blue-600">
                    <span class="absolute -left-3 top-0 h-5 w-5 rounded-full bg-blue-600 ring-4 ring-white"></span>
                    <p class="text-blue-700 font-extrabold text-xl">{{ $evento['anio'] }}</p>
                    <h3 class="text-xl font-bold text-gray-900 mt-2">{{ $evento['titulo'] }}</h3>
                    <p class="text-gray-600 leading-7 mt-2">{{ $evento['texto'] }}</p>
                </article>
            @endforeach
        </div>
    </section>
</section>

@endsection
