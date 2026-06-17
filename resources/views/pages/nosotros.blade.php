@extends('layouts.app')

@section('content')

{{-- Datos institucionales fijos. Los textos del hero e imágenes llegan del controlador. --}}
@php
    $incorporaciones = [
        [
            'nivel' => 'Maternal',
            'clave' => '21PDI0093H',
            'color' => 'border-lime-300 bg-lime-50 text-lime-900',
            'badge' => 'bg-lime-500 text-black',
        ],
        [
            'nivel' => 'Kinder',
            'clave' => '21PJN0912U',
            'color' => 'border-lime-300 bg-lime-50 text-lime-900',
            'badge' => 'bg-lime-500 text-black',
        ],
        [
            'nivel' => 'Elementary',
            'clave' => '21PPR0078B',
            'color' => 'border-red-200 bg-red-50 text-red-900',
            'badge' => 'bg-red-600 text-white',
        ],
        [
            'nivel' => 'Middle',
            'clave' => '21PES0097J',
            'color' => 'border-blue-200 bg-blue-50 text-blue-900',
            'badge' => 'bg-blue-700 text-white',
        ],
        [
            'nivel' => 'High',
            'clave' => '21PBH0513D',
            'color' => 'border-green-200 bg-green-50 text-green-900',
            'badge' => 'bg-green-600 text-white',
        ],
    ];

    $areas = __('site.pages.about.areas');

    $historia = $historiaNosotros;
@endphp

