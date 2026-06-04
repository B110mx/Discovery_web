@extends('layouts.app')

@section('content')

@php
    $titulo = $pagina?->titulo ?? config('colegio.contacto.titulo');
    $subtitulo = $pagina?->subtitulo ?? config('colegio.contacto.subtitulo');
    $descripcion = $pagina?->descripcion ?? config('colegio.contacto.descripcion');
    $direccion = $pagina?->direccion ?? config('colegio.contacto.direccion');
    $telefonoPrincipal = $pagina?->telefono_principal ?? config('colegio.contacto.telefono_principal');
    $telefonoSecundario = $pagina?->telefono_secundario ?? config('colegio.contacto.telefono_secundario');
    $correo = $pagina?->correo ?? config('colegio.contacto.correo');
    $mapaUrl = $pagina?->mapaEmbedUrl() ?? config('colegio.contacto.mapa_embed_url');
    $mapaExternoUrl = config('colegio.contacto.mapa_url');
    $whatsappNumero = preg_replace('/\D+/', '', config('colegio.contacto.whatsapp_numero'));
    $whatsappUrl = 'https://wa.me/' . $whatsappNumero . '?text=' . rawurlencode(config('colegio.contacto.whatsapp_mensaje'));
    $telefonoHref = 'tel:+52' . preg_replace('/\D+/', '', $telefonoSecundario);
@endphp

