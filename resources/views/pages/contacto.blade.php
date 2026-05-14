@extends('layouts.app')

@section('content')

@php
    $titulo = $pagina?->titulo ?? 'Contacto';
    $subtitulo = $pagina?->subtitulo ?? 'Informes y admisiones';
    $descripcion = $pagina?->descripcion ?? 'Compartenos tus datos y nos pondremos en contacto contigo para darte mas informacion sobre Colegio Discovery.';
    $direccion = $pagina?->direccion ?? 'Via Puebla #3611, Residencial Cinco Bosques, Tehuacan, Puebla C.P. 75855.';
    $telefonoPrincipal = $pagina?->telefono_principal ?? '(238) 688 11 79';
    $telefonoSecundario = $pagina?->telefono_secundario ?? '(238) 102 18 17';
    $correo = $pagina?->correo ?? 'informes@colegio-discovery.edu.mx';
    $imagenPrincipal = $pagina?->imagen_principal
        ? Illuminate\Support\Facades\Storage::disk('public')->url($pagina->imagen_principal)
        : 'https://images.unsplash.com/photo-1577896851231-70ef18881754?auto=format&fit=crop&w=1200&q=80';
    $imagenSecundaria = $pagina?->imagen_secundaria
        ? Illuminate\Support\Facades\Storage::disk('public')->url($pagina->imagen_secundaria)
        : 'https://images.unsplash.com/photo-1509062522246-3755977927d7?auto=format&fit=crop&w=900&q=80';
@endphp

<section class="max-w-7xl mx-auto">
    <div class="bg-blue-700 text-white rounded-xl overflow-hidden shadow-lg">
        <div class="grid lg:grid-cols-2">
            <div class="p-8 md:p-12 flex flex-col justify-center">
                <p class="font-semibold uppercase tracking-wide text-sm">{{ $subtitulo }}</p>
                <h1 class="text-4xl md:text-5xl font-bold mt-3">{{ $titulo }}</h1>
                <p class="text-blue-50 text-lg mt-5 max-w-xl">{{ $descripcion }}</p>
            </div>

            <img
                src="{{ $imagenPrincipal }}"
                alt="Colegio Discovery contacto"
                class="w-full h-72 lg:h-full object-cover"
            >
        </div>
    </div>

    <div class="grid lg:grid-cols-3 gap-8 mt-10">
        <div class="lg:col-span-2 bg-white rounded-xl shadow-md p-8">
            <h2 class="text-3xl font-bold text-blue-700 mb-6">Contacto</h2>

            @if (session('success'))
                <div class="bg-green-100 text-green-800 p-4 rounded mb-4">
                    {{ session('success') }}
                </div>
            @endif

            <form action="{{ url('/contacto') }}" method="POST" class="grid md:grid-cols-2 gap-4">
                @csrf

                <div>
                    <label for="nombre" class="block text-sm font-semibold text-gray-700 mb-1">Nombre</label>
                    <input id="nombre" type="text" name="nombre" class="w-full border border-gray-300 p-3 rounded focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                </div>

                <div>
                    <label for="email" class="block text-sm font-semibold text-gray-700 mb-1">Correo electronico</label>
                    <input id="email" type="email" name="email" class="w-full border border-gray-300 p-3 rounded focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                </div>

                <div class="md:col-span-2">
                    <label for="mensaje" class="block text-sm font-semibold text-gray-700 mb-1">Mensaje</label>
                    <textarea id="mensaje" name="mensaje" class="w-full border border-gray-300 p-3 rounded min-h-36 focus:outline-none focus:ring-2 focus:ring-blue-500" required></textarea>
                </div>

                <div class="md:col-span-2">
                    <button class="bg-blue-700 text-white px-8 py-3 rounded font-semibold hover:bg-blue-800">
                        Enviar
                    </button>
                </div>
            </form>
        </div>

        <aside class="space-y-6">
            <div class="bg-white rounded-xl shadow-md p-6">
                <h3 class="text-xl font-bold text-blue-700 mb-3">Informes</h3>
                <p class="text-gray-700">{{ $direccion }}</p>
                <p class="text-gray-700 mt-4">{{ $telefonoPrincipal }}</p>
                <p class="text-gray-700">{{ $telefonoSecundario }}</p>
                <a href="mailto:{{ $correo }}" class="block mt-4 text-blue-700 hover:underline">{{ $correo }}</a>
            </div>

            <img
                src="{{ $imagenSecundaria }}"
                alt="Comunidad Colegio Discovery"
                class="w-full h-64 object-cover rounded-xl shadow-md"
            >
        </aside>
    </div>

    <div class="mt-10 bg-white rounded-xl shadow-md p-8">
        <h2 class="text-2xl font-bold text-blue-700 mb-4">Ubicacion</h2>
        <p class="text-gray-700">{{ $direccion }} Tel: {{ $telefonoPrincipal }}.</p>
    </div>
</section>

@endsection
