<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('galeria_imagenes', function (Blueprint $table) {
            $table->id();
            $table->string('nivel', 80);
            $table->string('titulo')->nullable();
            $table->string('texto_alternativo')->nullable();
            $table->string('imagen')->nullable();
            $table->string('imagen_media_path')->nullable();
            $table->unsignedInteger('orden')->default(0);
            $table->boolean('activo')->default(true);
            $table->timestamps();

            $table->index(['nivel', 'activo', 'orden']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('galeria_imagenes');
    }
};
