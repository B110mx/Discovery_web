@extends('layouts.app')

@section('content')

{{-- Colores reutilizados por pestañas, ficha activa y tarjetas comparativas. --}}
@php
    $colores = [
        'lime' => [
            'chip' => 'bg-lime-100 text-lime-800',
            'button' => 'bg-lime-500 hover:bg-lime-600 text-black',
            'bar' => 'bg-lime-500',
            'ring' => 'ring-lime-300',
            'soft' => 'bg-lime-50 border-lime-200',
            'text' => 'text-lime-800',
        ],
        'red' => [
            'chip' => 'bg-red-100 text-red-800',
            'button' => 'bg-red-600 hover:bg-red-700 text-white',
            'bar' => 'bg-red-600',
            'ring' => 'ring-red-200',
            'soft' => 'bg-red-50 border-red-200',
            'text' => 'text-red-700',
        ],
        'blue' => [
            'chip' => 'bg-blue-100 text-blue-800',
            'button' => 'bg-blue-700 hover:bg-blue-800 text-white',
            'bar' => 'bg-blue-700',
            'ring' => 'ring-blue-200',
            'soft' => 'bg-blue-50 border-blue-200',
            'text' => 'text-blue-700',
        ],
        'green' => [
            'chip' => 'bg-green-100 text-green-800',
            'button' => 'bg-green-600 hover:bg-green-700 text-white',
            'bar' => 'bg-green-600',
            'ring' => 'ring-green-200',
            'soft' => 'bg-green-50 border-green-200',
            'text' => 'text-green-700',
        ],
        'amber' => [
            'chip' => 'bg-amber-100 text-amber-900',
            'button' => 'bg-amber-500 hover:bg-amber-600 text-black',
            'bar' => 'bg-amber-500',
            'ring' => 'ring-amber-200',
            'soft' => 'bg-amber-50 border-amber-200',
            'text' => 'text-amber-800',
        ],
        'sky' => [
            'chip' => 'bg-sky-100 text-sky-900',
            'button' => 'bg-sky-500 hover:bg-sky-600 text-white',
            'bar' => 'bg-sky-500',
            'ring' => 'ring-sky-200',
            'soft' => 'bg-sky-50 border-sky-200',
            'text' => 'text-sky-800',
        ],
    ];

    $primerSlug = array_key_first($ofertaNiveles);
@endphp

