<footer class="bg-black text-white mt-12">
    <div class="max-w-7xl mx-auto px-6 py-10 grid md:grid-cols-3 gap-8">
        <div>
            <img
                src="{{ url('/media/Logos%20principales/' . rawurlencode('logo-ib-y-discovery-Footer-1024x342.png')) }}"
                alt="Colegio Discovery®"
                class="mb-4 h-16 w-auto object-contain"
                loading="lazy"
            >
            <p class="text-gray-300">
                Vía Puebla #3611, Residencial Cinco Bosques, Tehuacán, Puebla C.P. 75855.
            </p>
            <p class="text-gray-300 mt-3">(238) 688 11 79</p>
            <p class="text-gray-300">(238) 102 18 17</p>
            <a href="mailto:informes@colegio-discovery.edu.mx" class="text-gray-300 hover:text-white">
                informes@colegio-discovery.edu.mx
            </a>
        </div>

        <div>
            <h3 class="text-xl font-bold mb-3">{{ __('site.footer.sections') }}</h3>
            <ul class="space-y-2 text-gray-300">
                <li><a href="{{ route('inicio') }}" class="hover:text-white">{{ __('site.nav.home') }}</a></li>
                <li><a href="{{ route('nosotros') }}" class="hover:text-white">{{ __('site.nav.about') }}</a></li>
                <li><a href="{{ route('oferta-academica') }}" class="hover:text-white">{{ __('site.nav.academic_offer') }}</a></li>
                <li><a href="{{ route('protagonistas') }}" class="hover:text-white">{{ __('site.nav.community') }}</a></li>
                <li><a href="{{ route('recursos-escolares') }}" class="hover:text-white">{{ __('site.nav.school_resources') }}</a></li>
                <li><a href="{{ route('contacto') }}" class="hover:text-white">{{ __('site.nav.contact') }}</a></li>
            </ul>
        </div>

        <div>
            <h3 class="text-xl font-bold mb-3">{{ __('site.footer.levels') }}</h3>
            <ul class="space-y-2 text-gray-300">
                <li><a href="{{ route('nivel', 'preescolar') }}" class="hover:text-white">Kindergarten</a></li>
                <li><a href="{{ route('nivel', 'primaria') }}" class="hover:text-white">Elementary</a></li>
                <li><a href="{{ route('nivel', 'secundaria') }}" class="hover:text-white">Middle School</a></li>
                <li><a href="{{ route('nivel', 'bachillerato') }}" class="hover:text-white">High School</a></li>
                <li><a href="{{ route('nivel', 'ib-en-discovery') }}" class="hover:text-white">IB® en Discovery®</a></li>
                <li><a href="{{ route('nivel', 'pop-del-ib') }}" class="hover:text-white">POP del IB®</a></li>
                <li><a href="{{ route('nivel', 'certificacion-de-ingles') }}" class="hover:text-white">{{ __('site.nav.english_certification') }}</a></li>
            </ul>
        </div>
    </div>

    <div class="border-t border-gray-800 p-4 text-center text-gray-400">
        <div class="flex flex-col items-center justify-center gap-2 text-sm md:flex-row md:gap-4">
            <span>{{ __('site.footer.copyright') }}</span>
            <a
                href="{{ url('/media/Aviso%20de%20privacidad/' . rawurlencode('Aviso de Privacidad _ DISCOVERY.pdf')) }}"
                target="_blank"
                rel="noopener"
                class="font-semibold text-gray-300 hover:text-white"
            >
                {{ __('site.footer.privacy') }}
            </a>
        </div>
    </div>
</footer>
