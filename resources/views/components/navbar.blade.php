{{--
    Los atributos data-* de este componente son utilizados por resources/js/app.js.
    Si se renombra uno, debe actualizarse también el selector JavaScript asociado.
--}}
<header class="sticky top-0 z-50 bg-white shadow-md">
    <div class="bg-blue-700 text-white text-sm">
        <div class="mx-auto flex max-w-7xl flex-col gap-2 px-4 py-2 md:flex-row md:items-center md:justify-end md:gap-6">
            <a href="mailto:informes@colegio-discovery.edu.mx" class="hover:underline">
                informes@colegio-discovery.edu.mx
            </a>
            <span>(238) 688 11 79, (238) 102 18 17</span>
            <div class="flex w-fit items-center gap-2" aria-label="Redes sociales de Colegio Discovery®">
                <a
                    href="https://www.instagram.com/colegio_discoveryth/"
                    target="_blank"
                    rel="noopener noreferrer"
                    class="inline-flex h-8 w-8 items-center justify-center rounded bg-white text-blue-700 transition-colors hover:bg-blue-50"
                    aria-label="Abrir Instagram de Colegio Discovery®"
                    title="Instagram"
                >
                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                        <rect width="20" height="20" x="2" y="2" rx="5" ry="5"></rect>
                        <path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z"></path>
                        <path d="M17.5 6.5h.01"></path>
                    </svg>
                    <span class="sr-only">Instagram</span>
                </a>
                <a
                    href="https://www.facebook.com/share/19AcsKx7vE/"
                    target="_blank"
                    rel="noopener noreferrer"
                    class="inline-flex h-8 w-8 items-center justify-center rounded bg-white text-blue-700 transition-colors hover:bg-blue-50"
                    aria-label="Abrir Facebook de Colegio Discovery®"
                    title="Facebook"
                >
                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                        <path d="M22 12.06C22 6.5 17.52 2 12 2S2 6.5 2 12.06c0 5.02 3.66 9.18 8.44 9.94v-7.03H7.9v-2.91h2.54V9.84c0-2.52 1.49-3.91 3.77-3.91 1.09 0 2.23.2 2.23.2v2.47h-1.26c-1.24 0-1.63.78-1.63 1.57v1.89h2.78l-.44 2.91h-2.34V22C18.34 21.24 22 17.08 22 12.06z"></path>
                    </svg>
                    <span class="sr-only">Facebook</span>
                </a>
                <a
                    href="https://www.tiktok.com/@colegio.discovery?_r=1&_t=ZS-96m1fcSPjkt"
                    target="_blank"
                    rel="noopener noreferrer"
                    class="inline-flex h-8 w-8 items-center justify-center rounded bg-white text-blue-700 transition-colors hover:bg-blue-50"
                    aria-label="Abrir TikTok de Colegio Discovery®"
                    title="TikTok"
                >
                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                        <path d="M16.6 5.82a5.54 5.54 0 0 0 3.25 1.04v3.02a8.48 8.48 0 0 1-3.25-.66v5.76A6.02 6.02 0 1 1 10.58 9v3.12a3 3 0 1 0 2.99 3V2h3.03v3.82z"></path>
                    </svg>
                    <span class="sr-only">TikTok</span>
                </a>
            </div>
        </div>
    </div>

    <nav class="mx-auto max-w-7xl px-4 py-4" data-site-nav>
        <div class="flex items-center justify-between gap-4">
            <a href="{{ route('inicio') }}" class="flex min-w-0 items-center gap-3">
                <img
                    src="{{ url('/media/Logos%20principales/' . rawurlencode('logo-ib-y-discovery-principal-1024x342.png')) }}"
                    alt="Colegio Discovery®"
                    class="h-12 w-auto max-w-[220px] object-contain sm:h-14 sm:max-w-none"
                >
                <span class="sr-only">Colegio Discovery®</span>
            </a>

            <button
                type="button"
                class="inline-flex h-11 w-11 items-center justify-center rounded border border-gray-200 text-blue-700 shadow-sm transition hover:bg-blue-50 focus:outline-none focus:ring-4 focus:ring-blue-100 lg:hidden"
                data-mobile-menu-toggle
                aria-controls="mobile-navigation"
                aria-expanded="false"
                aria-label="Abrir menú principal"
            >
                <svg class="h-6 w-6" data-mobile-menu-open-icon viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                    <path d="M4 6h16"></path>
                    <path d="M4 12h16"></path>
                    <path d="M4 18h16"></path>
                </svg>
                <svg class="hidden h-6 w-6" data-mobile-menu-close-icon viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                    <path d="M18 6 6 18"></path>
                    <path d="m6 6 12 12"></path>
                </svg>
            </button>
        </div>

        <ul class="hidden items-center gap-5 text-gray-700 font-semibold lg:flex">
            <li><a href="{{ route('inicio') }}" class="hover:text-blue-700 {{ request()->routeIs('inicio') ? 'text-blue-700' : '' }}">Inicio</a></li>
            <li><a href="{{ route('nosotros') }}" class="hover:text-blue-700 {{ request()->routeIs('nosotros') ? 'text-blue-700' : '' }}">Conócenos</a></li>
            <li class="relative group">
                <a href="{{ route('oferta-academica') }}" class="hover:text-blue-700 {{ request()->routeIs('oferta-academica') || request()->routeIs('nivel') ? 'text-blue-700' : '' }}">Oferta Educativa</a>
                <ul class="absolute left-0 top-full z-20 hidden w-56 rounded-md bg-white py-2 shadow-lg group-hover:block group-focus-within:block">
                    <li><a href="{{ route('nivel', 'preescolar') }}" class="block px-4 py-2 hover:bg-blue-50">Kinder</a></li>
                    <li><a href="{{ route('nivel', 'primaria') }}" class="block px-4 py-2 hover:bg-blue-50">Elementary</a></li>
                    <li><a href="{{ route('nivel', 'secundaria') }}" class="block px-4 py-2 hover:bg-blue-50">Middle</a></li>
                    <li><a href="{{ route('nivel', 'bachillerato') }}" class="block px-4 py-2 hover:bg-blue-50">High</a></li>
                    <li><a href="{{ route('nivel', 'ib-en-discovery') }}" class="block px-4 py-2 hover:bg-blue-50">IB en Discovery®</a></li>
                    <li><a href="{{ route('nivel', 'pop-del-ib') }}" class="block px-4 py-2 hover:bg-blue-50">POP del IB</a></li>
                    <li><a href="{{ route('nivel', 'certificacion-de-ingles') }}" class="block px-4 py-2 hover:bg-blue-50">Certificación de Inglés</a></li>
                </ul>
            </li>
            <li class="relative group">
                <a href="{{ route('protagonistas') }}" class="hover:text-blue-700 {{ request()->routeIs('protagonistas') || request()->routeIs('academias-vespertinas') ? 'text-blue-700' : '' }}">Comunidad</a>
                <ul class="absolute left-0 top-full z-20 hidden w-56 rounded-md bg-white py-2 shadow-lg group-hover:block group-focus-within:block">
                    <li><a href="{{ route('protagonistas') }}" class="block px-4 py-2 hover:bg-blue-50">Protagonistas</a></li>
                    <li><a href="{{ route('academias-vespertinas') }}" class="block px-4 py-2 hover:bg-blue-50">Academias Vespertinas</a></li>
                </ul>
            </li>
            <li><a href="{{ route('recursos-escolares') }}" class="hover:text-blue-700 {{ request()->routeIs('recursos-escolares') ? 'text-blue-700' : '' }}">Recursos escolares</a></li>
            <li><a href="{{ route('contacto') }}" class="hover:text-blue-700 {{ request()->routeIs('contacto') ? 'text-blue-700' : '' }}">Contacto</a></li>
        </ul>

        <div id="mobile-navigation" class="hidden pt-4 lg:hidden" data-mobile-menu>
            <ul class="space-y-2 rounded-xl border border-gray-100 bg-white p-3 text-gray-700 shadow-lg">
                <li><a href="{{ route('inicio') }}" class="block rounded-lg px-4 py-3 font-bold hover:bg-blue-50 {{ request()->routeIs('inicio') ? 'bg-blue-50 text-blue-700' : '' }}">Inicio</a></li>
                <li><a href="{{ route('nosotros') }}" class="block rounded-lg px-4 py-3 font-bold hover:bg-blue-50 {{ request()->routeIs('nosotros') ? 'bg-blue-50 text-blue-700' : '' }}">Conócenos</a></li>
                <li>
                    <button type="button" class="flex w-full items-center justify-between rounded-lg px-4 py-3 font-bold hover:bg-blue-50 {{ request()->routeIs('oferta-academica') || request()->routeIs('nivel') ? 'bg-blue-50 text-blue-700' : '' }}" data-mobile-submenu-toggle aria-expanded="{{ request()->routeIs('oferta-academica') || request()->routeIs('nivel') ? 'true' : 'false' }}">
                        Oferta Educativa
                        <span class="text-lg leading-none" aria-hidden="true">⌄</span>
                    </button>
                    <ul class="{{ request()->routeIs('oferta-academica') || request()->routeIs('nivel') ? '' : 'hidden' }} mt-1 space-y-1 pl-4" data-mobile-submenu>
                        <li><a href="{{ route('oferta-academica') }}" class="block rounded-lg px-4 py-2 text-sm font-semibold hover:bg-blue-50">Oferta Educativa</a></li>
                        <li><a href="{{ route('nivel', 'preescolar') }}" class="block rounded-lg px-4 py-2 text-sm font-semibold hover:bg-blue-50">Kinder</a></li>
                        <li><a href="{{ route('nivel', 'primaria') }}" class="block rounded-lg px-4 py-2 text-sm font-semibold hover:bg-blue-50">Elementary</a></li>
                        <li><a href="{{ route('nivel', 'secundaria') }}" class="block rounded-lg px-4 py-2 text-sm font-semibold hover:bg-blue-50">Middle</a></li>
                        <li><a href="{{ route('nivel', 'bachillerato') }}" class="block rounded-lg px-4 py-2 text-sm font-semibold hover:bg-blue-50">High</a></li>
                        <li><a href="{{ route('nivel', 'ib-en-discovery') }}" class="block rounded-lg px-4 py-2 text-sm font-semibold hover:bg-blue-50">IB en Discovery®</a></li>
                        <li><a href="{{ route('nivel', 'pop-del-ib') }}" class="block rounded-lg px-4 py-2 text-sm font-semibold hover:bg-blue-50">POP del IB</a></li>
                        <li><a href="{{ route('nivel', 'certificacion-de-ingles') }}" class="block rounded-lg px-4 py-2 text-sm font-semibold hover:bg-blue-50">Certificación de Inglés</a></li>
                    </ul>
                </li>
                <li>
                    <button type="button" class="flex w-full items-center justify-between rounded-lg px-4 py-3 font-bold hover:bg-blue-50 {{ request()->routeIs('protagonistas') || request()->routeIs('academias-vespertinas') ? 'bg-blue-50 text-blue-700' : '' }}" data-mobile-submenu-toggle aria-expanded="{{ request()->routeIs('protagonistas') || request()->routeIs('academias-vespertinas') ? 'true' : 'false' }}">
                        Comunidad
                        <span class="text-lg leading-none" aria-hidden="true">⌄</span>
                    </button>
                    <ul class="{{ request()->routeIs('protagonistas') || request()->routeIs('academias-vespertinas') ? '' : 'hidden' }} mt-1 space-y-1 pl-4" data-mobile-submenu>
                        <li><a href="{{ route('protagonistas') }}" class="block rounded-lg px-4 py-2 text-sm font-semibold hover:bg-blue-50">Protagonistas</a></li>
                        <li><a href="{{ route('academias-vespertinas') }}" class="block rounded-lg px-4 py-2 text-sm font-semibold hover:bg-blue-50">Academias Vespertinas</a></li>
                    </ul>
                </li>
                <li><a href="{{ route('recursos-escolares') }}" class="block rounded-lg px-4 py-3 font-bold hover:bg-blue-50 {{ request()->routeIs('recursos-escolares') ? 'bg-blue-50 text-blue-700' : '' }}">Recursos escolares</a></li>
                <li><a href="{{ route('contacto') }}" class="block rounded-lg px-4 py-3 font-bold hover:bg-blue-50 {{ request()->routeIs('contacto') ? 'bg-blue-50 text-blue-700' : '' }}">Contacto</a></li>
            </ul>
        </div>
    </nav>
</header>
