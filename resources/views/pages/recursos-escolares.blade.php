@extends('layouts.app')

@section('content')

{{-- $listasUtiles ya llega agrupado por nivel desde el panel o desde la carpeta. --}}
<section
    class="mx-auto max-w-7xl space-y-10"
    data-recursos
>
    <div class="overflow-hidden rounded-xl bg-blue-700 text-white shadow-lg">
        <div class="grid gap-8 p-8 md:p-12 lg:grid-cols-[.9fr_1.1fr] lg:items-end">
            <div>
                <p class="text-sm font-bold uppercase tracking-wide text-blue-50">Recursos escolares</p>
                <h1 class="mt-3 text-4xl font-extrabold md:text-5xl">Listas de útiles y calendario</h1>
                <p class="mt-5 max-w-3xl text-lg leading-8 text-blue-50">
                    Encuentra la lista correspondiente por nivel o grado y consulta el calendario escolar vigente.
                </p>
            </div>

            <div class="rounded-xl bg-white p-5 text-black shadow-lg">
                <p class="text-sm font-bold uppercase tracking-wide text-blue-700">Cómo usarlo</p>
                <h2 class="mt-2 text-2xl font-extrabold text-black">Encuentra tu PDF</h2>
                <div class="mt-4 grid gap-3 text-sm text-gray-700 md:grid-cols-3">
                    <div class="flex gap-2">
                        <span class="flex h-7 w-7 shrink-0 items-center justify-center rounded-full bg-blue-700 text-xs font-extrabold text-white">1</span>
                        <p>Elige el nivel educativo.</p>
                    </div>
                    <div class="flex gap-2">
                        <span class="flex h-7 w-7 shrink-0 items-center justify-center rounded-full bg-red-600 text-xs font-extrabold text-white">2</span>
                        <p>Ubica el grado correspondiente.</p>
                    </div>
                    <div class="flex gap-2">
                        <span class="flex h-7 w-7 shrink-0 items-center justify-center rounded-full bg-yellow-500 text-xs font-extrabold text-black">3</span>
                        <p>Abre el PDF para revisarlo.</p>
                    </div>
                </div>
                @if (! empty($listasUtiles))
                    <div class="mt-5 flex flex-wrap gap-2" role="tablist" aria-label="Filtrar listas por nivel">
                        <button
                            type="button"
                            data-recursos-filter="todos"
                            class="rounded border border-blue-700 bg-blue-700 px-4 py-2 text-sm font-bold text-white transition"
                            aria-pressed="true"
                        >
                            Todos
                        </button>
                        @foreach ($listasUtiles as $nivel => $listas)
                            <button
                                type="button"
                                data-recursos-filter="{{ Str::slug($nivel) }}"
                                class="rounded border border-gray-200 bg-gray-50 px-4 py-2 text-sm font-bold text-gray-700 transition hover:border-blue-600 hover:text-blue-700"
                                aria-pressed="false"
                            >
                                {{ $nivel }} <span class="ml-1 text-xs opacity-70">({{ count($listas) }})</span>
                            </button>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>

    <div class="grid gap-8 lg:grid-cols-[1.35fr_.65fr]">
        <section id="listas-recursos" class="rounded-xl bg-white p-6 shadow-md md:p-8">
            <div class="flex flex-col gap-2">
                <div>
                    <p class="text-sm font-bold uppercase tracking-wide text-red-600">Listas en PDF</p>
                    <h2 class="mt-2 text-3xl font-extrabold text-black">Listas por nivel educativo</h2>
                    <p class="mt-3 text-gray-600">Abre el PDF en una nueva pestaña para consultarlo o descargarlo.</p>
                </div>

            </div>

            @if (! empty($listasUtiles))
                <div class="mt-8 space-y-8">
                    @foreach ($listasUtiles as $nivel => $listas)
                        @php
                            $grupoNivelSlug = Str::slug($nivel);
                        @endphp
                        <div data-recursos-group="{{ $grupoNivelSlug }}">
                            <div class="mb-4 flex items-center justify-between gap-4">
                                <div>
                                    <h3 class="text-xl font-extrabold text-black">{{ $nivel }}</h3>
                                    <p class="text-sm text-gray-600">{{ count($listas) }} listas disponibles</p>
                                </div>
                                <span class="h-1.5 w-16 rounded-full {{ $loop->iteration % 2 === 0 ? 'bg-red-600' : 'bg-blue-700' }}"></span>
                            </div>

                            <div class="grid gap-3 sm:grid-cols-2 xl:grid-cols-3">
                                @foreach ($listas as $lista)
                                    <a
                                        href="{{ $lista['url'] ?? '' }}"
                                        target="_blank"
                                        rel="noopener"
                                        data-recursos-card
                                        data-recursos-nivel="{{ $grupoNivelSlug }}"
                                        class="group flex min-h-36 flex-col justify-between rounded-lg border border-gray-200 bg-gray-50 p-4 transition hover:-translate-y-1 hover:border-blue-600 hover:bg-blue-50 hover:shadow-md"
                                    >
                                        <div>
                                            <span class="inline-flex rounded-full bg-yellow-500 px-3 py-1 text-xs font-extrabold uppercase text-black">
                                                {{ $lista['grado'] }}
                                            </span>
                                            @if (! empty($lista['ciclo']))
                                                <span class="ml-2 inline-flex rounded-full bg-white px-3 py-1 text-xs font-bold text-gray-600 ring-1 ring-gray-200">
                                                    Ciclo {{ $lista['ciclo'] }}
                                                </span>
                                            @endif
                                            <h4 class="mt-4 text-lg font-extrabold leading-snug text-black">{{ $lista['titulo'] }}</h4>
                                        </div>
                                        <span class="mt-5 inline-flex items-center text-sm font-extrabold text-red-600 group-hover:underline">
                                            Abrir PDF
                                        </span>
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="mt-8 hidden rounded-lg border border-dashed border-gray-300 p-6 text-center text-gray-600" data-recursos-empty>
                    No encontramos una lista con ese filtro.
                </div>
            @else
                <div class="mt-8 rounded-lg border border-dashed border-gray-300 p-6 text-gray-600">
                    Aún no hay listas PDF disponibles.
                </div>
            @endif
        </section>

        <aside class="space-y-6">
            <section class="overflow-hidden rounded-xl bg-white shadow-md">
                <div class="bg-[#5263ff] p-5">
                    <img
                        src="{{ asset('images/cometa-logo.svg') }}"
                        alt="Cometa"
                        class="h-14 w-auto"
                        loading="lazy"
                    >
                </div>
                <div class="p-6 md:p-8">
                    <p class="text-sm font-bold uppercase tracking-wide text-blue-700">Portal de pagos</p>
                    <h2 class="mt-2 text-2xl font-extrabold text-black">Acceso a Cometa</h2>
                    <p class="mt-3 text-sm font-semibold leading-6 text-gray-700">
                        Para realizar el pedido y pago de los utiles escolares ingresa a:
                    </p>
                    <a
                        href="https://portal.getcometa.com/"
                        target="_blank"
                        rel="noopener"
                        class="mt-5 inline-flex rounded bg-red-600 px-5 py-3 text-sm font-extrabold text-white hover:bg-red-700"
                    >
                        Abrir Cometa
                    </a>
                    <div class="mt-4 rounded-lg border border-yellow-300 bg-yellow-50 p-4 text-sm font-semibold leading-6 text-yellow-900">
                        Si eres padre de familia o tutor de nuevo ingreso, comunicate con Cobranza antes de acceder para que puedan habilitarte sin problema.
                    </div>
                </div>
            </section>

            @if (! empty($calendarioEscolar['url']))
                <section class="overflow-hidden rounded-xl bg-white shadow-md">
                    <div class="p-6 md:p-8">
                        <p class="text-sm font-bold uppercase tracking-wide text-blue-700">Calendario 2025-2026</p>
                        <h2 class="mt-2 text-2xl font-extrabold text-black">Consulta el calendario escolar</h2>
                    </div>
                    <a href="{{ $calendarioEscolar['url'] }}" target="_blank" rel="noopener" class="block bg-gray-50 p-3">
                        <img
                            src="{{ $calendarioEscolar['url'] }}"
                            alt="Calendario escolar 2025-2026"
                            class="max-h-[520px] w-full rounded-lg object-contain"
                            loading="lazy"
                        >
                    </a>
                    <div class="px-6 pb-6 md:px-8">
                        <a href="{{ $calendarioEscolar['url'] }}" target="_blank" rel="noopener" class="inline-flex rounded bg-red-600 px-5 py-3 text-sm font-extrabold text-white hover:bg-red-700">
                            Abrir calendario
                        </a>
                    </div>
                </section>
            @endif
        </aside>
    </div>