<section class="max-w-7xl mx-auto space-y-14 py-8">
    
    <div class="bg-white rounded-xl overflow-hidden shadow-lg">
        <div class="grid lg:grid-cols-2">
            <div class="p-8 md:p-12 flex flex-col justify-center">
                <p class="font-semibold uppercase tracking-wide text-sm text-blue-700">{{ $paginaNosotros?->subtitulo ?? __('site.pages.about.hero_subtitle') }}</p>
                <h1 class="text-4xl md:text-5xl font-bold mt-3 text-black">{{ $paginaNosotros?->titulo ?? __('site.pages.about.hero_title') }}</h1>
                <p class="text-gray-600 text-lg mt-5 max-w-xl">
                    {{ $paginaNosotros?->descripcion ?? __('site.pages.about.hero_text') }}
                </p>
                <div class="mt-8">
                    <a href="#historia" class="bg-blue-700 text-white px-6 py-3 rounded font-semibold hover:bg-blue-800 transition-colors">
                        {{ __('site.pages.about.view_history') }}
                    </a>
                </div>
            </div>
            <x-imagen-seccion
                :imagen="$imagenesNosotros['hero']"
                alt="Colegio Discovery®"
                class="h-80 w-full bg-white object-contain p-8 lg:h-full lg:p-10"
                placeholder-class="h-80 lg:h-full"
            />
        </div>
    </div>

    <div class="grid items-start gap-8 lg:grid-cols-5">
        <section class="flex flex-col rounded-xl bg-white p-8 shadow-md lg:col-span-3">
            <div>
                <p class="font-semibold uppercase tracking-wide text-sm text-blue-700">{{ __('site.pages.about.welcome') }}</p>
                <h2 class="text-3xl font-bold text-black mt-2">{{ __('site.pages.about.community_title') }}</h2>
                <p class="text-gray-700 leading-7 mt-5">
                    {{ __('site.pages.about.community_text_1') }}
                </p>
                <p class="text-gray-700 leading-7 mt-4">
                    {{ __('site.pages.about.community_text_2') }}
                </p>
            </div>

            <div class="mt-8 grid gap-3 border-t border-gray-100 pt-6 sm:grid-cols-3">
                <div class="rounded-xl bg-blue-50 p-4 text-center text-blue-800">
                    <span class="block text-sm font-extrabold uppercase tracking-wide">{{ __('site.pages.about.values') }}</span>
                    <span class="mt-1 block text-xs font-semibold text-blue-600">{{ __('site.pages.about.values_caption') }}</span>
                </div>
                <div class="rounded-xl bg-red-50 p-4 text-center text-red-800">
                    <span class="block text-sm font-extrabold uppercase tracking-wide">{{ __('site.pages.about.attitudes') }}</span>
                    <span class="mt-1 block text-xs font-semibold text-red-600">{{ __('site.pages.about.attitudes_caption') }}</span>
                </div>
                <div class="rounded-xl bg-green-50 p-4 text-center text-green-800">
                    <span class="block text-sm font-extrabold uppercase tracking-wide">{{ __('site.pages.about.virtues') }}</span>
                    <span class="mt-1 block text-xs font-semibold text-green-600">{{ __('site.pages.about.virtues_caption') }}</span>
                </div>
            </div>
        </section>

        <aside class="lg:col-span-2 rounded-xl bg-white p-6 shadow-md md:p-8">
            <p class="text-sm font-bold uppercase tracking-wide text-blue-700">{{ __('site.pages.about.official') }}</p>
            <h3 class="mt-2 text-2xl font-bold text-gray-950">{{ __('site.pages.about.sep') }}</h3>
            <ul class="mt-6 space-y-3">
                @foreach ($incorporaciones as $incorporacion)
                    <li class="rounded-xl border p-4 {{ $incorporacion['color'] }}">
                        <div class="flex flex-wrap items-center gap-2">
                            <span class="rounded-full px-3 py-1 text-xs font-extrabold uppercase tracking-wide {{ $incorporacion['badge'] }}">
                                {{ $incorporacion['nivel'] }}
                            </span>
                            <strong class="text-base">{{ $incorporacion['clave'] }}</strong>
                        </div>
                        <p class="mt-2 text-sm font-semibold leading-6">
                            {{ $incorporacion['nivel'] }}: {{ $incorporacion['clave'] }} - {{ __('site.pages.about.sep_school') }}
                        </p>
                    </li>
                @endforeach
            </ul>
        </aside>
    </div>

    <section class="bg-white rounded-xl shadow-md overflow-hidden">
        <div class="grid lg:grid-cols-2">
            <a href="{{ $imagenesNosotros['modelo']['url'] }}" class="glightbox block" data-gallery="modelo-educativo" data-title="Modelo educativo Colegio Discovery®">
                <x-imagen-seccion
                    :imagen="$imagenesNosotros['modelo']"
                    alt="Modelo educativo Colegio Discovery®"
                    class="h-72 w-full object-cover lg:h-full transition-transform hover:scale-[1.02]"
                    placeholder-class="h-72 lg:h-full"
                />
            </a>
            <div class="p-8 md:p-10">
                <p class="font-semibold uppercase tracking-wide text-sm text-blue-700">{{ __('site.pages.about.model') }}</p>
                <h2 class="text-3xl font-bold text-black mt-2">{{ __('site.pages.about.planning_title') }}</h2>
                <p class="text-gray-700 leading-7 mt-5">
                    {{ __('site.pages.about.planning_text') }}
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
            <p class="font-semibold uppercase tracking-wide text-sm text-blue-100">{{ __('site.pages.about.mission') }}</p>
            <h2 class="text-3xl font-bold mt-3">{{ __('site.pages.about.mission_text') }}</h2>
        </div>
        <div class="bg-green-500 text-white rounded-xl shadow-md p-8">
            <p class="font-semibold uppercase tracking-wide text-sm text-green-50">{{ __('site.pages.about.vision') }}</p>
            <h2 class="text-3xl font-bold mt-3">{{ __('site.pages.about.vision_text') }}</h2>
        </div>
    </section>

    <section class="bg-white rounded-xl shadow-md overflow-hidden">
        <div class="grid lg:grid-cols-[.95fr_1.05fr]">
            <div class="p-8 md:p-10 flex flex-col justify-center">
                <p class="font-semibold uppercase tracking-wide text-sm text-blue-700">{{ __('site.pages.about.academic_projection') }}</p>
                <h2 class="text-3xl md:text-4xl font-bold text-black mt-2">{{ __('site.pages.about.university_title') }}</h2>
                <p class="text-gray-700 leading-8 mt-5">
                    {{ __('site.pages.about.university_text') }}
                </p>

                <div class="mt-6 flex items-center gap-4 rounded-xl border border-yellow-200 bg-yellow-50 p-5">
                    <span class="shrink-0 text-3xl font-extrabold text-yellow-600">+70%</span>
                    <p class="font-semibold leading-6 text-gray-800">
                        {{ __('site.pages.about.scholarships') }}
                    </p>
                </div>

                <div class="grid sm:grid-cols-3 gap-3 mt-7">
                    @foreach (__('site.pages.about.university_steps') as $step)
                        <div class="rounded-lg border {{ ['border-blue-100 bg-blue-50 text-blue-900', 'border-green-100 bg-green-50 text-green-900', 'border-red-100 bg-red-50 text-red-900'][$loop->index] }} p-4">
                            <span class="block text-2xl font-extrabold {{ ['text-blue-700', 'text-green-600', 'text-red-600'][$loop->index] }}">0{{ $loop->iteration }}</span>
                            <p class="mt-2 text-sm font-semibold leading-6">{{ $step }}</p>
                        </div>
                    @endforeach
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
            <p class="font-semibold uppercase tracking-wide text-sm text-blue-700">{{ __('site.pages.about.timeline') }}</p>
            <h2 class="text-3xl md:text-4xl font-bold text-black mt-2">{{ __('site.pages.about.history_title') }}</h2>
            <p class="text-gray-600 mt-4 text-lg">
                {{ __('site.pages.about.history_text') }}
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
