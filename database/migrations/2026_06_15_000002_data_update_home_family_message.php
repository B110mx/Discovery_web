<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::table('pagina_contenidos')
            ->where('slug', 'inicio')
            ->where('subtitulo', 'Conócenos')
            ->where('titulo', 'Sobre Nosotros')
            ->where('descripcion', 'En el Colegio Internacional Discovery® nos enfocamos en brindar una educación integral que combine valores, tecnología e innovación. Nuestro objetivo es formar Explorers preparados para enfrentar los retos del futuro con confianza.')
            ->update([
                'subtitulo' => 'Una comunidad que acompaña',
                'titulo' => 'Tu familia también puede encontrar su lugar en Discovery®',
                'descripcion' => 'Sabemos que elegir colegio es elegir quién acompañará a tus hijos mientras crecen. En Discovery® unimos formación académica, bienestar y una comunidad cercana para que cada Explorer avance con confianza y cada familia se sienta parte del camino.',
                'updated_at' => now(),
            ]);
    }

    public function down(): void
    {
        DB::table('pagina_contenidos')
            ->where('slug', 'inicio')
            ->where('subtitulo', 'Una comunidad que acompaña')
            ->where('titulo', 'Tu familia también puede encontrar su lugar en Discovery®')
            ->where('descripcion', 'Sabemos que elegir colegio es elegir quién acompañará a tus hijos mientras crecen. En Discovery® unimos formación académica, bienestar y una comunidad cercana para que cada Explorer avance con confianza y cada familia se sienta parte del camino.')
            ->update([
                'subtitulo' => 'Conócenos',
                'titulo' => 'Sobre Nosotros',
                'descripcion' => 'En el Colegio Internacional Discovery® nos enfocamos en brindar una educación integral que combine valores, tecnología e innovación. Nuestro objetivo es formar Explorers preparados para enfrentar los retos del futuro con confianza.',
                'updated_at' => now(),
            ]);
    }
};
