<header class="bg-white shadow-md">
    <div class="bg-blue-700 text-white text-sm">
        <div class="max-w-7xl mx-auto px-4 py-2 flex flex-col md:flex-row md:items-center md:justify-end gap-2 md:gap-6">
            <a href="mailto:informes@colegio-discovery.edu.mx" class="hover:underline">
                informes@colegio-discovery.edu.mx
            </a>
            <span>(238) 688 11 79, (238) 102 18 17</span>
            <a
                href="https://www.instagram.com/colegio_discoveryth/"
                target="_blank"
                rel="noopener noreferrer"
                class="inline-flex w-fit items-center justify-center rounded bg-white px-3 py-1 font-bold text-blue-700 transition-colors hover:bg-blue-50"
                aria-label="Abrir Instagram de Colegio Discovery"
            >
                Instagram
            </a>
        </div>
    </div>

    <nav class="max-w-7xl mx-auto px-4 py-4 flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
        <a href="{{ route('inicio') }}" class="flex items-center gap-3">
            <img
                src="{{ url('/media/Logos%20principales/' . rawurlencode('logo-ib-y-discovery-principal-1024x342.png')) }}"
                alt="Colegio Discovery"
                class="h-14 w-auto object-contain"
            >
            <span class="sr-only">Colegio Discovery</span>
        </a>

        <ul class="flex flex-wrap items-center gap-5 text-gray-700 font-semibold">
            <li>
                <a href="{{ route('inicio') }}" class="hover:text-blue-700 {{ request()->routeIs('inicio') ? 'text-blue-700' : '' }}">Inicio</a>
            </li>
            <li>
                <a href="{{ route('nosotros') }}" class="hover:text-blue-700 {{ request()->routeIs('nosotros') ? 'text-blue-700' : '' }}">Conócenos</a>
            </li>
            <li class="relative group">
                <a href="{{ route('oferta-academica') }}" class="hover:text-blue-700 {{ request()->routeIs('oferta-academica') || request()->routeIs('nivel') ? 'text-blue-700' : '' }}">
                    Oferta Educativa
                </a>
                <ul class="hidden group-hover:block group-focus-within:block absolute left-0 top-full bg-white shadow-lg rounded-md py-2 w-56 z-20">
                    <li><a href="{{ route('nivel', 'preescolar') }}" class="block px-4 py-2 hover:bg-blue-50">Preescolar</a></li>
                    <li><a href="{{ route('nivel', 'primaria') }}" class="block px-4 py-2 hover:bg-blue-50">Primaria</a></li>
                    <li><a href="{{ route('nivel', 'secundaria') }}" class="block px-4 py-2 hover:bg-blue-50">Secundaria</a></li>
                    <li><a href="{{ route('nivel', 'bachillerato') }}" class="block px-4 py-2 hover:bg-blue-50">Bachillerato</a></li>
                    <li><a href="{{ route('nivel', 'ib-en-discovery') }}" class="block px-4 py-2 hover:bg-blue-50">IB en Discovery</a></li>
                    <li><a href="{{ route('nivel', 'certificacion-de-ingles') }}" class="block px-4 py-2 hover:bg-blue-50">Certificación de Inglés</a></li>
                </ul>
            </li>
            <li>
                <a href="{{ route('protagonistas') }}" class="hover:text-blue-700 {{ request()->routeIs('protagonistas') ? 'text-blue-700' : '' }}">Comunidad</a>
            </li>
            <li>
                <a href="{{ route('recursos-escolares') }}" class="hover:text-blue-700 {{ request()->routeIs('recursos-escolares') ? 'text-blue-700' : '' }}">Recursos escolares</a>
            </li>
            <li>
                <a href="{{ route('tienda') }}" class="hover:text-blue-700 {{ request()->routeIs('tienda') ? 'text-blue-700' : '' }}">Tienda</a>
            </li>
            <li>
                <a href="{{ route('contacto') }}" class="hover:text-blue-700 {{ request()->routeIs('contacto') ? 'text-blue-700' : '' }}">Contacto</a>
            </li>
        </ul>
    </nav>
</header>
