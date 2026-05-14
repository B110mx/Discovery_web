@extends('layouts.app')

@section('content')

@php
    $imagenPrincipal = $galeria[0]['url'] ?? 'https://images.unsplash.com/photo-1509062522246-3755977927d7?auto=format&fit=crop&w=1000&q=80';
@endphp

<section class="max-w-6xl mx-auto">
    <div class="bg-blue-700 text-white rounded-xl shadow-lg overflow-hidden">
        <div class="grid md:grid-cols-2">
            <div class="p-8 md:p-12">
                <p class="font-semibold uppercase tracking-wide text-sm">Oferta Educativa</p>
                <h1 class="text-4xl md:text-5xl font-bold mt-3">{{ $nivel['titulo'] }}</h1>
                <p class="text-blue-50 text-lg mt-5">{{ $nivel['descripcion'] }}</p>
            </div>

            <img
                src="{{ $imagenPrincipal }}"
                alt="{{ $nivel['titulo'] }}"
                class="w-full h-72 md:h-full object-cover"
            >
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

    @if (! empty($galeria))
        <section class="mt-12">
            <div class="flex flex-col md:flex-row md:items-end md:justify-between gap-4 mb-6">
                <div>
                    <p class="font-semibold uppercase tracking-wide text-sm text-blue-700">Galeria</p>
                    <h2 class="text-3xl font-bold text-gray-900 mt-2">{{ $nivel['titulo'] }} en Discovery</h2>
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