<section class="mx-auto max-w-7xl space-y-10">
    <div class="overflow-hidden rounded-lg bg-white shadow-md">
        <div class="grid lg:grid-cols-[1.05fr_.95fr]">
            <div class="flex flex-col justify-center px-6 py-10 md:px-10 lg:px-12">
                <p class="text-sm font-bold uppercase tracking-wide text-red-600">{{ $subtitulo }}</p>
                <h1 class="mt-3 max-w-3xl text-4xl font-extrabold leading-tight text-gray-950 md:text-5xl">
                    {{ $titulo }}
                </h1>
                <p class="mt-5 max-w-2xl text-lg leading-8 text-gray-600">{{ $descripcion }}</p>

                <div class="mt-8 grid gap-3 sm:grid-cols-3">
                    <a href="{{ $whatsappUrl }}" target="_blank" rel="noopener noreferrer" class="rounded-lg border border-green-600 bg-green-600 px-4 py-3 text-center font-bold text-white hover:bg-green-700">
                        WhatsApp
                    </a>
                    <a href="{{ $telefonoHref }}" class="rounded-lg border border-blue-700 px-4 py-3 text-center font-bold text-blue-700 hover:bg-blue-50">
                        Llamar
                    </a>
                    <a href="mailto:{{ $correo }}" class="rounded-lg border border-red-600 px-4 py-3 text-center font-bold text-red-600 hover:bg-red-50">
                        Correo
                    </a>
                </div>
            </div>

            <x-imagen-seccion
                :imagen="$imagenesContacto['hero']"
                alt="Colegio Discovery® contacto"
                class="h-72 w-full bg-white object-contain p-8 lg:h-full lg:p-10"
                placeholder-class="h-72 lg:h-full"
            />
        </div>
    </div>

    @if (session('success'))
        <div class="rounded-lg border border-green-200 bg-green-50 p-4 font-semibold text-green-800">
            {{ session('success') }}
        </div>
    @endif

    @if (session('error'))
        <div class="rounded-lg border border-red-200 bg-red-50 p-4 font-semibold text-red-800">
            {{ session('error') }}
        </div>
    @endif

    <div class="grid gap-8 lg:grid-cols-[1.15fr_.85fr]">
        <form action="{{ route('contacto.store') }}" method="POST" class="rounded-lg bg-white p-6 shadow-md md:p-8">
            @csrf

            <div class="mb-7 flex flex-col gap-2">
                <p class="text-sm font-bold uppercase tracking-wide text-blue-700">Escríbenos</p>
                <h2 class="text-3xl font-extrabold text-gray-950">Agenda informes o solicita admisiones</h2>
            </div>

            <div class="grid gap-5">
                <div>
                    <label for="aspirante_nombre" class="mb-2 block text-sm font-bold text-gray-700">Nombre completo del aspirante</label>
                    <input id="aspirante_nombre" type="text" name="aspirante_nombre" value="{{ old('aspirante_nombre') }}" class="w-full rounded-lg border border-gray-300 p-3 outline-none focus:border-blue-700 focus:ring-4 focus:ring-blue-100" required>
                    @error('aspirante_nombre') <p class="mt-1 text-sm font-semibold text-red-600">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="tutor_nombre" class="mb-2 block text-sm font-bold text-gray-700">Nombre completo del tutor (a)</label>
                    <input id="tutor_nombre" type="text" name="tutor_nombre" value="{{ old('tutor_nombre') }}" class="w-full rounded-lg border border-gray-300 p-3 outline-none focus:border-blue-700 focus:ring-4 focus:ring-blue-100" required>
                    @error('tutor_nombre') <p class="mt-1 text-sm font-semibold text-red-600">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="email" class="mb-2 block text-sm font-bold text-gray-700">Email *</label>
                    <input id="email" type="email" name="email" value="{{ old('email') }}" class="w-full rounded-lg border border-gray-300 p-3 outline-none focus:border-blue-700 focus:ring-4 focus:ring-blue-100" required>
                    @error('email') <p class="mt-1 text-sm font-semibold text-red-600">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="telefono" class="mb-2 block text-sm font-bold text-gray-700">Telefono de contacto *</label>
                    <input id="telefono" type="tel" name="telefono" value="{{ old('telefono') }}" class="w-full rounded-lg border border-gray-300 p-3 outline-none focus:border-blue-700 focus:ring-4 focus:ring-blue-100" required>
                    @error('telefono') <p class="mt-1 text-sm font-semibold text-red-600">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="grado" class="mb-2 block text-sm font-bold text-gray-700">Grado al que aplica</label>
                    <select id="grado" name="grado" class="w-full rounded-lg border border-gray-300 bg-white p-3 outline-none focus:border-blue-700 focus:ring-4 focus:ring-blue-100" required>
                        <option value="">Selecciona una opción</option>
                        @foreach (['Kinder', 'Elementary', 'Middle', 'High'] as $grado)
                            <option value="{{ $grado }}" @selected(old('grado') === $grado)>{{ $grado }}</option>
                        @endforeach
                    </select>
                    @error('grado') <p class="mt-1 text-sm font-semibold text-red-600">{{ $message }}</p> @enderror
                </div>
            </div>

            <div class="mt-6 flex flex-col gap-3 sm:flex-row sm:items-center">
                <button class="rounded-lg bg-blue-700 px-8 py-3 font-bold text-white hover:bg-blue-800">
                    Enviar mensaje
                </button>
                <a href="{{ $whatsappUrl }}" target="_blank" rel="noopener noreferrer" class="rounded-lg border border-green-600 px-8 py-3 text-center font-bold text-green-600 hover:bg-green-50">
                    Contactar por WhatsApp
                </a>
            </div>
        </form>

        <aside class="space-y-6">
            <div class="rounded-lg bg-gray-950 p-6 text-white shadow-md">
                <p class="text-sm font-bold uppercase tracking-wide text-yellow-500">Atención directa</p>
                <h2 class="mt-2 text-2xl font-extrabold">Colegio Internacional Discovery®</h2>

                <div class="mt-6 space-y-5 text-sm leading-7 text-gray-200">
                    <div>
                        <p class="font-bold text-white">Dirección</p>
                        <p>{{ $direccion }}</p>
                    </div>
                    <div>
                        <p class="font-bold text-white">Teléfonos</p>
                        <p>{{ $telefonoPrincipal }}</p>
                        <p>{{ $telefonoSecundario }}</p>
                    </div>
                    <div>
                        <p class="font-bold text-white">Correo</p>
                        <a href="mailto:{{ $correo }}" class="text-yellow-500 hover:underline">{{ $correo }}</a>
                    </div>
                </div>
            </div>

            <x-imagen-seccion
                :imagen="$imagenesContacto['secundaria']"
                alt="Comunidad Colegio Discovery®"
                class="h-72 w-full rounded-lg object-cover shadow-md"
                placeholder-class="h-72"
            />
        </aside>
    </div>

    <div class="overflow-hidden rounded-lg bg-white shadow-md">
        <div class="grid lg:grid-cols-[.75fr_1.25fr]">
            <div class="p-6 md:p-8">
                <p class="text-sm font-bold uppercase tracking-wide text-blue-700">Ubicación</p>
                <h2 class="mt-2 text-3xl font-extrabold text-gray-950">Ven a conocernos</h2>
                <p class="mt-4 leading-7 text-gray-600">{{ $direccion }}</p>
                <div class="mt-6 flex flex-col gap-3">
                    <a href="{{ $whatsappUrl }}" target="_blank" rel="noopener noreferrer" class="rounded-lg bg-green-600 px-6 py-3 text-center font-bold text-white hover:bg-green-700">
                        Pedir indicaciones por WhatsApp
                    </a>
                    <a href="{{ $mapaExternoUrl }}" target="_blank" rel="noopener noreferrer" class="rounded-lg border border-blue-700 px-6 py-3 text-center font-bold text-blue-700 hover:bg-blue-50">
                        Abrir en Google Maps
                    </a>
                </div>
            </div>

            <iframe
                src="{{ $mapaUrl }}"
                title="Ubicación Colegio Discovery® en Google Maps"
                class="h-96 w-full border-0 lg:h-[500px]"
                loading="lazy"
                referrerpolicy="no-referrer-when-downgrade"
                allowfullscreen
            ></iframe>
        </div>
    </div>
</section>

@endsection
