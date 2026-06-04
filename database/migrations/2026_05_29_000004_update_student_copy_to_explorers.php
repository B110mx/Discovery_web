<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::table('pagina_contenidos')
            ->where('slug', 'inicio')
            ->where('descripcion', 'like', '%lideres%')
            ->update(['descripcion' => 'Formando Explorers del futuro con educación de excelencia']);

        DB::table('pagina_contenidos')
            ->where('slug', 'nosotros')
            ->where('descripcion', 'like', '%estudiante%')
            ->update(['descripcion' => 'Una comunidad educativa que acompaña a cada Explorer en su crecimiento académico, humano y social.']);

        DB::table('pagina_contenidos')
            ->where('slug', 'protagonistas')
            ->where('descripcion', 'like', '%alumnos%')
            ->update(['descripcion' => 'En Discovery® Explorers, padres de familia, docentes y alumni trabajamos en equipo para formar una comunidad de aprendizaje con mentalidad internacional.']);

        DB::table('hito_historias')
            ->where('texto', 'like', '%grandes lideres%')
            ->update(['texto' => 'Llega Discovery® High, preparando grandes Explorers y descubriendo su potencial.']);

        DB::table('hito_historias')
            ->where('texto', 'like', '%liderazgo y la diplomacia%')
            ->update(['texto' => 'Realizamos nuestra primera edición DKMUN, un espacio para el debate y la diplomacia.']);
    }

    public function down(): void
    {
        // Cambio editorial: no se revierte automaticamente.
    }
};
