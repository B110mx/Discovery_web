<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::table('pagina_contenidos')
            ->where('slug', 'inicio')
            ->where('titulo', 'Colegio Internacional Discovery®')
            ->where('subtitulo', 'Inicio')
            ->whereIn('descripcion', [
                'Formando líderes del futuro con educación de excelencia',
                'Formando Explorers del futuro con educación de excelencia',
                'Formando Explorers del futuro con educacion de excelencia',
            ])
            ->update([
                'subtitulo' => 'Conócenos',
                'titulo' => 'Sobre Nosotros',
                'descripcion' => 'En el Colegio Internacional Discovery® nos enfocamos en brindar una educación integral que combine valores, tecnología e innovación. Nuestro objetivo es formar Explorers preparados para enfrentar los retos del futuro con confianza.',
                'updated_at' => now(),
            ]);
    }

    public function down(): void
    {
        DB::table('pagina_contenidos')
            ->where('slug', 'inicio')
            ->where('titulo', 'Sobre Nosotros')
            ->where('subtitulo', 'Conócenos')
            ->where('descripcion', 'En el Colegio Internacional Discovery® nos enfocamos en brindar una educación integral que combine valores, tecnología e innovación. Nuestro objetivo es formar Explorers preparados para enfrentar los retos del futuro con confianza.')
            ->update([
                'subtitulo' => 'Inicio',
                'titulo' => 'Colegio Internacional Discovery®',
                'descripcion' => 'Formando Explorers del futuro con educación de excelencia',
                'updated_at' => now(),
            ]);
    }
};