</section>


<script>
    document.addEventListener('DOMContentLoaded', () => {
        const root = document.querySelector('[data-recursos]');
        if (!root) return;

        const filters = Array.from(root.querySelectorAll('[data-recursos-filter]'));
        const cards = Array.from(root.querySelectorAll('[data-recursos-card]'));
        const groups = Array.from(root.querySelectorAll('[data-recursos-group]'));
        const empty = root.querySelector('[data-recursos-empty]');
        let activeFilter = 'todos';

        const setActiveButton = () => {
            filters.forEach((button) => {
                const active = button.dataset.recursosFilter === activeFilter;
                button.setAttribute('aria-pressed', active ? 'true' : 'false');
                button.classList.toggle('bg-blue-700', active);
                button.classList.toggle('text-white', active);
                button.classList.toggle('border-blue-700', active);
                button.classList.toggle('bg-gray-50', !active);
                button.classList.toggle('text-gray-700', !active);
                button.classList.toggle('border-gray-200', !active);
            });
        };

        const applyFilters = () => {
            let visibleCards = 0;

            cards.forEach((card) => {
                const matchesLevel = activeFilter === 'todos' || card.dataset.recursosNivel === activeFilter;
                const visible = matchesLevel;
                card.classList.toggle('hidden', !visible);
                if (visible) visibleCards += 1;
            });

            groups.forEach((group) => {
                const groupCards = Array.from(group.querySelectorAll('[data-recursos-card]'));
                group.classList.toggle('hidden', groupCards.every((card) => card.classList.contains('hidden')));
            });

            if (empty) empty.classList.toggle('hidden', visibleCards > 0);
            setActiveButton();
        };

        filters.forEach((button) => {
            button.addEventListener('click', () => {
                activeFilter = button.dataset.recursosFilter;
                applyFilters();
                document.querySelector('#listas-recursos')?.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start',
                });
            });
        });
    });
</script>


@endsection
