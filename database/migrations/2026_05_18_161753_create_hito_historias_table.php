<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
{
    Schema::create('hito_historias', function (Blueprint $table) {
        $table->id();
        $table->string('anio', 4);        // Ej: "2003"
        $table->string('titulo');         // Ej: "Discovery Kinder"
        $table->text('texto');            // La descripción del hito
        $table->string('imagen_url')->nullable(); // Aquí guardaremos la ruta de la foto de Filament
        $table->integer('orden')->default(0);    // Para ordenar cronológicamente
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hito_historias');
    }
};
