<footer class="bg-gray-900 text-white mt-12">
    <div class="max-w-7xl mx-auto px-6 py-10 grid md:grid-cols-3 gap-8">
        <div>
            <h2 class="text-2xl font-bold mb-3">Colegio Discovery</h2>
            <p class="text-gray-300">
                Via Puebla #3611, Residencial Cinco Bosques, Tehuacan, Puebla C.P. 75855.
            </p>
            <p class="text-gray-300 mt-3">(238) 688 11 79</p>
            <p class="text-gray-300">(238) 102 18 17</p>
            <a href="mailto:informes@colegio-discovery.edu.mx" class="text-gray-300 hover:text-white">
                informes@colegio-discovery.edu.mx
            </a>
        </div>

        <div>
            <h3 class="text-xl font-bold mb-3">Secciones</h3>
            <ul class="space-y-2 text-gray-300">
                <li><a href="{{ route('inicio') }}" class="hover:text-white">Inicio</a></li>
                <li><a href="{{ route('nosotros') }}" class="hover:text-white">Conocenos</a></li>
                <li><a href="{{ route('oferta-academica') }}" class="hover:text-white">Oferta Educativa</a></li>
                <li><a href="{{ route('protagonistas') }}" class="hover:text-white">Comunidad</a></li>
                <li><a href="{{ route('recursos-escolares') }}" class="hover:text-white">Recursos escolares</a></li>
                <li><a href="{{ route('tienda') }}" class="hover:text-white">Tienda</a></li>
                <li><a href="{{ route('contacto') }}" class="hover:text-white">Contacto</a></li>
            </ul>
        </div>

        <div>
            <h3 class="text-xl font-bold mb-3">Niveles</h3>
            <ul class="space-y-2 text-gray-300">
                <li><a href="{{ route('nivel', 'preescolar') }}" class="hover:text-white">Preescolar</a></li>
                <li><a href="{{ route('nivel', 'primaria') }}" class="hover:text-white">Primaria</a></li>
                <li><a href="{{ route('nivel', 'secundaria') }}" class="hover:text-white">Secundaria</a></li>
                <li><a href="{{ route('nivel', 'bachillerato') }}" class="hover:text-white">Bachillerato</a></li>
                <li><a href="{{ route('nivel', 'ib-en-discovery') }}" class="hover:text-white">IB en Discovery</a></li>
                <li><a href="{{ route('nivel', 'certificacion-de-ingles') }}" class="hover:text-white">Certificacion de Ingles</a></li>
            </ul>
        </div>
    </div>

    <div class="border-t border-gray-700 text-center text-gray-400 p-4">
        Copyright Colegio Discovery
    </div>
</footer>
