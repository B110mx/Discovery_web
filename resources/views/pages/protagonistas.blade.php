@extends('layouts.app')

@section('content')

<section class="bg-white p-8 rounded-xl shadow-md">
    <h1 class="text-4xl font-bold text-blue-600 mb-4">Comunidad</h1>
    <p class="text-gray-700">
        En Discovery, los estudiantes son el centro de la experiencia educativa. Celebramos sus logros,
        proyectos, talentos y participacion dentro de la comunidad escolar.
    </p>
</section>

<section class="mt-10 grid md:grid-cols-3 gap-6">
    <div class="bg-white p-6 rounded-xl shadow-md">
        <h2 class="text-2xl font-bold text-blue-600 mb-2">Estudiantes</h2>
        <p class="text-gray-600">Impulsamos su curiosidad, creatividad y liderazgo desde el aula.</p>
    </div>

    <div class="bg-white p-6 rounded-xl shadow-md">
        <h2 class="text-2xl font-bold text-blue-600 mb-2">Docentes</h2>
        <p class="text-gray-600">Un equipo comprometido con guiar, motivar y acompanar cada proceso.</p>
    </div>

    <div class="bg-white p-6 rounded-xl shadow-md">
        <h2 class="text-2xl font-bold text-blue-600 mb-2">Familias</h2>
        <p class="text-gray-600">La comunicacion con madres, padres y tutores fortalece nuestra comunidad.</p>
    </div>
</section>

@endsection
