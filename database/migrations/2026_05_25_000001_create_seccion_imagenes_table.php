<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('seccion_imagenes', function (Blueprint $table) {
            $table->id();
            $table->string('vista');
            $table->string('clave');
            $table->string('titulo');
            $table->text('referencia')->nullable();
            $table->string('imagen')->nullable();
            $table->integer('orden')->default(0);
            $table->boolean('activo')->default(true);
            $table->timestamps();

            $table->unique(['vista', 'clave']);
        });

        $now = now();

        DB::table('seccion_imagenes')->insert([
            ['vista' => 'inicio', 'clave' => 'sobre_nosotros', 'titulo' => 'Inicio - Sobre Nosotros', 'referencia' => 'Imagen lateral de la seccion Sobre Nosotros en la pagina de inicio.', 'orden' => 10, 'activo' => true, 'created_at' => $now, 'updated_at' => $now],
            ['vista' => 'nosotros', 'clave' => 'hero', 'titulo' => 'Nosotros - Imagen principal', 'referencia' => 'Imagen grande del encabezado de la pagina Nosotros.', 'orden' => 10, 'activo' => true, 'created_at' => $now, 'updated_at' => $now],
            ['vista' => 'nosotros', 'clave' => 'modelo', 'titulo' => 'Nosotros - Modelo educativo', 'referencia' => 'Imagen de apoyo para la seccion de modelo educativo.', 'orden' => 20, 'activo' => true, 'created_at' => $now, 'updated_at' => $now],
            ['vista' => 'protagonistas', 'clave' => 'preescolar', 'titulo' => 'Comunidad - Preescolar', 'referencia' => 'Imagen para representar Preescolar dentro de Comunidad.', 'orden' => 10, 'activo' => true, 'created_at' => $now, 'updated_at' => $now],
            ['vista' => 'protagonistas', 'clave' => 'primaria', 'titulo' => 'Comunidad - Primaria', 'referencia' => 'Imagen para representar Primaria dentro de Comunidad.', 'orden' => 20, 'activo' => true, 'created_at' => $now, 'updated_at' => $now],
            ['vista' => 'protagonistas', 'clave' => 'secundaria', 'titulo' => 'Comunidad - Secundaria', 'referencia' => 'Imagen para representar Secundaria dentro de Comunidad.', 'orden' => 30, 'activo' => true, 'created_at' => $now, 'updated_at' => $now],
            ['vista' => 'protagonistas', 'clave' => 'bachillerato', 'titulo' => 'Comunidad - Bachillerato', 'referencia' => 'Imagen para representar Bachillerato dentro de Comunidad.', 'orden' => 40, 'activo' => true, 'created_at' => $now, 'updated_at' => $now],
            ['vista' => 'preescolar', 'clave' => 'hero', 'titulo' => 'Preescolar - Imagen principal', 'referencia' => 'Imagen principal del encabezado del nivel Preescolar.', 'orden' => 10, 'activo' => true, 'created_at' => $now, 'updated_at' => $now],
            ['vista' => 'primaria', 'clave' => 'hero', 'titulo' => 'Primaria - Imagen principal', 'referencia' => 'Imagen principal del encabezado del nivel Primaria.', 'orden' => 10, 'activo' => true, 'created_at' => $now, 'updated_at' => $now],
            ['vista' => 'secundaria', 'clave' => 'hero', 'titulo' => 'Secundaria - Imagen principal', 'referencia' => 'Imagen principal del encabezado del nivel Secundaria.', 'orden' => 10, 'activo' => true, 'created_at' => $now, 'updated_at' => $now],
            ['vista' => 'bachillerato', 'clave' => 'hero', 'titulo' => 'Bachillerato - Imagen principal', 'referencia' => 'Imagen principal del encabezado del nivel Bachillerato.', 'orden' => 10, 'activo' => true, 'created_at' => $now, 'updated_at' => $now],
            ['vista' => 'contacto', 'clave' => 'hero', 'titulo' => 'Contacto - Imagen principal', 'referencia' => 'Imagen principal de la vista Contacto.', 'orden' => 10, 'activo' => true, 'created_at' => $now, 'updated_at' => $now],
            ['vista' => 'contacto', 'clave' => 'secundaria', 'titulo' => 'Contacto - Imagen secundaria', 'referencia' => 'Imagen secundaria de apoyo de la vista Contacto.', 'orden' => 20, 'activo' => true, 'created_at' => $now, 'updated_at' => $now],
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('seccion_imagenes');
    }
};
