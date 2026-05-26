<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Colegio Discovery</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100">

    @include('components.navbar')

    <main class="p-6">
        @yield('content')
    </main>

    @include('components.footer')

    <x-whatsapp-button />

</body>
</html>
