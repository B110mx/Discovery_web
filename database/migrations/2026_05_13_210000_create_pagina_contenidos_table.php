<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pagina_contenidos', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->unique();
            $table->string('titulo');
            $table->string('subtitulo')->nullable();
            $table->text('descripcion')->nullable();
            $table->string('imagen_principal')->nullable();
            $table->string('imagen_secundaria')->nullable();
            $table->string('direccion')->nullable();
            $table->string('telefono_principal')->nullable();
            $table->string('telefono_secundario')->nullable();
            $table->string('correo')->nullable();
            $table->text('mapa_url')->nullable();
            $table->timestamps();
        });

        DB::table('pagina_contenidos')->insert([
            'slug' => 'contacto',
            'titulo' => 'Contacto',
            'subtitulo' => 'Informes y admisiones',
            'descripcion' => 'Compartenos tus datos y nos pondremos en contacto contigo para darte mas informacion sobre Colegio Discovery®.',
            'direccion' => 'Via Puebla #3611, Residencial Cinco Bosques, Tehuacan, Puebla C.P. 75855.',
            'telefono_principal' => '(238) 688 11 79',
            'telefono_secundario' => '(238) 102 18 17',
            'correo' => 'informes@colegio-discovery.edu.mx',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('pagina_contenidos');
    }
};
