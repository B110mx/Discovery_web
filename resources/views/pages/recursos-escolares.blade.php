@extends('layouts.app')

@section('content')

<section class="max-w-6xl mx-auto space-y-10">
    <div class="bg-blue-700 text-white rounded-xl shadow-lg p-8 md:p-12">
        <p class="font-semibold uppercase tracking-wide text-sm">Recursos escolares</p>
        <h1 class="text-4xl md:text-5xl font-bold mt-3">Lista de utiles y calendario escolar</h1>
        <p class="text-blue-50 text-lg mt-5 max-w-3xl">
            Consulta los materiales sugeridos para cada nivel y las fechas principales del ciclo escolar.
        </p>
    </div>

    <div class="grid lg:grid-cols-2 gap-8">
        <section class="bg-white rounded-xl shadow-md p-6 md:p-8">
            <h2 class="text-2xl font-bold text-blue-700 mb-5">Lista de utiles</h2>

            <div class="space-y-6">
                <div>
                    <h3 class="font-bold text-gray-900 mb-2">Preescolar</h3>
                    <ul class="list-disc list-inside text-gray-600 space-y-1">
                        <li>Cuaderno profesional cuadro grande</li>
                        <li>Crayones, lapices de colores y resistol</li>
                        <li>Paquete de hojas blancas y folder plastico</li>
                    </ul>
                </div>

                <div>
                    <h3 class="font-bold text-gray-900 mb-2">Primaria</h3>
                    <ul class="list-disc list-inside text-gray-600 space-y-1">
                        <li>Cuadernos profesionales para materias base</li>
                        <li>Lapices, plumas, colores, tijeras y pegamento</li>
                        <li>Juego de geometria y diccionario escolar</li>
                    </ul>
                </div>

                <div>
                    <h3 class="font-bold text-gray-900 mb-2">Secundaria y Bachillerato</h3>
                    <ul class="list-disc list-inside text-gray-600 space-y-1">
                        <li>Libretas por asignatura o carpeta de argollas</li>
                        <li>Calculadora cientifica y juego de geometria</li>
                        <li>Memoria USB y materiales segun laboratorio o taller</li>
                    </ul>
                </div>
            </div>
        </section>

        <section class="bg-white rounded-xl shadow-md p-6 md:p-8">
            <h2 class="text-2xl font-bold text-blue-700 mb-5">Calendario escolar</h2>

            <div class="space-y-4">
                <div class="border-l-4 border-blue-600 pl-4">
                    <p class="font-bold text-gray-900">Inicio de clases</p>
                    <p class="text-gray-600">Bienvenida, integracion y entrega de indicaciones generales.</p>
                </div>

                <div class="border-l-4 border-blue-600 pl-4">
                    <p class="font-bold text-gray-900">Evaluaciones parciales</p>
                    <p class="text-gray-600">Periodos de seguimiento academico durante el ciclo escolar.</p>
                </div>

                <div class="border-l-4 border-blue-600 pl-4">
                    <p class="font-bold text-gray-900">Consejo tecnico y suspensiones</p>
                    <p class="text-gray-600">Fechas de trabajo docente y descansos oficiales.</p>
                </div>

                <div class="border-l-4 border-blue-600 pl-4">
                    <p class="font-bold text-gray-900">Cierre de ciclo</p>
                    <p class="text-gray-600">Entrega de resultados, actividades finales y ceremonias escolares.</p>
                </div>
            </div>
        </section>
    </div>
</section>

@endsection
