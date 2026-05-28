@extends('layouts.app')

@section('content')

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

<section class="space-y-12">
    <section class="grid overflow-hidden rounded-lg bg-white shadow-xl lg:min-h-[calc(100vh-12rem)] lg:grid-cols-[.9fr_1.1fr] xl:min-h-[calc(100vh-11rem)]">
        <div class="relative min-h-72 bg-gray-100 lg:min-h-0">
            @foreach ($ofertaNiveles as $slug => $programa)
                @php($color = $colores[$programa['color']] ?? $colores['blue'])
                <div
                    class="{{ $loop->first ? '' : 'hidden' }} h-full"
                    data-oferta-panel="{{ $slug }}"
                    aria-hidden="{{ $loop->first ? 'false' : 'true' }}"
                >
                    <x-imagen-seccion
                        :imagen="$programa['imagen']"
                        :alt="$programa['titulo']"
                        class="h-72 w-full object-cover lg:h-full"
                        placeholder-class="min-h-72 lg:h-full"
                    />
                    <span class="absolute left-5 top-5 inline-flex rounded-full px-3 py-1 text-xs font-bold shadow-sm {{ $color['chip'] }}">
                        {{ $programa['edad'] }}
                    </span>
                    <div class="absolute inset-x-0 bottom-0 bg-gradient-to-t from-gray-950/80 to-transparent p-6 text-white">
                        <h2 class="text-3xl font-extrabold md:text-4xl">{{ $programa['titulo'] }}</h2>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="flex flex-col justify-center p-5 md:p-6 lg:p-7">
            <div>
                <p class="text-sm font-bold uppercase tracking-wide text-blue-700">{{ $paginaOferta?->subtitulo ?? 'Oferta Educativa' }}</p>
                <h1 class="mt-2 text-3xl font-extrabold leading-tight text-gray-950 md:text-4xl lg:text-[2.35rem]">
                    {{ $paginaOferta?->titulo ?? 'Una ruta académica para cada etapa' }}
                </h1>
                <p class="mt-3 leading-7 text-gray-600 lg:text-sm lg:leading-6 xl:text-base xl:leading-7">
                    {{ $paginaOferta?->descripcion ?? 'Explora niveles, enfoques y experiencias de aprendizaje para encontrar el programa que mejor acompaña a tu familia.' }}
                </p>
            </div>

            <div class="mt-4 grid gap-2 sm:grid-cols-2" role="tablist" aria-label="Niveles académicos">
                @foreach ($ofertaNiveles as $slug => $programa)
                    @php($color = $colores[$programa['color']] ?? $colores['blue'])
                    <button
                        type="button"
                        class="group rounded border border-gray-200 bg-white p-2.5 text-left shadow-sm transition duration-300 hover:border-blue-300 hover:shadow-md data-[active=true]:ring-4 {{ $color['ring'] }}"
                        data-oferta-tab="{{ $slug }}"
                        data-color="{{ $programa['color'] }}"
                        data-active="{{ $loop->first ? 'true' : 'false' }}"
                        role="tab"
                        aria-selected="{{ $loop->first ? 'true' : 'false' }}"
                    >
                        <span class="block h-1 w-10 rounded-full {{ $color['bar'] }}"></span>
                        <span class="mt-2 block text-sm font-extrabold text-gray-950">{{ $programa['titulo'] }}</span>
                        <span class="mt-1 block text-xs font-semibold text-gray-500">{{ $programa['edad'] }}</span>
                    </button>
                @endforeach
            </div>

            <div class="mt-5 border-t border-gray-100 pt-5">
                @foreach ($ofertaNiveles as $slug => $programa)
                    @php($color = $colores[$programa['color']] ?? $colores['blue'])
                    <article
                        class="{{ $loop->first ? '' : 'hidden' }}"
                        data-oferta-panel="{{ $slug }}"
                        role="tabpanel"
                    >
                    <div class="flex items-start justify-between gap-4">
                        <div>
                            <p class="text-sm font-bold uppercase tracking-wide {{ $color['text'] }}">{{ $programa['edad'] }}</p>
                            <h2 class="mt-1 text-2xl font-extrabold leading-tight text-gray-950 md:text-3xl lg:text-[1.7rem]">
                                {{ $programa['titulo'] }}
                            </h2>
                        </div>

                        @if ($programa['logo'])
                            <img
                                src="{{ $programa['logo'] }}"
                                alt="{{ $programa['titulo'] }}"
                                class="h-12 w-16 shrink-0 object-contain md:h-14 md:w-20"
                                loading="lazy"
                            >
                        @endif
                    </div>

                    <p class="mt-3 text-lg font-extrabold leading-7 text-gray-800 lg:text-base lg:leading-6 xl:text-lg xl:leading-7">{{ $programa['subtitulo'] }}</p>
                    <p class="mt-3 leading-7 text-gray-600 lg:text-sm lg:leading-6 xl:text-base xl:leading-7">{{ $programa['descripcion'] }}</p>

                    <div class="mt-4 grid gap-2 sm:grid-cols-3">
                        @foreach ($programa['puntos'] as $punto)
                            <div class="flex items-center gap-2 rounded border px-3 py-2 {{ $color['soft'] }}">
                                <span class="h-2.5 w-2.5 shrink-0 rounded-full {{ $color['bar'] }}"></span>
                                <span class="text-xs font-bold leading-5 text-gray-800 md:text-sm">{{ $punto }}</span>
                            </div>
                        @endforeach
                    </div>

                    <div class="mt-5 flex flex-col gap-3 sm:flex-row">
                        <a
                            href="{{ $programa['ruta'] }}"
                            class="inline-flex items-center justify-center rounded px-5 py-2.5 font-bold transition {{ $color['button'] }}"
                        >
                            Ver programa
                        </a>
                        <a href="{{ route('contacto') }}" class="inline-flex items-center justify-center rounded border border-blue-700 px-5 py-2.5 font-bold text-blue-700 transition hover:bg-blue-50">
                            Solicitar informes
                        </a>
                    </div>
                    </article>
                @endforeach
            </div>
        </div>
    </section>

    <section class="grid gap-5 lg:grid-cols-3">
        <div class="rounded-lg bg-gray-950 p-6 text-white shadow-md">
            <p class="text-sm font-bold uppercase tracking-wide text-yellow-500">Continuidad</p>
            <h2 class="mt-3 text-2xl font-extrabold">De preescolar a bachillerato</h2>
            <p class="mt-4 leading-7 text-gray-200">
                Una misma comunidad acompaña el crecimiento académico, emocional y social en cada etapa.
            </p>
        </div>

        <div class="rounded-lg bg-blue-700 p-6 text-white shadow-md">
            <p class="text-sm font-bold uppercase tracking-wide text-blue-100">Acompañamiento</p>
            <h2 class="mt-3 text-2xl font-extrabold">Grupos cercanos y seguimiento</h2>
            <p class="mt-4 leading-7 text-blue-50">
                Las familias encuentran rutas claras, comunicación constante y atención personalizada.
            </p>
        </div>

        <div class="rounded-lg bg-white p-6 shadow-md">
            <p class="text-sm font-bold uppercase tracking-wide text-red-600">Visión internacional</p>
            <h2 class="mt-3 text-2xl font-extrabold text-gray-950">Idiomas, IB y proyectos</h2>
            <p class="mt-4 leading-7 text-gray-600">
                Los programas integran pensamiento crítico, colaboración y experiencias conectadas con el mundo.
            </p>
        </div>
    </section>

    <section>
        <div class="mb-6 flex flex-col gap-2 md:flex-row md:items-end md:justify-between">
            <div>
                <p class="text-sm font-bold uppercase tracking-wide text-blue-700">Comparativa rápida</p>
                <h2 class="mt-2 text-3xl font-extrabold text-gray-950">Explora por etapa</h2>
            </div>
            <a href="{{ route('contacto') }}" class="w-fit font-bold text-blue-700 underline decoration-blue-200 decoration-4 underline-offset-4 hover:text-blue-900">
                Agendar informes
            </a>
        </div>

        <div class="grid gap-4 md:grid-cols-2 xl:grid-cols-3">
            @foreach ($ofertaNiveles as $slug => $programa)
                @php($color = $colores[$programa['color']] ?? $colores['blue'])
                <a
                    href="{{ $programa['ruta'] }}"
                    class="group overflow-hidden rounded-lg border border-gray-200 bg-white shadow-sm transition duration-300 hover:-translate-y-1 hover:shadow-lg"
                >
                    <div class="grid grid-cols-[.42fr_.58fr]">
                        <x-imagen-seccion
                            :imagen="$programa['imagen']"
                            :alt="$programa['titulo']"
                            class="h-full min-h-40 w-full object-cover"
                            placeholder-class="min-h-40"
                        />
                        <div class="p-5">
                            <span class="block h-1.5 w-14 rounded-full {{ $color['bar'] }}"></span>
                            <h3 class="mt-4 text-xl font-extrabold text-gray-950">{{ $programa['titulo'] }}</h3>
                            <p class="mt-2 text-sm font-semibold {{ $color['text'] }}">{{ $programa['edad'] }}</p>
                            <p class="mt-3 text-sm leading-6 text-gray-600">{{ $programa['subtitulo'] }}</p>
                            <span class="mt-4 inline-flex text-sm font-bold text-blue-700 group-hover:text-blue-900">
                                Explorar nivel
                            </span>
                        </div>
                    </div>
                </a>
            @endforeach
        </div>
    </section>
</section>

<script>
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
            });
        });
    });
</script>

@endsection
