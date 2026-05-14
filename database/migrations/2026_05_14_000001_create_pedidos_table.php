<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pedidos', function (Blueprint $table) {
            $table->id();
            $table->string('alumno_nombre');
            $table->string('alumno_nivel');
            $table->string('alumno_grado')->nullable();
            $table->string('padre_nombre');
            $table->string('padre_telefono');
            $table->string('padre_email')->nullable();
            $table->json('productos');
            $table->decimal('total', 10, 2)->default(0);
            $table->string('estado')->default('incompleto');
            $table->text('notas')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pedidos');
    }
};
