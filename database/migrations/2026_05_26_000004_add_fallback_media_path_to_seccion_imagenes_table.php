<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('seccion_imagenes', function (Blueprint $table) {
            $table->string('respaldo_media_path')->nullable()->after('imagen');
        });

        $respaldos = [
            ['vista' => 'inicio', 'clave' => 'sobre_nosotros', 'path' => 'Kinder fotos actuales/IMG_5775.JPG'],
            ['vista' => 'nosotros', 'clave' => 'hero', 'path' => 'Logos principales/LOGO DISCOVERY PNG.png'],
            ['vista' => 'nosotros', 'clave' => 'modelo', 'path' => 'Modelos educativos/modelo-educativo-Principal.png'],
            ['vista' => 'nosotros', 'clave' => 'historia_2003', 'path' => 'Linea del tiempo/2003-1.jpg'],
            ['vista' => 'nosotros', 'clave' => 'historia_2003_2', 'path' => 'Linea del tiempo/2003-2.jpg'],
            ['vista' => 'nosotros', 'clave' => 'historia_2005', 'path' => 'Linea del tiempo/2005-1.jpg'],
            ['vista' => 'nosotros', 'clave' => 'historia_2005_2', 'path' => 'Linea del tiempo/2005-2.jpg'],
            ['vista' => 'nosotros', 'clave' => 'historia_2011', 'path' => 'Linea del tiempo/2011-1.jpg'],
            ['vista' => 'nosotros', 'clave' => 'historia_2016', 'path' => 'Linea del tiempo/2016-1.jpg'],
            ['vista' => 'nosotros', 'clave' => 'historia_2018', 'path' => 'Linea del tiempo/2018-1.jpg'],
            ['vista' => 'nosotros', 'clave' => 'historia_2019', 'path' => 'Linea del tiempo/2019-1.jpg'],
            ['vista' => 'nosotros', 'clave' => 'historia_2019_2', 'path' => 'Linea del tiempo/2019-2.jpg'],
            ['vista' => 'nosotros', 'clave' => 'historia_2023', 'path' => 'Linea del tiempo/2023-1.jpg'],
            ['vista' => 'nosotros', 'clave' => 'historia_2023_2', 'path' => 'Linea del tiempo/2023-2.jpg'],
            ['vista' => 'nosotros', 'clave' => 'historia_2025', 'path' => 'Linea del tiempo/2025-1.jpg'],
            ['vista' => 'oferta-academica', 'clave' => 'preescolar', 'path' => 'Kinder fotos actuales/IMG_5775.JPG'],
            ['vista' => 'oferta-academica', 'clave' => 'primaria', 'path' => 'Elementary fotos actuales/IMG_5684.JPG'],
            ['vista' => 'oferta-academica', 'clave' => 'secundaria', 'path' => 'Middle fotos actuales/IMG_5867.JPG'],
            ['vista' => 'oferta-academica', 'clave' => 'bachillerato', 'path' => 'High fotos actuales/1234.jpg'],
            ['vista' => 'oferta-academica', 'clave' => 'ib', 'path' => 'Logos de niveles educativos/Logo IB cl.jpeg'],
            ['vista' => 'oferta-academica', 'clave' => 'certificacion_ingles', 'path' => 'Elementary fotos actuales/IMG_5782.JPG'],
            ['vista' => 'protagonistas', 'clave' => 'preescolar', 'path' => 'Kinder fotos actuales/IMG_5775.JPG'],
            ['vista' => 'protagonistas', 'clave' => 'primaria', 'path' => 'Elementary fotos actuales/IMG_5684.JPG'],
            ['vista' => 'protagonistas', 'clave' => 'secundaria', 'path' => 'Middle fotos actuales/IMG_5867.JPG'],
            ['vista' => 'protagonistas', 'clave' => 'bachillerato', 'path' => 'High fotos actuales/1234.jpg'],
            ['vista' => 'protagonistas', 'clave' => 'alumnos', 'path' => 'Elementary fotos actuales/IMG_5684.JPG'],
            ['vista' => 'protagonistas', 'clave' => 'docentes', 'path' => 'Fotos Docentes/1.png'],
            ['vista' => 'protagonistas', 'clave' => 'padres', 'path' => 'Kinder fotos actuales/IMG_5775.JPG'],
            ['vista' => 'protagonistas', 'clave' => 'alumni', 'path' => 'High fotos actuales/1234.jpg'],
            ['vista' => 'preescolar', 'clave' => 'hero', 'path' => 'Kinder fotos actuales/IMG_5775.JPG'],
            ['vista' => 'primaria', 'clave' => 'hero', 'path' => 'Elementary fotos actuales/IMG_5684.JPG'],
            ['vista' => 'secundaria', 'clave' => 'hero', 'path' => 'Middle fotos actuales/IMG_5867.JPG'],
            ['vista' => 'bachillerato', 'clave' => 'hero', 'path' => 'High fotos actuales/1234.jpg'],
        ];

        foreach ($respaldos as $respaldo) {
            DB::table('seccion_imagenes')
                ->where('vista', $respaldo['vista'])
                ->where('clave', $respaldo['clave'])
                ->update(['respaldo_media_path' => $respaldo['path']]);
        }
    }

    public function down(): void
    {
        Schema::table('seccion_imagenes', function (Blueprint $table) {
            $table->dropColumn('respaldo_media_path');
        });
    }
};
