@extends('layouts.app')

@section('content')

@php
    $totalListas = collect($listasUtiles ?? [])->flatten(1)->count();
    $niveles = array_keys($listasUtiles ?? []);
@endphp

<section class="mx-auto max-w-7xl space-y-10">
    <div class="overflow-hidden rounded-xl bg-blue-700 text-white shadow-lg">
        <div class="grid gap-8 p-8 md:p-12 lg:grid-cols-[1.1fr_.9fr] lg:items-end">
            <div>
                <p class="text-sm font-bold uppercase tracking-wide text-blue-50">Recursos escolares</p>
                <h1 class="mt-3 text-4xl font-extrabold md:text-5xl">Listas de útiles y calendario</h1>
                <p class="mt-5 max-w-3xl text-lg leading-8 text-blue-50">
                    Encuentra la lista correspondiente por nivel o grado y consulta el calendario escolar vigente.
                </p>
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div class="rounded-lg bg-white/90 p-4 text-black">
                    <span class="text-3xl font-extrabold">{{ $totalListas }}</span>
                    <p class="mt-1 text-sm font-bold uppercase text-blue-700">Listas disponibles</p>
                </div>
                <div class="rounded-lg bg-yellow-500 p-4 text-black">
                    <span class="text-3xl font-extrabold">{{ count($niveles) }}</span>
                    <p class="mt-1 text-sm font-bold uppercase">Niveles con PDF</p>
                </div>
            </div>
        </div>
    </div>

    <div class="grid gap-8 lg:grid-cols-[1.35fr_.65fr]">
        <section class="rounded-xl bg-white p-6 shadow-md md:p-8" data-recursos>
            <div class="flex flex-col gap-5 md:flex-row md:items-end md:justify-between">
                <div>
                    <p class="text-sm font-bold uppercase tracking-wide text-red-600">Listas en PDF</p>
                    <h2 class="mt-2 text-3xl font-extrabold text-black">Busca por nivel o grado</h2>
                    <p class="mt-3 text-gray-600">Abre el PDF en una nueva pestaña para consultarlo o descargarlo.</p>
                </div>

                <label class="block w-full md:max-w-xs">
                    <span class="mb-2 block text-sm font-bold text-gray-700">Buscar lista</span>
                    <input
                        type="search"
                        data-recursos-search
                        placeholder="Ej. 6 grado, primaria..."
                        class="w-full rounded border border-gray-300 p-3 focus:outline-none focus:ring-2 focus:ring-blue-500"
                    >
                </label>
            </div>

            @if (! empty($listasUtiles))
                <div class="mt-7 flex flex-wrap gap-2" role="tablist" aria-label="Filtrar listas por nivel">
                    <button
                        type="button"
                        data-recursos-filter="todos"
                        class="rounded border border-blue-700 bg-blue-700 px-4 py-2 text-sm font-bold text-white"
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

                <div class="mt-8 space-y-8">
                    @foreach ($listasUtiles as $nivel => $listas)
                        <div data-recursos-group="{{ Str::slug($nivel) }}">
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
                                        href="{{ $lista['url'] }}"
                                        target="_blank"
                                        rel="noopener"
                                        data-recursos-card
                                        data-recursos-nivel="{{ Str::slug($nivel) }}"
                                        data-recursos-text="{{ Str::lower($nivel . ' ' . $lista['grado'] . ' ' . $lista['titulo']) }}"
                                        class="group flex min-h-36 flex-col justify-between rounded-lg border border-gray-200 bg-gray-50 p-4 transition hover:-translate-y-1 hover:border-blue-600 hover:bg-blue-50 hover:shadow-md"
                                    >
                                        <div>
                                            <span class="inline-flex rounded-full bg-yellow-500 px-3 py-1 text-xs font-extrabold uppercase text-black">
                                                {{ $lista['grado'] }}
                                            </span>
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
            @if (! empty($calendarioEscolarUrl))
                <section class="overflow-hidden rounded-xl bg-white shadow-md">
                    <div class="p-6 md:p-8">
                        <p class="text-sm font-bold uppercase tracking-wide text-blue-700">Calendario 2025-2026</p>
                        <h2 class="mt-2 text-2xl font-extrabold text-black">Consulta el calendario escolar</h2>
                    </div>
                    <a href="{{ $calendarioEscolarUrl }}" target="_blank" rel="noopener" class="block bg-gray-50 p-3">
                        <img
                            src="{{ $calendarioEscolarUrl }}"
                            alt="Calendario escolar 2025-2026"
                            class="max-h-[520px] w-full rounded-lg object-contain"
                            loading="lazy"
                        >
                    </a>
                    <div class="px-6 pb-6 md:px-8">
                        <a href="{{ $calendarioEscolarUrl }}" target="_blank" rel="noopener" class="inline-flex rounded bg-red-600 px-5 py-3 text-sm font-extrabold text-white hover:bg-red-700">
                            Abrir calendario
                        </a>
                    </div>
                </section>
            @endif

            <section class="rounded-xl bg-white p-6 shadow-md md:p-8">
                <p class="text-sm font-bold uppercase tracking-wide text-blue-700">Cómo usarlo</p>
                <h2 class="mt-2 text-2xl font-extrabold text-black">Encuentra tu PDF en tres pasos</h2>
                <div class="mt-6 space-y-4">
                    <div class="flex gap-3">
                        <span class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-blue-700 text-sm font-extrabold text-white">1</span>
                        <p class="text-gray-700">Filtra por nivel educativo.</p>
                    </div>
                    <div class="flex gap-3">
                        <span class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-red-600 text-sm font-extrabold text-white">2</span>
                        <p class="text-gray-700">Busca el grado o nombre de la lista.</p>
                    </div>
                    <div class="flex gap-3">
                        <span class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-yellow-500 text-sm font-extrabold text-black">3</span>
                        <p class="text-gray-700">Abre el PDF para revisarlo o descargarlo.</p>
                    </div>
                </div>
            </section>
        </aside>
    </div>
</section>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const root = document.querySelector('[data-recursos]');
        if (!root) return;

        const search = root.querySelector('[data-recursos-search]');
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
            const term = (search?.value || '').trim().toLowerCase();
            let visibleCards = 0;

            cards.forEach((card) => {
                const matchesLevel = activeFilter === 'todos' || card.dataset.recursosNivel === activeFilter;
                const matchesTerm = !term || card.dataset.recursosText.includes(term);
                const visible = matchesLevel && matchesTerm;
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
            });
        });

        search?.addEventListener('input', applyFilters);
    });
</script>

@endsection
