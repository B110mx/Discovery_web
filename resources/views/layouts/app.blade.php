<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    {{-- Layout público compartido. Vite compila Tailwind y las interacciones globales. --}}
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Colegio Discovery®</title>
    <link rel="icon" type="image/png" sizes="64x64" href="{{ asset('favicon.png') }}?v=dk2">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('apple-touch-icon.png') }}?v=dk2">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100">
    {{-- Los administradores pueden previsualizar una vista cerrada al público. --}}
    @if (! empty($vistaEnPrevisualizacion ?? null))
        <div class="bg-amber-500 px-4 py-2 text-center text-sm font-bold text-black">
            {{ __('site.maintenance.preview', ['name' => $vistaEnPrevisualizacion]) }}
        </div>
    @endif

    {{-- Navegación y pie se mantienen como componentes para todas las páginas. --}}
    @include('components.navbar')

    <main class="p-6">
        @yield('content')
    </main>

    @include('components.footer')

    <x-explorer-help-button />
    <x-whatsapp-button />
</body>
</html>
