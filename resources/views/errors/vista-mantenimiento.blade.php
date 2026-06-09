<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="robots" content="noindex, nofollow">
    <title>{{ $nombreVista }} en mantenimiento | Colegio Discovery®</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-gray-100">
    <main class="flex min-h-screen items-center justify-center px-6 py-12">
        <section class="w-full max-w-2xl overflow-hidden rounded-2xl bg-white text-center shadow-xl">
            <div class="bg-blue-700 px-8 py-10 text-white">
                <img
                    src="{{ url('/media/Logos%20principales/' . rawurlencode('logo-ib-y-discovery-principal-1024x342.png')) }}"
                    alt="Colegio Discovery®"
                    class="mx-auto h-20 max-w-full rounded bg-white object-contain p-3"
                >
                <p class="mt-7 text-sm font-bold uppercase tracking-widest text-blue-100">Actualización en proceso</p>
                <h1 class="mt-3 text-3xl font-extrabold md:text-4xl">{{ $nombreVista }}</h1>
            </div>

            <div class="px-8 py-10">
                <h2 class="text-2xl font-extrabold text-gray-950">Estamos preparando esta sección</h2>
                <p class="mx-auto mt-4 max-w-xl leading-8 text-gray-600">
                    Estamos realizando mejoras para ofrecerte información clara y actualizada. El resto del sitio continúa disponible.
                </p>
                <div class="mt-8 flex flex-col justify-center gap-3 sm:flex-row">
                    @if ($nombreVista !== 'Inicio')
                        <a href="{{ route('inicio') }}" class="rounded bg-blue-700 px-6 py-3 font-bold text-white transition hover:bg-blue-800">
                            Ir al inicio
                        </a>
                    @endif
                    <a href="mailto:informes@colegio-discovery.edu.mx" class="rounded border border-blue-700 px-6 py-3 font-bold text-blue-700 transition hover:bg-blue-50">
                        Escribir al colegio
                    </a>
                </div>
            </div>
        </section>
    </main>
</body>
</html>