<section class="space-y-10">
    {{--
        Selector principal de niveles.
        En escritorio la altura es deliberadamente compacta para mostrar imagen,
        pestañas, información y botones sin desplazar la página.
    --}}
    <section id="oferta-hero" class="grid overflow-hidden rounded-lg bg-white shadow-xl lg:h-[608px] lg:grid-cols-[.85fr_1.15fr] xl:h-[589px]">
        <div class="relative min-h-60 bg-gray-100 lg:min-h-0">
            @foreach ($ofertaNiveles as $slug => $programa)
                @php
                    $color = $colores[$programa['color']] ?? $colores['blue'];
                @endphp
                <div
                    class="{{ $loop->first ? '' : 'hidden' }} h-full"
                    data-oferta-panel="{{ $slug }}"
                    aria-hidden="{{ $loop->first ? 'false' : 'true' }}"
                >
                    <x-imagen-seccion
                        :imagen="$programa['imagen']"
                        :alt="$programa['titulo']"
                        class="h-60 w-full object-cover md:h-64 lg:h-full"
                        placeholder-class="min-h-60 md:min-h-64 lg:h-full"
                    />
                    <span class="absolute left-5 top-5 inline-flex rounded-full px-3 py-1 text-xs font-bold shadow-sm {{ $color['chip'] }}">
                        {{ $programa['edad'] }}
                    </span>
                    <div class="absolute inset-x-0 bottom-0 bg-gradient-to-t from-gray-950/80 to-transparent p-5 text-white">
                        <h2 class="text-2xl font-extrabold md:text-3xl">{{ $programa['titulo'] }}</h2>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="flex min-h-0 flex-col justify-center p-4 md:p-5">
            <div>
                <p class="text-xs font-bold uppercase tracking-wide text-blue-700 md:text-sm">{{ $paginaOferta?->subtitulo ?? __('site.pages.offer.default_subtitle') }}</p>
                <h1 class="mt-1 text-2xl font-extrabold leading-tight text-gray-950 md:text-3xl lg:text-[2rem]">
                    {{ $paginaOferta?->titulo ?? __('site.pages.offer.default_title') }}
                </h1>
                <p class="mt-1 text-sm leading-5 text-gray-600 xl:text-[0.95rem]">
                    {{ $paginaOferta?->descripcion ?? __('site.pages.offer.default_text') }}
                </p>
            </div>

            <div class="mt-2 grid auto-rows-[56px] gap-2 sm:grid-cols-2" role="tablist" aria-label="{{ __('site.pages.offer.tabs_label') }}">
                @foreach ($ofertaNiveles as $slug => $programa)
                    @php
                        $color = $colores[$programa['color']] ?? $colores['blue'];
                    @endphp
                    <button
                        type="button"
                        class="group flex h-14 flex-col justify-between overflow-hidden rounded border border-gray-200 bg-white px-2 py-1.5 text-left shadow-sm transition duration-300 hover:border-blue-300 hover:shadow-md data-[active=true]:ring-2 {{ $color['ring'] }}"
                        data-oferta-tab="{{ $slug }}"
                        data-color="{{ $programa['color'] }}"
                        data-active="{{ $loop->first ? 'true' : 'false' }}"
                        role="tab"
                        aria-selected="{{ $loop->first ? 'true' : 'false' }}"
                    >
                        <span class="block h-1 w-9 shrink-0 rounded-full {{ $color['bar'] }}"></span>
                        <span class="block truncate text-[0.8rem] font-extrabold leading-4 text-gray-950">{{ $programa['titulo'] }}</span>
                        <span class="block truncate text-[0.68rem] font-semibold leading-3 text-gray-500">{{ $programa['edad'] }}</span>
                    </button>
                @endforeach
            </div>

            <div class="mt-3 min-h-0 border-t border-gray-100 pt-3">
                @foreach ($ofertaNiveles as $slug => $programa)
                    @php
                        $color = $colores[$programa['color']] ?? $colores['blue'];
                    @endphp
                    <article
                        class="{{ $loop->first ? '' : 'hidden' }}"
                        data-oferta-panel="{{ $slug }}"
                        role="tabpanel"
                    >
                    <div class="flex items-start justify-between gap-4">
                        <div>
                            <p class="text-xs font-bold uppercase tracking-wide {{ $color['text'] }}">{{ $programa['edad'] }}</p>
                            <h2 class="text-2xl font-extrabold leading-tight text-gray-950 lg:text-[1.4rem]">
                                {{ $programa['titulo'] }}
                            </h2>
                        </div>

                        @if ($programa['logo'])
                            <img
                                src="{{ $programa['logo'] }}"
                                alt="{{ $programa['titulo'] }}"
                                class="h-10 w-14 shrink-0 object-contain md:h-12 md:w-16"
                                loading="lazy"
                            >
                        @endif
                    </div>

                    <p class="mt-1 text-sm font-extrabold leading-5 text-gray-800">{{ $programa['subtitulo'] }}</p>
                    <p class="mt-1 text-sm leading-5 text-gray-600">{{ $programa['descripcion'] }}</p>

                    <div class="mt-2 grid gap-2 sm:grid-cols-3">
                        @foreach ($programa['puntos'] as $punto)
                            <div class="flex items-center gap-2 rounded border px-2.5 py-1.5 {{ $color['soft'] }}">
                                <span class="h-2 w-2 shrink-0 rounded-full {{ $color['bar'] }}"></span>
                                <span class="text-xs font-bold leading-4 text-gray-800">{{ $punto }}</span>
                            </div>
                        @endforeach
                    </div>

                    <div class="mt-3 flex flex-col gap-2 sm:flex-row">
                        <a
                            href="{{ $programa['ruta'] }}"
                            class="inline-flex items-center justify-center rounded px-4 py-2 text-sm font-bold transition {{ $color['button'] }}"
                        >
                            {{ __('site.pages.offer.view_program') }}
                        </a>
                        <a href="{{ route('contacto') }}" class="inline-flex items-center justify-center rounded border border-blue-700 px-4 py-2 text-sm font-bold text-blue-700 transition hover:bg-blue-50">
                            {{ __('site.pages.offer.request_info') }}
                        </a>
                    </div>
                    </article>
                @endforeach
            </div>
        </div>
    </section>

    <section class="grid gap-5 lg:grid-cols-3">
        @foreach (__('site.pages.offer.cards') as $card)
            <div class="rounded-lg {{ ['bg-gray-950 text-white', 'bg-blue-700 text-white', 'bg-white'][$loop->index] }} p-6 shadow-md">
                <p class="text-sm font-bold uppercase tracking-wide {{ ['text-yellow-500', 'text-blue-100', 'text-red-600'][$loop->index] }}">{{ $card['eyebrow'] }}</p>
                <h2 class="mt-3 text-2xl font-extrabold {{ $loop->index === 2 ? 'text-gray-950' : '' }}">{{ $card['title'] }}</h2>
                <p class="mt-4 leading-7 {{ ['text-gray-200', 'text-blue-50', 'text-gray-600'][$loop->index] }}">
                    {{ $card['text'] }}
                </p>
            </div>
        @endforeach
    </section>

    <section class="overflow-hidden rounded-xl bg-white shadow-md" data-university-linkage>
        <div class="grid gap-8 p-6 md:p-10 lg:grid-cols-[.8fr_1.2fr] lg:items-end">
            <div>
                <p class="text-sm font-semibold uppercase tracking-wide text-blue-700">{{ __('site.pages.about.academic_projection') }}</p>
                <h2 class="mt-2 text-3xl font-bold text-black md:text-4xl">{{ __('site.pages.about.university_title') }}</h2>
                <p class="mt-5 leading-8 text-gray-700">
                    {{ __('site.pages.about.university_text') }}
                </p>

                <div class="mt-6 flex items-center gap-4 rounded-xl border border-yellow-200 bg-yellow-50 p-5">
                    <span class="shrink-0 text-3xl font-extrabold text-yellow-600">+70%</span>
                    <p class="font-semibold leading-6 text-gray-800">
                        {{ __('site.pages.about.scholarships') }}
                    </p>
                </div>
            </div>

            <div>
                <p class="mb-4 text-sm font-semibold leading-6 text-gray-600">
                    {{ __('site.pages.offer.university_hover_hint') }}
                </p>
                <div class="grid gap-3 sm:grid-cols-3">
                    @foreach (__('site.pages.about.university_steps') as $step)
                        <div class="rounded-lg border {{ ['border-blue-100 bg-blue-50 text-blue-900', 'border-green-100 bg-green-50 text-green-900', 'border-red-100 bg-red-50 text-red-900'][$loop->index] }} p-4">
                            <span class="block text-2xl font-extrabold {{ ['text-blue-700', 'text-green-600', 'text-red-600'][$loop->index] }}">0{{ $loop->iteration }}</span>
                            <p class="mt-2 text-sm font-semibold leading-6">{{ $step }}</p>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <div class="bg-gray-100 p-4 md:p-6">
            <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
                @foreach ($universidadesVinculacion as $universidad)
                    <a
                        href="{{ $universidad['sitio'] }}"
                        target="_blank"
                        rel="noopener noreferrer"
                        class="university-card group relative block h-80 overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm focus:outline-none focus-visible:ring-4 focus-visible:ring-blue-300"
                        aria-label="{{ __('site.pages.offer.university_visit', ['university' => $universidad['nombre']]) }}"
                    >
                        <div class="flex h-full items-start justify-center px-5 pb-20 pt-7">
                            <img
                                src="{{ $universidad['logo'] }}"
                                alt="{{ $universidad['nombre'] }}"
                                class="max-h-24 w-full object-contain transition duration-300 group-hover:scale-95"
                                loading="lazy"
                            >
                        </div>
                        <div class="university-card__details absolute inset-x-0 bottom-0 z-10 min-h-[4.75rem] bg-slate-950 px-5 py-4 text-white">
                            <div class="flex items-start justify-between gap-3">
                                <h3 class="text-base font-extrabold leading-6">{{ $universidad['nombre'] }}</h3>
                                <span aria-hidden="true" class="mt-0.5 shrink-0 text-lg">&#8599;</span>
                            </div>
                            <div class="university-card__benefits mt-3">
                                <p class="text-sm font-medium leading-5 text-blue-100">{{ $universidad['resumen'] }}</p>
                                <ul class="mt-3 space-y-2 text-xs leading-5 text-gray-200">
                                    @foreach ($universidad['beneficios'] as $beneficio)
                                        <li class="flex gap-2"><span class="text-yellow-400" aria-hidden="true">&#8226;</span><span>{{ $beneficio }}</span></li>
                                    @endforeach
                                </ul>
                                @if ($universidad['convocatoria'] ?? false)
                                    <p class="mt-3 inline-flex rounded-full bg-yellow-400/15 px-2.5 py-1 text-[11px] font-bold uppercase tracking-wide text-yellow-300">
                                        {{ __('site.pages.offer.university_review_call') }}
                                    </p>
                                @endif
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>
            <p class="mt-5 text-center text-xs font-medium leading-5 text-gray-600">
                {{ __('site.pages.offer.university_validity_note') }}
            </p>
        </div>
    </section>

    <section class="overflow-hidden rounded-2xl bg-gradient-to-br from-slate-50 via-white to-blue-50/70 px-5 py-8 ring-1 ring-slate-200 sm:px-8 sm:py-10">
        <div class="mb-8 flex flex-col gap-4 md:flex-row md:items-end md:justify-between">
            <div>
                <p class="text-sm font-bold uppercase tracking-wide text-blue-700">{{ __('site.pages.offer.comparison_eyebrow') }}</p>
                <h2 class="mt-2 text-3xl font-extrabold tracking-tight text-gray-950 sm:text-4xl">{{ __('site.pages.offer.comparison_title') }}</h2>
                <p class="mt-3 max-w-2xl text-base leading-7 text-gray-600">
                    {{ __('site.pages.offer.comparison_text') }}
                </p>
            </div>
            <a href="{{ route('contacto') }}" class="group inline-flex w-fit items-center gap-2 rounded-full bg-blue-700 px-5 py-3 text-sm font-bold text-white shadow-sm transition hover:-translate-y-0.5 hover:bg-blue-800 hover:shadow-md">
                {{ __('site.pages.offer.schedule_info') }}
                <svg class="h-4 w-4 transition-transform group-hover:translate-x-1" viewBox="0 0 20 20" fill="none" aria-hidden="true">
                    <path d="M4 10h12m-5-5 5 5-5 5" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
            </a>
        </div>

        <div class="grid gap-5 sm:grid-cols-2 xl:grid-cols-4">
            @foreach ($ofertaNiveles as $slug => $programa)
                @php
                    $color = $colores[$programa['color']] ?? $colores['blue'];
                    $esLogo = in_array($slug, ['ib-en-discovery', 'certificacion-de-ingles']);
                @endphp
                <a
                    href="{{ $programa['ruta'] }}"
                    class="group flex min-h-full flex-col overflow-hidden rounded-2xl border border-gray-200/80 bg-white shadow-sm transition duration-300 hover:-translate-y-1 hover:border-blue-200 hover:shadow-xl"
                >
                    <div class="relative h-44 overflow-hidden {{ $esLogo ? 'bg-slate-50' : 'bg-slate-100' }}">
                        @if (! empty($programa['imagen']['url']))
                            <img
                                src="{{ $programa['imagen']['url'] }}"
                                alt="{{ $programa['titulo'] }}"
                                class="h-full w-full transition duration-500 group-hover:scale-105 {{ $esLogo ? 'object-contain p-8' : 'object-cover' }}"
                                loading="lazy"
                            >
                        @else
                            <div class="flex h-full flex-col items-center justify-center bg-gradient-to-br from-slate-50 to-blue-50 px-6 text-center">
                                <span class="flex h-11 w-11 items-center justify-center rounded-full bg-white text-blue-700 shadow-sm ring-1 ring-blue-100">
                                    <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                                        <path d="M4 7.5A2.5 2.5 0 0 1 6.5 5h11A2.5 2.5 0 0 1 20 7.5v9a2.5 2.5 0 0 1-2.5 2.5h-11A2.5 2.5 0 0 1 4 16.5v-9Z" stroke="currentColor" stroke-width="1.8"/>
                                        <path d="m5 16 4-4 3 3 2-2 5 4.5M15.5 9.5h.01" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>
                                    </svg>
                                </span>
                                <p class="mt-3 text-xs font-bold uppercase tracking-wider text-slate-500">{{ __('site.pages.offer.photo_soon') }}</p>
                            </div>
                        @endif

                        <span class="absolute left-4 top-4 rounded-full bg-white/95 px-3 py-1.5 text-xs font-bold shadow-sm backdrop-blur {{ $color['text'] }}">
                            {{ $programa['edad'] }}
                        </span>
                    </div>

                    <div class="flex flex-1 flex-col p-5">
                        <span class="block h-1.5 w-12 rounded-full {{ $color['bar'] }}"></span>
                        <h3 class="mt-4 text-xl font-extrabold leading-tight text-gray-950">{{ $programa['titulo'] }}</h3>
                        <p class="mt-3 flex-1 text-sm leading-6 text-gray-600">{{ $programa['subtitulo'] }}</p>
                        <div class="mt-5 flex items-center justify-between border-t border-gray-100 pt-4">
                            <span class="text-sm font-bold text-blue-700 transition group-hover:text-blue-900">
                                {{ __('site.pages.offer.explore_level') }}
                            </span>
                            <span class="flex h-8 w-8 items-center justify-center rounded-full bg-blue-50 text-blue-700 transition group-hover:bg-blue-700 group-hover:text-white">
                                <svg class="h-4 w-4 transition-transform group-hover:translate-x-0.5" viewBox="0 0 20 20" fill="none" aria-hidden="true">
                                    <path d="M4 10h12m-5-5 5 5-5 5" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                            </span>
                        </div>
                    </div>
                </a>
            @endforeach
        </div>
    </section>
</section>

<script>
    // Sincroniza los dos paneles que comparten data-oferta-panel:
    // la imagen izquierda y la ficha descriptiva derecha.
    document.addEventListener('DOMContentLoaded', () => {
        const tabs = Array.from(document.querySelectorAll('[data-oferta-tab]'));
        const panels = Array.from(document.querySelectorAll('[data-oferta-panel]'));

        tabs.forEach((tab) => {
            tab.addEventListener('click', () => {
                const selected = tab.dataset.ofertaTab;

                tabs.forEach((item) => {
                    const isActive = item === tab;
                    item.dataset.active = isActive ? 'true' : 'false';
                    item.setAttribute('aria-selected', isActive ? 'true' : 'false');
                });

                panels.forEach((panel) => {
                    panel.classList.toggle('hidden', panel.dataset.ofertaPanel !== selected);
                });

                document.querySelector('#oferta-hero')?.scrollIntoView({
                    behavior: 'smooth',
                    block: 'center',
                });
            });
        });
    });
</script>

@if (! empty($videosPromocionales))
    <x-videos-promocionales :videos="$videosPromocionales" />
@endif

@endsection
