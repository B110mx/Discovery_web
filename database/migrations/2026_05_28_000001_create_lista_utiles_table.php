<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('lista_utiles', function (Blueprint $table) {
            $table->id();
            $table->string('ciclo_escolar', 20);
            $table->string('nivel', 80);
            $table->string('grado', 120);
            $table->string('titulo');
            $table->string('archivo_pdf');
            $table->unsignedInteger('orden')->default(0);
            $table->boolean('activo')->default(true);
            $table->timestamps();

            $table->index(['activo', 'ciclo_escolar', 'nivel']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('lista_utiles');
    }
};
