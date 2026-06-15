<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('nivel_contenidos', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->unique();
            $table->string('titulo');
            $table->text('descripcion');
            $table->string('contenido_titulo');
            $table->text('contenido_intro');
            $table->string('oferta_subtitulo');
            $table->text('oferta_descripcion');
            $table->string('oferta_edad');
            $table->json('oferta_puntos');
            $table->timestamps();
        });

    }

    public function down(): void
    {
        Schema::dropIfExists('nivel_contenidos');
    }
};
