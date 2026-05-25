@extends('layouts.app')

@section('content')

@php
    $colores = [
        'lime' => [
            'tab' => 'border-lime-300 bg-lime-50 text-lime-800',
            'active' => 'is-active border-lime-500 bg-lime-500 text-white shadow-lg',
            'pill' => 'bg-lime-100 text-lime-800',
            'button' => 'bg-lime-500 hover:bg-lime-600',
            'bar' => 'bg-lime-500',
        ],
        'red' => [
            'tab' => 'border-red-200 bg-red-50 text-red-800',
            'active' => 'is-active border-red-600 bg-red-600 text-white shadow-lg',
            'pill' => 'bg-red-100 text-red-800',
            'button' => 'bg-red-600 hover:bg-red-700',
            'bar' => 'bg-red-600',
        ],
        'blue' => [
            'tab' => 'border-blue-200 bg-blue-50 text-blue-800',
            'active' => 'is-active border-blue-700 bg-blue-700 text-white shadow-lg',
            'pill' => 'bg-blue-100 text-blue-800',
            'button' => 'bg-blue-700 hover:bg-blue-800',
            'bar' => 'bg-blue-700',
        ],
        'green' => [
            'tab' => 'border-green-200 bg-green-50 text-green-800',
            'active' => 'is-active border-green-600 bg-green-600 text-white shadow-lg',
            'pill' => 'bg-green-100 text-green-800',
            'button' => 'bg-green-600 hover:bg-green-700',
            'bar' => 'bg-green-600',
        ],
        'amber' => [
            'tab' => 'border-amber-200 bg-amber-50 text-amber-900',
            'active' => 'is-active border-amber-500 bg-amber-500 text-white shadow-lg',
            'pill' => 'bg-amber-100 text-amber-900',
            'button' => 'bg-amber-500 hover:bg-amber-600',
            'bar' => 'bg-amber-500',
        ],
        'sky' => [
            'tab' => 'border-sky-200 bg-sky-50 text-sky-900',
            'active' => 'is-active border-sky-500 bg-sky-500 text-white shadow-lg',
            'pill' => 'bg-sky-100 text-sky-900',
            'button' => 'bg-sky-500 hover:bg-sky-600',
            'bar' => 'bg-sky-500',
        ],
    ];
@endphp

<section class="space-y-10">
    <div class="grid gap-8 lg:grid-cols-[.8fr_1.2fr] lg:items-end">
        <div>
            <p class="text-sm font-bold uppercase tracking-wide text-blue-700">Oferta Educativa</p>
            <h1 class="mt-3 text-4xl font-extrabold text-black md:text-5xl">
                Elige una etapa y explora lo que la hace distinta
            </h1>
            <p class="mt-5 max-w-2xl text-lg leading-8 text-gray-600">
                Cada programa acompana una edad, una forma de aprender y una meta de crecimiento.
            </p>
        </div>

        <div class="grid grid-cols-2 gap-3 sm:grid-cols-3" role="tablist" aria-label="Niveles academicos">
            @foreach ($ofertaNiveles as $slug => $programa)
                @php($color = $colores[$programa['color']])
                <button
                    type="button"
                    class="{{ $loop->first ? $color['active'] : $color['tab'] }} group flex min-h-24 flex-col justify-between rounded-lg border p-4 text-left transition duration-300 hover:-translate-y-1 hover:shadow-md"
                    data-oferta-tab="{{ $slug }}"
                    role="tab"
                    aria-selected="{{ $loop->first ? 'true' : 'false' }}"
                >
                    <span class="text-sm font-bold">{{ $programa['titulo'] }}</span>
                    <span class="mt-3 text-xs font-semibold opacity-80">{{ $programa['edad'] }}</span>
                </button>
            @endforeach
        </div>
    </div>

    <div class="relative overflow-hidden rounded-xl bg-white shadow-xl">
        @foreach ($ofertaNiveles as $slug => $programa)
            @php($color = $colores[$programa['color']])
            <article
                class="{{ $loop->first ? '' : 'hidden' }} grid gap-0 lg:grid-cols-[1.05fr_.95fr]"
                data-oferta-panel="{{ $slug }}"
                role="tabpanel"
            >
                <div class="relative min-h-80 overflow-hidden bg-gray-100">
                    <x-imagen-seccion
                        :imagen="$programa['imagen']"
                        :alt="$programa['titulo']"
                        class="h-80 w-full object-cover transition duration-700 ease-out lg:h-full"
                        placeholder-class="min-h-80 lg:min-h-[520px]"
                    />
                    <div class="absolute left-0 top-0 h-full w-2 {{ $color['bar'] }}"></div>
                </div>

                <div class="flex flex-col justify-center p-7 md:p-10">
                    <div class="flex items-start justify-between gap-5">
                        <div>
                            <span class="inline-flex rounded-full px-3 py-1 text-xs font-bold {{ $color['pill'] }}">
                                {{ $programa['edad'] }}
                            </span>
                            <h2 class="mt-4 text-3xl font-extrabold text-black md:text-4xl">
                                {{ $programa['titulo'] }}
                            </h2>
                        </div>

                        @if ($programa['logo'])
                            <img
                                src="{{ $programa['logo'] }}"
                                alt="{{ $programa['titulo'] }}"
                                class="h-16 w-20 shrink-0 object-contain"
                                loading="lazy"
                            >
                        @endif
                    </div>

                    <p class="mt-4 text-xl font-bold text-gray-800">{{ $programa['subtitulo'] }}</p>
                    <p class="mt-4 leading-8 text-gray-600">{{ $programa['descripcion'] }}</p>

                    <div class="mt-7 grid gap-3 sm:grid-cols-3">
                        @foreach ($programa['puntos'] as $punto)
                            <div class="rounded-lg border border-gray-200 bg-gray-50 px-4 py-3 text-sm font-semibold text-gray-700">
                                {{ $punto }}
                            </div>
                        @endforeach
                    </div>

                    <div class="mt-8 flex flex-wrap items-center gap-4">
                        <a
                            href="{{ $programa['ruta'] }}"
                            class="inline-flex items-center justify-center rounded px-6 py-3 font-bold text-white transition {{ $color['button'] }}"
                        >
                            Ver programa
                        </a>
                        <a href="{{ route('contacto') }}" class="font-bold text-blue-700 underline decoration-blue-200 decoration-4 underline-offset-4 hover:text-blue-900">
                            Solicitar informes
                        </a>
                    </div>
                </div>
            </article>
        @endforeach
    </div>

    <section class="grid gap-4 md:grid-cols-2 lg:grid-cols-3">
        @foreach ($ofertaNiveles as $slug => $programa)
            @php($color = $colores[$programa['color']])
            <a
                href="{{ $programa['ruta'] }}"
                class="group rounded-lg border border-gray-200 bg-white p-5 shadow-sm transition duration-300 hover:-translate-y-1 hover:shadow-lg"
            >
                <span class="block h-1.5 w-16 rounded-full {{ $color['bar'] }}"></span>
                <h3 class="mt-4 text-xl font-extrabold text-black">{{ $programa['titulo'] }}</h3>
                <p class="mt-3 min-h-16 text-sm leading-6 text-gray-600">{{ $programa['subtitulo'] }}</p>
                <span class="mt-4 inline-flex text-sm font-bold text-blue-700 group-hover:text-blue-900">
                    Explorar nivel
                </span>
            </a>
        @endforeach
    </section>
