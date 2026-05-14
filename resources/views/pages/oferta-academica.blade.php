@extends('layouts.app')

@section('content')

<section class="bg-blue-600 text-white p-10 rounded-xl shadow-lg">
    <h1 class="text-4xl font-bold mb-4">Oferta academica</h1>
    <p class="text-lg">Programas disenados para acompanar cada etapa del crecimiento escolar.</p>
</section>

<section class="mt-10 grid md:grid-cols-2 lg:grid-cols-3 gap-6">
    <a href="{{ route('nivel', 'preescolar') }}" class="block bg-white p-6 rounded-xl shadow-md hover:shadow-lg hover:-translate-y-1 transition">
        <h2 class="text-2xl font-bold text-blue-600 mb-3">Preescolar</h2>
        <p class="text-gray-600">Un entorno cercano para iniciar el aprendizaje con seguridad y creatividad.</p>
    </a>

    <a href="{{ route('nivel', 'primaria') }}" class="block bg-white p-6 rounded-xl shadow-md hover:shadow-lg hover:-translate-y-1 transition">
        <h2 class="text-2xl font-bold text-blue-600 mb-3">Primaria</h2>
        <p class="text-gray-600">Aprendizaje activo, bases solidas y desarrollo de habilidades sociales.</p>
    </a>

    <a href="{{ route('nivel', 'secundaria') }}" class="block bg-white p-6 rounded-xl shadow-md hover:shadow-lg hover:-translate-y-1 transition">
        <h2 class="text-2xl font-bold text-blue-600 mb-3">Secundaria</h2>
        <p class="text-gray-600">Formacion academica con tecnologia, proyectos y acompanamiento personal.</p>
    </a>

    <a href="{{ route('nivel', 'bachillerato') }}" class="block bg-white p-6 rounded-xl shadow-md hover:shadow-lg hover:-translate-y-1 transition">
        <h2 class="text-2xl font-bold text-blue-600 mb-3">Bachillerato</h2>
        <p class="text-gray-600">Preparacion universitaria, liderazgo y orientacion vocacional.</p>
    </a>

    <a href="{{ route('nivel', 'ib-en-discovery') }}" class="block bg-white p-6 rounded-xl shadow-md hover:shadow-lg hover:-translate-y-1 transition">
        <h2 class="text-2xl font-bold text-blue-600 mb-3">IB en Discovery</h2>
        <p class="text-gray-600">Enfoque internacional para fortalecer pensamiento critico y autonomia.</p>
    </a>

    <a href="{{ route('nivel', 'certificacion-de-ingles') }}" class="block bg-white p-6 rounded-xl shadow-md hover:shadow-lg hover:-translate-y-1 transition">
        <h2 class="text-2xl font-bold text-blue-600 mb-3">Certificacion de Ingles</h2>
        <p class="text-gray-600">Metas claras para desarrollar el idioma ingles con acompanamiento academico.</p>
    </a>
</section>

@endsection