</section>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const tabs = Array.from(document.querySelectorAll('[data-oferta-tab]'));
        const panels = Array.from(document.querySelectorAll('[data-oferta-panel]'));

        const colorClasses = {
            lime: ['border-lime-500', 'bg-lime-500', 'text-white', 'shadow-lg'],
            red: ['border-red-600', 'bg-red-600', 'text-white', 'shadow-lg'],
            blue: ['border-blue-700', 'bg-blue-700', 'text-white', 'shadow-lg'],
            green: ['border-green-600', 'bg-green-600', 'text-white', 'shadow-lg'],
            amber: ['border-amber-500', 'bg-amber-500', 'text-white', 'shadow-lg'],
            sky: ['border-sky-500', 'bg-sky-500', 'text-white', 'shadow-lg'],
        };

        const inactiveClasses = {
            lime: ['border-lime-300', 'bg-lime-50', 'text-lime-800'],
            red: ['border-red-200', 'bg-red-50', 'text-red-800'],
            blue: ['border-blue-200', 'bg-blue-50', 'text-blue-800'],
            green: ['border-green-200', 'bg-green-50', 'text-green-800'],
            amber: ['border-amber-200', 'bg-amber-50', 'text-amber-900'],
            sky: ['border-sky-200', 'bg-sky-50', 'text-sky-900'],
        };

        const getColor = (tab) => {
            return Object.keys(inactiveClasses).find((color) =>
                inactiveClasses[color].some((className) => tab.classList.contains(className)) ||
                colorClasses[color].some((className) => tab.classList.contains(className))
            ) || 'blue';
        };

        tabs.forEach((tab) => {
            tab.addEventListener('click', () => {
                const selected = tab.dataset.ofertaTab;

                tabs.forEach((item) => {
                    const color = getColor(item);
                    item.classList.remove('is-active', ...colorClasses[color], ...inactiveClasses[color]);
                    item.classList.add(...inactiveClasses[color]);
                    item.setAttribute('aria-selected', 'false');
                });

                const selectedColor = getColor(tab);
                tab.classList.remove(...inactiveClasses[selectedColor]);
                tab.classList.add('is-active', ...colorClasses[selectedColor]);
                tab.setAttribute('aria-selected', 'true');

                panels.forEach((panel) => {
                    panel.classList.toggle('hidden', panel.dataset.ofertaPanel !== selected);
                });
            });
        });
    });
</script>

@endsection
